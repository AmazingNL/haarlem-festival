<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

interface IDanceScheduleService
{
    public function getFilterOptions(): array;

    public function getFiltersFromQuery(array $query, array $filterOptions = []): array;

    public function hasActiveFilters(array $filters): bool;

    public function getPublishedDanceSessions(array $filters): array;
}
