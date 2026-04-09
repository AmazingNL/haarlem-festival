<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Services\ProgramService;
use Stripe\Checkout\Session as StripeCheckoutSession;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;

final class ShopController extends BaseController
{
    private ProgramService $programService;

    public function __construct(ProgramService $programService)
    {
        $this->programService = $programService;
    }

    public function checkout(): void
    {
        $this->ensureSession();

        if (!$this->programService->hasItems()) {
            $this->setErrorMessage('Add at least one ticket to My Program before checkout.');
            $this->redirect('/events');
            return;
        }

        if (!$this->isLoggedIn()) {
            $_SESSION['auth_redirect'] = '/checkout';
            $this->setErrorMessage('Log in before you pay for My Program.');
            $this->redirect('/loginForm');
            return;
        }

        $this->view('shop/checkout', [
            'title' => 'Checkout',
            'programItems' => $this->programService->getItems(),
            'programTotal' => $this->programService->getTotal(),
            'customerName' => (string) ($_SESSION['user_name'] ?? 'Guest'),
            'customerEmail' => (string) ($_SESSION['user_email'] ?? ''),
            'customerPhone' => (string) ($_SESSION['user_phone'] ?? ''),
        ]);
    }

    public function pay(): void
    {
        $this->ensureSession();
        $this->verifyCsrf();

        if (!$this->isLoggedIn()) {
            $_SESSION['auth_redirect'] = '/program';
            $this->redirect('/loginForm');
            return;
        }

        $programItems = $this->programService->getItems();
        if ($programItems === []) {
            $this->setErrorMessage('My Program is empty.');
            $this->redirect('/program');
            return;
        }

        try {
            // Start a Stripe checkout session with the items from My Program.
            Stripe::setApiKey($this->stripeSecretKey());

            $provider = $this->normalizePaymentProvider((string) $this->input('payment_provider', 'ideal'));
            $customer = $this->getCustomerData();

            $checkoutSession = StripeCheckoutSession::create($this->buildCheckoutSessionPayload(
                $provider,
                $programItems,
                $customer
            ));

            $this->programService->storePendingStripeCheckout(
                (string) $checkoutSession->id,
                (int) $this->currentUserId(),
                $customer,
                $programItems,
                $provider
            );

            $this->redirect((string) $checkoutSession->url, 303);
        } catch (ApiErrorException $e) {
            $this->setErrorMessage('The secure payment page could not be opened. Please try again.');
            $this->redirect('/program');
        } catch (\Throwable $e) {
            $this->setErrorMessage('The payment could not be started right now.');
            $this->redirect('/program');
        }
    }

    public function checkoutSuccess(): void
    {
        $this->ensureSession();

        $sessionId = trim($this->str('session_id'));
        if ($sessionId === '') {
            $this->setErrorMessage('The payment page did not return a valid session.');
            $this->redirect('/program');
            return;
        }

        if (!$this->isLoggedIn()) {
            $_SESSION['auth_redirect'] = '/checkout/success?session_id=' . urlencode($sessionId);
            $this->redirect('/loginForm');
            return;
        }

        $existingOrder = $this->programService->findOrderByStripeSessionId((int) $this->currentUserId(), $sessionId);
        if ($existingOrder !== null) {
            $this->redirect('/orders/' . (int) ($existingOrder['order_id'] ?? 0) . '/success');
            return;
        }

        $pendingCheckout = $this->programService->getPendingStripeCheckout($sessionId);
        if ($pendingCheckout === null || (int) ($pendingCheckout['user_id'] ?? 0) !== (int) $this->currentUserId()) {
            $this->setErrorMessage('This Stripe checkout could not be matched to your program.');
            $this->redirect('/program');
            return;
        }

        try {
            Stripe::setApiKey($this->stripeSecretKey());
            $checkoutSession = StripeCheckoutSession::retrieve($sessionId);

            if ((string) ($checkoutSession->payment_status ?? '') !== 'paid') {
                $this->setErrorMessage('The payment has not been marked as paid yet.');
                $this->redirect('/program');
                return;
            }

            // Use the data we saved before the redirect to Stripe.
            $customer = $this->getCustomerData();
            if (is_array($pendingCheckout['customer'] ?? null)) {
                $customer = $pendingCheckout['customer'];
            }

            $items = [];
            if (is_array($pendingCheckout['items'] ?? null)) {
                $items = $pendingCheckout['items'];
            }

            $orderId = $this->programService->createPaidOrder(
                (int) $this->currentUserId(),
                $customer,
                (string) ($pendingCheckout['provider'] ?? 'stripe-ideal'),
                $items,
                $sessionId
            );

            $this->programService->clearPendingStripeCheckout($sessionId);
            $this->redirect('/orders/' . $orderId . '/success');
        } catch (ApiErrorException $e) {
            $this->setErrorMessage('The payment could not be verified.');
            $this->redirect('/program');
        } catch (\Throwable $e) {
            $this->setErrorMessage('The order could not be completed right now.');
            $this->redirect('/program');
        }
    }

