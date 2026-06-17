<?php

namespace App\Schemas\stories;

use App\Schemas\BaseSection;

final class StoriesHero extends BaseSection
{
    public function __construct(string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct('stories_hero', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'title'       => ['type' => 'text',    'label' => 'Title',        'required' => 'true'],
            'content'     => ['type' => 'wysiwyg', 'label' => 'Content'],
            'image_path'  => ['type' => 'image',   'label' => 'Hero Image', 'folder' => 'stories', 'prefix' => 'stories-hero'],
            'button_text' => ['type' => 'text',    'label' => 'Button Text'],
            'button_link' => ['type' => 'text',    'label' => 'Button Link'],
        ];
    }
}
