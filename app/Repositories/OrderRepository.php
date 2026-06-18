<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\BaseRepository;
use PDO;
use RuntimeException;

final class OrderRepository extends BaseRepository implements IOrderRepository
{
    public function createPaidOrder(
        int $userId,
        array $customer,
        string $provider,
        array $items,
        string $stripeSessionId,
        ?string $providerPaymentId
    ): int
    {
        $total = $this->calculateTotal($items);
        $timestamp = date('Y-m-d H:i:s');

        try {
            $this->beginTransaction();

            $orderSql = 'INSERT INTO `order`
                (user_id, first_name, last_name, email, phone, total_price, status, provider, created_at)
                VALUES
                (:user_id, :first_name, :last_name, :email, :phone, :total_price, :status, :provider, :created_at)';

            $orderStmt = $this->getConnection()->prepare($orderSql);
            $orderStmt->execute([
                ':user_id' => $userId,
                ':first_name' => trim((string) ($customer['first_name'] ?? '')),
                ':last_name' => trim((string) ($customer['last_name'] ?? '')),
                ':email' => trim((string) ($customer['email'] ?? '')),
                ':phone' => trim((string) ($customer['phone'] ?? '')),
                ':total_price' => $total,
                ':status' => 'paid',
                ':provider' => $provider,
                ':created_at' => $timestamp,
            ]);

            $orderId = (int) $this->getConnection()->lastInsertId();

            // Sequential, human-readable invoice number derived from the order id, e.g. HF-2026-000042.
            $invoiceNumber = sprintf('HF-%s-%06d', date('Y'), $orderId);
            $invoiceStmt = $this->getConnection()->prepare(
                'UPDATE `order` SET invoice_number = :invoice_number, invoice_issued_at = :issued_at WHERE order_id = :order_id'
            );
            $invoiceStmt->execute([
                ':invoice_number' => $invoiceNumber,
                ':issued_at' => $timestamp,
                ':order_id' => $orderId,
            ]);

            foreach ($items as $item) {
                $orderLineId = $this->insertOrderLine($orderId, $item);
                $this->insertTicketsForItem($orderLineId, $item);
                if (($item['type'] ?? '') === 'yummy-reservation') {
                    $this->insertReservationForLine($orderId, $orderLineId, $userId, $item);
                }
            }

            $paymentSql = 'INSERT INTO payment
                (order_id, provider, provider_payment_id, stripe_session_id, amount, currency, status, paid_at)
                VALUES
                (:order_id, :provider, :provider_payment_id, :stripe_session_id, :amount, :currency, :status, :paid_at)';

            $paymentStmt = $this->getConnection()->prepare($paymentSql);
            $paymentStmt->execute([
                ':order_id' => $orderId,
                ':provider' => 'stripe',
                ':provider_payment_id' => $providerPaymentId,
                ':stripe_session_id' => $stripeSessionId,
                ':amount' => $total,
                ':currency' => 'EUR',
                ':status' => 'paid',
                ':paid_at' => $timestamp,
            ]);

            $this->commit();

            return $orderId;
        } catch (\Throwable $e) {
            $this->rollBack();
            error_log('createPaidOrder failed: ' . $e->getMessage());
            throw new RuntimeException('Failed to create paid order: ' . $e->getMessage(), 0, $e);
        }
    }

