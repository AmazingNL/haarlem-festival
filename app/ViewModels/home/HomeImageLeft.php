<?php

namespace App\ViewModels\home;

use App\ViewModels\BaseSection;

final class HomeImageLeft extends BaseSection
{
    public function __construct(int $pageId = 0, int $sectionId = 0, string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct($pageId, $sectionId, 'image_left', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'heading' => ['type' => 'text', 'label' => 'Heading', 'required' => true],
            'body' => ['type' => 'textarea', 'label' => 'Body Text', 'required' => true],
            'image' => ['type' => 'image', 'label' => 'Main Image', 'folder' => 'home', 'prefix' => 'home-image-left'],
            'image_alt' => ['type' => 'text', 'label' => 'Image Alt Text'],
            'button_text' => ['type' => 'text', 'label' => 'Button Text'],
            'button_link' => ['type' => 'text', 'label' => 'Button Link'],
        ];
    }
}
