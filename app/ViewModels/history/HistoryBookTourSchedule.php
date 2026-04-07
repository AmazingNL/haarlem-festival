<?php

namespace App\ViewModels\history;

use App\ViewModels\BaseSection;

final class HistoryBookTourSchedule extends BaseSection
{
    public function __construct(int $pageId = 0, int $sectionId = 0, string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct($pageId, $sectionId, 'history_book_tour_schedule', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'heading' => ['type' => 'text', 'label' => 'Heading', 'required' => true],
            'intro' => ['type' => 'text', 'label' => 'Intro Text'],
            'day_one' => ['type' => 'text', 'label' => 'Day One'],
            'day_two' => ['type' => 'text', 'label' => 'Day Two'],
            'day_three' => ['type' => 'text', 'label' => 'Day Three'],
            'day_four' => ['type' => 'text', 'label' => 'Day Four'],
            'guide_label' => ['type' => 'text', 'label' => 'Guide Label'],
            'time_one' => ['type' => 'text', 'label' => 'Time One'],
            'time_two' => ['type' => 'text', 'label' => 'Time Two'],
            'time_three' => ['type' => 'text', 'label' => 'Time Three'],
        ];
    }
}
