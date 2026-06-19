<?php

namespace App\Schemas\jazz;

use App\Schemas\BaseSection;

final class JazzIntro extends BaseSection
{
    public function __construct(string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct('jazz_intro', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'heading' => ['type' => 'text', 'label' => 'Heading', 'required' => true],
            'body' => ['type' => 'textarea', 'label' => 'Body Text', 'class' => 'js-wysiwyg'],
        ];
    }
}
