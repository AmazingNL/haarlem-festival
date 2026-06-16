<?php

namespace App\Schemas\home;

use App\Schemas\BaseSection;

final class HomeImageRight extends BaseSection
{
    public function __construct(string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct('image_right', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'heading' => ['type' => 'text', 'label' => 'Heading', 'required' => true],
            'body' => ['type' => 'textarea', 'label' => 'Body Text', 'required' => true],
            'image_one' => ['type' => 'image', 'label' => 'First Image', 'folder' => 'home', 'prefix' => 'home-image-right-one'],
            'image_one_alt' => ['type' => 'text', 'label' => 'First Image Alt Text'],
            'image_two' => ['type' => 'image', 'label' => 'Second Image', 'folder' => 'home', 'prefix' => 'home-image-right-two'],
            'image_two_alt' => ['type' => 'text', 'label' => 'Second Image Alt Text'],
            'button_text' => ['type' => 'text', 'label' => 'Button Text'],
            'button_link' => ['type' => 'text', 'label' => 'Button Link'],
        ];
    }
}
