<?php

declare(strict_types=1);

namespace App\Models;

final class Restaurant
{
    public function __construct(
        public ?int $restaurant_id = null,
        public string $name = '',
        public string $slug = '',
        public ?string $description = null,
        public int $capacity = 0,
        public ?int $location_id = null,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {
    }
}
