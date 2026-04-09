<?php

namespace App\ViewModels\history;

use App\ViewModels\BaseSection;

final class HistoryStBavoSidebar extends BaseSection
{
    public function __construct(int $pageId = 0, int $sectionId = 0, string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct($pageId, $sectionId, 'history_st_bavo_sidebar', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'map_heading' => ['type' => 'text', 'label' => 'Map Card Heading'],
            'map_address' => ['type' => 'text', 'label' => 'Map Card Address'],
            'map_image' => ['type' => 'image', 'label' => 'Map Image', 'folder' => 'history', 'prefix' => 'history-st-bavo-map'],
            'map_link_text' => ['type' => 'text', 'label' => 'Map Link Text'],
            'map_link_url' => ['type' => 'text', 'label' => 'Map Link URL'],
            'details_heading' => ['type' => 'text', 'label' => 'Details Heading'],
            'full_address_label' => ['type' => 'text', 'label' => 'Full Address Label'],
            'full_address_value' => ['type' => 'textarea', 'label' => 'Full Address Value'],
            'construction_label' => ['type' => 'text', 'label' => 'Construction Label'],
            'construction_value' => ['type' => 'text', 'label' => 'Construction Value'],
            'style_label' => ['type' => 'text', 'label' => 'Style Label'],
            'style_value' => ['type' => 'text', 'label' => 'Style Value'],
            'purpose_label' => ['type' => 'text', 'label' => 'Original Purpose Label'],
            'purpose_value' => ['type' => 'text', 'label' => 'Original Purpose Value'],
            'function_label' => ['type' => 'text', 'label' => 'Current Function Label'],
            'function_value' => ['type' => 'text', 'label' => 'Current Function Value'],
            'opening_label' => ['type' => 'text', 'label' => 'Opening Hours Label'],
            'opening_value' => ['type' => 'textarea', 'label' => 'Opening Hours Value'],
            'facts_heading' => ['type' => 'text', 'label' => 'Did You Know Heading'],
            'fact_one' => ['type' => 'text', 'label' => 'Did You Know Fact One'],
            'fact_two' => ['type' => 'text', 'label' => 'Did You Know Fact Two'],
            'fact_three' => ['type' => 'text', 'label' => 'Did You Know Fact Three'],
            'fact_four' => ['type' => 'text', 'label' => 'Did You Know Fact Four'],
            'fact_five' => ['type' => 'text', 'label' => 'Did You Know Fact Five'],
            'tour_heading' => ['type' => 'text', 'label' => 'Tour Card Heading'],
            'tour_text' => ['type' => 'textarea', 'label' => 'Tour Card Text'],
            'tour_button_text' => ['type' => 'text', 'label' => 'Tour Button Text'],
            'tour_button_link' => ['type' => 'text', 'label' => 'Tour Button Link'],
        ];
    }
}
