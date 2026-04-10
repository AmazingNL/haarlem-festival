<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\EventCatalogRepository;

final class EventCatalogService
{
    private EventCatalogRepository $eventCatalogRepository;

    public function __construct(EventCatalogRepository $eventCatalogRepository)
    {
        $this->eventCatalogRepository = $eventCatalogRepository;
    }

    public function getPublishedEvents(string $tag = ''): array
    {
        $events = [];

        foreach ($this->eventCatalogRepository->findPublishedEvents($tag) as $row) {
            $eventId = (int) ($row['event_id'] ?? 0);
            if ($eventId <= 0) {
                continue;
            }

            if (!isset($events[$eventId])) {
                $events[$eventId] = [
                    'event_id' => $eventId,
                    'title' => trim((string) ($row['title'] ?? 'Festival Event')),
                    'slug' => trim((string) ($row['slug'] ?? '')),
                    'description' => trim((string) ($row['description'] ?? '')),
                    'start_datetime' => trim((string) ($row['start_datetime'] ?? '')),
                    'end_datetime' => trim((string) ($row['end_datetime'] ?? '')),
                    'location_name' => trim((string) ($row['location_name'] ?? 'Haarlem')),
                    'location_address' => trim((string) ($row['location_address'] ?? '')),
                    'location_city' => trim((string) ($row['location_city'] ?? 'Haarlem')),
                    'image_path' => trim((string) ($row['image_path'] ?? '')),
                    'category_label' => $this->categoryLabel($row),
                    'ticket_types' => [],
                ];
            }

            $ticketTypeId = (int) ($row['ticket_type_id'] ?? 0);
            if ($ticketTypeId <= 0) {
                continue;
            }

            $events[$eventId]['ticket_types'][] = [
                'ticket_type_id' => $ticketTypeId,
                'name' => trim((string) ($row['ticket_type_name'] ?? 'Ticket')),
                'price' => round((float) ($row['ticket_price'] ?? 0), 2),
                'max_quantity' => max(0, (int) ($row['max_quantity'] ?? 0)),
            ];
        }

        return array_values($events);
    }

    public function getBookableTicketType(int $eventId, int $ticketTypeId): ?array
    {
        $row = $this->eventCatalogRepository->findBookableTicketType($eventId, $ticketTypeId);
        if ($row === null) {
            return null;
        }

        return [
            'event_id' => (int) ($row['event_id'] ?? 0),
            'title' => trim((string) ($row['title'] ?? 'Festival Event')),
            'slug' => trim((string) ($row['slug'] ?? '')),
            'description' => trim((string) ($row['description'] ?? '')),
            'start_datetime' => trim((string) ($row['start_datetime'] ?? '')),
            'end_datetime' => trim((string) ($row['end_datetime'] ?? '')),
            'location_name' => trim((string) ($row['location_name'] ?? 'Haarlem')),
            'location_address' => trim((string) ($row['location_address'] ?? '')),
            'location_city' => trim((string) ($row['location_city'] ?? 'Haarlem')),
            'ticket_type_id' => (int) ($row['ticket_type_id'] ?? 0),
            'ticket_type_name' => trim((string) ($row['ticket_type_name'] ?? 'Ticket')),
            'ticket_price' => round((float) ($row['ticket_price'] ?? 0), 2),
            'max_quantity' => max(0, (int) ($row['max_quantity'] ?? 0)),
            'category_label' => $this->categoryLabel($row),
        ];
    }

    private function categoryLabel(array $row): string
    {
        $haystack = strtolower(implode(' ', [
            (string) ($row['title'] ?? ''),
            (string) ($row['slug'] ?? ''),
            (string) ($row['description'] ?? ''),
        ]));

        if (str_contains($haystack, 'jazz')) {
            return 'Jazz';
        }

        if (str_contains($haystack, 'dance')) {
            return 'Dance';
        }

        if (str_contains($haystack, 'food') || str_contains($haystack, 'drink')) {
            return 'Restaurants';
        }

        if (str_contains($haystack, 'history') || str_contains($haystack, 'historic') || str_contains($haystack, 'museum')) {
            return 'History';
        }

        if (str_contains($haystack, 'classical')) {
            return 'Music';
        }

        return 'Festival';
    }
}
