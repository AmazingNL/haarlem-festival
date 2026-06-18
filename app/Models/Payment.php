<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Enum\PaymentStatus;

/**
 * A payment against an order (Stripe), including provider references.
 */
final class Payment
{
    public function __construct(
        public ?int $payment_id = null,
        public int $order_id = 0,
        public ?string $provider = null,
        public ?string $provider_payment_id = null,
        public ?string $stripe_session_id = null,
        public float $amount = 0.0,
        public string $currency = 'EUR',
        public PaymentStatus $status = PaymentStatus::pending,
        public ?string $paid_at = null,
    ) {
    }
}
