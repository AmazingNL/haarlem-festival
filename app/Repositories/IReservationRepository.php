<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Reservation;

interface IReservationRepository
{
    public function create(Reservation $reservation): int;

    public function findById(int $id): ?Reservation;

    /** @return Reservation[] */
    public function findByUser(int $userId): array;

    /** Total guests (adults + children) already booked for a restaurant date/session (excluding cancelled). */
    public function countGuestsForSlot(int $restaurantId, string $date, string $session): int;
}
