<?php

declare(strict_types=1);

namespace App\Services;

final class ProgramService
{
    private const ITEMS_KEY = 'program_items';
    private const ORDERS_KEY = 'program_orders';
    private const PENDING_CHECKOUTS_KEY = 'pending_stripe_checkouts';
    private const ORDER_SEQUENCE_KEY = 'program_order_sequence';
    private const LAST_ORDER_KEY = 'last_order_id';

    // Return the current My Program items from the session.
    public function getItems(): array
    {
        $this->ensureSession();

        $items = $_SESSION[self::ITEMS_KEY] ?? [];
        if (!is_array($items)) {
            return [];
        }

        $items = $this->deduplicateItems(array_values($items));
        $_SESSION[self::ITEMS_KEY] = $items;

        return $items;
    }

    // Add a program item or replace the matching one with the newest selection.
    public function addItem(array $item): void
    {
        $this->ensureSession();

        $items = $this->getItems();
        $normalizedItem = $this->normalizeItem($item);
        $matchingIndex = $this->findMatchingProgramItemIndex($items, $normalizedItem);

        if ($matchingIndex !== null) {
            $normalizedItem['id'] = (string) ($items[$matchingIndex]['id'] ?? $normalizedItem['id']);
            $items[$matchingIndex] = $normalizedItem;
        } else {
            $items[] = $normalizedItem;
        }

        $_SESSION[self::ITEMS_KEY] = $items;
    }

    // Remove one program item by its generated id.
    public function removeItem(string $itemId): void
    {
        $this->ensureSession();

        $_SESSION[self::ITEMS_KEY] = array_values(array_filter(
            $this->getItems(),
            static fn(array $item): bool => (string) ($item['id'] ?? '') !== $itemId
        ));
    }

    // Remove multiple items after they have been turned into a paid order.
    public function removeItemsByIds(array $itemIds): void
    {
        $this->ensureSession();

        $itemIds = array_values(array_filter(array_map(
            static fn(mixed $itemId): string => trim((string) $itemId),
            $itemIds
        )));

        if ($itemIds === []) {
            return;
        }

        $_SESSION[self::ITEMS_KEY] = array_values(array_filter(
            $this->getItems(),
            static fn(array $item): bool => !in_array((string) ($item['id'] ?? ''), $itemIds, true)
        ));
    }

    // Empty the current My Program cart.
    public function clearItems(): void
    {
        $this->ensureSession();
        unset($_SESSION[self::ITEMS_KEY]);
    }

    // Check whether My Program currently has any saved items.
    public function hasItems(): bool
    {
        return $this->getItems() !== [];
    }

    // Count the total quantity across all saved items.
    public function getItemCount(): int
    {
        $count = 0;
        foreach ($this->getItems() as $item) {
            $count += max(1, (int) ($item['quantity'] ?? 1));
        }

        return $count;
    }

    // Calculate the full total for the current My Program cart.
    public function getTotal(): float
    {
        return $this->calculateTotal($this->getItems());
    }

