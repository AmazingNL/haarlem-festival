<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Repositories\IOrderRepository;

final class OrderService
{
    private const LAST_ORDER_KEY = 'last_order_id';

    private IOrderRepository $orderRepository;

    public function __construct(IOrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function completePaidCheckout(
        int $userId,
        array $customer,
        string $provider,
        array $items,
        string $stripeSessionId,
        ?string $providerPaymentId = null
    ): int
    {
        $existing = $this->orderRepository->findByStripeSessionId($userId, $stripeSessionId);
        if ($existing !== null) {
            $orderId = (int) ($existing['order_id'] ?? 0);
            $this->rememberLastOrder($orderId);
            return $orderId;
        }

        $orderId = $this->orderRepository->createPaidOrder(
            $userId,
            $customer,
            $provider,
            $items,
            $stripeSessionId,
            $providerPaymentId
        );

        $this->rememberLastOrder($orderId);

        return $orderId;
    }

    public function findOrderForUser(int $orderId, int $userId): ?array
    {
        return $this->orderRepository->findOrderForUser($orderId, $userId);
    }

    public function findPaidOrdersForUser(int $userId): array
    {
        return $this->orderRepository->findPaidOrdersForUser($userId);
    }

    public function findByStripeSessionId(int $userId, string $stripeSessionId): ?array
    {
        return $this->orderRepository->findByStripeSessionId($userId, $stripeSessionId);
    }

    public function getLastOrderId(int $userId): int
    {
        $this->ensureSession();

        $sessionOrderId = (int) ($_SESSION[self::LAST_ORDER_KEY] ?? 0);
        if ($sessionOrderId > 0) {
            $order = $this->orderRepository->findOrderForUser($sessionOrderId, $userId);
            if ($order !== null) {
                return $sessionOrderId;
            }
        }

        return $this->orderRepository->getLatestOrderIdForUser($userId);
    }

    public function findOrdersForAdmin(): array
    {
        return $this->orderRepository->findOrdersForAdmin();
    }

    public function findOrderForAdmin(int $orderId): ?array
    {
        if ($orderId <= 0) {
            return null;
        }

        return $this->orderRepository->findOrderForAdmin($orderId);
    }

    public function getOrderExportRows(): array
    {
        return $this->orderRepository->findOrderExportRows();
    }

    private function rememberLastOrder(int $orderId): void
    {
        if ($orderId <= 0) {
            return;
        }

        $this->ensureSession();
        $_SESSION[self::LAST_ORDER_KEY] = $orderId;
    }

    private function ensureSession(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
}
