<?php

declare(strict_types=1);

namespace App\Services\Implementations\Booking;

use App\Repositories\IReservationRepository;
use App\Repositories\IRestaurantRepository;

final class RestaurantAvailabilityService
{
    public function __construct(
        private IRestaurantRepository $restaurants,
        private IReservationRepository $reservations,
    ) {
    }

    /**
     * @return array{has_venue: bool, capacity: int, booked: int, remaining: int}
     */
    public function availabilityForLink(string $link): array
    {
        $slug = $this->slugFromLink($link);
        if ($slug === '') {
            return $this->emptyAvailability();
        }

        $restaurant = $this->restaurants->findBySlug($slug);
        if ($restaurant === null) {
            return $this->emptyAvailability();
        }

        $capacity = max(0, (int) $restaurant->capacity);
        $booked = $restaurant->restaurant_id === null
            ? 0
            : $this->reservations->BookedGuestsForRestaurant((int) $restaurant->restaurant_id);

        return [
            'has_venue' => true,
            'capacity' => $capacity,
            'booked' => $booked,
            'remaining' => max(0, $capacity - $booked),
        ];
    }

    public function syncCapacityFromCard(string $link, int $capacity, string $name = ''): void
    {
        $slug = $this->slugFromLink($link);
        if ($slug === '') {
            return;
        }

        $this->restaurants->upsertCapacityBySlug($slug, $capacity, $name);
    }

    private function slugFromLink(string $link): string
    {
        $path = parse_url(trim($link), PHP_URL_PATH);
        if (!is_string($path) || $path === '') {
            $path = trim($link);
        }

        $parts = array_values(array_filter(explode('/', trim($path, '/'))));
        return (string) end($parts);
    }

    /**
     * @return array{has_venue: bool, capacity: int, booked: int, remaining: int}
     */
    private function emptyAvailability(): array
    {
        return [
            'has_venue' => false,
            'capacity' => 0,
            'booked' => 0,
            'remaining' => 0,
        ];
    }
}
