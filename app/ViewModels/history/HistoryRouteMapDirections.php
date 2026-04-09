<?php

namespace App\ViewModels\history;

use App\ViewModels\BaseSection;

final class HistoryRouteMapDirections extends BaseSection
{
    public function __construct(int $pageId = 0, int $sectionId = 0, string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct($pageId, $sectionId, 'history_route_map_directions', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'heading' => ['type' => 'text', 'label' => 'Heading', 'required' => true],
            'intro' => ['type' => 'textarea', 'label' => 'Intro Text'],
            'step_one_title' => ['type' => 'text', 'label' => 'Step One Title'],
            'step_one_text' => ['type' => 'textarea', 'label' => 'Step One Text'],
            'step_two_title' => ['type' => 'text', 'label' => 'Step Two Title'],
            'step_two_text' => ['type' => 'textarea', 'label' => 'Step Two Text'],
            'step_three_title' => ['type' => 'text', 'label' => 'Step Three Title'],
            'step_three_text' => ['type' => 'textarea', 'label' => 'Step Three Text'],
        ];
    }
}
