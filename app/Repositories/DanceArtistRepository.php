<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\BaseRepository;
use PDO;

final class DanceArtistRepository extends BaseRepository implements IDanceArtistRepository
{
    public function findPublishedArtists(): array
    {
        $statement = $this->getConnection()->prepare(
            <<<SQL
                SELECT
                    da.dance_artist_id,
                    da.slug,
                    da.name,
                    da.genre,
                    da.short_description,
                    da.image_path,
                    da.image_alt,
                    da.latest_event_id,
                    da.sort_order,
                    e.title AS latest_event_title,
                    e.start_datetime AS latest_start_datetime,
                    e.end_datetime AS latest_end_datetime,
                    l.name AS latest_location_name
                FROM dance_artist da
                LEFT JOIN event e ON e.event_id = da.latest_event_id
                LEFT JOIN location l ON l.location_id = e.location_id
                WHERE da.is_published = :is_published
                ORDER BY da.sort_order ASC, da.name ASC
            SQL
        );

        $statement->bindValue(':is_published', 1, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function findPublishedArtistBySlug(string $slug): ?array
    {
        $statement = $this->getConnection()->prepare(
            <<<SQL
                SELECT
                    da.dance_artist_id,
                    da.slug,
                    da.name,
                    da.genre,
                    da.short_description,
                    da.biography,
                    da.career_highlights,
                    da.gallery_images,
                    da.image_path,
                    da.image_alt,
                    da.latest_event_id,
                    da.sort_order,
                    e.title AS latest_event_title,
                    e.start_datetime AS latest_start_datetime,
                    e.end_datetime AS latest_end_datetime,
                    l.name AS latest_location_name
                FROM dance_artist da
                LEFT JOIN event e ON e.event_id = da.latest_event_id
                LEFT JOIN location l ON l.location_id = e.location_id
                WHERE da.is_published = :is_published
                  AND da.slug = :slug
                LIMIT 1
            SQL
        );

        $statement->execute([
            ':is_published' => 1,
            ':slug' => $slug,
        ]);

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return is_array($row) ? $row : null;
    }

    public function findPublishedArtistEventRows(int $artistId): array
    {
        $statement = $this->getConnection()->prepare(
            <<<SQL
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
                    tt.max_quantity,
                    dae.sort_order AS artist_event_sort_order
                FROM dance_artist_event dae
                INNER JOIN event e ON e.event_id = dae.event_id
                INNER JOIN location l ON l.location_id = e.location_id
                LEFT JOIN image i ON i.image_id = e.image_id
                LEFT JOIN ticket_type tt ON tt.event_id = e.event_id
                WHERE dae.dance_artist_id = :artist_id
                  AND e.is_published = :is_published
                ORDER BY dae.sort_order ASC, e.start_datetime ASC, tt.price ASC, tt.name ASC
            SQL
        );

        $statement->execute([
            ':artist_id' => $artistId,
            ':is_published' => 1,
        ]);

        return $statement->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}
