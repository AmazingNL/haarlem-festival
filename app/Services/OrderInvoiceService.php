<?php

declare(strict_types=1);

namespace App\Services;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Dompdf\Dompdf;
use Dompdf\Options;

final class OrderInvoiceService
{
    public function generatePdf(array $order): string
    {
        if (!class_exists(Dompdf::class)) {
            throw new \RuntimeException('dompdf is not installed. Run: composer require dompdf/dompdf');
        }

        $dompdf = new Dompdf($this->options());
        $dompdf->loadHtml($this->buildHtml($order));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return (string) $dompdf->output();
    }

    public function filename(int $orderId): string
    {
        return 'haarlem-festival-invoice-' . $orderId . '.pdf';
    }

    private function options(): Options
    {
        $options = new Options();
        $options->set('isRemoteEnabled', false);
        $options->set('defaultFont', 'DejaVu Sans');

        return $options;
    }

    private function buildHtml(array $order): string
    {
        $orderId = (int) ($order['order_id'] ?? 0);
        $name = trim((string) ($order['first_name'] ?? '') . ' ' . (string) ($order['last_name'] ?? ''));
        $email = htmlspecialchars((string) ($order['email'] ?? ''), ENT_QUOTES, 'UTF-8');
        $phone = htmlspecialchars((string) ($order['phone'] ?? ''), ENT_QUOTES, 'UTF-8');
        $paidAt = htmlspecialchars((string) ($order['paid_at'] ?? $order['created_at'] ?? ''), ENT_QUOTES, 'UTF-8');
        $provider = htmlspecialchars(\App\Support\PaymentProvider::label((string) ($order['provider'] ?? '')), ENT_QUOTES, 'UTF-8');
        $total = number_format((float) ($order['total_price'] ?? 0), 2, '.', ',');
        $items = is_array($order['items'] ?? null) ? $order['items'] : [];
        $tickets = is_array($order['tickets'] ?? null) ? $order['tickets'] : [];

        $rows = '';
        foreach ($items as $item) {
            $title = htmlspecialchars((string) ($item['title'] ?? 'Booking'), ENT_QUOTES, 'UTF-8');
            $qty = (int) ($item['quantity'] ?? 1);
            $line = number_format((float) ($item['line_total'] ?? $item['total_price'] ?? 0), 2, '.', ',');
            $rows .= "<tr><td>{$title}</td><td style=\"text-align:center\">{$qty}</td><td style=\"text-align:right\">EUR {$line}</td></tr>";
        }

        $ticketHtml = '';
        foreach ($tickets as $i => $ticket) {
            $token = (string) ($ticket['qr_token'] ?? '');
            if ($token === '') {
                continue;
            }
            $num = $i + 1;
            $qr = $this->qrDataUri($token);
            $ticketHtml .= '<div style="display:inline-block;text-align:center;margin:8px 12px 8px 0;border:1px solid #ddd;padding:8px;">'
                . '<img src="' . $qr . '" width="120" height="120" alt="Ticket ' . $num . '">'
                . '<p style="font-size:11px;margin:4px 0 0;">Ticket ' . $num . '</p></div>';
        }

        $nameEsc = htmlspecialchars($name !== '' ? $name : 'Festival guest', ENT_QUOTES, 'UTF-8');

        $ticketSection = '';
        if ($ticketHtml !== '') {
            $ticketSection = '<h2 style="font-size:14px;margin-top:24px;">Your tickets</h2>'
                . '<p>Show these QR codes at the venue.</p>' . $ticketHtml;
        }

        return <<<HTML
<!DOCTYPE html>
<html><head><meta charset="utf-8"><style>
body{font-family:DejaVu Sans,sans-serif;font-size:12px;color:#111;}
h1{font-size:20px;margin:0 0 4px;} table{width:100%;border-collapse:collapse;margin:16px 0;}
th,td{border-bottom:1px solid #ddd;padding:8px 4px;text-align:left;}
th{background:#f5f5f5;} .meta{margin:12px 0;} .total{font-size:14px;font-weight:bold;}
</style></head><body>
<h1>Haarlem Festival 2026</h1>
<p>Invoice #{$orderId}</p>
<div class="meta"><strong>{$nameEsc}</strong><br>{$email}<br>{$phone}</div>
<p>Paid: {$paidAt}<br>Payment: {$provider}</p>
<table><thead><tr><th>Item</th><th style="text-align:center">Qty</th><th style="text-align:right">Amount</th></tr></thead>
<tbody>{$rows}</tbody></table>
<p class="total">Total paid: EUR {$total}</p>
{$ticketSection}
</body></html>
HTML;
    }

    private function qrDataUri(string $token): string
    {
        $options = new QROptions();
        $options->outputBase64 = false;
        $svg = (new QRCode($options))->render($token);

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}
