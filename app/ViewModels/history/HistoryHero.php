<?php

namespace App\ViewModels\history;

use App\ViewModels\BaseSection;

final class HistoryHero extends BaseSection
{
    public function __construct(int $pageId = 0, int $sectionId = 0, string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct($pageId, $sectionId, 'history_hero', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'title_line_one' => ['type' => 'text', 'label' => 'Title Line One', 'required' => true],
            'title_line_two' => ['type' => 'text', 'label' => 'Title Line Two', 'required' => true],
            'intro' => ['type' => 'textarea', 'label' => 'Introduction'],
            'hero_image' => ['type' => 'image', 'label' => 'Hero Image', 'folder' => 'history', 'prefix' => 'history-hero-banner'],
            'quick_link_one_label' => ['type' => 'text', 'label' => 'Quick Link One Label'],
            'quick_link_one_link' => ['type' => 'text', 'label' => 'Quick Link One Link'],
            'quick_link_two_label' => ['type' => 'text', 'label' => 'Quick Link Two Label'],
            'quick_link_two_link' => ['type' => 'text', 'label' => 'Quick Link Two Link'],
            'quick_link_three_label' => ['type' => 'text', 'label' => 'Quick Link Three Label'],
            'quick_link_three_link' => ['type' => 'text', 'label' => 'Quick Link Three Link'],
            'quick_link_four_label' => ['type' => 'text', 'label' => 'Quick Link Four Label'],
            'quick_link_four_link' => ['type' => 'text', 'label' => 'Quick Link Four Link'],
            'primary_button_text' => ['type' => 'text', 'label' => 'Primary Button Text'],
            'primary_button_link' => ['type' => 'text', 'label' => 'Primary Button Link'],
            'secondary_button_text' => ['type' => 'text', 'label' => 'Secondary Button Text'],
            'secondary_button_link' => ['type' => 'text', 'label' => 'Secondary Button Link'],
        ];
    }
}
