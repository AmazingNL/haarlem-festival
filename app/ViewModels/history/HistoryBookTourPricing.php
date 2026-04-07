<?php

namespace App\ViewModels\history;

use App\ViewModels\BaseSection;

final class HistoryBookTourPricing extends BaseSection
{
    public function __construct(int $pageId = 0, int $sectionId = 0, string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct($pageId, $sectionId, 'history_book_tour_pricing', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'heading' => ['type' => 'text', 'label' => 'Heading', 'required' => true],
            'intro' => ['type' => 'text', 'label' => 'Intro Text'],
            'left_title' => ['type' => 'text', 'label' => 'Left Card Title'],
            'left_subtitle' => ['type' => 'text', 'label' => 'Left Card Subtitle'],
            'left_price' => ['type' => 'text', 'label' => 'Left Card Price'],
            'left_feature_one' => ['type' => 'text', 'label' => 'Left Card Feature One'],
            'left_feature_two' => ['type' => 'text', 'label' => 'Left Card Feature Two'],
            'right_badge' => ['type' => 'text', 'label' => 'Right Card Badge'],
            'right_title' => ['type' => 'text', 'label' => 'Right Card Title'],
            'right_subtitle' => ['type' => 'text', 'label' => 'Right Card Subtitle'],
            'right_price' => ['type' => 'text', 'label' => 'Right Card Price'],
            'right_note' => ['type' => 'text', 'label' => 'Right Card Note'],
            'right_feature_one' => ['type' => 'text', 'label' => 'Right Card Feature One'],
            'right_feature_two' => ['type' => 'text', 'label' => 'Right Card Feature Two'],
        ];
    }
}
