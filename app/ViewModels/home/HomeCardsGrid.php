<?php

namespace App\ViewModels\home;

use App\ViewModels\BaseSection;

final class HomeCardsGrid extends BaseSection
{
    public function __construct(int $pageId = 0, int $sectionId = 0, string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct($pageId, $sectionId, 'cards_grid', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        $fields = [
            'heading' => ['type' => 'text', 'label' => 'Section Heading', 'required' => true],
        ];

        $cards = [
            'card_one' => ['label' => 'Card One', 'prefix' => 'home-card-one'],
            'card_two' => ['label' => 'Card Two', 'prefix' => 'home-card-two'],
            'card_three' => ['label' => 'Card Three', 'prefix' => 'home-card-three'],
            'card_four' => ['label' => 'Card Four', 'prefix' => 'home-card-four'],
            'card_five' => ['label' => 'Card Five', 'prefix' => 'home-card-five'],
        ];

        foreach ($cards as $fieldPrefix => $config) {
            $label = $config['label'];
            $fields[$fieldPrefix . '_title'] = ['type' => 'text', 'label' => $label . ' Title', 'required' => true];
            $fields[$fieldPrefix . '_text'] = ['type' => 'textarea', 'label' => $label . ' Text', 'required' => true];
            $fields[$fieldPrefix . '_image'] = [
                'type' => 'image',
                'label' => $label . ' Image',
                'folder' => 'home',
                'prefix' => $config['prefix'],
            ];
            $fields[$fieldPrefix . '_alt'] = ['type' => 'text', 'label' => $label . ' Alt Text'];
            $fields[$fieldPrefix . '_button_text'] = ['type' => 'text', 'label' => $label . ' Button Text'];
            $fields[$fieldPrefix . '_button_link'] = ['type' => 'text', 'label' => $label . ' Button Link'];
        }

        return $fields;
    }
}
