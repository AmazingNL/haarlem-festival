<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\BaseRepository;
use PDO;

final class AdminDanceAvailabilityRepository extends BaseRepository
{
    public function findDanceEventsWithTicketTypes(): array
    {
        $sql = 'SELECT
                e.event_id,
                e.title,
                e.slug,
                e.start_datetime,
                e.end_datetime,
                e.is_published,
                l.name AS location_name,
                l.address AS location_address,
                l.city AS location_city,
                tt.ticket_type_id,
                tt.name AS ticket_type_name,
                tt.price AS ticket_price,
                tt.max_quantity
            FROM event e
            INNER JOIN (
                SELECT DISTINCT event_id
                FROM dance_artist_event
            ) dance_scope ON dance_scope.event_id = e.event_id
            INNER JOIN location l ON l.location_id = e.location_id
            LEFT JOIN ticket_type tt ON tt.event_id = e.event_id
            ORDER BY e.start_datetime ASC, e.event_id ASC, tt.name ASC';

        $statement = $this->getConnection()->prepare($sql);
        $statement->execute();

        return $this->groupEventRows($statement->fetchAll(PDO::FETCH_ASSOC) ?: []);
    }

    public function findDanceEventWithTicketTypes(int $eventId): ?array
    {
        $sql = 'SELECT
                e.event_id,
                e.title,
                e.slug,
                e.start_datetime,
                e.end_datetime,
                e.is_published,
                l.name AS location_name,
                l.address AS location_address,
                l.city AS location_city,
                tt.ticket_type_id,
                tt.name AS ticket_type_name,
                tt.price AS ticket_price,
                tt.max_quantity
            FROM event e
            INNER JOIN (
                SELECT DISTINCT event_id
                FROM dance_artist_event
            ) dance_scope ON dance_scope.event_id = e.event_id
            INNER JOIN location l ON l.location_id = e.location_id
            LEFT JOIN ticket_type tt ON tt.event_id = e.event_id
            WHERE e.event_id = :event_id
            ORDER BY tt.name ASC';

        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([':event_id' => $eventId]);

        $events = $this->groupEventRows($statement->fetchAll(PDO::FETCH_ASSOC) ?: []);

        return $events[0] ?? null;
    }

    public function updateTicketTypeMaxQuantity(int $ticketTypeId, int $eventId, int $maxQuantity): void
    {
        $sql = 'UPDATE ticket_type
            SET max_quantity = :max_quantity
            WHERE ticket_type_id = :ticket_type_id
              AND event_id = :event_id';

        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([
            ':max_quantity' => $maxQuantity,
            ':ticket_type_id' => $ticketTypeId,
            ':event_id' => $eventId,
        ]);
    }

    private function groupEventRows(array $rows): array
    {
        $events = [];

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
                    'title' => trim((string) ($row['title'] ?? 'Dance Event')),
                    'slug' => trim((string) ($row['slug'] ?? '')),
                    'start_datetime' => trim((string) ($row['start_datetime'] ?? '')),
                    'end_datetime' => trim((string) ($row['end_datetime'] ?? '')),
                    'is_published' => ((int) ($row['is_published'] ?? 0)) === 1,
                    'location_name' => trim((string) ($row['location_name'] ?? '')),
                    'location_address' => trim((string) ($row['location_address'] ?? '')),
                    'location_city' => trim((string) ($row['location_city'] ?? '')),
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
}
