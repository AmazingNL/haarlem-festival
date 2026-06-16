<?php

namespace App\Schemas\history;

use App\Schemas\BaseSection;

final class HistoryCta extends BaseSection
{
    public function __construct(string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct('history_cta', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'eyebrow' => ['type' => 'text', 'label' => 'Eyebrow'],
            'title_line_one' => ['type' => 'text', 'label' => 'Title Line One', 'required' => true],
            'title_line_two' => ['type' => 'text', 'label' => 'Title Line Two', 'required' => true],
            'body' => ['type' => 'textarea', 'label' => 'Body'],
            'background_image' => ['type' => 'image', 'label' => 'Background Image', 'folder' => 'history', 'prefix' => 'history-ready-to-explore'],
            'primary_button_text' => ['type' => 'text', 'label' => 'Primary Button Text'],
            'primary_button_link' => ['type' => 'text', 'label' => 'Primary Button Link'],
            'secondary_button_text' => ['type' => 'text', 'label' => 'Secondary Button Text'],
            'secondary_button_link' => ['type' => 'text', 'label' => 'Secondary Button Link'],
        ];
    }
}
