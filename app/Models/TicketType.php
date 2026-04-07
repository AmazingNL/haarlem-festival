<?php
// src/Domain/TicketType.php

declare(strict_types=1);
namespace App\Models;

final class TicketType
{
    public ?int $id = null;
    public int $eventId = 0;
    public string $name = '';
    public float $price = 0.0;

    public function __construct(
        int $id, int $eventId, string $name, float $price
    ){
        $this->id = $id;
        $this->eventId = $eventId;
        $this->name = $name;
        $this->price = $price;
    }
}
