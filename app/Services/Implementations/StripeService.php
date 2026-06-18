<?php
namespace App\Services\Implementations;

use App\Repositories\PaymentRepository;
use Stripe\StripeClient;

class StripeService
{
    private PaymentRepository $repo;
    private StripeClient $stripe;

    public function __construct(PaymentRepository $repo)
    {
        $this->repo = $repo;
        $secret = $_ENV['STRIPE_SECRET'] ?? '';
        $this->stripe = new StripeClient($secret);
    }

    /**
     * Create a Checkout session (scaffold).
     * Returns an array with session id or an error message.
     */
    public function createCheckoutSession(int $orderId, float $amount, string $currency = 'EUR'): array
    {
        // This is a scaffold; in real code, map order items and success/cancel URLs.
        try {
            $session = $this->stripe->checkout->sessions->create([
                'payment_method_types' => ['card'],
                'mode' => 'payment',
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => strtolower($currency),
                            'product_data' => ['name' => 'Order ' . $orderId],
                            'unit_amount' => (int) round($amount * 100),
                        ],
                        'quantity' => 1,
                    ]
                ],
                'success_url' => ($_ENV['APP_URL'] ?? '') . '/payments/success?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => ($_ENV['APP_URL'] ?? '') . '/payments/cancel',
            ]);

            return ['id' => $session->id];
        } catch (\Throwable $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Handle a webhook payload (scaffold).
     */
    public function handleWebhook(string $payload, ?string $sigHeader, string $endpointSecret = null): array
    {
        // In scaffold mode we just return parsed event info; production code must verify signature
        try {
            if ($endpointSecret && $sigHeader) {
                $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
            } else {
                $event = json_decode($payload);
            }

            // Example: if payment succeeded, update DB.
            if (isset($event->type) && $event->type === 'checkout.session.completed') {
                // Implement mapping from session -> payment record as needed.
                return ['handled' => true, 'type' => $event->type];
            }

            return ['handled' => false, 'type' => $event->type ?? null];
        } catch (\Throwable $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
