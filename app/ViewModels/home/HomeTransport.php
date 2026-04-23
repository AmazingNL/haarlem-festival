<?php

namespace App\ViewModels\home;

use App\ViewModels\BaseSection;

final class HomeTransport extends BaseSection
{
    public function __construct(int $pageId = 0, int $sectionId = 0, string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct($pageId, $sectionId, 'transport', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'heading' => ['type' => 'text', 'label' => 'Section Heading', 'required' => true],
            'intro' => ['type' => 'textarea', 'label' => 'Introduction', 'required' => true],
            'list_intro' => ['type' => 'text', 'label' => 'List Intro Text'],
            'item_one' => ['type' => 'text', 'label' => 'List Item One'],
            'item_two' => ['type' => 'text', 'label' => 'List Item Two'],
            'item_three' => ['type' => 'text', 'label' => 'List Item Three'],
            'item_four' => ['type' => 'text', 'label' => 'List Item Four'],
            'image' => ['type' => 'image', 'label' => 'Transport Image', 'folder' => 'home', 'prefix' => 'home-transport'],
            'image_alt' => ['type' => 'text', 'label' => 'Image Alt Text'],
            'button_text' => ['type' => 'text', 'label' => 'Button Text'],
            'button_link' => ['type' => 'text', 'label' => 'Button Link'],
        ];
    }
}