    // Save a Stripe checkout snapshot so success can rebuild the order after redirecting back.
    public function storePendingStripeCheckout(
        string $sessionId,
        int $userId,
        array $customer,
        array $items,
        string $provider
    ): void
    {
        $this->ensureSession();

        $pendingCheckouts = $_SESSION[self::PENDING_CHECKOUTS_KEY] ?? [];
        if (!is_array($pendingCheckouts)) {
            $pendingCheckouts = [];
        }

        $pendingCheckouts[$sessionId] = [
            'session_id' => $sessionId,
            'user_id' => $userId,
            'customer' => $customer,
            'items' => array_map(fn(array $item): array => $this->normalizeItem($item), $items),
            'provider' => $provider,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $_SESSION[self::PENDING_CHECKOUTS_KEY] = $pendingCheckouts;
    }

    // Read one pending Stripe checkout by session id.
    public function getPendingStripeCheckout(string $sessionId): ?array
    {
        $this->ensureSession();

        $pendingCheckouts = $_SESSION[self::PENDING_CHECKOUTS_KEY] ?? [];
        $checkout = is_array($pendingCheckouts) ? ($pendingCheckouts[$sessionId] ?? null) : null;

        return is_array($checkout) ? $checkout : null;
    }

    // Remove a pending Stripe checkout after the order is completed.
    public function clearPendingStripeCheckout(string $sessionId): void
    {
        $this->ensureSession();

        $pendingCheckouts = $_SESSION[self::PENDING_CHECKOUTS_KEY] ?? [];
        if (!is_array($pendingCheckouts)) {
            return;
        }

        unset($pendingCheckouts[$sessionId]);
        $_SESSION[self::PENDING_CHECKOUTS_KEY] = $pendingCheckouts;
    }

    // Turn paid program items into an order record with invoice data and generated tickets.
    public function createPaidOrder(
        int $userId,
        array $customer,
        string $provider = 'stripe-ideal',
        ?array $itemsOverride = null,
        ?string $stripeSessionId = null
    ): int
    {
        $this->ensureSession();

        if ($stripeSessionId !== null) {
            $existingOrder = $this->findOrderByStripeSessionId($userId, $stripeSessionId);
            if ($existingOrder !== null) {
                $_SESSION[self::LAST_ORDER_KEY] = (int) ($existingOrder['order_id'] ?? 0);
                return (int) ($existingOrder['order_id'] ?? 0);
            }
        }

        $items = is_array($itemsOverride) ? array_values($itemsOverride) : $this->getItems();
        if ($items === []) {
            throw new \InvalidArgumentException('My Program is empty.');
        }

        $orderId = $this->getNextOrderId();
        $timestamp = date('Y-m-d H:i:s');
        $orderItems = [];
        $tickets = [];
        $ticketNumber = 1;

        foreach ($items as $item) {
            $normalizedItem = $this->normalizeItem($item);
            $quantity = max(1, (int) ($normalizedItem['quantity'] ?? 1));
            $unitPrice = round((float) ($normalizedItem['unit_price'] ?? 0), 2);
            $lineTotal = round($unitPrice * $quantity, 2);
            $title = trim((string) ($normalizedItem['title'] ?? 'Festival Booking'));
            $selectionText = trim((string) ($normalizedItem['selection_text'] ?? ''));
            $ticketTitle = trim((string) ($normalizedItem['ticket_title'] ?? 'Ticket'));
            $ticketSummaryText = trim((string) ($normalizedItem['ticket_summary_text'] ?? ''));
            $locationName = trim((string) ($normalizedItem['location_name'] ?? 'Bavo Church'));

            $orderItems[] = [
                'title' => $title,
                'selection_text' => $selectionText,
                'ticket_title' => $ticketTitle,
                'ticket_summary_text' => $ticketSummaryText,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'line_total' => $lineTotal,
                'location_name' => $locationName,
            ];

            for ($index = 0; $index < $quantity; $index++) {
                $tickets[] = [
                    'ticket_id' => ($orderId * 100) + $ticketNumber,
                    'ticket_number' => $ticketNumber,
                    'qr_token' => strtoupper(substr(bin2hex(random_bytes(10)), 0, 16)),
                    'status' => 'valid',
                    'title' => $title,
                    'selection_text' => $selectionText,
                    'ticket_title' => $ticketTitle,
                    'ticket_summary_text' => $ticketSummaryText,
                    'location_name' => $locationName,
                ];

                $ticketNumber++;
            }
        }

        $order = [
            'order_id' => $orderId,
            'user_id' => $userId,
            'total_price' => $this->calculateTotal($items),
            'status' => 'paid',
            'created_at' => $timestamp,
            'provider' => $provider,
            'payment_status' => 'paid',
            'paid_at' => $timestamp,
            'stripe_session_id' => $stripeSessionId,
            'first_name' => trim((string) ($customer['first_name'] ?? '')),
            'last_name' => trim((string) ($customer['last_name'] ?? '')),
            'email' => trim((string) ($customer['email'] ?? '')),
            'phone' => trim((string) ($customer['phone'] ?? '')),
            'items' => $orderItems,
            'tickets' => $tickets,
        ];

        $orders = $_SESSION[self::ORDERS_KEY] ?? [];
        if (!is_array($orders)) {
            $orders = [];
        }

        $orders[(string) $orderId] = $order;
        $_SESSION[self::ORDERS_KEY] = $orders;
        $_SESSION[self::LAST_ORDER_KEY] = $orderId;

        if (is_array($itemsOverride)) {
            $this->removeItemsByIds(array_column($itemsOverride, 'id'));
        } else {
            $this->clearItems();
        }

        return $orderId;
    }

    // Return all paid orders that belong to the current user.
    public function getPaidOrdersForUser(int $userId): array
    {
        $this->ensureSession();

        $orders = $_SESSION[self::ORDERS_KEY] ?? [];
        if (!is_array($orders)) {
            return [];
        }

        $userOrders = array_values(array_filter(
            $orders,
            static fn(mixed $order): bool => is_array($order) && (int) ($order['user_id'] ?? 0) === $userId
        ));

        usort(
            $userOrders,
            static fn(array $left, array $right): int => (int) ($right['order_id'] ?? 0) <=> (int) ($left['order_id'] ?? 0)
        );

        return $userOrders;
    }

    // Return one order only if it belongs to the logged-in user.
    public function getOrderForUser(int $orderId, int $userId): ?array
    {
        $this->ensureSession();

        $orders = $_SESSION[self::ORDERS_KEY] ?? [];
        $order = is_array($orders) ? ($orders[(string) $orderId] ?? null) : null;
        if (!is_array($order)) {
            return null;
        }

        if ((int) ($order['user_id'] ?? 0) !== $userId) {
            return null;
        }

        return $order;
    }

    // Prevent duplicate success handling by checking whether this Stripe session already created an order.
    public function findOrderByStripeSessionId(int $userId, string $stripeSessionId): ?array
    {
        $this->ensureSession();

        foreach ($this->getPaidOrdersForUser($userId) as $order) {
            if ((string) ($order['stripe_session_id'] ?? '') === $stripeSessionId) {
                return $order;
            }
        }

        return null;
    }

    // Return the last successful order id for the confirmation shortcut on My Program.
    public function getLastOrderId(): int
    {
        $this->ensureSession();

        $orderId = (int) ($_SESSION[self::LAST_ORDER_KEY] ?? 0);
        if ($orderId < 1) {
            return 0;
        }

        $orders = $_SESSION[self::ORDERS_KEY] ?? [];
        if (!is_array($orders) || !is_array($orders[(string) $orderId] ?? null)) {
            return 0;
        }

        return $orderId;
    }

    // Sum all normalized item totals into one final amount.
    private function calculateTotal(array $items): float
    {
        $total = 0.0;
        foreach ($items as $item) {
            $normalizedItem = $this->normalizeItem($item);
            $total += (float) ($normalizedItem['total_price'] ?? 0);
        }

        return round($total, 2);
    }

    // Normalize one program item so event tickets and history bookings share one consistent structure.
    private function normalizeItem(array $item): array
    {
        $quantity = max(1, min(10, (int) ($item['quantity'] ?? 1)));
        $unitPrice = round((float) ($item['unit_price'] ?? 0), 2);
        $type = trim((string) ($item['type'] ?? 'history-book-tour'));
        if ($type === '') {
            $type = 'history-book-tour';
        }

        return [
            'id' => trim((string) ($item['id'] ?? '')) !== ''
                ? trim((string) $item['id'])
                : bin2hex(random_bytes(8)),
            'type' => $type,
            'event_id' => max(0, (int) ($item['event_id'] ?? 0)),
            'ticket_type_id' => max(0, (int) ($item['ticket_type_id'] ?? 0)),
            'title' => trim((string) ($item['title'] ?? 'Festival Booking')),
            'day' => trim((string) ($item['day'] ?? '')),
            'time' => trim((string) ($item['time'] ?? '')),
            'language' => trim((string) ($item['language'] ?? '')),
            'ticket_key' => trim((string) ($item['ticket_key'] ?? '')),
            'ticket_title' => trim((string) ($item['ticket_title'] ?? '')),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => round($unitPrice * $quantity, 2),
            'selection_text' => trim((string) ($item['selection_text'] ?? '')),
            'ticket_summary_text' => trim((string) ($item['ticket_summary_text'] ?? '')),
            'location_name' => trim((string) ($item['location_name'] ?? 'Bavo Church')),
            'category_label' => trim((string) ($item['category_label'] ?? 'Festival')),
            'starts_at' => trim((string) ($item['starts_at'] ?? '')),
            'ends_at' => trim((string) ($item['ends_at'] ?? '')),
        ];
    }

    // Remove duplicates so the same selection is stored only once.
    private function deduplicateItems(array $items): array
    {
        $uniqueItems = [];

        foreach ($items as $item) {
            $normalizedItem = $this->normalizeItem($item);
            $matchingIndex = $this->findMatchingProgramItemIndex($uniqueItems, $normalizedItem);

            if ($matchingIndex !== null) {
                $normalizedItem['id'] = (string) ($uniqueItems[$matchingIndex]['id'] ?? $normalizedItem['id']);
                $uniqueItems[$matchingIndex] = $normalizedItem;
                continue;
            }

            $uniqueItems[] = $normalizedItem;
        }

        return $uniqueItems;
    }

    // Find the matching line in My Program for a newly added item.
    private function findMatchingProgramItemIndex(array $items, array $candidate): ?int
    {
        foreach ($items as $index => $item) {
            if ($this->isSameProgramItem($item, $candidate)) {
                return $index;
            }
        }

        return null;
    }

    // Decide whether two program items represent the same booking or ticket choice.
    private function isSameProgramItem(array $left, array $right): bool
    {
        $leftEventId = (int) ($left['event_id'] ?? 0);
        $rightEventId = (int) ($right['event_id'] ?? 0);
        $leftTicketTypeId = (int) ($left['ticket_type_id'] ?? 0);
        $rightTicketTypeId = (int) ($right['ticket_type_id'] ?? 0);

        if ($leftEventId > 0 || $rightEventId > 0 || $leftTicketTypeId > 0 || $rightTicketTypeId > 0) {
            return trim((string) ($left['type'] ?? '')) === trim((string) ($right['type'] ?? ''))
                && $leftEventId === $rightEventId
                && $leftTicketTypeId === $rightTicketTypeId;
        }

        $keys = ['type', 'title', 'day', 'time', 'language', 'ticket_key', 'location_name'];

        foreach ($keys as $key) {
            if (trim((string) ($left[$key] ?? '')) !== trim((string) ($right[$key] ?? ''))) {
                return false;
            }
        }

        return true;
    }

    // Generate the next simple order number in this session-based checkout flow.
    private function getNextOrderId(): int
    {
        $currentValue = (int) ($_SESSION[self::ORDER_SEQUENCE_KEY] ?? 0) + 1;
        $_SESSION[self::ORDER_SEQUENCE_KEY] = $currentValue;

        return $currentValue;
    }

    // Start the PHP session if it has not been started yet.
    private function ensureSession(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
}
