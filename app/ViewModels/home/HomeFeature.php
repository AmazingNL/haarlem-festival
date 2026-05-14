<?php

namespace App\ViewModels\home;

use App\ViewModels\BaseSection;

final class HomeFeature extends BaseSection
{
    public function __construct(int $pageId = 0, int $sectionId = 0, string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct($pageId, $sectionId, 'feature', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'title' => ['type' => 'text', 'label' => 'Section Title', 'required' => true],
            'article' => ['type' => 'textarea', 'label' => 'Main Text', 'required' => true],
            'button_text' => ['type' => 'text', 'label' => 'Button Text'],
            'button_link' => ['type' => 'text', 'label' => 'Button Link'],
        ];
    }
}
