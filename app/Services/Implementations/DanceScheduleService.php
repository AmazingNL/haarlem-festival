<?php

declare(strict_types=1);

namespace App\Services\Implementations;

use App\Repositories\DanceScheduleRepository;

final class DanceScheduleService
{
    public function __construct(private DanceScheduleRepository $danceScheduleRepository)
    {
    }

    public function getFilterOptions(): array
    {
        return [
            'dates' => $this->buildDateOptions($this->danceScheduleRepository->findFilterDates()),
            'venues' => $this->buildVenueOptions($this->danceScheduleRepository->findFilterVenues()),
            'artists' => $this->buildArtistOptions($this->danceScheduleRepository->findFilterArtists()),
            'sessions' => $this->buildSessionOptions($this->danceScheduleRepository->findFilterSessions()),
        ];
    }

    public function getFiltersFromQuery(array $query, array $filterOptions = []): array
    {
        $filterOptions = $filterOptions !== [] ? $filterOptions : $this->getFilterOptions();
        $filters = [
            'date' => '',
            'venue' => '',
            'artist' => '',
            'session' => '',
            'location_id' => 0,
            'ticket_type_name' => '',
            'invalid' => false,
        ];

        $rawDate = $this->scalar($query['date'] ?? '');
        if ($rawDate !== '') {
            $date = $this->normalizeDate($rawDate);
            if ($date === '') {
                $filters['invalid'] = true;
            } else {
                $filters['date'] = $date;
            }
        }

        $rawVenue = $this->cleanSlug($query['venue'] ?? '');
        if ($rawVenue !== '') {
            $venue = $this->findOptionByValue($filterOptions['venues'] ?? [], $rawVenue);
            if ($venue === null) {
                $filters['venue'] = $rawVenue;
                $filters['invalid'] = true;
            } else {
                $filters['venue'] = $rawVenue;
                $filters['location_id'] = max(0, (int) ($venue['location_id'] ?? 0));
            }
        }

        $rawArtist = $this->cleanSlug($query['artist'] ?? '');
        if ($rawArtist !== '') {
            $artist = $this->findOptionByValue($filterOptions['artists'] ?? [], $rawArtist);
            if ($artist === null) {
                $filters['artist'] = $rawArtist;
                $filters['invalid'] = true;
            } else {
                $filters['artist'] = $rawArtist;
            }
        }

        $rawSession = $this->cleanSlug($query['session'] ?? '');
        if ($rawSession !== '') {
            $session = $this->findOptionByValue($filterOptions['sessions'] ?? [], $rawSession);
            if ($session === null) {
                $filters['session'] = $rawSession;
                $filters['invalid'] = true;
            } else {
                $filters['session'] = $rawSession;
                $filters['ticket_type_name'] = $this->clean($session['ticket_type_name'] ?? '');
            }
        }

        return $filters;
    }

    public function hasActiveFilters(array $filters): bool
    {
        if (!empty($filters['invalid'])) {
            return true;
        }

        foreach (['date', 'venue', 'artist', 'session'] as $key) {
            if (($filters[$key] ?? '') !== '') {
                return true;
            }
        }

        return false;
    }

    public function getPublishedDanceSessions(array $filters): array
    {
        if (!empty($filters['invalid'])) {
            return [];
        }

        $criteria = [];
        if (($filters['date'] ?? '') !== '') {
            $criteria['date'] = (string) $filters['date'];
        }

        if ((int) ($filters['location_id'] ?? 0) > 0) {
            $criteria['location_id'] = (int) $filters['location_id'];
        }

        if (($filters['artist'] ?? '') !== '') {
            $criteria['artist_slug'] = (string) $filters['artist'];
        }

        if (($filters['ticket_type_name'] ?? '') !== '') {
            $criteria['ticket_type_name'] = (string) $filters['ticket_type_name'];
        }

        return DanceEventCardMapper::mapRows($this->danceScheduleRepository->findPublishedDanceEventRows($criteria));
    }

    private function buildDateOptions(array $rows): array
    {
        $options = [];
        foreach ($rows as $row) {
            $date = $this->normalizeDate($this->clean($row['event_date'] ?? ''));
            if ($date === '') {
                continue;
            }

            $options[] = [
                'value' => $date,
                'label' => $this->formatDateLabel($date),
            ];
        }

        return $options;
    }

    private function buildVenueOptions(array $rows): array
    {
        $options = [];
        $seen = [];

        foreach ($rows as $row) {
            $name = $this->clean($row['location_name'] ?? '');
            $slug = $this->slugify($name);
            $locationId = max(0, (int) ($row['location_id'] ?? 0));
            if ($name === '' || $slug === '' || $locationId <= 0 || isset($seen[$slug])) {
                continue;
            }

            $city = $this->clean($row['location_city'] ?? '');
            $options[] = [
                'value' => $slug,
                'label' => $city !== '' ? $name . ', ' . $city : $name,
                'location_id' => $locationId,
            ];
            $seen[$slug] = true;
        }

        return $options;
    }

    private function buildArtistOptions(array $rows): array
    {
        $options = [];
        $seen = [];

        foreach ($rows as $row) {
            $slug = $this->cleanSlug($row['slug'] ?? '');
            $name = $this->clean($row['name'] ?? '');
            if ($slug === '' || $name === '' || isset($seen[$slug])) {
                continue;
            }

            $options[] = [
                'value' => $slug,
                'label' => $name,
            ];
            $seen[$slug] = true;
        }

        return $options;
    }

    private function buildSessionOptions(array $rows): array
    {
        $options = [];
        $seen = [];

        foreach ($rows as $row) {
            $name = $this->clean($row['ticket_type_name'] ?? '');
            $slug = $this->slugify($name);
            if ($name === '' || $slug === '' || isset($seen[$slug])) {
                continue;
            }

            $options[] = [
                'value' => $slug,
                'label' => $name,
                'ticket_type_name' => $name,
            ];
            $seen[$slug] = true;
        }

        return $options;
    }

    private function findOptionByValue(array $options, string $value): ?array
    {
        foreach ($options as $option) {
            if (is_array($option) && ($option['value'] ?? '') === $value) {
                return $option;
            }
        }

        return null;
    }

    private function normalizeDate(string $value): string
    {
        $value = trim($value);
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return '';
        }

        [$year, $month, $day] = array_map('intval', explode('-', $value));

        return checkdate($month, $day, $year) ? $value : '';
    }

    private function formatDateLabel(string $date): string
    {
        $timestamp = strtotime($date . ' 00:00:00');

        return $timestamp ? date('l, F j', $timestamp) : $date;
    }

    private function cleanSlug(mixed $value): string
    {
        $value = strtolower($this->scalar($value));
        $value = preg_replace('/[^a-z0-9-]/', '', $value) ?? '';

        return trim($value, '-');
    }

    private function slugify(string $value): string
    {
        $value = strtolower($this->clean($value));
        $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';

        return trim($value, '-');
    }

    private function clean(mixed $value): string
    {
        return trim(strip_tags($this->scalar($value)));
    }

    private function scalar(mixed $value): string
    {
        return is_scalar($value) ? trim((string) $value) : '';
    }
}
