<?php
// src/Domain/Location.php

declare(strict_types=1);
namespace App\Models;
use App\Core\BaseEntity;

final class Location extends BaseEntity
{
    public ?int $location_id = null;
    public string $name = '';
    public string $address = '';
    public string $city = '';
    public int $capacity = 0;

    public ?string $created_at = null;
    public ?string $updated_at = null;
}
