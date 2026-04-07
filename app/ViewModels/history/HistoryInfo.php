<?php

namespace App\ViewModels\history;

use App\ViewModels\BaseSection;

final class HistoryInfo extends BaseSection
{
    public function __construct(int $pageId = 0, int $sectionId = 0, string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct($pageId, $sectionId, 'history_info', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'item_one_value' => ['type' => 'text', 'label' => 'Item One Value'],
            'item_one_label' => ['type' => 'text', 'label' => 'Item One Label'],
            'item_two_value' => ['type' => 'text', 'label' => 'Item Two Value'],
            'item_two_label' => ['type' => 'text', 'label' => 'Item Two Label'],
            'item_three_value' => ['type' => 'text', 'label' => 'Item Three Value'],
            'item_three_label' => ['type' => 'text', 'label' => 'Item Three Label'],
            'item_four_value' => ['type' => 'text', 'label' => 'Item Four Value'],
            'item_four_label' => ['type' => 'text', 'label' => 'Item Four Label'],
        ];
    }
}
