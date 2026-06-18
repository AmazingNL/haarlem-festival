<?php

declare(strict_types=1);

namespace App\Models;

/**
 * A single line of an order (an event ticket or a reservation), with a
 * per-line VAT rate. Replaces the old item_data JSON blob.
 */
final class OrderLine
{
    public function __construct(
        public ?int $order_line_id = null,
        public int $order_id = 0,
        public string $item_type = 'booking',
        public string $title = '',
        public ?string $selection_text = null,
        public ?string $ticket_title = null,
        public ?string $ticket_summary_text = null,
        public int $quantity = 1,
        public float $unit_price = 0.0,
        public float $line_total = 0.0,
        public float $vat_rate = 9.0,
        public ?string $location_name = null,
        public ?string $special_requests = null,
        public ?int $event_id = null,
        public ?int $ticket_type_id = null,
        public ?int $reservation_id = null,
    ) {
    }
}
