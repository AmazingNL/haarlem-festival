<?php

namespace App\ViewModels\history;

use App\ViewModels\BaseSection;

final class HistoryRoute extends BaseSection
{
    public function __construct(int $pageId = 0, int $sectionId = 0, string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct($pageId, $sectionId, 'history_route', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        $fields = [
            'eyebrow' => ['type' => 'text', 'label' => 'Eyebrow'],
            'heading' => ['type' => 'text', 'label' => 'Heading', 'required' => true],
            'intro' => ['type' => 'textarea', 'label' => 'Intro Text'],
        ];

        $labels = [
            'one' => 'One',
            'two' => 'Two',
            'three' => 'Three',
            'four' => 'Four',
            'five' => 'Five',
            'six' => 'Six',
            'seven' => 'Seven',
            'eight' => 'Eight',
            'nine' => 'Nine',
        ];

        foreach ($labels as $key => $label) {
            $fields['venue_' . $key . '_title'] = ['type' => 'text', 'label' => 'Venue ' . $label . ' Title'];
            $fields['venue_' . $key . '_text'] = ['type' => 'text', 'label' => 'Venue ' . $label . ' Description'];
            $fields['venue_' . $key . '_link'] = ['type' => 'text', 'label' => 'Venue ' . $label . ' Link'];
        }

        $fields['venue_five_badge'] = ['type' => 'text', 'label' => 'Venue Five Badge'];
        $fields['button_text'] = ['type' => 'text', 'label' => 'Bottom Button Text'];
        $fields['button_link'] = ['type' => 'text', 'label' => 'Bottom Button Link'];

        return $fields;
    }
}
