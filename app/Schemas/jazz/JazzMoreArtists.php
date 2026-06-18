<?php

namespace App\Schemas\jazz;

use App\Schemas\BaseSection;

final class JazzMoreArtists extends BaseSection
{
    public function __construct(string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct('jazz_more_artists', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'intro' => ['type' => 'text', 'label' => 'Intro Text'],
            'artists' => ['type' => 'textarea', 'label' => 'Artist Names (one per line or comma separated)'],
        ];
    }
}
