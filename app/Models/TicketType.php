<?php

declare(strict_types=1);

namespace App\Models;

/**
 * A purchasable ticket type for an event, with a price and an availability cap.
 */
final class TicketType
{
    public function __construct(
        public ?int $ticket_type_id = null,
        public int $event_id = 0,
        public string $name = '',
        public float $price = 0.0,
        public int $max_quantity = 0,
    ) {
    }
}
