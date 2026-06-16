<?php

namespace App\Schemas\home;

use App\Schemas\BaseSection;

final class HomeGallery extends BaseSection
{
    public function __construct(string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct('gallery', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        $fields = [];
        $items = [
            'item_one' => ['label' => 'Slide One', 'prefix' => 'home-gallery-one'],
            'item_two' => ['label' => 'Slide Two', 'prefix' => 'home-gallery-two'],
            'item_three' => ['label' => 'Slide Three', 'prefix' => 'home-gallery-three'],
            'item_four' => ['label' => 'Slide Four', 'prefix' => 'home-gallery-four'],
        ];

        foreach ($items as $fieldPrefix => $config) {
            $label = $config['label'];
            $fields[$fieldPrefix . '_label'] = ['type' => 'text', 'label' => $label . ' Label', 'required' => true];
            $fields[$fieldPrefix . '_image'] = [
                'type' => 'image',
                'label' => $label . ' Image',
                'folder' => 'home',
                'prefix' => $config['prefix'],
            ];
            $fields[$fieldPrefix . '_alt'] = ['type' => 'text', 'label' => $label . ' Alt Text'];
        }

        return $fields;
    }
}
