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

    /**
     * Set the enforced capacity for the venue with this slug, creating the venue row
     * if it does not exist yet. Used to mirror the CMS restaurant-card "Capacity" field.
     */
    public function upsertCapacityBySlug(string $slug, int $capacity, string $name): void;
}
