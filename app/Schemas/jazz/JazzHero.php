<?php

namespace App\Schemas\jazz;

use App\Schemas\BaseSection;

final class JazzHero extends BaseSection
{
    public function __construct(string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct('jazz_hero', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'title_line_one' => ['type' => 'text', 'label' => 'Title Line One', 'required' => true],
            'title_line_two' => ['type' => 'text', 'label' => 'Title Line Two'],
            'section_image' => ['type' => 'image', 'label' => 'Background Image'],
        ];
    }
}
