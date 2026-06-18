<?php

declare(strict_types=1);

namespace App\Repositories;

interface IOrderRepository
{
    public function createPaidOrder(
        int $userId,
        array $customer,
        string $provider,
        array $items,
        string $stripeSessionId,
        ?string $providerPaymentId
    ): int;

    public function findOrderForUser(int $orderId, int $userId): ?array;

    public function findPaidOrdersForUser(int $userId): array;

    public function findByStripeSessionId(int $userId, string $stripeSessionId): ?array;

    public function getLatestOrderIdForUser(int $userId): int;

    public function findOrdersForAdmin(): array;

    public function findOrderForAdmin(int $orderId): ?array;

    public function findOrderExportRows(): array;
}
