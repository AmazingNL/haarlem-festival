<?php

namespace App\ViewModels\yummy;
use App\ViewModels\BaseSection;

final class RestaurantCard extends BaseSection
{

    public string $title = '';
    public string $introduction = '';
    public string $rating = '0.0';
    public string $buttonText = '';
    public string $buttonLink = '';
    public ?string $image = null;
    public array $cuisine = [];

    public function __construct(
        int $pageId = 0,
        int $sectionId = 0,
        string $customClass = '',
        int $sortOrder = 0,
        string $title = '',
        string $introduction = '',
        string $rating = '0.0',
        string $buttonText = '',
        string $buttonLink = '',
        ?string $image = null,
        array $cuisine = []
    ) {
        $this->page_id = $pageId;
        $this->section_id = $sectionId;
        $this->type = 'restaurant_card';
        $this->sortOrder = $sortOrder;
        $this->customClass = $customClass;

        $this->title = $title;
        $this->introduction = $introduction;
        $this->rating = $rating;
        $this->buttonText = $buttonText;
        $this->buttonLink = $buttonLink;
        $this->image = $image;
        $this->cuisine = $cuisine;
    }

    public function getAdminFormFields(): array
    {
        return [
            'title' => ['type' => 'text', 'label' => 'Main Title', 'required' => true],
            'introduction' => ['type' => 'textarea', 'label' => 'Introduction', 'class' => 'js-wysiwyg'],
            'rating' => ['type' => 'number', 'label' => 'Rating'],
            'event_id' => ['type' => 'number', 'label' => 'Event ID'],
            'capacity' => ['type' => 'number', 'label' => 'Capacity'],
            'section_image' => ['type' => 'image', 'label' => 'Background Image'],
            'cuisine' => ['type' => 'textarea', 'label' => 'Cuisine (comma or new line separated)'],
            'button_text' => ['type' => 'text', 'label' => 'Button Text'],
            'button_link' => ['type' => 'text', 'label' => 'Button Link'],
            'custom_class' => ['type' => 'custom_class', 'label' => 'Custom CSS class (optional)']
        ];
    }

}