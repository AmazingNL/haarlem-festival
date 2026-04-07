<?php

namespace App\ViewModels\history;

use App\ViewModels\BaseSection;

final class HistoryStBavoHero extends BaseSection
{
    public function __construct(int $pageId = 0, int $sectionId = 0, string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct($pageId, $sectionId, 'history_st_bavo_hero', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'back_text' => ['type' => 'text', 'label' => 'Back Button Text'],
            'back_link' => ['type' => 'text', 'label' => 'Back Button Link'],
            'eyebrow' => ['type' => 'text', 'label' => 'Eyebrow'],
            'heading' => ['type' => 'text', 'label' => 'Heading', 'required' => true],
            'subtitle' => ['type' => 'text', 'label' => 'Subtitle'],
            'hero_image' => ['type' => 'image', 'label' => 'Hero Image', 'folder' => 'history', 'prefix' => 'history-st-bavo-hero'],
        ];
    }
}
