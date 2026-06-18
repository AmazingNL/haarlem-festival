<?php

namespace App\Schemas\jazz;

use App\Schemas\BaseSection;

final class JazzWhatToExpect extends BaseSection
{
    public function __construct(string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct('jazz_what_to_expect', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'heading' => ['type' => 'text', 'label' => 'Heading', 'required' => true],
            'body' => ['type' => 'textarea', 'label' => 'Body Text', 'class' => 'js-wysiwyg'],
            'section_image' => ['type' => 'image', 'label' => 'Image'],
            'button_text' => ['type' => 'text', 'label' => 'Button Text'],
            'button_link' => ['type' => 'text', 'label' => 'Button Link (e.g. #agenda)'],
        ];
    }
}
