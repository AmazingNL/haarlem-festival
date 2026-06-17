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
                'view_href' => '/dance/artists/' . $slug,
            ];
        }

        return $artists;
    }

    public function getArtistDetailBySlug(string $slug): ?array
    {
        $slug = $this->cleanSlug($slug);
        if ($slug === '') {
            return null;
        }

        $row = $this->danceArtistRepository->findPublishedArtistBySlug($slug);
        if ($row === null) {
            return null;
        }

        $artistId = max(0, (int) ($row['dance_artist_id'] ?? 0));
        $imagePath = $this->clean($row['image_path'] ?? '');
        if ($imagePath === '') {
            $imagePath = self::FALLBACK_IMAGE;
        }

        $name = $this->clean($row['name'] ?? 'Dance Artist');

        return [
            'dance_artist_id' => $artistId,
            'slug' => $this->clean($row['slug'] ?? $slug),
            'name' => $name,
            'genre' => $this->clean($row['genre'] ?? 'Dance'),
            'short_description' => $this->clean($row['short_description'] ?? ''),
            'biography' => $this->clean($row['biography'] ?? ''),
            'career_highlights' => $this->splitLines($this->clean($row['career_highlights'] ?? '')),
            'gallery_images' => $this->decodeGalleryImages($this->clean($row['gallery_images'] ?? '')),
            'image_path' => $imagePath,
            'image_alt' => $this->clean($row['image_alt'] ?? $name . ' artist image'),
            'latest_session_title' => $this->clean($row['latest_event_title'] ?? 'Latest session to be announced'),
            'latest_session_time' => $this->formatDateTime(
                $this->clean($row['latest_start_datetime'] ?? ''),
                $this->clean($row['latest_end_datetime'] ?? '')
            ),
            'latest_session_venue' => $this->clean($row['latest_location_name'] ?? ''),
            'related_events' => $this->groupEventRows($this->danceArtistRepository->findPublishedArtistEventRows($artistId)),
        ];
    }

    private function clean(mixed $value): string
    {
        return trim(strip_tags((string) $value));
    }

    private function cleanSlug(string $slug): string
    {
        $slug = strtolower(trim($slug));
        $slug = preg_replace('/[^a-z0-9-]/', '', $slug) ?? '';

        return trim($slug, '-');
    }

    private function splitLines(string $value): array
    {
        if ($value === '') {
            return [];
        }

        $lines = preg_split('/\r\n|\r|\n/', $value) ?: [];

        return array_values(array_filter(array_map(
            fn(string $line): string => $this->clean($line),
            $lines
        ), static fn(string $line): bool => $line !== ''));
    }

    private function decodeGalleryImages(string $json): array
    {
        if ($json === '') {
            return [];
        }

        $decoded = json_decode($json, true);
        if (!is_array($decoded)) {
            return [];
        }

        $images = [];
        foreach ($decoded as $image) {
            if (!is_array($image)) {
                continue;
            }

            $src = $this->clean($image['src'] ?? '');
            if ($src === '') {
                continue;
            }

            $images[] = [
                'src' => $src,
                'alt' => $this->clean($image['alt'] ?? 'Dance artist gallery image'),
            ];
        }

        return $images;
    }

    private function groupEventRows(array $rows): array
    {
        $events = [];

        foreach ($rows as $row) {
            $eventId = (int) ($row['event_id'] ?? 0);
            if ($eventId <= 0) {
                continue;
            }

            if (!isset($events[$eventId])) {
                $events[$eventId] = [
                    'event_id' => $eventId,
                    'title' => $this->clean($row['title'] ?? 'Dance Event'),
                    'slug' => $this->clean($row['slug'] ?? ''),
                    'description' => $this->clean($row['description'] ?? ''),
                    'start_datetime' => $this->clean($row['start_datetime'] ?? ''),
                    'end_datetime' => $this->clean($row['end_datetime'] ?? ''),
                    'location_name' => $this->clean($row['location_name'] ?? 'Haarlem'),
                    'location_address' => $this->clean($row['location_address'] ?? ''),
                    'location_city' => $this->clean($row['location_city'] ?? 'Haarlem'),
                    'image_path' => $this->clean($row['image_path'] ?? ''),
                    'category_label' => 'Dance',
                    'ticket_types' => [],
                ];
            }

            $ticketTypeId = (int) ($row['ticket_type_id'] ?? 0);
            if ($ticketTypeId <= 0) {
                continue;
            }

            $events[$eventId]['ticket_types'][] = [
                'ticket_type_id' => $ticketTypeId,
                'name' => $this->clean($row['ticket_type_name'] ?? 'Ticket'),
                'price' => round((float) ($row['ticket_price'] ?? 0), 2),
                'max_quantity' => max(0, (int) ($row['max_quantity'] ?? 0)),
            ];
        }

        return array_values($events);
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
