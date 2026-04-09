<?php

namespace App\ViewModels\yummy;
use App\ViewModels\BaseSection;

class HaarlemUnique extends BaseSection
{

    public string $title = '';
    public string $text = '';
    public string $content = '';
    public string $images = '';

    public function __construct(
        int $pageId = 0,
        int $sectionId = 0,
        string $customClass = '',
        int $sortOrder = 0,
        string $title = '',
        string $content = '',
        string $images = ''
    ) {
            parent::__construct(
        $pageId,
        $sectionId,
        'haarlem_unique',
        $customClass,
        $sortOrder
    );
        $this->page_id = $pageId;
        $this->section_id = $sectionId;
        $this->type = 'haarlem_unique';
        $this->customClass = $customClass;
        $this->sortOrder = $sortOrder;
        $this->title = $title;
        $this->content = $content;
        $this->images = $images;

    }

    public function getAdminFormFields(): array
    {
        return [
            'title' => ['type' => 'text', 'label' => 'Main Title', 'required' => true],
            'content' => ['type' => 'textarea', 'label' => 'Content', 'class' => 'js-wysiwyg'],
            'section_image' => ['type' => 'textarea', 'label' => 'Gallery', 'class' => 'js-wysiwyg'],
            'custom_class' => ['type' => 'custom_class', 'label' => 'Custom CSS class (optional)'],
        ];
    }

}

?>