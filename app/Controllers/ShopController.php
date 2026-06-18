<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Repositories\PendingCheckoutRepository;
use App\Services\CheckoutValidationService;
use App\Services\OrderEmailService;
use App\Services\OrderInvoiceService;
use App\Services\OrderService;
use App\Services\ProgramService;
use App\Services\StripePaymentService;
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

    public function __construct(
        ProgramService $programService,
        OrderService $orderService,
        CheckoutValidationService $checkoutValidationService,
        StripePaymentService $stripePaymentService,
        PendingCheckoutRepository $pendingCheckoutRepository,
        OrderEmailService $orderEmailService,
        OrderInvoiceService $orderInvoiceService
    ) {
        $this->programService = $programService;
        $this->orderService = $orderService;
        $this->checkoutValidationService = $checkoutValidationService;
        $this->stripePaymentService = $stripePaymentService;
        $this->pendingCheckoutRepository = $pendingCheckoutRepository;
        $this->orderEmailService = $orderEmailService;
        $this->orderInvoiceService = $orderInvoiceService;
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

        if (!\App\Support\StripeConfig::isConfigured()) {
            $this->setErrorMessage('Payments are not configured. Set STRIPE_SECRET_KEY in .env.');
            $this->redirect('/program');
            return;
        }

        try {
            $validatedItems = $this->checkoutValidationService->validateAndNormalize($this->programService->getItems());
            $provider = PaymentProvider::normalize((string) $this->input('payment_provider', 'ideal'));
            $customer = SessionUser::customerData();
            $userId = (int) $this->currentUserId();

            $checkoutSession = $this->stripePaymentService->createCheckoutSession(
                $userId,
                $provider,
                $validatedItems,
                $customer
            );

            $sessionId = (string) $checkoutSession->id;
            $this->pendingCheckoutRepository->store($sessionId, $userId, $customer, $validatedItems, $provider);

            $this->redirect((string) $checkoutSession->url, 303);
        } catch (ApiErrorException $e) {
            error_log('Stripe checkout failed: ' . $e->getMessage());
            $this->setErrorMessage('The secure payment page could not be opened.');
            $this->redirect('/program');
        } catch (\InvalidArgumentException $e) {
            $this->setErrorMessage($e->getMessage());
            $this->redirect('/program');
        } catch (\Throwable $e) {
            error_log('Checkout error: ' . $e->getMessage());
            $this->setErrorMessage($this->paymentErrorMessage($e, 'start'));
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

        $userId = (int) $this->currentUserId();
        $existingOrder = $this->orderService->findByStripeSessionId($userId, $sessionId);
        if ($existingOrder !== null) {
            $this->redirect('/orders/' . (int) ($existingOrder['order_id'] ?? 0) . '/success');
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

            $existingOrder = $this->orderService->findByStripeSessionId($userId, $sessionId);
            if ($existingOrder !== null) {
                $this->redirect('/orders/' . (int) ($existingOrder['order_id'] ?? 0) . '/success');
                return;
            }

            $this->setErrorMessage($this->paymentErrorMessage($e, 'complete'));
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
        $existingOrder = $this->orderService->findByStripeSessionId($userId, $sessionId);
        if ($existingOrder !== null) {
            return (int) ($existingOrder['order_id'] ?? 0);
        }

        $pendingCheckout = $this->pendingCheckoutRepository->find($sessionId);
        if ($pendingCheckout === null || (int) ($pendingCheckout['user_id'] ?? 0) !== $userId) {
            throw new \RuntimeException('Checkout session could not be matched.');
        }

        $storedItems = is_array($pendingCheckout['items'] ?? null) ? $pendingCheckout['items'] : [];
        if ($storedItems === []) {
            throw new \RuntimeException('Checkout session had no items.');
        }

        try {
            $items = $this->checkoutValidationService->validateAndNormalize($storedItems);
        } catch (\Throwable $e) {
            error_log('Checkout re-validation failed, using stored items: ' . $e->getMessage());
            $items = $storedItems;
        }

        $expectedTotalCents = $this->checkoutValidationService->calculateTotalCents($items);
        $checkoutSession = $this->stripePaymentService->retrieveCheckoutSession($sessionId);
        $this->stripePaymentService->verifyPaidSession($checkoutSession, $userId, $expectedTotalCents);

        $customer = is_array($pendingCheckout['customer'] ?? null) ? $pendingCheckout['customer'] : SessionUser::customerData();
        $provider = PaymentProvider::normalize((string) ($pendingCheckout['provider'] ?? 'ideal'));

        if ($providerPaymentId === null || $providerPaymentId === '') {
            $providerPaymentId = (string) ($checkoutSession->payment_intent ?? '');
        }

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

        $order = $this->orderService->findOrderForUser($orderId, $userId);
        if ($order !== null) {
            $this->sendOrderConfirmationEmail($customer, $order);
        }

        return $orderId;
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

        if (!$this->isLoggedIn()) {
            $_SESSION['auth_redirect'] = $redirectPath;
            $this->redirect('/loginForm');
            return null;
        }

        $order = $this->orderService->findOrderForUser($orderId, (int) $this->currentUserId());
        if ($order === null) {
            $this->abort(404, 'Order not found.');
        }

        return $order;
    }

    private function paymentErrorMessage(\Throwable $e, string $phase): string
    {
        $message = $e->getMessage();

        if (stripos($message, 'Stripe secret key') !== false) {
            return 'Payments are not configured. Set STRIPE_SECRET_KEY in .env.';
        }

        if (stripos($message, 'pending_stripe_checkout') !== false) {
            return 'Payment tables are missing. Run: docker compose exec php php /app/migrate.php up';
        }

        if ($phase === 'start' && (stripos($message, 'unit_amount') !== false || stripos($message, 'minimum') !== false)) {
            return 'One of the items has an invalid price. Remove it from My Program and add it again.';
        }

        if ($phase === 'complete') {
            if (stripos($message, 'order_ticket') !== false || stripos($message, 'ticket_type_id') !== false) {
                return 'Ticket tables need updating. Run: docker compose exec php php /app/migrate.php up';
            }

            if (stripos($message, 'Checkout session could not be matched') !== false) {
                return 'We could not match your payment session. Please contact support with your payment confirmation.';
            }

            if (stripos($message, 'amount mismatch') !== false) {
                return 'The paid amount did not match your cart. Contact support if money was taken.';
            }
        }

        if (($_ENV['APP_DEBUG'] ?? 'false') === 'true' && $message !== '') {
            return ($phase === 'complete' ? 'The order could not be completed: ' : 'Payment could not start: ') . $message;
        }

        return $phase === 'complete'
            ? 'The order could not be completed right now.'
            : 'The payment could not be started right now.';
    }

    private function shouldShowMailpitLink(): bool
    {
        return ($_ENV['APP_DEBUG'] ?? 'false') === 'true'
            && trim((string) ($_ENV['SMTP_HOST'] ?? '')) === 'mailpit';
    }
}
