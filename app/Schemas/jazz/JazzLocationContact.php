<?php

namespace App\Schemas\jazz;

use App\Schemas\BaseSection;

final class JazzLocationContact extends BaseSection
{
    public function __construct(string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct('jazz_location_contact', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'heading' => ['type' => 'text', 'label' => 'Heading', 'required' => true],
            'intro' => ['type' => 'textarea', 'label' => 'Intro Text'],
            'place_name' => ['type' => 'text', 'label' => 'Place Name (e.g. Patronaat)'],
            'address_line_one' => ['type' => 'text', 'label' => 'Address Line One'],
            'address_line_two' => ['type' => 'text', 'label' => 'Address Line Two'],
            'email' => ['type' => 'text', 'label' => 'Email'],
            'phone_office' => ['type' => 'text', 'label' => 'Office Phone'],
            'office_hours' => ['type' => 'text', 'label' => 'Office Hours'],
            'phone_cash_desk' => ['type' => 'text', 'label' => 'Cash Desk Phone'],
        ];
    }
}
