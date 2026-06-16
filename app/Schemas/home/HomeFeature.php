<?php

namespace App\Schemas\home;

use App\Schemas\BaseSection;

final class HomeFeature extends BaseSection
{
    public function __construct(string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct('feature', $customClass, $sortOrder);
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
