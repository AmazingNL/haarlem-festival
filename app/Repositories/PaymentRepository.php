<?php
namespace App\Repositories;

use App\Core\BaseRepository;
use App\Models\Payment;

class PaymentRepository extends BaseRepository
{
    public function create(Payment $payment): int
    {
        $stmt = $this->connection->prepare(
            'INSERT INTO payments (order_id, provider, provider_payment_id, amount, currency, status, created_at)
             VALUES (:order_id, :provider, :provider_payment_id, :amount, :currency, :status, NOW())'
        );

        $stmt->execute([
            ':order_id' => $payment->order_id,
            ':provider' => $payment->provider,
            ':provider_payment_id' => $payment->provider_payment_id,
            ':amount' => $payment->amount,
            ':currency' => $payment->currency,
            ':status' => $payment->status->value ?? (string) $payment->status,
        ]);

        return (int) $this->connection->lastInsertId();
    }

    public function updateStatus(int $paymentId, string $status): void
    {
        $stmt = $this->connection->prepare('UPDATE payments SET status = :status, paid_at = NOW() WHERE payment_id = :id');
        $stmt->execute([':status' => $status, ':id' => $paymentId]);
    }
}
