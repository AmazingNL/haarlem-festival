<?php

namespace App\Schemas\jazz;

use App\Schemas\BaseSection;

final class JazzFeaturedArtists extends BaseSection
{
    public function __construct(string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct('jazz_featured_artists', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'heading' => ['type' => 'text', 'label' => 'Heading', 'required' => true],
            'intro' => ['type' => 'textarea', 'label' => 'Intro Text'],

            'one_name' => ['type' => 'text', 'label' => 'Card 1 — Name'],
            'one_text' => ['type' => 'textarea', 'label' => 'Card 1 — Description'],
            'one_image' => ['type' => 'image', 'label' => 'Card 1 — Image'],
            'one_button_text' => ['type' => 'text', 'label' => 'Card 1 — Button Text'],
            'one_button_link' => ['type' => 'text', 'label' => 'Card 1 — Button Link'],

            'two_name' => ['type' => 'text', 'label' => 'Card 2 — Name'],
            'two_text' => ['type' => 'textarea', 'label' => 'Card 2 — Description'],
            'two_image' => ['type' => 'image', 'label' => 'Card 2 — Image'],
            'two_button_text' => ['type' => 'text', 'label' => 'Card 2 — Button Text'],
            'two_button_link' => ['type' => 'text', 'label' => 'Card 2 — Button Link'],

            'three_name' => ['type' => 'text', 'label' => 'Card 3 — Name'],
            'three_text' => ['type' => 'textarea', 'label' => 'Card 3 — Description'],
            'three_image' => ['type' => 'image', 'label' => 'Card 3 — Image'],
            'three_button_text' => ['type' => 'text', 'label' => 'Card 3 — Button Text'],
            'three_button_link' => ['type' => 'text', 'label' => 'Card 3 — Button Link'],

            'four_name' => ['type' => 'text', 'label' => 'Card 4 — Name'],
            'four_text' => ['type' => 'textarea', 'label' => 'Card 4 — Description'],
            'four_image' => ['type' => 'image', 'label' => 'Card 4 — Image'],
            'four_button_text' => ['type' => 'text', 'label' => 'Card 4 — Button Text'],
            'four_button_link' => ['type' => 'text', 'label' => 'Card 4 — Button Link'],
        ];
    }
}
