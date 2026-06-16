<?php

namespace App\Schemas\history;

use App\Schemas\BaseSection;

final class HistoryStBavoRouteCta extends BaseSection
{
    public function __construct(string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct('history_st_bavo_route_cta', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'heading' => ['type' => 'text', 'label' => 'Heading', 'required' => true],
            'body' => ['type' => 'textarea', 'label' => 'Body'],
            'button_text' => ['type' => 'text', 'label' => 'Button Text'],
            'button_link' => ['type' => 'text', 'label' => 'Button Link'],
        ];
    }
}
