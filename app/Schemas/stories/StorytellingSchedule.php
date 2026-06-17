<?php

namespace App\Schemas\stories;

use App\Schemas\BaseSection;

final class StorytellingSchedule extends BaseSection
{
    public function __construct(string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct('storytelling_schedule', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'title'   => ['type' => 'text',    'label' => 'Title', 'required' => 'true'],
            'content' => ['type' => 'wysiwyg', 'label' => 'Schedule HTML (.sched-day[data-day=thu|fri|sat|sun] > .sched-cards > .sched-card)'],
        ];
    }
}
