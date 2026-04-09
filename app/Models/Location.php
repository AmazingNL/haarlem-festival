<?php
// src/Domain/Location.php

declare(strict_types=1);
namespace App\Models;
use App\Core\BaseEntity;

final class Location
{
    public ?int $Id = null;
    public string $name = '';
    public string $address = '';
    public string $city = '';
    public int $capacity = 0;
    public function __construct(
        int $id, string $name, string $address, string $city, int $capacity
    ){
        $this->Id = $id;
        $this->name = $name;
        $this->address = $address;
        $this->city = $city;
        $this->capacity = $capacity;
    }
}
