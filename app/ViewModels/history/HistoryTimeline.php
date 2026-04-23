<?php

namespace App\ViewModels\history;

use App\ViewModels\BaseSection;

final class HistoryTimeline extends BaseSection
{
    public function __construct(int $pageId = 0, int $sectionId = 0, string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct($pageId, $sectionId, 'history_timeline', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'eyebrow' => ['type' => 'text', 'label' => 'Eyebrow'],
            'heading' => ['type' => 'text', 'label' => 'Main Heading', 'required' => true],
            'item_one_title' => ['type' => 'text', 'label' => 'Item One Title', 'required' => true],
            'item_one_text' => ['type' => 'textarea', 'label' => 'Item One Text'],
            'item_one_text_secondary' => ['type' => 'textarea', 'label' => 'Item One Secondary Text'],
            'item_one_image' => ['type' => 'image', 'label' => 'Item One Image', 'folder' => 'history', 'prefix' => 'history-century-of-origins'],
            'item_one_label' => ['type' => 'text', 'label' => 'Item One Label'],
            'item_one_caption' => ['type' => 'text', 'label' => 'Item One Caption'],
            'item_two_title' => ['type' => 'text', 'label' => 'Item Two Title', 'required' => true],
            'item_two_text' => ['type' => 'textarea', 'label' => 'Item Two Text'],
            'item_two_text_secondary' => ['type' => 'textarea', 'label' => 'Item Two Secondary Text'],
            'item_two_image' => ['type' => 'image', 'label' => 'Item Two Image', 'folder' => 'history', 'prefix' => 'history-golden-age'],
            'item_two_label' => ['type' => 'text', 'label' => 'Item Two Label'],
            'item_two_caption' => ['type' => 'text', 'label' => 'Item Two Caption'],
            'item_three_title' => ['type' => 'text', 'label' => 'Item Three Title', 'required' => true],
            'item_three_text' => ['type' => 'textarea', 'label' => 'Item Three Text'],
            'item_three_text_secondary' => ['type' => 'textarea', 'label' => 'Item Three Secondary Text'],
            'item_three_image' => ['type' => 'image', 'label' => 'Item Three Image', 'folder' => 'history', 'prefix' => 'history-siege-of-haarlem'],
            'item_three_label' => ['type' => 'text', 'label' => 'Item Three Label'],
            'item_three_caption' => ['type' => 'text', 'label' => 'Item Three Caption'],
        ];
    }
}
