<?php

namespace App\ViewModels\history;

use App\ViewModels\BaseSection;

final class HistoryRouteMapHero extends BaseSection
{
    public function __construct(int $pageId = 0, int $sectionId = 0, string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct($pageId, $sectionId, 'history_route_map_hero', $customClass, $sortOrder);
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
