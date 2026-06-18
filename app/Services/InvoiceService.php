<?php

declare(strict_types=1);

namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Renders a PDF invoice for a paid order, including a VAT breakdown per rate.
 *
 * Prices stored on order lines are VAT-inclusive (Dutch consumer pricing), so the
 * net amount and VAT are derived from the gross line total: net = gross / (1 + rate),
 * vat = gross - net. Restaurant reservations carry the 9% low rate, everything else 21%.
 */
final class InvoiceService
{
    /** @param array<string, mixed> $order */
    public function renderPdf(array $order): string
    {
        $options = new Options();
        $options->set('isRemoteEnabled', false);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($this->buildHtml($order), 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return (string) $dompdf->output();
    }

    /** @param array<string, mixed> $order */
    public function fileName(array $order): string
    {
        $number = trim((string) ($order['invoice_number'] ?? ''));
        if ($number === '') {
            $number = 'order-' . (int) ($order['order_id'] ?? 0);
        }

        return 'invoice-' . preg_replace('/[^A-Za-z0-9_-]+/', '-', $number) . '.pdf';
    }

    /** @param array<string, mixed> $order */
    private function buildHtml(array $order): string
    {
        $items = is_array($order['items'] ?? null) ? $order['items'] : [];

        $rows = '';
        $grossTotal = 0.0;
        $vatByRate = [];

        foreach ($items as $line) {
            if (!is_array($line)) {
                continue;
            }

            $gross = round((float) ($line['line_total'] ?? 0), 2);
            $rate = round((float) ($line['vat_rate'] ?? 21), 2);
            $net = $rate > 0 ? round($gross / (1 + $rate / 100), 2) : $gross;
            $vat = round($gross - $net, 2);

            $grossTotal += $gross;
            $rateKey = number_format($rate, 2, '.', '');
            $vatByRate[$rateKey]['net'] = ($vatByRate[$rateKey]['net'] ?? 0.0) + $net;
            $vatByRate[$rateKey]['vat'] = ($vatByRate[$rateKey]['vat'] ?? 0.0) + $vat;

            $rows .= '<tr>'
                . '<td>' . $this->describeLine($line) . '</td>'
                . '<td class="num">' . (int) ($line['quantity'] ?? 1) . '</td>'
                . '<td class="num">' . $this->money($net) . '</td>'
                . '<td class="num">' . rtrim(rtrim(number_format($rate, 2, '.', ''), '0'), '.') . '%</td>'
                . '<td class="num">' . $this->money($gross) . '</td>'
                . '</tr>';
        }

        $netTotal = 0.0;
        $vatTotal = 0.0;
        $vatRows = '';
        ksort($vatByRate, SORT_NUMERIC);
        foreach ($vatByRate as $rateKey => $amounts) {
            $netTotal += $amounts['net'];
            $vatTotal += $amounts['vat'];
            $label = rtrim(rtrim(number_format((float) $rateKey, 2, '.', ''), '0'), '.');
            $vatRows .= '<tr><td>VAT ' . $label . '% over ' . $this->money($amounts['net']) . '</td>'
                . '<td class="num">' . $this->money($amounts['vat']) . '</td></tr>';
        }

        $issuedAt = trim((string) ($order['invoice_issued_at'] ?? '')) ?: (string) ($order['created_at'] ?? '');
        $issuedDate = $issuedAt !== '' ? date('d-m-Y', strtotime($issuedAt)) : date('d-m-Y');
        $invoiceNumber = trim((string) ($order['invoice_number'] ?? '')) ?: ('—');

        $customerName = trim(((string) ($order['first_name'] ?? '')) . ' ' . ((string) ($order['last_name'] ?? '')));
        $email = (string) ($order['email'] ?? '');
        $phone = (string) ($order['phone'] ?? '');

        return '<!DOCTYPE html><html><head><meta charset="utf-8"><style>'
            . 'body { font-family: "DejaVu Sans", sans-serif; font-size: 11px; color: #1d1d1f; }'
            . 'h1 { font-size: 22px; margin: 0; color: #b8002e; }'
            . '.muted { color: #6b6b6b; }'
            . '.meta { margin-top: 18px; width: 100%; }'
            . '.meta td { vertical-align: top; padding: 2px 0; }'
            . 'table.lines { width: 100%; border-collapse: collapse; margin-top: 24px; }'
            . 'table.lines th, table.lines td { border-bottom: 1px solid #e3e3e3; padding: 8px 6px; text-align: left; }'
            . 'table.lines th { background: #faf3f5; font-size: 10px; text-transform: uppercase; letter-spacing: .04em; }'
            . '.num { text-align: right; white-space: nowrap; }'
            . 'table.totals { width: 45%; margin-left: 55%; margin-top: 16px; border-collapse: collapse; }'
            . 'table.totals td { padding: 4px 6px; }'
            . 'table.totals tr.grand td { border-top: 2px solid #1d1d1f; font-weight: bold; font-size: 13px; }'
            . '</style></head><body>'
            . '<table style="width:100%"><tr>'
            . '<td><h1>Haarlem Festival</h1><div class="muted">Grote Markt, Haarlem<br>info@haarlemfestival.nl</div></td>'
            . '<td style="text-align:right"><div style="font-size:16px;font-weight:bold">INVOICE</div>'
            . '<div class="muted">' . $this->e($invoiceNumber) . '</div></td>'
            . '</tr></table>'
            . '<table class="meta"><tr>'
            . '<td><strong>Billed to</strong><br>' . $this->e($customerName) . '<br>' . $this->e($email)
            . ($phone !== '' ? '<br>' . $this->e($phone) : '') . '</td>'
            . '<td style="text-align:right"><strong>Invoice date</strong><br>' . $this->e($issuedDate)
            . '<br><strong>Order</strong><br>#' . (int) ($order['order_id'] ?? 0) . '</td>'
            . '</tr></table>'
            . '<table class="lines"><thead><tr>'
            . '<th>Description</th><th class="num">Qty</th><th class="num">Net</th><th class="num">VAT</th><th class="num">Amount</th>'
            . '</tr></thead><tbody>' . ($rows !== '' ? $rows : '<tr><td colspan="5" class="muted">No items.</td></tr>') . '</tbody></table>'
            . '<table class="totals">'
            . '<tr><td>Subtotal (excl. VAT)</td><td class="num">' . $this->money($netTotal) . '</td></tr>'
            . $vatRows
            . '<tr class="grand"><td>Total (incl. VAT)</td><td class="num">' . $this->money($grossTotal) . '</td></tr>'
            . '</table>'
            . '<p class="muted" style="margin-top:40px">Thank you for supporting the Haarlem Festival. This invoice was generated automatically; no signature is required.</p>'
            . '</body></html>';
    }

    /** @param array<string, mixed> $line */
    private function describeLine(array $line): string
    {
        $title = trim((string) ($line['title'] ?? '')) ?: 'Festival item';
        $detail = trim((string) ($line['selection_text'] ?? ''));
        if ($detail === '') {
            $detail = trim((string) ($line['ticket_summary_text'] ?? ''));
        }

        $html = '<strong>' . $this->e($title) . '</strong>';
        if ($detail !== '') {
            $html .= '<br><span class="muted">' . $this->e($detail) . '</span>';
        }

        return $html;
    }

    private function money(float $amount): string
    {
        return '&euro; ' . number_format($amount, 2, ',', '.');
    }

    private function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}
