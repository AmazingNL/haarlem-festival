<?php

namespace App\ViewModels\history;

use App\ViewModels\BaseSection;

final class HistoryRouteMapStops extends BaseSection
{
    public function __construct(int $pageId = 0, int $sectionId = 0, string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct($pageId, $sectionId, 'history_route_map_stops', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'heading' => ['type' => 'text', 'label' => 'Section Heading', 'required' => true],
            'summary_one' => ['type' => 'text', 'label' => 'Summary One'],
            'summary_two' => ['type' => 'text', 'label' => 'Summary Two'],
            'summary_three' => ['type' => 'text', 'label' => 'Summary Three'],
            'legend_start' => ['type' => 'text', 'label' => 'Legend Start Label'],
            'legend_break' => ['type' => 'text', 'label' => 'Legend Break Label'],
            'legend_stop' => ['type' => 'text', 'label' => 'Legend Stop Label'],
            'stop_one_title' => ['type' => 'text', 'label' => 'Stop One Title'],
            'stop_one_map_label' => ['type' => 'text', 'label' => 'Stop One Map Label'],
            'stop_one_text' => ['type' => 'text', 'label' => 'Stop One Text'],
            'stop_one_time' => ['type' => 'text', 'label' => 'Stop One Time'],
            'stop_one_tag' => ['type' => 'text', 'label' => 'Stop One Tag'],
            'stop_one_tone' => ['type' => 'text', 'label' => 'Stop One Tone (start, stop, break, end)'],
            'stop_two_title' => ['type' => 'text', 'label' => 'Stop Two Title'],
            'stop_two_map_label' => ['type' => 'text', 'label' => 'Stop Two Map Label'],
            'stop_two_text' => ['type' => 'text', 'label' => 'Stop Two Text'],
            'stop_two_time' => ['type' => 'text', 'label' => 'Stop Two Time'],
            'stop_two_tone' => ['type' => 'text', 'label' => 'Stop Two Tone (start, stop, break, end)'],
            'stop_three_title' => ['type' => 'text', 'label' => 'Stop Three Title'],
            'stop_three_map_label' => ['type' => 'text', 'label' => 'Stop Three Map Label'],
            'stop_three_text' => ['type' => 'text', 'label' => 'Stop Three Text'],
            'stop_three_time' => ['type' => 'text', 'label' => 'Stop Three Time'],
            'stop_three_tone' => ['type' => 'text', 'label' => 'Stop Three Tone (start, stop, break, end)'],
            'stop_four_title' => ['type' => 'text', 'label' => 'Stop Four Title'],
            'stop_four_map_label' => ['type' => 'text', 'label' => 'Stop Four Map Label'],
            'stop_four_text' => ['type' => 'text', 'label' => 'Stop Four Text'],
            'stop_four_time' => ['type' => 'text', 'label' => 'Stop Four Time'],
            'stop_four_tone' => ['type' => 'text', 'label' => 'Stop Four Tone (start, stop, break, end)'],
            'stop_five_title' => ['type' => 'text', 'label' => 'Stop Five Title'],
            'stop_five_map_label' => ['type' => 'text', 'label' => 'Stop Five Map Label'],
            'stop_five_text' => ['type' => 'text', 'label' => 'Stop Five Text'],
            'stop_five_time' => ['type' => 'text', 'label' => 'Stop Five Time'],
            'stop_five_tag' => ['type' => 'text', 'label' => 'Stop Five Tag'],
            'stop_five_tone' => ['type' => 'text', 'label' => 'Stop Five Tone (start, stop, break, end)'],
            'stop_six_title' => ['type' => 'text', 'label' => 'Stop Six Title'],
            'stop_six_map_label' => ['type' => 'text', 'label' => 'Stop Six Map Label'],
            'stop_six_text' => ['type' => 'text', 'label' => 'Stop Six Text'],
            'stop_six_time' => ['type' => 'text', 'label' => 'Stop Six Time'],
            'stop_six_tone' => ['type' => 'text', 'label' => 'Stop Six Tone (start, stop, break, end)'],
            'stop_seven_title' => ['type' => 'text', 'label' => 'Stop Seven Title'],
            'stop_seven_map_label' => ['type' => 'text', 'label' => 'Stop Seven Map Label'],
            'stop_seven_text' => ['type' => 'text', 'label' => 'Stop Seven Text'],
            'stop_seven_time' => ['type' => 'text', 'label' => 'Stop Seven Time'],
            'stop_seven_tone' => ['type' => 'text', 'label' => 'Stop Seven Tone (start, stop, break, end)'],
            'stop_eight_title' => ['type' => 'text', 'label' => 'Stop Eight Title'],
            'stop_eight_map_label' => ['type' => 'text', 'label' => 'Stop Eight Map Label'],
            'stop_eight_text' => ['type' => 'text', 'label' => 'Stop Eight Text'],
            'stop_eight_time' => ['type' => 'text', 'label' => 'Stop Eight Time'],
            'stop_eight_tone' => ['type' => 'text', 'label' => 'Stop Eight Tone (start, stop, break, end)'],
            'stop_nine_title' => ['type' => 'text', 'label' => 'Stop Nine Title'],
            'stop_nine_map_label' => ['type' => 'text', 'label' => 'Stop Nine Map Label'],
            'stop_nine_text' => ['type' => 'text', 'label' => 'Stop Nine Text'],
            'stop_nine_time' => ['type' => 'text', 'label' => 'Stop Nine Time'],
            'stop_nine_tag' => ['type' => 'text', 'label' => 'Stop Nine Tag'],
            'stop_nine_tone' => ['type' => 'text', 'label' => 'Stop Nine Tone (start, stop, break, end)'],
        ];
    }
}
