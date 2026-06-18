<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\BaseRepository;

final class EventCatalogRepository extends BaseRepository
{
    public function findPublishedEvents(string $tag = ''): array
    {
        $sql = <<<SQL
            SELECT
                e.event_id,
                e.title,
                LOWER(REPLACE(e.title, ' ', '-')) AS slug,
                '' AS description,
                e.type AS event_type,
                e.start_datetime,
                e.end_datetime,
                l.name AS location_name,
                l.address AS location_address,
                l.city AS location_city,
                '' AS image_path,
                tt.ticket_type_id,
                tt.name AS ticket_type_name,
                tt.price AS ticket_price,
                tt.max_quantity
            FROM event e
            INNER JOIN location l ON l.location_id = e.location_id
            LEFT JOIN ticket_type tt ON tt.event_id = e.event_id
            WHERE 1 = 1
        SQL;

        $params = [];
        if ($tag !== '') {
            $sql .= ' AND (LOWER(e.title) LIKE :tag OR LOWER(e.type) LIKE :tag)';
            $params[':tag'] = '%' . strtolower($tag) . '%';
        }

        $sql .= ' ORDER BY e.start_datetime ASC, tt.price ASC, tt.name ASC';

        $statement = $this->getConnection()->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }

    public function findBookableTicketType(int $eventId, int $ticketTypeId): ?array
    {
        $statement = $this->getConnection()->prepare(
            <<<SQL
                SELECT
                    e.event_id,
                    e.title,
                    LOWER(REPLACE(e.title, ' ', '-')) AS slug,
                    '' AS description,
                    e.type AS event_type,
                    e.start_datetime,
                    e.end_datetime,
                    l.name AS location_name,
                    l.address AS location_address,
                    l.city AS location_city,
                    tt.ticket_type_id,
                    tt.name AS ticket_type_name,
                    tt.price AS ticket_price,
                    tt.max_quantity
                FROM event e
                INNER JOIN location l ON l.location_id = e.location_id
                INNER JOIN ticket_type tt ON tt.event_id = e.event_id
                WHERE e.event_id = :event_id
                  AND tt.ticket_type_id = :ticket_type_id
                LIMIT 1
            SQL
        );

        $statement->execute([
            ':event_id' => $eventId,
            ':ticket_type_id' => $ticketTypeId,
        ]);

        $row = $statement->fetch(\PDO::FETCH_ASSOC);

        return is_array($row) ? $row : null;
    }
}
