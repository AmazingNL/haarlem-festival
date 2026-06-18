<?php

namespace App\Schemas\stories;

use App\Schemas\BaseSection;

final class StoriesPreview extends BaseSection
{
    public function __construct(string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct('stories_preview', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'title'   => ['type' => 'text',    'label' => 'Title', 'required' => 'true'],
            'content' => ['type' => 'wysiwyg', 'label' => 'Content (include .sp-mosaic grid with <img> tags)'],
        ];
    }
}
