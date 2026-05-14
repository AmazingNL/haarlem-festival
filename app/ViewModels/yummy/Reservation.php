<?php

namespace App\ViewModels\yummy;
use App\ViewModels\BaseSection;

final class Reservation extends BaseSection
{

    public string $title = '';
    public string $information = '';
    public array $date = [];
    public array $session = [];
    public float $adultPrice = 0.0;
    public float $kidsPrice = 0.0;
    public string $buttonText = '';
    public string $buttonLink = '';



    public function __construct(
        int $pageId = 0,
        int $sectionId = 0,
        string $customClass = '',
        int $sortOrder = 0,
        string $title = '',
        string $information = '',
        array $session = [],
        array $date = [],
        float $adultPrice = 0.0,
        float $kidsPrice = 0.0,
        string $buttonText = '',
        string $buttonLink = '',

    ) {
        parent::__construct($pageId, $sectionId, 'reservation_card', $customClass, $sortOrder);

        $this->title = $title;
        $this->information = $information;
        $this->session = $session;
        $this->buttonText = $buttonText;
        $this->buttonLink = $buttonLink;
        $this->date = $date;
        $this->adultPrice = $adultPrice;
        $this->kidsPrice = $kidsPrice;

    }

    public function getAdminFormFields(): array
    {
        return [
            'title' => ['type' => 'text', 'label' => 'Main Title', 'required' => true],
            'information' => ['type' => 'textarea', 'label' => 'information', 'class' => 'js-wysiwyg'],
            'session' => ['type' => 'textarea', 'label' => 'Session (comma or new line separated)', 'class' => 'js-wysiwyg'],
            'date' => ['type' => 'textarea', 'label' => 'Date (comma or new line separated)', 'class' => 'js-wysiwyg'],
            'adultPrice' => ['type' => 'text', 'label' => 'AdultPrice', 'required' => true],
            'kidsPrice' => ['type' => 'text', 'label' => 'KidsPrice', 'required' => true],
            'button_text' => ['type' => 'text', 'label' => 'Button Text'],
            'button_link' => ['type' => 'text', 'label' => 'Button Link'],
            'custom_class' => ['type' => 'custom_class', 'label' => 'Custom CSS class (optional)']
        ];
    }

}