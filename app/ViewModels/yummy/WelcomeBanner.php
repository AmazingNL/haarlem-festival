<?php

namespace App\ViewModels\yummy;
use App\ViewModels\BaseSection;

final class WelcomeBanner extends BaseSection
{

    public string $title = '';
    public string $introduction = '';
    public string $buttonText = '';
    public string $buttonLink = '';
    public ?string $backgroundImg = null;

    public function __construct(
        int $pageId = 0,
        int $sectionId = 0,
        string $customClass = '',
        int $sortOrder = 0,
        string $title = '',
        string $introduction = '',
        string $buttonText = '',
        string $buttonLink = '',
        ?string $backgroundImg = null
    ) {
        $this->page_id = $pageId;
        $this->section_id = $sectionId;
        $this->type = 'welcome_banner';
        $this->sortOrder = $sortOrder;
        $this->customClass = $customClass;

        $this->title = $title;
        $this->introduction = $introduction;
        $this->buttonText = $buttonText;
        $this->buttonLink = $buttonLink;
        $this->backgroundImg = $backgroundImg;
    }

public function getAdminFormFields(): array
    {
        return [
            'title' => ['type' => 'text', 'label' => 'Main Title', 'required' => true],
            'introduction' => ['type' => 'textarea', 'label' => 'Introduction', 'class' => 'js-wysiwyg'],
            'section_image' => ['type' => 'image', 'label' => 'Background Image'],
            'button_text' => ['type' => 'text', 'label' => 'Button Text'],
            'button_link' => ['type' => 'text', 'label' => 'Button Link'],
            'custom_class' => ['type' => 'custom_class', 'label' => 'Custom CSS class (optional)'],
        ];
    }

}