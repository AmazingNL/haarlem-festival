<?php

declare(strict_types=1);

namespace App\Services;

final class DanceEventCardMapper
{
    public static function mapRows(array $rows): array
    {
        $events = [];
        $seenTickets = [];

        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }

            $eventId = (int) ($row['event_id'] ?? 0);
            if ($eventId <= 0) {
                continue;
            }

            if (!isset($events[$eventId])) {
                $events[$eventId] = [
                    'event_id' => $eventId,
                    'title' => self::clean($row['title'] ?? 'Dance Event'),
                    'slug' => self::clean($row['slug'] ?? ''),
                    'description' => self::clean($row['description'] ?? ''),
                    'start_datetime' => self::clean($row['start_datetime'] ?? ''),
                    'end_datetime' => self::clean($row['end_datetime'] ?? ''),
                    'location_name' => self::clean($row['location_name'] ?? 'Haarlem'),
                    'location_address' => self::clean($row['location_address'] ?? ''),
                    'location_city' => self::clean($row['location_city'] ?? 'Haarlem'),
                    'image_path' => self::clean($row['image_path'] ?? ''),
                    'category_label' => 'Dance',
                    'ticket_types' => [],
                ];
                $seenTickets[$eventId] = [];
            }

            $ticketTypeId = (int) ($row['ticket_type_id'] ?? 0);
            if ($ticketTypeId <= 0 || isset($seenTickets[$eventId][$ticketTypeId])) {
                continue;
            }

            $events[$eventId]['ticket_types'][] = [
                'ticket_type_id' => $ticketTypeId,
                'name' => self::clean($row['ticket_type_name'] ?? 'Ticket'),
                'price' => round((float) ($row['ticket_price'] ?? 0), 2),
                'max_quantity' => max(0, (int) ($row['max_quantity'] ?? 0)),
            ];
            $seenTickets[$eventId][$ticketTypeId] = true;
        }

        return array_values($events);
    }

    private static function clean(mixed $value): string
    {
        return trim(strip_tags(self::scalar($value)));
    }

    private static function scalar(mixed $value): string
    {
        return is_scalar($value) ? trim((string) $value) : '';
    }
}
