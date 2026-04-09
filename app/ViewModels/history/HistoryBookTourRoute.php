<?php

namespace App\ViewModels\history;

use App\ViewModels\BaseSection;

final class HistoryBookTourRoute extends BaseSection
{
    public function __construct(int $pageId = 0, int $sectionId = 0, string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct($pageId, $sectionId, 'history_book_tour_route', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'heading' => ['type' => 'text', 'label' => 'Heading', 'required' => true],
            'stop_one_title' => ['type' => 'text', 'label' => 'Stop One Title'],
            'stop_one_text' => ['type' => 'text', 'label' => 'Stop One Text'],
            'stop_one_time' => ['type' => 'text', 'label' => 'Stop One Time'],
            'stop_two_title' => ['type' => 'text', 'label' => 'Stop Two Title'],
            'stop_two_text' => ['type' => 'text', 'label' => 'Stop Two Text'],
            'stop_two_time' => ['type' => 'text', 'label' => 'Stop Two Time'],
            'stop_three_title' => ['type' => 'text', 'label' => 'Stop Three Title'],
            'stop_three_text' => ['type' => 'text', 'label' => 'Stop Three Text'],
            'stop_three_time' => ['type' => 'text', 'label' => 'Stop Three Time'],
            'stop_four_title' => ['type' => 'text', 'label' => 'Stop Four Title'],
            'stop_four_text' => ['type' => 'text', 'label' => 'Stop Four Text'],
            'stop_four_time' => ['type' => 'text', 'label' => 'Stop Four Time'],
            'stop_five_title' => ['type' => 'text', 'label' => 'Stop Five Title'],
            'stop_five_text' => ['type' => 'text', 'label' => 'Stop Five Text'],
            'stop_five_time' => ['type' => 'text', 'label' => 'Stop Five Time'],
            'stop_five_badge' => ['type' => 'text', 'label' => 'Stop Five Badge'],
            'stop_six_title' => ['type' => 'text', 'label' => 'Stop Six Title'],
            'stop_six_text' => ['type' => 'text', 'label' => 'Stop Six Text'],
            'stop_six_time' => ['type' => 'text', 'label' => 'Stop Six Time'],
            'stop_seven_title' => ['type' => 'text', 'label' => 'Stop Seven Title'],
            'stop_seven_text' => ['type' => 'text', 'label' => 'Stop Seven Text'],
            'stop_seven_time' => ['type' => 'text', 'label' => 'Stop Seven Time'],
            'stop_eight_title' => ['type' => 'text', 'label' => 'Stop Eight Title'],
            'stop_eight_text' => ['type' => 'text', 'label' => 'Stop Eight Text'],
            'stop_eight_time' => ['type' => 'text', 'label' => 'Stop Eight Time'],
            'stop_nine_title' => ['type' => 'text', 'label' => 'Stop Nine Title'],
            'stop_nine_text' => ['type' => 'text', 'label' => 'Stop Nine Text'],
            'stop_nine_time' => ['type' => 'text', 'label' => 'Stop Nine Time'],
            'total_label' => ['type' => 'text', 'label' => 'Total Label'],
            'total_value' => ['type' => 'text', 'label' => 'Total Value'],
            'total_note' => ['type' => 'text', 'label' => 'Total Note'],
            'meeting_title' => ['type' => 'text', 'label' => 'Meeting Title'],
            'meeting_text' => ['type' => 'textarea', 'label' => 'Meeting Text'],
            'button_text' => ['type' => 'text', 'label' => 'Button Text'],
            'button_link' => ['type' => 'text', 'label' => 'Button Link'],
        ];
    }
}
