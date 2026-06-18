<?php

declare(strict_types=1);

namespace App\ViewModels\stories;

use App\ViewModels\BaseSection;

/**
 * ViewModel for the storytelling_schedule section type.
 *
 * Drives the tabbed Thu/Fri/Sat/Sun schedule section on the stories page.
 * Content should contain .sched-day[data-day] divs with .sched-card children.
 */
final class StorytellingSchedule extends BaseSection
{
    /**
     * @param int    $pageId      Associated page ID.
     * @param int    $sectionId   Section ID.
     * @param string $customClass Optional CSS class override.
     * @param int    $sortOrder   Display order.
     */
    public function __construct(int $pageId = 0, int $sectionId = 0, string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct($pageId, $sectionId, 'storytelling_schedule', $customClass, $sortOrder);
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
            'content' => ['type' => 'wysiwyg', 'label' => 'Schedule HTML (use .sched-day / .sched-card structure)'],
        ];
    }
}
