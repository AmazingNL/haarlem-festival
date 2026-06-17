<?php

namespace App\Schemas\yummy;
use App\Schemas\BaseSection;

final class HaarlemUnique extends BaseSection
{

    public string $title = '';
    public string $content = '';
    public string $images = '';

    public function __construct(string $customClass = '', int $sortOrder = 0, string $title = '', string $content = '', string $images = '')
    {
        parent::__construct('haarlem_unique', $customClass, $sortOrder);
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