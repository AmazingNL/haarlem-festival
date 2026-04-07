<?php

namespace App\ViewModels\history;

use App\ViewModels\BaseSection;

final class HistoryFeaturedLocations extends BaseSection
{
    public function __construct(int $pageId = 0, int $sectionId = 0, string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct($pageId, $sectionId, 'history_featured_locations', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'eyebrow' => ['type' => 'text', 'label' => 'Eyebrow'],
            'heading' => ['type' => 'text', 'label' => 'Heading', 'required' => true],
            'intro' => ['type' => 'textarea', 'label' => 'Intro Text'],
            'one_label' => ['type' => 'text', 'label' => 'First Card Label'],
            'one_title' => ['type' => 'text', 'label' => 'First Card Title', 'required' => true],
            'one_text' => ['type' => 'textarea', 'label' => 'First Card Text'],
            'one_badge' => ['type' => 'text', 'label' => 'First Card Badge'],
            'one_image' => ['type' => 'image', 'label' => 'First Card Image', 'folder' => 'history', 'prefix' => 'history-grote-kerk'],
            'one_feature_one' => ['type' => 'text', 'label' => 'First Card Feature One'],
            'one_feature_two' => ['type' => 'text', 'label' => 'First Card Feature Two'],
            'one_feature_three' => ['type' => 'text', 'label' => 'First Card Feature Three'],
            'one_feature_four' => ['type' => 'text', 'label' => 'First Card Feature Four'],
            'one_button_text' => ['type' => 'text', 'label' => 'First Card Button Text'],
            'one_button_link' => ['type' => 'text', 'label' => 'First Card Button Link'],
            'two_label' => ['type' => 'text', 'label' => 'Second Card Label'],
            'two_title' => ['type' => 'text', 'label' => 'Second Card Title', 'required' => true],
            'two_text' => ['type' => 'textarea', 'label' => 'Second Card Text'],
            'two_badge' => ['type' => 'text', 'label' => 'Second Card Badge'],
            'two_image' => ['type' => 'image', 'label' => 'Second Card Image', 'folder' => 'history', 'prefix' => 'history-molen-de-adriaan'],
            'two_feature_one' => ['type' => 'text', 'label' => 'Second Card Feature One'],
            'two_feature_two' => ['type' => 'text', 'label' => 'Second Card Feature Two'],
            'two_feature_three' => ['type' => 'text', 'label' => 'Second Card Feature Three'],
            'two_feature_four' => ['type' => 'text', 'label' => 'Second Card Feature Four'],
            'two_button_text' => ['type' => 'text', 'label' => 'Second Card Button Text'],
            'two_button_link' => ['type' => 'text', 'label' => 'Second Card Button Link'],
        ];
    }
}
