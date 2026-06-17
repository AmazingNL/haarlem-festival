<?php

namespace App\Schemas\stories;

use App\Schemas\BaseSection;

final class WhatIsStories extends BaseSection
{
    public function __construct(string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct('what_is_stories', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'title'   => ['type' => 'text',    'label' => 'Title', 'required' => 'true'],
            'content' => ['type' => 'wysiwyg', 'label' => 'Content (include <img class="wis-image"> for the right-column image)'],
        ];
    }
}
