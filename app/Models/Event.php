<?php

declare(strict_types=1);

namespace App\Models;

/**
 * A festival event held at a location, offering one or more ticket types.
 */
final class Event
{
    public function __construct(
        public ?int $event_id = null,
        public string $title = '',
        public string $slug = '',
        public string $description = '',
        public ?string $start_datetime = null,
        public ?string $end_datetime = null,
        public int $location_id = 0,
        public ?int $image_id = null,
        public bool $is_published = false,
    ) {
    }
}
