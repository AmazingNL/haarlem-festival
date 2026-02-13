<?php
// src/Domain/TicketType.php

declare(strict_types=1);
namespace App\Models;
use App\Core\BaseEntity;

final class TicketType extends BaseEntity
{
    public ?int $ticket_type_id = null;
    public int $event_id = 0;
    public string $name = '';
    public float $price = 0.0;
    public float $vat_rate = 0.21;
    public int $max_quantity = 0;
    public int $is_active = 1;

    public ?string $created_at = null;
    public ?string $updated_at = null;
}
