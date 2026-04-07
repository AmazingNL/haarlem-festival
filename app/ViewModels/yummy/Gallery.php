<?php

namespace App\ViewModels\yummy;
use App\ViewModels\BaseSection;

final class Gallery extends BaseSection
{

    public string $title = '';
    public string $images = '';

    public function __construct(
        int $pageId = 0,
        int $sectionId = 0,
        string $customClass = '',
        int $sortOrder = 0,
        string $title = '',
        string $images = ''
    ) {
            parent::__construct(
        $pageId,
        $sectionId,
        'gallery',
        $customClass,
        $sortOrder
    );
        $this->page_id = $pageId;
        $this->section_id = $sectionId;
        $this->type = 'gallery';
        $this->customClass = $customClass;
        $this->sortOrder = $sortOrder;
        $this->title = $title;
        $this->images = $images;

    }

    public function getAdminFormFields(): array
    {
        return [
            'title' => ['type' => 'text', 'label' => 'Main Title', 'required' => true],
            'section_image' => ['type' => 'textarea', 'label' => 'Gallery', 'class' => 'js-wysiwyg'],
            'custom_class' => ['type' => 'custom_class', 'label' => 'Custom CSS class (optional)'],
        ];
    }

}