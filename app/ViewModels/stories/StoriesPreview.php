<?php

declare(strict_types=1);

namespace App\ViewModels\stories;

use App\ViewModels\BaseSection;

/**
 * ViewModel for the stories_preview section type.
 *
 * Drives the dark mosaic gallery section on the stories landing page.
 * Content should contain a .sp-mosaic grid built in the WYSIWYG editor.
 */
final class StoriesPreview extends BaseSection
{
    /**
     * @param int    $pageId      Associated page ID.
     * @param int    $sectionId   Section ID.
     * @param string $customClass Optional CSS class override.
     * @param int    $sortOrder   Display order.
     */
    public function __construct(int $pageId = 0, int $sectionId = 0, string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct($pageId, $sectionId, 'stories_preview', $customClass, $sortOrder);
    }

    /**
     * Returns the admin form field definitions for this section type.
     *
     * @return array<string, array<string, string>>
     */
    public function getAdminFormFields(): array
    {
        return [
            'title'   => ['type' => 'text',    'label' => 'Title', 'required' => 'true'],
            'content' => ['type' => 'wysiwyg', 'label' => 'Content (include .sp-mosaic grid)'],
        ];
    }
}
