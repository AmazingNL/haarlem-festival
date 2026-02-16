<?php
// src/Domain/Payment.php

declare(strict_types=1);
namespace App\Models;
use App\Core\BaseEntity;
use App\Models\Enum\PaymentStatus;

final class Payment extends BaseEntity
{
    public ?int $payment_id = null;
    public int $order_id = 0;

    public string $provider = '';
    public ?string $provider_payment_id = null;

    public float $amount = 0.0;
    public string $currency = 'EUR';

    public PaymentStatus $status = PaymentStatus::pending;

    public ?string $created_at = null;
    public ?string $paid_at = null;

    /** @param array<string,mixed> $row */
    public static function fromArray(array $row): static
    {
        $rawRole = $row['status'] ?? null;
        unset($row['status']); // prevent parent from assigning a raw string to the enum-typed property

        $p = parent::fromArray($row);

        if ($rawRole !== null) {
            $role = PaymentStatus::tryFrom((string) $rawRole);
            if ($role !== null)
                $p->status = $role;
        }

        return $p;

    }
}
