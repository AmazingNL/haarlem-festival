<?php

declare(strict_types=1);

namespace App\ViewModels\stories;

use App\ViewModels\BaseSection;

/**
 * ViewModel for the stories_hero section type.
 *
 * Drives the full-bleed hero banner at the top of the stories landing page.
 */
final class StoriesHero extends BaseSection
{
    /**
     * @param int    $pageId      Associated page ID.
     * @param int    $sectionId   Section ID.
     * @param string $customClass Optional CSS class override.
     * @param int    $sortOrder   Display order.
     */
    public function __construct(int $pageId = 0, int $sectionId = 0, string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct($pageId, $sectionId, 'stories_hero', $customClass, $sortOrder);
    }

    /**
     * Returns the admin form field definitions for this section type.
     *
     * @return array<string, array<string, string>>
     */
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
