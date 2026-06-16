<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\BaseRepository;
use PDO;

final class DanceArtistRepository extends BaseRepository
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
}
