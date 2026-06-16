<?php

namespace App\Schemas\yummy;

use App\Schemas\BaseSection;

final class WelcomeBannerCard extends BaseSection
{
    public string $title = '';
    public string $info = '';

    public function __construct(string $customClass = '', int $sortOrder = 0, string $title = '', string $info = '')
    {
        parent::__construct('welcome_banner_card', $customClass, $sortOrder);
        $this->title = $title;
        $this->info = $info;
    }

    public function getAdminFormFields(): array
    {
        return [
            'title' => ['type' => 'text', 'label' => 'Title', 'required' => true],
            'info' => ['type' => 'textarea', 'label' => 'Info', 'class' => 'js-wysiwyg'],
            'custom_class' => ['type' => 'custom_class', 'label' => 'Custom CSS class (optional)'],
        ];
    }

}