<?php
// src/Domain/InvoiceLine.php

declare(strict_types=1);
namespace App\Models;
use App\Core\BaseEntity;

final class InvoiceLine extends BaseEntity
{
    public ?int $invoice_line_id = null;
    public int $invoice_id = 0;

    public string $description = '';
    public int $quantity = 1;

    public float $unit_price = 0.0;
    public float $vat_rate = 0.0;

    public float $line_subtotal = 0.0;
    public float $line_vat = 0.0;
    public float $line_total = 0.0;
}
