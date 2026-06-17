<?php

declare(strict_types=1);

namespace App\ViewModels\stories;

use App\ViewModels\BaseSection;

/**
 * ViewModel for the stories_booking section type.
 *
 * Each section of this type represents one bookable show.
 * The section_id is used as the show_id in the booking form.
 */
final class StoriesBooking extends BaseSection
{
    /**
     * @param int    $pageId      Associated page ID.
     * @param int    $sectionId   Section ID.
     * @param string $customClass Optional CSS class override.
     * @param int    $sortOrder   Display order.
     */
    public function __construct(int $pageId = 0, int $sectionId = 0, string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct($pageId, $sectionId, 'stories_booking', $customClass, $sortOrder);
    }

    /**
     * Returns the admin form field definitions for this section type.
     *
     * @return array<string, array<string, string>>
     */
    public function getAdminFormFields(): array
    {
        return array_merge($this->headingFields(), $this->showDetailFields(), $this->logisticsFields());
    }

    /**
     * Section header and info callout fields.
     *
     * @return array<string, array<string, string>>
     */
    private function headingFields(): array
    {
        return [
            'title'     => ['type' => 'text',    'label' => 'Section Heading', 'required' => 'true'],
            'subtitle'  => ['type' => 'text',    'label' => 'Section Subtitle'],
            'info_text' => ['type' => 'wysiwyg', 'label' => 'Info Callout Text'],
        ];
    }

    /**
     * Show identity and pricing fields.
     *
     * @return array<string, array<string, string>>
     */
    private function showDetailFields(): array
    {
        return [
            'show_title'       => ['type' => 'text', 'label' => 'Show Title',                 'required' => 'true'],
            'show_description' => ['type' => 'text', 'label' => 'Show Description'],
            'price'            => ['type' => 'text', 'label' => 'Display Price (e.g. €10)'],
            'price_raw'        => ['type' => 'text', 'label' => 'Price (numeric, e.g. 10.00)'],
        ];
    }

    /**
     * Date, time, availability, and venue fields.
     *
     * @return array<string, array<string, string>>
     */
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
