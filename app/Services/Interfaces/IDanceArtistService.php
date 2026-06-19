<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

interface IDanceArtistService
{
    public function getPublishedArtists(): array;

    public function getArtistDetailBySlug(string $slug): ?array;
}
