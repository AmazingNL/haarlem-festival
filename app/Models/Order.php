<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Enum\OrderStatus;

/**
 * A customer order: header, customer snapshot, status, total and invoice fields.
 */
final class Order
{
    public function __construct(
        public ?int $order_id = null,
        public int $user_id = 0,
        public float $total_price = 0.0,
        public OrderStatus $status = OrderStatus::pending,
        public ?string $invoice_number = null,
        public ?string $invoice_issued_at = null,
        public ?string $first_name = null,
        public ?string $last_name = null,
        public ?string $email = null,
        public ?string $phone = null,
        public ?string $provider = null,
        public ?string $created_at = null,
    ) {
    }
}
