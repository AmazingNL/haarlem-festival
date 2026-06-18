<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Services\Implementations\StripeService;
use App\Repositories\PaymentRepository;

class PaymentController
{
    private StripeService $service;

    public function __construct(StripeService $service)
    {
        $this->service = $service;
    }

    // Show a simple checkout page (scaffold)
    public function checkoutPage(array $vars)
    {
        $orderId = $vars['order_id'] ?? 0;
        // Minimal view: app/Views/payments/checkout.php
        require __DIR__ . '/../Views/payments/checkout.php';
    }

    // Create a Stripe Checkout session
    public function createCheckoutSession()
    {
        $data = $_POST;
        $orderId = (int) ($data['order_id'] ?? 0);
        $amount = (float) ($data['amount'] ?? 0.0);

        $result = $this->service->createCheckoutSession($orderId, $amount);

        header('Content-Type: application/json');
        echo json_encode($result);
    }

    // Stripe webhook endpoint
    public function webhook()
    {
        $payload = file_get_contents('php://input');
        $sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? null;
        $endpointSecret = $_ENV['STRIPE_WEBHOOK_SECRET'] ?? null;

        $result = $this->service->handleWebhook($payload, $sigHeader, $endpointSecret);

        header('Content-Type: application/json');
        echo json_encode($result);
    }
}
