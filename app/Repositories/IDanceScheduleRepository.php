<?php

declare(strict_types=1);

namespace App\Repositories;

interface IDanceScheduleRepository
{
    public function findPublishedDanceEventRows(array $criteria = []): array;

    public function findFilterDates(): array;

    public function findFilterVenues(): array;

    public function findFilterArtists(): array;

    public function findFilterSessions(): array;
}
