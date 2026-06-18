<?php

declare(strict_types=1);

namespace App\Services\Implementations\Booking;

use App\Repositories\IRestaurantRepository;
use App\Repositories\IReservationRepository;

/**
 * Restaurant reservation business rules — currently capacity enforcement.
 */
final class ReservationService
{
    public function __construct(
        private IRestaurantRepository $restaurants,
        private IReservationRepository $reservations,
    ) {
    }

    /**
     * Throw when booking the requested guests would exceed the restaurant's capacity
     * for that date/session. No-op when the venue or its capacity is not configured.
     */
    public function assertCapacityAvailable(string $restaurantSlug, string $date, string $session, int $requestedGuests): void
    {
        if ($requestedGuests < 1 || trim($restaurantSlug) === '') {
            return;
        }

        $restaurant = $this->restaurants->findBySlug($restaurantSlug);
        if ($restaurant === null || $restaurant->capacity <= 0) {
            return;
        }

        $booked = $this->reservations->countGuestsForSlot((int) $restaurant->restaurant_id, $date, $session);
        if (($booked + $requestedGuests) > $restaurant->capacity) {
            $remaining = max(0, $restaurant->capacity - $booked);
            throw new \InvalidArgumentException(sprintf(
                '%s is fully booked for %s at %s — only %d seat%s left.',
                $restaurant->name,
                $date,
                $session,
                $remaining,
                $remaining === 1 ? '' : 's'
            ));
        }
    }
}
