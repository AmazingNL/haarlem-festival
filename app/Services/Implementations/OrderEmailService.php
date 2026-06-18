<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Services\Interfaces\IMailer;
use App\Services\Implementations\InvoiceService;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

final class OrderEmailService
{
    private IMailer $mailer;
<<<<<<< HEAD:app/Services/OrderEmailService.php
    private OrderInvoiceService $invoiceService;

    public function __construct(IMailer $mailer, OrderInvoiceService $invoiceService)
=======
    private InvoiceService $invoiceService;

    public function __construct(IMailer $mailer, InvoiceService $invoiceService)
>>>>>>> origin/dev:app/Services/Implementations/OrderEmailService.php
    {
        $this->mailer = $mailer;
        $this->invoiceService = $invoiceService;
    }

    /**
     * Send an order confirmation email with one embedded QR code per ticket.
     *
     * @param  array $customer Keys: email, first_name, last_name
     * @param  array $order    Keys: order_id, total_price, tickets[]
     * @return void
     */
    public function sendOrderConfirmation(array $customer, array $order): void
    {
        $email = $this->resolveRecipientEmail($customer, $order);
        if ($email === '') {
            throw new \RuntimeException('Cannot send order email: no valid customer email on the order.');
        }

        $firstName = trim((string) ($customer['first_name'] ?? $order['first_name'] ?? ''));
        $lastName  = trim((string) ($customer['last_name'] ?? $order['last_name'] ?? ''));
        $name      = trim($firstName . ' ' . $lastName) ?: 'Festival guest';
        $orderId   = (int) ($order['order_id'] ?? 0);

        $attachments = [];
        try {
            $attachments[] = [
<<<<<<< HEAD:app/Services/OrderEmailService.php
                'filename' => $this->invoiceService->filename($orderId),
                'content' => $this->invoiceService->generatePdf($order),
                'mime' => 'application/pdf',
            ];
        } catch (\Throwable $e) {
            error_log('Invoice PDF failed: ' . $e->getMessage());
=======
                'content' => $this->invoiceService->renderPdf($order),
                'filename' => $this->invoiceService->fileName($order),
                'mime' => 'application/pdf',
            ];
        } catch (\Throwable $e) {
            // A failed invoice render must not block the confirmation email.
            $attachments = [];
>>>>>>> origin/dev:app/Services/Implementations/OrderEmailService.php
        }

        $this->mailer->send(
            $email,
            $name,
            'Haarlem Festival 2026 — Order #' . $orderId . ' confirmed',
            $this->buildHtml($name, $order),
            $this->buildText($name, $order),
            $attachments
        );
    }

    private function resolveRecipientEmail(array $customer, array $order): string
    {
        foreach ([$customer['email'] ?? '', $order['email'] ?? ''] as $candidate) {
            $email = trim((string) $candidate);
            if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $email;
            }
        }

        return '';
    }

    /**
     * @param  string $name
     * @param  array  $order
     * @return string HTML email body
     */
    private function buildHtml(string $name, array $order): string
    {
        $orderId = (int) ($order['order_id'] ?? 0);
        $total   = number_format((float) ($order['total_price'] ?? 0), 2, '.', ',');
        $tickets = is_array($order['tickets'] ?? null) ? $order['tickets'] : [];

        $html  = '<div style="font-family:sans-serif;max-width:600px;margin:0 auto;color:#111;">';
        $html .= '<h2 style="color:#1a1a1a;">Haarlem Festival 2026</h2>';
        $html .= '<p>Hello ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ',</p>';
        $html .= '<p>Your payment for order <strong>#' . $orderId . '</strong> is confirmed.</p>';
        $html .= '<p><strong>Total paid: EUR ' . htmlspecialchars($total, ENT_QUOTES, 'UTF-8') . '</strong></p>';
        $html .= '<p>Your invoice (PDF) is attached to this email.</p>';

        $html .= '<p style="margin-top:16px;">Your invoice (PDF) is attached to this email.</p>';

        if ($tickets !== []) {
            $html .= '<p style="margin-top:24px;">Show the QR code(s) below at the venue entrance:</p>';
            $html .= '<div style="display:flex;flex-wrap:wrap;gap:16px;margin-top:8px;">';

            foreach ($tickets as $i => $ticket) {
                $token = (string) ($ticket['qr_token'] ?? '');
                if ($token === '') {
                    continue;
                }
                $num    = $i + 1;
                $status = htmlspecialchars(ucfirst((string) ($ticket['status'] ?? 'valid')), ENT_QUOTES, 'UTF-8');
                $qrUri  = $this->generateQrDataUri($token);

                $html .= '<div style="text-align:center;border:1px solid #e5e7eb;border-radius:8px;padding:12px;">';
                $html .= '<img src="' . $qrUri . '" width="160" height="160" alt="QR code ticket ' . $num . '" style="display:block;">';
                $html .= '<p style="font-size:12px;color:#555;margin:8px 0 0;">Ticket ' . $num . ' &mdash; ' . $status . '</p>';
                $html .= '</div>';
            }

            $html .= '</div>';
        }

        $html .= '<p style="margin-top:32px;font-size:12px;color:#888;">Haarlem Festival 2026 &mdash; this is an automated message.</p>';
        $html .= '</div>';

        return $html;
    }

    /**
     * @param  string $name
     * @param  array  $order
     * @return string Plain-text email body
     */
    private function buildText(string $name, array $order): string
    {
        $orderId = (int) ($order['order_id'] ?? 0);
        $total   = number_format((float) ($order['total_price'] ?? 0), 2, '.', ',');
        $tickets = is_array($order['tickets'] ?? null) ? $order['tickets'] : [];
        $count   = count($tickets);

        return "Hello {$name},\n\n"
            . "Your payment for order #{$orderId} is confirmed. Total paid: EUR {$total}.\n\n"
<<<<<<< HEAD:app/Services/OrderEmailService.php
            . "Your invoice PDF is attached.\n\n"
=======
            . "Your invoice (PDF) is attached to this email.\n\n"
>>>>>>> origin/dev:app/Services/Implementations/OrderEmailService.php
            . ($count > 0 ? "You have {$count} ticket(s). Log in to view your QR codes:\n/orders/{$orderId}/success\n\n" : '')
            . "Haarlem Festival 2026";
    }

    /**
     * Generate a base64 SVG data URI for the given QR token.
     *
     * @param  string $token 64-char hex qr_token
     * @return string data:image/svg+xml;base64,...
     */
    private function generateQrDataUri(string $token): string
    {
        $options = new QROptions;
        $options->outputBase64 = false;
        $svg = (new QRCode($options))->render($token);
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}
