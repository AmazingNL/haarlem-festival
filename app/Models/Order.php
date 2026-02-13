<?php
// src/Domain/Order.php

declare(strict_types=1);
namespace App\Models;
use App\Core\BaseEntity;
use App\Models\Enum\OrderStatus;

final class Order extends BaseEntity
{
    public ?int $order_id = null;
    public int $user_id = 0;
    public ?string $order_datetime = null;

    public float $subtotal_amount = 0.0;
    public float $vat_amount = 0.0;
    public float $total_price = 0.0;
    public string $currency = 'EUR';

    public OrderStatus $status = OrderStatus::pending;
    public ?string $payment_due_at = null;
    public ?string $cancelled_at = null;

    public ?string $created_at = null;
    public ?string $updated_at = null;

    /** @param array<string,mixed> $row */
    public static function fromArray(array $row): static
    {
        $o = parent::fromArray($row);
        if (isset($row['status'])) $o->status = OrderStatus::from((string)$row['status']);
        return $o;
    }
}
