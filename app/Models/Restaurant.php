<?php
declare(strict_types=1);
namespace App\Models;

final class Restaurant {

    public int $id = 0;
    public int $eventId = 0;
    public int $orderId = 0;
    public int $childCount =0;
    public int $adultCount = 0;
    public float $adultPrice = 0;
    public float $childPrice = 0;
    public float $totalPrice = 0;

    public function __construct(
        int $id, int $eventId, int $orderId, int $childCount,
        int $adultCount, float $adultPrice, float $childPrice, float $totalPrice
    ){
        $this->id = $id;
        $this->eventId = $eventId;
        $this->orderId = $orderId;
        $this->childCount = $childCount;
        $this->adultCount = $adultCount;
        $this->adultPrice = $adultPrice;
        $this->childPrice = $childPrice;
        $this->totalPrice = $totalPrice;
    }

}
