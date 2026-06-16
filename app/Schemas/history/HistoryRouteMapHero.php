<?php

namespace App\Schemas\history;

use App\Schemas\BaseSection;

final class HistoryRouteMapHero extends BaseSection
{
    public function __construct(string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct('history_route_map_hero', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'back_text' => ['type' => 'text', 'label' => 'Back Link Text'],
            'back_link' => ['type' => 'text', 'label' => 'Back Link URL'],
            'eyebrow' => ['type' => 'text', 'label' => 'Eyebrow'],
            'heading' => ['type' => 'text', 'label' => 'Heading', 'required' => true],
            'intro' => ['type' => 'textarea', 'label' => 'Intro Text'],
        ];
    }
}
