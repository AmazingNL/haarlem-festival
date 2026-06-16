<?php

namespace App\Schemas\history;

use App\Schemas\BaseSection;

final class HistoryBookTourHero extends BaseSection
{
    public function __construct(string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct('history_book_tour_hero', $customClass, $sortOrder);
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
