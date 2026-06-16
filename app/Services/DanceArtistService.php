<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\DanceArtistRepository;

final class DanceArtistService
{
    private const FALLBACK_IMAGE = '/assets/images/home/home-dance.jpg';

    public function __construct(private DanceArtistRepository $danceArtistRepository)
    {
    }

    public function getPublishedArtists(): array
    {
        $artists = [];

        foreach ($this->danceArtistRepository->findPublishedArtists() as $row) {
            $slug = $this->clean($row['slug'] ?? '');
            if ($slug === '') {
                continue;
            }

            $imagePath = $this->clean($row['image_path'] ?? '');
            if ($imagePath === '') {
                $imagePath = self::FALLBACK_IMAGE;
            }

            $name = $this->clean($row['name'] ?? 'Dance Artist');
            $latestTitle = $this->clean($row['latest_event_title'] ?? '');

            $artists[] = [
                'dance_artist_id' => max(0, (int) ($row['dance_artist_id'] ?? 0)),
                'slug' => $slug,
                'name' => $name,
                'genre' => $this->clean($row['genre'] ?? 'Dance'),
                'short_description' => $this->clean($row['short_description'] ?? ''),
                'image_path' => $imagePath,
                'image_alt' => $this->clean($row['image_alt'] ?? $name . ' artist image'),
                'latest_session_title' => $latestTitle !== '' ? $latestTitle : 'Latest session to be announced',
                'latest_session_time' => $this->formatDateTime(
                    $this->clean($row['latest_start_datetime'] ?? ''),
                    $this->clean($row['latest_end_datetime'] ?? '')
                ),
                'latest_session_venue' => $this->clean($row['latest_location_name'] ?? ''),
                'view_href' => '/dance#artist-' . $slug,
            ];
        }

        return $artists;
    }

    private function clean(mixed $value): string
    {
        return trim(strip_tags((string) $value));
    }

    private function formatDateTime(string $startDateTime, string $endDateTime): string
    {
        $start = strtotime($startDateTime);
        if (!$start) {
            return 'Date to be announced';
        }

        $label = date('l, F j', $start) . ' | ' . date('H:i', $start);

        $end = strtotime($endDateTime);
        if ($end) {
            $label .= ' - ' . date('H:i', $end);
        }

        return $label;
    }
}
