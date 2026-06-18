<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Restaurant;

interface IRestaurantRepository
{
    public function findById(int $id): ?Restaurant;

    public function findBySlug(string $slug): ?Restaurant;

    /** @return Restaurant[] */
    public function all(): array;

    public function save(Restaurant $restaurant): int;
}
