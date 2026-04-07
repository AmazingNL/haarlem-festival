<?php

namespace App\ViewModels\history;

use App\ViewModels\BaseSection;

final class HistoryGallery extends BaseSection
{
    public function __construct(int $pageId = 0, int $sectionId = 0, string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct($pageId, $sectionId, 'history_gallery', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'eyebrow' => ['type' => 'text', 'label' => 'Eyebrow'],
            'heading' => ['type' => 'text', 'label' => 'Heading', 'required' => true],
            'card_one_label' => ['type' => 'text', 'label' => 'Card One Label'],
            'card_one_title' => ['type' => 'text', 'label' => 'Card One Title'],
            'card_one_text' => ['type' => 'textarea', 'label' => 'Card One Text'],
            'card_one_image' => ['type' => 'image', 'label' => 'Card One Image', 'folder' => 'history', 'prefix' => 'history-hidden-gems'],
            'card_two_label' => ['type' => 'text', 'label' => 'Card Two Label'],
            'card_two_title' => ['type' => 'text', 'label' => 'Card Two Title'],
            'card_two_text' => ['type' => 'textarea', 'label' => 'Card Two Text'],
            'card_two_image' => ['type' => 'image', 'label' => 'Card Two Image', 'folder' => 'history', 'prefix' => 'history-molen-sunset'],
            'card_three_label' => ['type' => 'text', 'label' => 'Card Three Label'],
            'card_three_title' => ['type' => 'text', 'label' => 'Card Three Title'],
            'card_three_text' => ['type' => 'textarea', 'label' => 'Card Three Text'],
            'card_three_image' => ['type' => 'image', 'label' => 'Card Three Image', 'folder' => 'history', 'prefix' => 'history-sacred-architecture'],
        ];
    }
}