    public function findOrderForUser(int $orderId, int $userId): ?array
    {
        $sql = 'SELECT o.*, p.stripe_session_id, p.paid_at AS payment_paid_at, p.status AS payment_status,
            COALESCE(NULLIF(o.email, ""), u.email) AS email,
            COALESCE(NULLIF(o.first_name, ""), u.first_name) AS first_name,
            COALESCE(NULLIF(o.last_name, ""), u.last_name) AS last_name
            FROM `order` o
            INNER JOIN payment p ON p.order_id = o.order_id
            INNER JOIN `user` u ON u.user_id = o.user_id
            WHERE o.order_id = :order_id AND o.user_id = :user_id
            LIMIT 1';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([
            ':order_id' => $orderId,
            ':user_id' => $userId,
        ]);

        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!is_array($order)) {
            return null;
        }

        return $this->hydrateOrder($order);
    }

    public function findPaidOrdersForUser(int $userId): array
    {
        $sql = 'SELECT o.*, p.stripe_session_id, p.paid_at AS payment_paid_at, p.status AS payment_status,
            COALESCE(NULLIF(o.email, ""), u.email) AS email,
            COALESCE(NULLIF(o.first_name, ""), u.first_name) AS first_name,
            COALESCE(NULLIF(o.last_name, ""), u.last_name) AS last_name
            FROM `order` o
            INNER JOIN payment p ON p.order_id = o.order_id
            INNER JOIN `user` u ON u.user_id = o.user_id
            WHERE o.user_id = :user_id AND o.status = :status
            ORDER BY o.order_id DESC';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([
            ':user_id' => $userId,
            ':status' => 'paid',
        ]);

        $orders = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            if (is_array($row)) {
                $orders[] = $this->hydrateOrder($row);
            }
        }

        return $orders;
    }

    public function findByStripeSessionId(int $userId, string $stripeSessionId): ?array
    {
        $sql = 'SELECT o.*, p.stripe_session_id, p.paid_at AS payment_paid_at, p.status AS payment_status,
            COALESCE(NULLIF(o.email, ""), u.email) AS email,
            COALESCE(NULLIF(o.first_name, ""), u.first_name) AS first_name,
            COALESCE(NULLIF(o.last_name, ""), u.last_name) AS last_name
            FROM `order` o
            INNER JOIN payment p ON p.order_id = o.order_id
            INNER JOIN `user` u ON u.user_id = o.user_id
            WHERE p.stripe_session_id = :stripe_session_id AND o.user_id = :user_id
            LIMIT 1';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([
            ':stripe_session_id' => $stripeSessionId,
            ':user_id' => $userId,
        ]);

        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!is_array($order)) {
            return null;
        }

        return $this->hydrateOrder($order);
    }

    public function getLatestOrderIdForUser(int $userId): int
    {
        $sql = 'SELECT order_id FROM `order`
            WHERE user_id = :user_id AND status = :status
            ORDER BY order_id DESC
            LIMIT 1';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([
            ':user_id' => $userId,
            ':status' => 'paid',
        ]);

        return (int) $stmt->fetchColumn();
    }

    public function findOrdersForAdmin(): array
    {
        $sql = 'SELECT o.*, p.stripe_session_id, p.paid_at AS payment_paid_at, p.status AS payment_status,
            COALESCE(NULLIF(o.email, ""), u.email) AS email,
            COALESCE(NULLIF(o.first_name, ""), u.first_name) AS first_name,
            COALESCE(NULLIF(o.last_name, ""), u.last_name) AS last_name
            FROM `order` o
            LEFT JOIN payment p ON p.order_id = o.order_id
            INNER JOIN `user` u ON u.user_id = o.user_id
            ORDER BY o.order_id DESC';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute();

        $orders = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            if (is_array($row)) {
                $orders[] = $this->hydrateAdminOrderSummary($row);
            }
        }

        return $orders;
    }

    public function findOrderForAdmin(int $orderId): ?array
    {
        $sql = 'SELECT o.*, p.stripe_session_id, p.paid_at AS payment_paid_at, p.status AS payment_status,
            COALESCE(NULLIF(o.email, ""), u.email) AS email,
            COALESCE(NULLIF(o.first_name, ""), u.first_name) AS first_name,
            COALESCE(NULLIF(o.last_name, ""), u.last_name) AS last_name
            FROM `order` o
            LEFT JOIN payment p ON p.order_id = o.order_id
            INNER JOIN `user` u ON u.user_id = o.user_id
            WHERE o.order_id = :order_id
            LIMIT 1';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([':order_id' => $orderId]);

        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!is_array($order)) {
            return null;
        }

        return $this->hydrateOrder($order);
    }

    public function findOrderExportRows(): array
    {
        $sql = 'SELECT
                o.order_id,
                COALESCE(NULLIF(o.first_name, ""), u.first_name) AS first_name,
                COALESCE(NULLIF(o.last_name, ""), u.last_name) AS last_name,
                COALESCE(NULLIF(o.email, ""), u.email) AS email,
                o.phone,
                o.status AS order_status,
                p.status AS payment_status,
                o.provider,
                o.total_price,
                p.paid_at,
                ol.title AS item_title,
                COALESCE(NULLIF(ol.ticket_title, ""), ol.ticket_summary_text) AS ticket_title,
                ol.quantity,
                ol.unit_price,
                ol.line_total,
                ol.location_name,
                ol.event_id,
                ol.ticket_type_id
            FROM `order` o
            INNER JOIN `user` u ON u.user_id = o.user_id
            LEFT JOIN payment p ON p.order_id = o.order_id
            LEFT JOIN order_line ol ON ol.order_id = o.order_id
            ORDER BY o.order_id DESC, ol.order_line_id ASC';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function hydrateOrder(array $row): array
    {
        $orderId = (int) ($row['order_id'] ?? 0);

        return [
            'order_id' => $orderId,
            'user_id' => (int) ($row['user_id'] ?? 0),
            'total_price' => round((float) ($row['total_price'] ?? 0), 2),
            'status' => (string) ($row['status'] ?? 'paid'),
            'created_at' => (string) ($row['created_at'] ?? ''),
            'invoice_number' => (string) ($row['invoice_number'] ?? ''),
            'invoice_issued_at' => (string) ($row['invoice_issued_at'] ?? ''),
            'provider' => (string) ($row['provider'] ?? ''),
            'payment_status' => (string) ($row['payment_status'] ?? 'paid'),
            'paid_at' => (string) ($row['payment_paid_at'] ?? $row['created_at'] ?? ''),
            'stripe_session_id' => (string) ($row['stripe_session_id'] ?? ''),
            'first_name' => (string) ($row['first_name'] ?? ''),
            'last_name' => (string) ($row['last_name'] ?? ''),
            'email' => (string) ($row['email'] ?? ''),
            'phone' => (string) ($row['phone'] ?? ''),
            'items' => $this->fetchOrderLines($orderId),
            'tickets' => $this->fetchTicketsForOrder($orderId),
        ];
    }

    private function hydrateAdminOrderSummary(array $row): array
    {
        return [
            'order_id' => (int) ($row['order_id'] ?? 0),
            'user_id' => (int) ($row['user_id'] ?? 0),
            'total_price' => round((float) ($row['total_price'] ?? 0), 2),
            'status' => (string) ($row['status'] ?? 'paid'),
            'created_at' => (string) ($row['created_at'] ?? ''),
            'provider' => (string) ($row['provider'] ?? ''),
            'payment_status' => (string) ($row['payment_status'] ?? ''),
            'paid_at' => (string) ($row['payment_paid_at'] ?? ''),
            'stripe_session_id' => (string) ($row['stripe_session_id'] ?? ''),
            'first_name' => (string) ($row['first_name'] ?? ''),
            'last_name' => (string) ($row['last_name'] ?? ''),
            'email' => (string) ($row['email'] ?? ''),
            'phone' => (string) ($row['phone'] ?? ''),
        ];
    }

  /** @return list<array<string, mixed>> */
    private function fetchOrderLines(int $orderId): array
    {
        $sql = 'SELECT * FROM order_line WHERE order_id = :order_id ORDER BY order_line_id ASC';
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([':order_id' => $orderId]);

        $lines = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            if (!is_array($row)) {
                continue;
            }

            $itemData = [];
            if (!empty($row['item_data'])) {
                $decoded = json_decode((string) $row['item_data'], true);
                if (is_array($decoded)) {
                    $itemData = $decoded;
                }
            }

            $lines[] = array_merge($itemData, [
                'title' => (string) ($row['title'] ?? ''),
                'selection_text' => (string) ($row['selection_text'] ?? ''),
                'ticket_title' => (string) ($row['ticket_title'] ?? ''),
                'ticket_summary_text' => (string) ($row['ticket_summary_text'] ?? ''),
                'quantity' => (int) ($row['quantity'] ?? 1),
                'unit_price' => round((float) ($row['unit_price'] ?? 0), 2),
                'line_total' => round((float) ($row['line_total'] ?? 0), 2),
                'vat_rate' => round((float) ($row['vat_rate'] ?? 21), 2),
                'location_name' => (string) ($row['location_name'] ?? ''),
                'special_requests' => (string) ($row['special_requests'] ?? ''),
                'type' => (string) ($row['item_type'] ?? 'booking'),
                'event_id' => (int) ($row['event_id'] ?? 0),
                'ticket_type_id' => (int) ($row['ticket_type_id'] ?? 0),
            ]);
        }

        return $lines;
    }

  /** @return list<array<string, mixed>> */
    private function fetchTicketsForOrder(int $orderId): array
    {
        if ($this->columnExists('ticket', 'order_line_id')) {
            $sql = 'SELECT t.ticket_id, t.qr_token, t.status, ol.ticket_type_id
                FROM order_line ol
                INNER JOIN ticket t ON t.order_line_id = ol.order_line_id
                WHERE ol.order_id = :order_id
                ORDER BY t.ticket_id ASC';
        } elseif ($this->columnExists('ticket', 'order_ticket_id')) {
            $sql = 'SELECT t.ticket_id, t.qr_token, t.status, ot.ticket_type_id
                FROM order_ticket ot
                INNER JOIN ticket t ON t.order_ticket_id = ot.order_ticket_id
                WHERE ot.order_id = :order_id
                ORDER BY t.ticket_id ASC';
        } else {
            return [];
        }

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([':order_id' => $orderId]);

        $tickets = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            if (!is_array($row)) {
                continue;
            }

            $tickets[] = [
                'ticket_id' => (int) ($row['ticket_id'] ?? 0),
                'qr_token' => (string) ($row['qr_token'] ?? ''),
                'status' => (string) ($row['status'] ?? 'valid'),
                'ticket_type_id' => (int) ($row['ticket_type_id'] ?? 0),
            ];
        }

        return $tickets;
    }

    private function columnExists(string $tableName, string $columnName): bool
    {
        $sql = 'SELECT COUNT(*)
            FROM information_schema.columns
            WHERE table_schema = DATABASE()
              AND table_name = :table_name
              AND column_name = :column_name';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([
            ':table_name' => $tableName,
            ':column_name' => $columnName,
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }

    private function insertOrderLine(int $orderId, array $item): int
    {
        $quantity = max(1, (int) ($item['quantity'] ?? 1));
        $unitPrice = round((float) ($item['unit_price'] ?? 0), 2);
        $lineTotal = round((float) ($item['total_price'] ?? ($unitPrice * $quantity)), 2);
        $vatRate = $this->vatRateForItem(trim((string) ($item['type'] ?? 'booking')));

        $extraData = array_diff_key($item, array_flip([
            'title', 'selection_text', 'ticket_title', 'ticket_summary_text',
            'quantity', 'unit_price', 'total_price', 'line_total', 'location_name',
            'special_requests', 'type', 'event_id', 'ticket_type_id',
        ]));

        $sql = 'INSERT INTO order_line
            (order_id, item_type, title, selection_text, ticket_title, ticket_summary_text,
             quantity, unit_price, line_total, vat_rate, location_name, special_requests, event_id, ticket_type_id, item_data)
            VALUES
            (:order_id, :item_type, :title, :selection_text, :ticket_title, :ticket_summary_text,
             :quantity, :unit_price, :line_total, :vat_rate, :location_name, :special_requests, :event_id, :ticket_type_id, :item_data)';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([
            ':order_id' => $orderId,
            ':item_type' => trim((string) ($item['type'] ?? 'booking')),
            ':title' => trim((string) ($item['title'] ?? 'Festival Booking')),
            ':selection_text' => trim((string) ($item['selection_text'] ?? '')),
            ':ticket_title' => trim((string) ($item['ticket_title'] ?? '')),
            ':ticket_summary_text' => trim((string) ($item['ticket_summary_text'] ?? '')),
            ':quantity' => $quantity,
            ':unit_price' => $unitPrice,
            ':line_total' => $lineTotal,
            ':vat_rate' => $vatRate,
            ':location_name' => trim((string) ($item['location_name'] ?? '')),
            ':special_requests' => trim((string) ($item['special_requests'] ?? '')),
            ':event_id' => max(0, (int) ($item['event_id'] ?? 0)) ?: null,
            ':ticket_type_id' => max(0, (int) ($item['ticket_type_id'] ?? 0)) ?: null,
            ':item_data' => $extraData !== [] ? json_encode($extraData, JSON_UNESCAPED_UNICODE) : null,
        ]);

        return (int) $this->getConnection()->lastInsertId();
    }

    private function insertTicketsForItem(int $orderLineId, array $item): void
    {
        $ticketTypeId = (int) ($item['ticket_type_id'] ?? 0);
        // Event tickets: one QR per seat. Other items (reservations, tours): one QR per booking.
        $quantity = $ticketTypeId > 0 ? max(1, (int) ($item['quantity'] ?? 1)) : 1;

        $ticketStmt = $this->getConnection()->prepare(
            'INSERT INTO ticket (order_line_id, qr_token, status) VALUES (?, ?, ?)'
        );
        for ($i = 0; $i < $quantity; $i++) {
            $ticketStmt->execute([$orderLineId, bin2hex(random_bytes(32)), 'valid']);
        }
    }

    /** Dutch VAT: food &amp; drink (restaurant reservations) at the 9% low rate, everything else at 21%. */
    private function vatRateForItem(string $type): float
    {
        return $type === 'yummy-reservation' ? 9.00 : 21.00;
    }

    /** Persist a reservation row for a yummy-reservation line and link it back to the order_line. */
    private function insertReservationForLine(int $orderId, int $orderLineId, int $userId, array $item): void
    {
        $slug = trim((string) ($item['page_slug'] ?? ''));
        if ($slug === '') {
            return;
        }

        $lookup = $this->getConnection()->prepare('SELECT restaurant_id FROM restaurant WHERE slug = :slug LIMIT 1');
        $lookup->execute([':slug' => $slug]);
        $restaurantId = (int) $lookup->fetchColumn();
        if ($restaurantId <= 0) {
            return; // no matching restaurant venue — skip persisting a reservation row
        }

        $resStmt = $this->getConnection()->prepare(
            'INSERT INTO reservation
                (restaurant_id, user_id, order_id, reservation_date, session, adult_count, child_count, special_requests, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $resStmt->execute([
            $restaurantId,
            $userId,
            $orderId,
            trim((string) ($item['day'] ?? '')),
            trim((string) ($item['time'] ?? '')),
            max(0, (int) ($item['adult_count'] ?? 0)),
            max(0, (int) ($item['child_count'] ?? 0)),
            trim((string) ($item['special_requests'] ?? '')) ?: null,
            'confirmed',
        ]);
        $reservationId = (int) $this->getConnection()->lastInsertId();

        $upd = $this->getConnection()->prepare('UPDATE order_line SET reservation_id = :rid WHERE order_line_id = :lid');
        $upd->execute([':rid' => $reservationId, ':lid' => $orderLineId]);
    }

    private function calculateTotal(array $items): float
    {
        $total = 0.0;
        foreach ($items as $item) {
            $total += round((float) ($item['total_price'] ?? 0), 2);
        }

        return round($total, 2);
    }
}
