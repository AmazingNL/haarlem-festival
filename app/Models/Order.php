<?php
// src/Domain/Order.php

declare(strict_types=1);
namespace App\Models;
use App\Core\BaseEntity;
use App\Models\Enum\OrderStatus;

final class Order
{
    public ?int $orderId = null;
    public int $userId = 0;
    public float $amount = 0.0;
    public float $vat = 0.0;
    public OrderStatus $status = OrderStatus::pending;
    public ?string $createdAt = null;

    public function __construct(
        int $orderId,int $userId, float $amount, float $vat,
        OrderStatus $status, string $createdAt
    ){
        $this->orderId = $orderId;
        $this->userId = $userId;
        $this->amount = $amount;
        $this->vat = $vat;
        $this->status = $status;
        $this->createdAt = $createdAt;
    }

}
