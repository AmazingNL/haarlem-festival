<?php

namespace App\ViewModels\history;

use App\ViewModels\BaseSection;

final class HistoryPageNav extends BaseSection
{
    public function __construct(int $pageId = 0, int $sectionId = 0, string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct($pageId, $sectionId, 'history_page_nav', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'book_tour_label' => ['type' => 'text', 'label' => 'Book Tour Label'],
            'book_tour_link' => ['type' => 'text', 'label' => 'Book Tour Link'],
            'route_map_label' => ['type' => 'text', 'label' => 'Route Map Label'],
            'route_map_link' => ['type' => 'text', 'label' => 'Route Map Link'],
            'st_bavo_label' => ['type' => 'text', 'label' => 'St. Bavo Label'],
            'st_bavo_link' => ['type' => 'text', 'label' => 'St. Bavo Link'],
            'molen_label' => ['type' => 'text', 'label' => 'Molen Label'],
            'molen_link' => ['type' => 'text', 'label' => 'Molen Link'],
        ];
    }
}
