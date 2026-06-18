<?php

declare(strict_types=1);

namespace App\Services\Implementations\Booking;

use App\Models\ProgramItem;
use App\Repositories\EventCatalogRepository;
use App\Services\Interfaces\IBookingStrategy;

final class EventBookingService implements IBookingStrategy
{
    public function __construct(private EventCatalogRepository $eventCatalogRepository)
    {
    }

    public function handledType(): string
    {
        return 'event-ticket';
    }

    public function buildProgramItem(int $eventId, int $ticketTypeId, int $requestedQuantity): array
    {
        if ($eventId <= 0 || $ticketTypeId <= 0) {
            throw new \InvalidArgumentException('Choose a valid event ticket before adding it to My Program.');
        }

        $ticketSelection = $this->getBookableTicketType($eventId, $ticketTypeId);
        if ($ticketSelection === null) {
            throw new \InvalidArgumentException('This event ticket is not available right now.');
        }

        $availableSeats = max(0, (int) ($ticketSelection['max_quantity'] ?? 0));
        if ($availableSeats < 1) {
            throw new \InvalidArgumentException('This ticket type is sold out.');
        }

        $quantity = min(max(1, $requestedQuantity), min(10, $availableSeats));
        $unitPrice = round((float) ($ticketSelection['ticket_price'] ?? 0), 2);
        $startDateTime = (string) ($ticketSelection['start_datetime'] ?? '');
        $endDateTime = (string) ($ticketSelection['end_datetime'] ?? '');

        $item = [
            'type' => 'event-ticket',
            'event_id' => (int) ($ticketSelection['event_id'] ?? 0),
            'ticket_type_id' => (int) ($ticketSelection['ticket_type_id'] ?? 0),
            'title' => (string) ($ticketSelection['title'] ?? 'Festival Event'),
            'day' => $this->formatDate($startDateTime),
            'time' => $this->formatTimeRange($startDateTime, $endDateTime),
            'ticket_key' => 'ticket-type-' . (int) ($ticketSelection['ticket_type_id'] ?? 0),
            'ticket_title' => (string) ($ticketSelection['ticket_type_name'] ?? 'Ticket'),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => round($unitPrice * $quantity, 2),
            'selection_text' => $this->formatSelectionText($startDateTime, $endDateTime),
            'ticket_summary_text' => (string) ($ticketSelection['ticket_type_name'] ?? 'Ticket'),
            'location_name' => (string) ($ticketSelection['location_name'] ?? 'Haarlem'),
            'category_label' => (string) ($ticketSelection['category_label'] ?? 'Festival'),
            'starts_at' => $startDateTime,
            'ends_at' => $endDateTime,
        ];

        return ProgramItem::fromArray($item)->toArray();
    }

    public function validateProgramItem(array $item): array
    {
        $built = $this->buildProgramItem(
            max(0, (int) ($item['event_id'] ?? 0)),
            max(0, (int) ($item['ticket_type_id'] ?? 0)),
            max(1, (int) ($item['quantity'] ?? 1))
        );

        $built['id'] = trim((string) ($item['id'] ?? ''));
        $built['special_requests'] = trim((string) ($item['special_requests'] ?? ''));

        return $built;
    }

    private function getBookableTicketType(int $eventId, int $ticketTypeId): ?array
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
            (string) ($row['event_type'] ?? ''),
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

    private function formatDate(string $dateTime): string
    {
        $timestamp = strtotime($dateTime);

        return $timestamp ? date('D d M Y', $timestamp) : '';
    }

    private function formatTimeRange(string $startDateTime, string $endDateTime): string
    {
        $start = strtotime($startDateTime);
        $end = strtotime($endDateTime);

        if (!$start) {
            return '';
        }

        if (!$end) {
            return date('H:i', $start);
        }

        return date('H:i', $start) . ' - ' . date('H:i', $end);
    }

    private function formatSelectionText(string $startDateTime, string $endDateTime): string
    {
        return trim(implode(' | ', array_filter([
            $this->formatDate($startDateTime),
            $this->formatTimeRange($startDateTime, $endDateTime),
        ], static fn(string $value): bool => $value !== '')));
    }
}
