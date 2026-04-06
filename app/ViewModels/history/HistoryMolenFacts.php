<?php

namespace App\ViewModels\history;

use App\ViewModels\BaseSection;

final class HistoryMolenFacts extends BaseSection
{
    public function __construct(int $pageId = 0, int $sectionId = 0, string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct($pageId, $sectionId, 'history_molen_facts', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'fact_one_label' => ['type' => 'text', 'label' => 'First Fact Label'],
            'fact_one_value' => ['type' => 'text', 'label' => 'First Fact Value'],
            'fact_two_label' => ['type' => 'text', 'label' => 'Second Fact Label'],
            'fact_two_value' => ['type' => 'text', 'label' => 'Second Fact Value'],
            'fact_three_label' => ['type' => 'text', 'label' => 'Third Fact Label'],
            'fact_three_value' => ['type' => 'text', 'label' => 'Third Fact Value'],
            'fact_four_label' => ['type' => 'text', 'label' => 'Fourth Fact Label'],
            'fact_four_value' => ['type' => 'text', 'label' => 'Fourth Fact Value'],
        ];
    }
}
