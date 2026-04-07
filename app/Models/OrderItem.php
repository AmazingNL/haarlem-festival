<?php
declare(strict_types=1);
namespace App\Models;

final class OrderItem
{
    public ?int $id = null;
    public int $orderId = 0;
    public int $ticketTypeId = 0;
    public int $quantity = 1;
    public float $unitPrice = 0.0;
    public float $subTotal = 0.0;

    public function __construct(
        int $id, int $orderId, int $ticketTypeId, int $quantity,
        float $unitPrice, float $subTotal 
    ){
        $this->id = $id;
        $this->orderId = $orderId;
        $this->quantity = $quantity;
        $this->subTotal = $subTotal;
        $this->unitPrice = $unitPrice;
    }
}
