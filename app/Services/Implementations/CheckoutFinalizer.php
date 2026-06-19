<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Repositories\PendingCheckoutRepository;
use App\Services\Implementations\Booking\CheckoutValidationService;
use App\Support\PaymentProvider;
use App\Support\SessionUser;
use Stripe\Checkout\Session as StripeCheckoutSession;

/**
 * Finishes a paid checkout — the money-critical sequence shared by the Stripe
 * success redirect and the webhook.
 *
 * It matches the parked checkout, re-validates and re-prices its items, verifies
 * Stripe actually charged the expected amount, then persists the paid order. It
 * is idempotent: a Stripe session that already has an order is returned untouched
 * ('created' => false), so the redirect and the webhook can both run without
 * creating duplicate orders. Sending the confirmation email is a UX concern left
 * to the caller, which is why the resolved customer is returned.
 */
final class CheckoutFinalizer
{
    private OrderService $orderService;
    private CheckoutValidationService $checkoutValidationService;
    private StripePaymentService $stripePaymentService;
    private PendingCheckoutRepository $pendingCheckoutRepository;
    private ProgramService $programService;

    public function __construct(
        OrderService $orderService,
        CheckoutValidationService $checkoutValidationService,
        StripePaymentService $stripePaymentService,
        PendingCheckoutRepository $pendingCheckoutRepository,
        ProgramService $programService
    ) {
        $this->orderService = $orderService;
        $this->checkoutValidationService = $checkoutValidationService;
        $this->stripePaymentService = $stripePaymentService;
        $this->pendingCheckoutRepository = $pendingCheckoutRepository;
        $this->programService = $programService;
    }

    /**
     * @return array{order_id:int, created:bool, customer:array<string,mixed>}
     */
    public function finalize(string $sessionId, int $userId, ?string $providerPaymentId, bool $clearCart): array
    {
        // Idempotency: if this session already produced an order, return it untouched.
        $existingOrder = $this->orderService->findByStripeSessionId($userId, $sessionId);
        if ($existingOrder !== null) {
            return ['order_id' => (int) ($existingOrder['order_id'] ?? 0), 'created' => false, 'customer' => []];
        }

        $pendingCheckout = $this->requireMatchedPendingCheckout($sessionId, $userId);
        $items = $this->revalidateItems($pendingCheckout['items']);
        $checkoutSession = $this->verifyPaidStripeSession($sessionId, $userId, $items);

        $customer = is_array($pendingCheckout['customer'] ?? null) ? $pendingCheckout['customer'] : SessionUser::customerData();
        $provider = PaymentProvider::normalize((string) ($pendingCheckout['provider'] ?? 'ideal'));
        $providerPaymentId = $this->resolveProviderPaymentId($providerPaymentId, $checkoutSession);

        $orderId = $this->orderService->completePaidCheckout(
            $userId,
            $customer,
            $provider,
            $items,
            $sessionId,
            $providerPaymentId !== '' ? $providerPaymentId : null
        );

        $this->pendingCheckoutRepository->delete($sessionId);

        if ($clearCart) {
            $this->programService->removeItemsByIds(array_column($items, 'id'));
        }

        return ['order_id' => $orderId, 'created' => true, 'customer' => $customer];
    }

    /**
     * The parked checkout must exist, belong to this user, and have at least one item.
     *
     * @return array<string, mixed> the pending row, with 'items' guaranteed to be a non-empty array
     */
    private function requireMatchedPendingCheckout(string $sessionId, int $userId): array
    {
        $pendingCheckout = $this->pendingCheckoutRepository->find($sessionId);
        if ($pendingCheckout === null || (int) ($pendingCheckout['user_id'] ?? 0) !== $userId) {
            throw new \RuntimeException('Checkout session could not be matched.');
        }

        $items = is_array($pendingCheckout['items'] ?? null) ? $pendingCheckout['items'] : [];
        if ($items === []) {
            throw new \RuntimeException('Checkout session had no items.');
        }

        $pendingCheckout['items'] = $items;

        return $pendingCheckout;
    }

    /**
     * Re-validate and re-price the stored items; fall back to the stored copy if a
     * now-changed item fails validation (the customer has already paid).
     *
     * @param array<int, array<string, mixed>> $storedItems
     * @return array<int, array<string, mixed>>
     */
    private function revalidateItems(array $storedItems): array
    {
        try {
            return $this->checkoutValidationService->validateAndNormalize($storedItems);
        } catch (\Throwable $e) {
            error_log('Checkout re-validation failed, using stored items: ' . $e->getMessage());
            return $storedItems;
        }
    }

    /** Confirm Stripe charged the expected amount for this user, returning the session. */
    private function verifyPaidStripeSession(string $sessionId, int $userId, array $items): StripeCheckoutSession
    {
        $expectedTotalCents = $this->checkoutValidationService->calculateTotalCents($items);
        $checkoutSession = $this->stripePaymentService->retrieveCheckoutSession($sessionId);
        $this->stripePaymentService->verifyPaidSession($checkoutSession, $userId, $expectedTotalCents);

        return $checkoutSession;
    }

    private function resolveProviderPaymentId(?string $providerPaymentId, StripeCheckoutSession $checkoutSession): string
    {
        if ($providerPaymentId === null || $providerPaymentId === '') {
            return (string) ($checkoutSession->payment_intent ?? '');
        }

        return $providerPaymentId;
    }
}
