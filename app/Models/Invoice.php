<?php
// src/Domain/Invoice.php

declare(strict_types=1);
namespace App\Models;
use App\Core\BaseEntity;

final class Invoice extends BaseEntity
{
    public ?int $invoice_id = null;
    public int $order_id = 0;

    public string $invoice_number = '';
    public string $invoice_date = ''; // YYYY-MM-DD

    public string $customer_name = '';
    public string $customer_email = '';
    public ?string $customer_phone = null;

    public string $billing_street = '';
    public string $billing_postal_code = '';
    public string $billing_city = '';
    public string $billing_country = '';

    public float $subtotal_amount = 0.0;
    public float $vat_amount = 0.0;
    public float $total_amount = 0.0;

    public ?string $payment_date = null; // YYYY-MM-DD
}
