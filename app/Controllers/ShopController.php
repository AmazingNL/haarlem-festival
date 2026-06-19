<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Repositories\PendingCheckoutRepository;
use App\Services\Implementations\Booking\CheckoutValidationService;
use App\Services\Implementations\Booking\EventBookingService;
use App\Services\Implementations\Booking\HistoryBookingService;
use App\Services\Implementations\Booking\ReservationService;
use App\Services\Implementations\Booking\RestaurantAvailabilityService;
use App\Services\Implementations\Booking\RestaurantBookingService;
use App\Services\Implementations\Catalog\EventCatalogService;
use App\Services\Implementations\CheckoutFinalizer;
use App\Services\Implementations\OrderEmailService;
use App\Services\Implementations\OrderService;
use App\Services\Implementations\ProgramService;
use App\Services\Implementations\StripePaymentService;
use App\Services\OrderInvoiceService;
use App\Support\CheckoutErrorPresenter;
use App\Support\PaymentProvider;
use App\Support\SessionUser;
use Stripe\Exception\ApiErrorException;

final class ShopController extends BaseController
{
    private ProgramService $programService;
    private OrderService $orderService;
    private CheckoutValidationService $checkoutValidationService;
    private StripePaymentService $stripePaymentService;
    private PendingCheckoutRepository $pendingCheckoutRepository;
    private OrderEmailService $orderEmailService;
    private OrderInvoiceService $orderInvoiceService;
    private CheckoutFinalizer $checkoutFinalizer;

    public function __construct(
        ProgramService $programService,
        OrderService $orderService,
        CheckoutValidationService $checkoutValidationService,
        StripePaymentService $stripePaymentService,
        PendingCheckoutRepository $pendingCheckoutRepository,
        OrderEmailService $orderEmailService,
        OrderInvoiceService $orderInvoiceService,
        CheckoutFinalizer $checkoutFinalizer
    ) {
        $this->programService = $programService;
        $this->orderService = $orderService;
        $this->checkoutValidationService = $checkoutValidationService;
        $this->stripePaymentService = $stripePaymentService;
        $this->pendingCheckoutRepository = $pendingCheckoutRepository;
        $this->orderEmailService = $orderEmailService;
        $this->orderInvoiceService = $orderInvoiceService;
        $this->checkoutFinalizer = $checkoutFinalizer;
    }

