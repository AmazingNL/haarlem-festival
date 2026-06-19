<?php

declare(strict_types=1);

namespace App\Repositories;

interface IDanceArtistRepository
{
    public function findPublishedArtists(): array;

    public function findPublishedArtistBySlug(string $slug): ?array;

    public function findPublishedArtistEventRows(int $artistId): array;
}