    public function checkoutCancel(): void
    {
        $this->ensureSession();
        $this->setErrorMessage('The payment was cancelled. Your selected tickets are still saved in My Program.');
        $this->redirect('/program');
    }

    public function success(int $orderId): void
    {
        $order = $this->getOrderForSuccessPage($orderId, '/orders/' . $orderId . '/success');
        if ($order === null) {
            return;
        }

        $this->view('shop/success', [
            'title' => 'Payment Complete',
            'order' => $order,
            'orderItems' => is_array($order['items'] ?? null) ? $order['items'] : [],
        ]);
    }

    private function getOrderForSuccessPage(int $orderId, string $redirectPath): ?array
    {
        $this->ensureSession();

        if (!$this->isLoggedIn()) {
            $_SESSION['auth_redirect'] = $redirectPath;
            $this->redirect('/loginForm');
            return null;
        }

        $order = $this->programService->getOrderForUser($orderId, (int) $this->currentUserId());
        if ($order === null) {
            $this->abort(404, 'Order not found.');
        }

        return $order;
    }

    private function buildCheckoutSessionPayload(string $provider, array $programItems, array $customer): array
    {
        $payload = [
            'mode' => 'payment',
            'payment_method_types' => $this->getPaymentMethodTypes($provider),
            'line_items' => $this->buildCheckoutLineItems($programItems),
            'success_url' => $this->appUrl() . '/checkout/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $this->appUrl() . '/checkout/cancel',
            'client_reference_id' => (string) $this->currentUserId(),
            'metadata' => [
                'user_id' => (string) $this->currentUserId(),
                'program_items' => (string) count($programItems),
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

            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $title,
                        'description' => implode(' | ', $descriptionParts),
                    ],
                    'unit_amount' => (int) round(((float) ($programItem['unit_price'] ?? 0)) * 100),
                ],
                'quantity' => max(1, (int) ($programItem['quantity'] ?? 1)),
            ];
        }

        return $lineItems;
    }

    private function getPaymentMethodTypes(string $provider): array
    {
        return match ($provider) {
            'card' => ['card'],
            'card-ideal' => ['ideal', 'card'],
            'stripe-card' => ['card'],
            'stripe-ideal-card' => ['ideal', 'card'],
            default => ['ideal'],
        };
    }

    private function getCustomerData(): array
    {
        return [
            'first_name' => (string) ($_SESSION['user_first_name'] ?? ''),
            'last_name' => (string) ($_SESSION['user_last_name'] ?? ''),
            'email' => (string) ($_SESSION['user_email'] ?? ''),
            'phone' => (string) ($_SESSION['user_phone'] ?? ''),
        ];
    }

    private function normalizePaymentProvider(string $provider): string
    {
        $provider = trim($provider);
        $allowedProviders = ['ideal', 'card', 'card-ideal', 'stripe-ideal', 'stripe-card', 'stripe-ideal-card'];

        if (!in_array($provider, $allowedProviders, true)) {
            return 'ideal';
        }

        return match ($provider) {
            'stripe-ideal' => 'ideal',
            'stripe-card' => 'card',
            'stripe-ideal-card' => 'card-ideal',
            default => $provider,
        };
    }

    private function stripeSecretKey(): string
    {
        $key = trim((string) ($_ENV['STRIPE_SECRET_KEY'] ?? ''));
        if ($key === '') {
            throw new \RuntimeException('Stripe secret key is missing.');
        }

        return $key;
    }

    private function appUrl(): string
    {
        $configuredUrl = trim((string) ($_ENV['APP_URL'] ?? ''));
        if ($configuredUrl !== '') {
            return rtrim($configuredUrl, '/');
        }

        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = trim((string) ($_SERVER['HTTP_HOST'] ?? 'localhost'));

        return $scheme . '://' . $host;
    }
}
