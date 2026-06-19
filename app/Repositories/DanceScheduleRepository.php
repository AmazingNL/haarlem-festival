<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\BaseRepository;
use PDO;

final class DanceScheduleRepository extends BaseRepository implements IDanceScheduleRepository
{
    public function findPublishedDanceEventRows(array $criteria = []): array
    {
        $sql = <<<SQL
            SELECT
                e.event_id,
                e.title,
                e.slug,
                e.description,
                e.start_datetime,
                e.end_datetime,
                l.name AS location_name,
                l.address AS location_address,
                l.city AS location_city,
                i.file_path AS image_path,
                tt.ticket_type_id,
                tt.name AS ticket_type_name,
                tt.price AS ticket_price,
                tt.max_quantity
            FROM event e
            INNER JOIN location l ON l.location_id = e.location_id
            LEFT JOIN image i ON i.image_id = e.image_id
            LEFT JOIN ticket_type tt ON tt.event_id = e.event_id
            WHERE e.is_published = :is_published
              AND EXISTS (
                  SELECT 1
                  FROM dance_artist_event dae_scope
                  WHERE dae_scope.event_id = e.event_id
              )
        SQL;

        $params = [':is_published' => 1];

        if (!empty($criteria['date'])) {
            $sql .= ' AND DATE(e.start_datetime) = :event_date';
            $params[':event_date'] = (string) $criteria['date'];
        }

        if (!empty($criteria['location_id'])) {
            $sql .= ' AND e.location_id = :location_id';
            $params[':location_id'] = (int) $criteria['location_id'];
        }

        if (!empty($criteria['artist_slug'])) {
            $sql .= <<<SQL
              AND EXISTS (
                  SELECT 1
                  FROM dance_artist_event dae_artist
                  INNER JOIN dance_artist da_filter
                      ON da_filter.dance_artist_id = dae_artist.dance_artist_id
                  WHERE dae_artist.event_id = e.event_id
                    AND da_filter.is_published = :artist_is_published
                    AND da_filter.slug = :artist_slug
              )
            SQL;
            $params[':artist_is_published'] = 1;
            $params[':artist_slug'] = (string) $criteria['artist_slug'];
        }

        if (!empty($criteria['ticket_type_name'])) {
            $sql .= ' AND tt.name = :ticket_type_name';
            $params[':ticket_type_name'] = (string) $criteria['ticket_type_name'];
        }

        $sql .= ' ORDER BY e.start_datetime ASC, tt.price ASC, tt.name ASC';

        $statement = $this->getConnection()->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function findFilterDates(): array
    {
        $statement = $this->getConnection()->prepare(
            <<<SQL
                SELECT DISTINCT DATE(e.start_datetime) AS event_date
                FROM event e
                WHERE e.is_published = :is_published
                  AND EXISTS (
                      SELECT 1
                      FROM dance_artist_event dae_scope
                      WHERE dae_scope.event_id = e.event_id
                  )
                ORDER BY event_date ASC
            SQL
        );

        $statement->execute([':is_published' => 1]);

        return $statement->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function findFilterVenues(): array
    {
        $statement = $this->getConnection()->prepare(
            <<<SQL
                SELECT DISTINCT
                    l.location_id,
                    l.name AS location_name,
                    l.city AS location_city
                FROM event e
                INNER JOIN location l ON l.location_id = e.location_id
                WHERE e.is_published = :is_published
                  AND EXISTS (
                      SELECT 1
                      FROM dance_artist_event dae_scope
                      WHERE dae_scope.event_id = e.event_id
                  )
                ORDER BY l.name ASC
            SQL
        );

        $statement->execute([':is_published' => 1]);

        return $statement->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function findFilterArtists(): array
    {
        $statement = $this->getConnection()->prepare(
            <<<SQL
                SELECT DISTINCT
                    da.slug,
                    da.name,
                    da.sort_order
                FROM dance_artist da
                INNER JOIN dance_artist_event dae ON dae.dance_artist_id = da.dance_artist_id
                INNER JOIN event e ON e.event_id = dae.event_id
                WHERE da.is_published = :artist_is_published
                  AND e.is_published = :event_is_published
                ORDER BY da.sort_order ASC, da.name ASC
            SQL
        );

        $statement->execute([
            ':artist_is_published' => 1,
            ':event_is_published' => 1,
        ]);

        return $statement->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function findFilterSessions(): array
    {
        $statement = $this->getConnection()->prepare(
            <<<SQL
                SELECT DISTINCT
                    tt.name AS ticket_type_name
                FROM ticket_type tt
                INNER JOIN event e ON e.event_id = tt.event_id
                WHERE e.is_published = :is_published
                  AND EXISTS (
                      SELECT 1
                      FROM dance_artist_event dae_scope
                      WHERE dae_scope.event_id = e.event_id
                  )
                ORDER BY tt.name ASC
            SQL
        );

        $statement->execute([':is_published' => 1]);

        return $statement->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}
