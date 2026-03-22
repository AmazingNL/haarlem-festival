<?php

namespace App\ViewModels\yummy;
use App\ViewModels\BaseSection;

final class TextBlock extends BaseSection
{

    public string $title = '';
    public string $subTitle = '';
    public string $article = '';

    public function __construct(
        int $pageId = 0,
        int $sectionId = 0,
        string $customClass = '',
        int $sortOrder = 0,
        string $title = '',
        string $subTitle = '',
        string $article = ''
    ) {
            parent::__construct(
        $pageId,
        $sectionId,
        'welcome_banner',
        $customClass,
        $sortOrder
    );
        $this->page_id = $pageId;
        $this->section_id = $sectionId;
        $this->type = 'text_block';
        $this->customClass = $customClass;
        $this->sortOrder = $sortOrder;

        $this->title = $title;
        $this->subTitle = $subTitle;
        $this->article = $article;
    }

    public function getAdminFormFields(): array
    {
        return [
            'title' => ['type' => 'text', 'label' => 'Main Title', 'required' => true],
            'sub_title' => ['type' => 'text', 'label' => 'Subtitle'],
            'article' => ['type' => 'textarea', 'label' => 'Article', 'class' => 'js-wysiwyg'],
            'custom_class' => ['type' => 'custom_class', 'label' => 'Custom CSS class (optional)'],
        ];
    }

}