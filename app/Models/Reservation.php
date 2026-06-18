<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Enum\ReservationStatus;

/**
 * A restaurant booking (date, session, party size) tied to a user and,
 * once paid, to an order.
 */
final class Reservation
{
    public function __construct(
        public ?int $reservation_id = null,
        public int $restaurant_id = 0,
        public int $user_id = 0,
        public ?int $order_id = null,
        public string $reservation_date = '',
        public string $session = '',
        public int $adult_count = 0,
        public int $child_count = 0,
        public ?string $special_requests = null,
        public ReservationStatus $status = ReservationStatus::pending,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {
    }
}
