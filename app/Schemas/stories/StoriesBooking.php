<?php

namespace App\Schemas\stories;

use App\Schemas\BaseSection;

final class StoriesBooking extends BaseSection
{
    public function __construct(string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct('stories_booking', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return array_merge($this->headingFields(), $this->showDetailFields(), $this->logisticsFields());
    }

    /** @return array<string, array<string, string>> */
    private function headingFields(): array
    {
        return [
            'title'     => ['type' => 'text',    'label' => 'Section Heading', 'required' => 'true'],
            'subtitle'  => ['type' => 'text',    'label' => 'Section Subtitle'],
            'info_text' => ['type' => 'wysiwyg', 'label' => 'Info Callout Text'],
        ];
    }

    /** @return array<string, array<string, string>> */
    private function showDetailFields(): array
    {
        return [
            'show_title'       => ['type' => 'text', 'label' => 'Show Title',                  'required' => 'true'],
            'show_description' => ['type' => 'text', 'label' => 'Show Description'],
            'price'            => ['type' => 'text', 'label' => 'Display Price (e.g. €10)'],
            'price_raw'        => ['type' => 'text', 'label' => 'Price (numeric, e.g. 10.00)'],
        ];
    }

    /** @return array<string, array<string, string>> */
    private function logisticsFields(): array
    {
        return [
            'date'            => ['type' => 'text', 'label' => 'Date (e.g. Sunday, July 27, 2026)'],
            'time'            => ['type' => 'text', 'label' => 'Time (e.g. 10:00-11:00 (60 minutes))'],
            'spots_available' => ['type' => 'text', 'label' => 'Spots Available (number)'],
            'spots_total'     => ['type' => 'text', 'label' => 'Total Spots (number)'],
            'location'        => ['type' => 'text', 'label' => 'Location / Venue'],
        ];
    }
}
