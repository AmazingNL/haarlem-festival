<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Support\PaymentProvider;
use App\Support\StripeConfig;
use Stripe\Checkout\Session as StripeCheckoutSession;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe;
use Stripe\Webhook;

final class StripePaymentService
{
    public function createCheckoutSession(
        int $userId,
        string $provider,
        array $items,
        array $customer
    ): StripeCheckoutSession
    {
        $this->assertCheckoutItemsAreChargeable($items);

        Stripe::setApiKey(StripeConfig::secretKey());

        return StripeCheckoutSession::create($this->buildCheckoutSessionPayload($userId, $provider, $items, $customer));
    }

    /** @param list<array<string, mixed>> $items */
    private function assertCheckoutItemsAreChargeable(array $items): void
    {
        if ($items === []) {
            throw new \InvalidArgumentException('My Program is empty.');
        }

        foreach ($items as $item) {
            $unitPrice = round((float) ($item['unit_price'] ?? 0), 2);
            if ($unitPrice < 0.50) {
                throw new \InvalidArgumentException(
                    'Item "' . trim((string) ($item['title'] ?? 'Booking')) . '" has an invalid price for checkout.'
                );
            }
        }
    }

    public function retrieveCheckoutSession(string $sessionId): StripeCheckoutSession
    {
        Stripe::setApiKey(StripeConfig::secretKey());

        return StripeCheckoutSession::retrieve($sessionId);
    }

    public function verifyPaidSession(StripeCheckoutSession $session, int $userId, int $expectedTotalCents): void
    {
        if ((string) ($session->payment_status ?? '') !== 'paid') {
            throw new \RuntimeException('Payment is not marked as paid.');
        }

        if ((string) ($session->client_reference_id ?? '') !== (string) $userId) {
            throw new \RuntimeException('Checkout session user mismatch.');
        }

        $metadataUserId = (string) (($session->metadata['user_id'] ?? '') ?? '');
        if ($metadataUserId !== '' && $metadataUserId !== (string) $userId) {
            throw new \RuntimeException('Checkout metadata user mismatch.');
        }

        $sessionAmount = (int) ($session->amount_total ?? 0);
        if ($sessionAmount !== $expectedTotalCents) {
            throw new \RuntimeException('Checkout amount mismatch.');
        }
    }

    public function parseWebhookEvent(string $payload, ?string $signatureHeader): \Stripe\Event
    {
        $webhookSecret = trim((string) ($_ENV['STRIPE_WEBHOOK_SECRET'] ?? ''));
        if ($webhookSecret === '') {
            throw new \RuntimeException('Stripe webhook secret is missing.');
        }

        if ($signatureHeader === null || trim($signatureHeader) === '') {
            throw new \RuntimeException('Stripe signature header is missing.');
        }

        try {
            return Webhook::constructEvent($payload, $signatureHeader, $webhookSecret);
        } catch (SignatureVerificationException $e) {
            throw new \RuntimeException('Invalid Stripe webhook signature.', 0, $e);
        }
    }

    private function buildCheckoutSessionPayload(
        int $userId,
        string $provider,
        array $items,
        array $customer
    ): array
    {
        $payload = [
            'mode' => 'payment',
            'payment_method_types' => PaymentProvider::stripeMethodTypes($provider),
            'line_items' => $this->buildCheckoutLineItems($items),
            'success_url' => StripeConfig::appUrl() . '/checkout/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => StripeConfig::appUrl() . '/checkout/cancel',
            'client_reference_id' => (string) $userId,
            'branding_settings' => [
                'display_name' => StripeConfig::checkoutDisplayName(),
            ],
            'metadata' => [
                'user_id' => (string) $userId,
                'program_items' => (string) count($items),
            ],
        ];

        $customerEmail = trim((string) ($customer['email'] ?? ''));
        if ($customerEmail !== '') {
            $payload['customer_email'] = $customerEmail;
        }

        return $payload;
    }

    private function buildCheckoutLineItems(array $programItems): array
    {
        $lineItems = [];

        foreach ($programItems as $programItem) {
            $title = trim((string) ($programItem['title'] ?? 'Festival Booking'));
            $selectionText = trim((string) ($programItem['selection_text'] ?? ''));
            $ticketSummaryText = trim((string) ($programItem['ticket_summary_text'] ?? ''));
            $descriptionParts = array_values(array_filter([$selectionText, $ticketSummaryText]));
            $description = implode(' | ', $descriptionParts);
            if (strlen($description) > 500) {
                $description = substr($description, 0, 497) . '...';
            }

            $productData = [
                'name' => mb_substr($title, 0, 250),
            ];
            if ($description !== '') {
                $productData['description'] = $description;
            }

            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => $productData,
                    'unit_amount' => (int) round(((float) ($programItem['unit_price'] ?? 0)) * 100),
                ],
                'quantity' => max(1, (int) ($programItem['quantity'] ?? 1)),
            ];
        }

        return $lineItems;
    }
}
