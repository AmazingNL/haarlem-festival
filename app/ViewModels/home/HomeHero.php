<?php

namespace App\ViewModels\home;

use App\ViewModels\BaseSection;

final class HomeHero extends BaseSection
{
    public function __construct(int $pageId = 0, int $sectionId = 0, string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct($pageId, $sectionId, 'hero', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'heading' => ['type' => 'text', 'label' => 'Main Heading', 'required' => true],
            'hero_image' => ['type' => 'image', 'label' => 'Hero Image', 'folder' => 'home', 'prefix' => 'home-hero'],
            'hero_image_alt' => ['type' => 'text', 'label' => 'Hero Image Alt Text'],
        ];
    }
}
