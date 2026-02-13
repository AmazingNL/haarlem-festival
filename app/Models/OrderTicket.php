<?php
// src/Domain/OrderTicket.php

declare(strict_types=1);
namespace App\Models;
use App\Core\BaseEntity;

final class OrderTicket extends BaseEntity
{
    public ?int $order_ticket_id = null;
    public int $order_id = 0;
    public int $ticket_type_id = 0;

    public int $quantity = 1;

    public float $unit_price_at_purchase = 0.0;
    public float $vat_rate_at_purchase = 0.0;
    public float $line_total_at_purchase = 0.0;
}
