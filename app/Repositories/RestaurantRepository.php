<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\BaseRepository;
use App\Models\Restaurant;
use PDO;

final class RestaurantRepository extends BaseRepository implements IRestaurantRepository
{
    public function findById(int $id): ?Restaurant
    {
        $stmt = $this->getConnection()->prepare('SELECT * FROM restaurant WHERE restaurant_id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->hydrate($row) : null;
    }

    public function findBySlug(string $slug): ?Restaurant
    {
        $stmt = $this->getConnection()->prepare('SELECT * FROM restaurant WHERE slug = :slug LIMIT 1');
        $stmt->execute([':slug' => $slug]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->hydrate($row) : null;
    }

    /** @return Restaurant[] */
    public function all(): array
    {
        $stmt = $this->getConnection()->query('SELECT * FROM restaurant ORDER BY name ASC');

        return array_map(fn (array $row): Restaurant => $this->hydrate($row), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function save(Restaurant $restaurant): int
    {
        $sql = 'INSERT INTO restaurant (name, slug, description, capacity, location_id)
                VALUES (:name, :slug, :description, :capacity, :location_id)';
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([
            ':name' => $restaurant->name,
            ':slug' => $restaurant->slug,
            ':description' => $restaurant->description,
            ':capacity' => $restaurant->capacity,
            ':location_id' => $restaurant->location_id,
        ]);

        return (int) $this->getConnection()->lastInsertId();
    }

    /** @param array<string, mixed> $row */
    private function hydrate(array $row): Restaurant
    {
        return new Restaurant(
            restaurant_id: isset($row['restaurant_id']) ? (int) $row['restaurant_id'] : null,
            name: (string) ($row['name'] ?? ''),
            slug: (string) ($row['slug'] ?? ''),
            description: $row['description'] ?? null,
            capacity: (int) ($row['capacity'] ?? 0),
            location_id: isset($row['location_id']) ? (int) $row['location_id'] : null,
            created_at: $row['created_at'] ?? null,
            updated_at: $row['updated_at'] ?? null,
        );
    }
}
