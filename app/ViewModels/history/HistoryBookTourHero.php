<?php

namespace App\ViewModels\history;

use App\ViewModels\BaseSection;

final class HistoryBookTourHero extends BaseSection
{
    public function __construct(int $pageId = 0, int $sectionId = 0, string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct($pageId, $sectionId, 'history_book_tour_hero', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'eyebrow' => ['type' => 'text', 'label' => 'Eyebrow'],
            'heading' => ['type' => 'text', 'label' => 'Heading', 'required' => true],
            'intro' => ['type' => 'textarea', 'label' => 'Intro Text'],
            'stat_one' => ['type' => 'text', 'label' => 'First Stat'],
            'stat_two' => ['type' => 'text', 'label' => 'Second Stat'],
            'stat_three' => ['type' => 'text', 'label' => 'Third Stat'],
            'stat_four' => ['type' => 'text', 'label' => 'Fourth Stat'],
        ];
    }
}
