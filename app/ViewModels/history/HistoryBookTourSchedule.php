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
            'default_day' => ['type' => 'text', 'label' => 'Default Schedule Day'],
            'guide_label' => ['type' => 'text', 'label' => 'Guide Label'],
            'time_one' => ['type' => 'text', 'label' => 'Time One'],
            'time_two' => ['type' => 'text', 'label' => 'Time Two'],
            'time_three' => ['type' => 'text', 'label' => 'Time Three'],
            'day_one_time_one_guides' => ['type' => 'text', 'label' => 'Day One Guides at Time One'],
            'day_one_time_two_guides' => ['type' => 'text', 'label' => 'Day One Guides at Time Two'],
            'day_one_time_three_guides' => ['type' => 'text', 'label' => 'Day One Guides at Time Three'],
            'day_two_time_one_guides' => ['type' => 'text', 'label' => 'Day Two Guides at Time One'],
            'day_two_time_two_guides' => ['type' => 'text', 'label' => 'Day Two Guides at Time Two'],
            'day_two_time_three_guides' => ['type' => 'text', 'label' => 'Day Two Guides at Time Three'],
            'day_three_time_one_guides' => ['type' => 'text', 'label' => 'Day Three Guides at Time One'],
            'day_three_time_two_guides' => ['type' => 'text', 'label' => 'Day Three Guides at Time Two'],
            'day_three_time_three_guides' => ['type' => 'text', 'label' => 'Day Three Guides at Time Three'],
            'day_four_time_one_guides' => ['type' => 'text', 'label' => 'Day Four Guides at Time One'],
            'day_four_time_two_guides' => ['type' => 'text', 'label' => 'Day Four Guides at Time Two'],
            'day_four_time_three_guides' => ['type' => 'text', 'label' => 'Day Four Guides at Time Three'],
        ];
    }
}
