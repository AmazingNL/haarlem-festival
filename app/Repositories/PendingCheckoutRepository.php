<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\BaseRepository;
use PDO;

final class PendingCheckoutRepository extends BaseRepository
{
    public function store(
        string $sessionId,
        int $userId,
        array $customer,
        array $items,
        string $provider
    ): void
    {
        $sql = 'INSERT INTO pending_stripe_checkout
            (stripe_session_id, user_id, customer_json, items_json, provider, created_at)
            VALUES
            (:stripe_session_id, :user_id, :customer_json, :items_json, :provider, :created_at)
            ON DUPLICATE KEY UPDATE
                user_id = VALUES(user_id),
                customer_json = VALUES(customer_json),
                items_json = VALUES(items_json),
                provider = VALUES(provider),
                created_at = VALUES(created_at)';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([
            ':stripe_session_id' => $sessionId,
            ':user_id' => $userId,
            ':customer_json' => json_encode($customer, JSON_UNESCAPED_UNICODE),
            ':items_json' => json_encode($items, JSON_UNESCAPED_UNICODE),
            ':provider' => $provider,
            ':created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function find(string $sessionId): ?array
    {
        $sql = 'SELECT * FROM pending_stripe_checkout WHERE stripe_session_id = :stripe_session_id LIMIT 1';
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([':stripe_session_id' => $sessionId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!is_array($row)) {
            return null;
        }

        $customer = json_decode((string) ($row['customer_json'] ?? ''), true);
        $items = json_decode((string) ($row['items_json'] ?? ''), true);

        return [
            'session_id' => (string) ($row['stripe_session_id'] ?? ''),
            'user_id' => (int) ($row['user_id'] ?? 0),
            'customer' => is_array($customer) ? $customer : [],
            'items' => is_array($items) ? $items : [],
            'provider' => (string) ($row['provider'] ?? 'ideal'),
            'created_at' => (string) ($row['created_at'] ?? ''),
        ];
    }

    public function delete(string $sessionId): void
    {
        $sql = 'DELETE FROM pending_stripe_checkout WHERE stripe_session_id = :stripe_session_id';
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([':stripe_session_id' => $sessionId]);
    }
}