    public function pay(): void
    {
        $this->ensureSession();
        $this->verifyCsrf();

        if (!$this->requireLogin('/program')) {
            return;
        }
        if (!$this->requireStripeConfigured()) {
            return;
        }

        try {
            $this->redirect($this->startStripeCheckout(), 303);
        } catch (ApiErrorException $e) {
            error_log('Stripe checkout failed: ' . $e->getMessage());
            $this->setErrorMessage('The secure payment page could not be opened.');
            $this->redirect('/program');
        } catch (\InvalidArgumentException $e) {
            $this->setErrorMessage($e->getMessage());
            $this->redirect('/program');
        } catch (\Throwable $e) {
            error_log('Checkout error: ' . $e->getMessage());
            $this->setErrorMessage(CheckoutErrorPresenter::message($e, 'start'));
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

        if (!$this->requireLogin('/checkout/success?session_id=' . urlencode($sessionId))) {
            return;
        }

        $userId = (int) $this->currentUserId();
        if ($this->redirectToExistingOrder($userId, $sessionId)) {
            return;
        }

        try {
            $orderId = $this->finalizeCheckoutSession($sessionId, $userId, null, true);
            $this->redirect('/orders/' . $orderId . '/success');
        } catch (ApiErrorException $e) {
            $this->setErrorMessage('The payment could not be verified.');
            $this->redirect('/program');
        } catch (\Throwable $e) {
            error_log('Checkout success failed: ' . $e->getMessage());

            // A concurrent webhook may have already created the order — use it if so.
            if ($this->redirectToExistingOrder($userId, $sessionId)) {
                return;
            }

            $this->setErrorMessage(CheckoutErrorPresenter::message($e, 'complete'));
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

        $this->ensureOrderConfirmationEmailSent($order);

        $this->view('shop/success', [
            'title' => 'Payment Complete',
            'order' => $order,
            'orderItems' => is_array($order['items'] ?? null) ? $order['items'] : [],
            'emailSentTo' => (string) ($this->getFlash('email_sent_to') ?? ''),
            'emailError' => (string) ($this->getFlash('email_error') ?? ''),
            'showMailpitLink' => $this->shouldShowMailpitLink(),
        ]);
    }

    public function invoice(int $orderId): void
    {
        $order = $this->getOrderForSuccessPage($orderId, '/orders/' . $orderId . '/invoice');
        if ($order === null) {
            return;
        }

        $pdf = $this->orderInvoiceService->generatePdf($order);

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $this->orderInvoiceService->filename($orderId) . '"');
        header('Content-Length: ' . strlen($pdf));
        echo $pdf;
        exit;
    }

    public function stripeWebhook(): void
    {
        $payload = (string) file_get_contents('php://input');
        $signature = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? null;

        try {
            $event = $this->stripePaymentService->parseWebhookEvent($payload, is_string($signature) ? $signature : null);
        } catch (\Throwable $e) {
            $this->json(['error' => 'Invalid webhook'], 400);
            return;
        }

        if (($event->type ?? '') !== 'checkout.session.completed') {
            $this->json(['received' => true]);
            return;
        }

        $session = $event->data->object ?? null;
        if (!is_object($session)) {
            $this->json(['error' => 'Missing session'], 400);
            return;
        }

        $sessionId = trim((string) ($session->id ?? ''));
        $userId = (int) ($session->client_reference_id ?? 0);
        if ($sessionId === '' || $userId <= 0) {
            $this->json(['error' => 'Invalid session'], 400);
            return;
        }

        try {
            $this->finalizeCheckoutSession($sessionId, $userId, (string) ($session->payment_intent ?? ''), false);
            $this->json(['received' => true]);
        } catch (\Throwable $e) {
            error_log('Stripe webhook failed: ' . $e->getMessage());
            $this->json(['error' => 'Could not finalize checkout'], 500);
        }
    }

    private function finalizeCheckoutSession(
        string $sessionId,
        int $userId,
        ?string $providerPaymentId = null,
        bool $clearCart = false
    ): int {
        $result = $this->checkoutFinalizer->finalize($sessionId, $userId, $providerPaymentId, $clearCart);

        // Email only a freshly-created order; an already-existing one was emailed on its first finalize.
        if ($result['created']) {
            $order = $this->orderService->findOrderForUser($result['order_id'], $userId);
            if ($order !== null) {
                $this->sendOrderConfirmationEmail($result['customer'], $order);
            }
        }

        return $result['order_id'];
    }

    private function ensureOrderConfirmationEmailSent(array $order): void
    {
        $orderId = (int) ($order['order_id'] ?? 0);
        if ($orderId <= 0 || !empty($_SESSION['order_email_sent_' . $orderId])) {
            return;
        }

        $this->sendOrderConfirmationEmail([
            'email' => trim((string) ($order['email'] ?? '')),
            'first_name' => trim((string) ($order['first_name'] ?? '')),
            'last_name' => trim((string) ($order['last_name'] ?? '')),
        ], $order);
    }

    private function sendOrderConfirmationEmail(array $customer, array $order): void
    {
        $orderId = (int) ($order['order_id'] ?? 0);
        $recipient = trim((string) ($customer['email'] ?? $order['email'] ?? ''));

        if ($recipient === '') {
            $this->setFlash('email_error', 'missing');
            return;
        }

        try {
            $this->orderEmailService->sendOrderConfirmation($customer, $order);
            $_SESSION['order_email_sent_' . $orderId] = true;
            $this->setFlash('email_sent_to', $recipient);
        } catch (\Throwable $e) {
            error_log('Order confirmation email failed: ' . $e->getMessage());
            $this->setFlash('email_error', 'failed');
        }
    }

    private function getOrderForSuccessPage(int $orderId, string $redirectPath): ?array
    {
        $this->ensureSession();

        if (!$this->requireLogin($redirectPath)) {
            return null;
        }

        $order = $this->orderService->findOrderForUser($orderId, (int) $this->currentUserId());
        if ($order === null) {
            $this->abort(404, 'Order not found.');
        }

        return $order;
    }

    /**
     * Require a logged-in visitor; if not, remember where to return and send them
     * to the login form. Returns false when the caller should stop.
     */
    private function requireLogin(string $returnTo): bool
    {
        if ($this->isLoggedIn()) {
            return true;
        }

        $_SESSION['auth_redirect'] = $returnTo;
        $this->redirect('/loginForm');

        return false;
    }

    /** Require Stripe to be configured; otherwise message the visitor. Returns false to stop. */
    private function requireStripeConfigured(): bool
    {
        if (\App\Support\StripeConfig::isConfigured()) {
            return true;
        }

        $this->setErrorMessage('Payments are not configured. Set STRIPE_SECRET_KEY in .env.');
        $this->redirect('/program');

        return false;
    }

    /**
     * Validate the cart, open a Stripe Checkout session and park it for finalising,
     * returning the URL the visitor should be sent to. Throws on any failure.
     */
    private function startStripeCheckout(): string
    {
        $validatedItems = $this->checkoutValidationService->validateAndNormalize($this->programService->getItems());
        $provider = PaymentProvider::normalize((string) $this->input('payment_provider', 'ideal'));
        $customer = SessionUser::customerData();
        $userId = (int) $this->currentUserId();

        $checkoutSession = $this->stripePaymentService->createCheckoutSession($userId, $provider, $validatedItems, $customer);

        $this->pendingCheckoutRepository->store((string) $checkoutSession->id, $userId, $customer, $validatedItems, $provider);

        return (string) $checkoutSession->url;
    }

    /** If this session already produced an order, redirect to its success page. Returns true if it did. */
    private function redirectToExistingOrder(int $userId, string $sessionId): bool
    {
        $existingOrder = $this->orderService->findByStripeSessionId($userId, $sessionId);
        if ($existingOrder === null) {
            return false;
        }

        $this->redirect('/orders/' . (int) ($existingOrder['order_id'] ?? 0) . '/success');

        return true;
    }

    private function shouldShowMailpitLink(): bool
    {
        return ($_ENV['APP_DEBUG'] ?? 'false') === 'true'
            && trim((string) ($_ENV['SMTP_HOST'] ?? '')) === 'mailpit';
    }
}
