<?php

namespace App\Schemas\jazz;

use App\Schemas\BaseSection;

// One performance in the agenda. Add one section per performance; the Jazz page
// groups them into the day tabs by the "day" field.
final class JazzAgendaEvent extends BaseSection
{
    public function __construct(string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct('jazz_agenda_event', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'day' => ['type' => 'text', 'label' => 'Day (Thursday, Friday, Saturday or Sunday)', 'required' => true],
            'venue' => ['type' => 'text', 'label' => 'Venue (e.g. Patronaat, Main Hall)'],
            'title' => ['type' => 'text', 'label' => 'Artist / Performance Title', 'required' => true],
            'time_text' => ['type' => 'text', 'label' => 'Time (e.g. 18:00 - 19:00)'],
            'description' => ['type' => 'textarea', 'label' => 'Description'],
            'price' => ['type' => 'number', 'label' => 'Price in euros (e.g. 15)'],
            'image' => ['type' => 'image', 'label' => 'Card Background Image (optional)'],
            'learn_more_link' => ['type' => 'text', 'label' => 'Learn More Link (artist page)'],
        ];
    }
}
