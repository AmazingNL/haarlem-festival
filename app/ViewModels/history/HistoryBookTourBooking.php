<?php

namespace App\ViewModels\history;

use App\ViewModels\BaseSection;

// Defines the CMS form fields for the History book tour booking section.
final class HistoryBookTourBooking extends BaseSection
{
    // Register this ViewModel as the history_book_tour_booking section type.
    public function __construct(int $pageId = 0, int $sectionId = 0, string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct($pageId, $sectionId, 'history_book_tour_booking', $customClass, $sortOrder);
    }

    // Return the inputs that the admin should see when editing this section.
    public function getAdminFormFields(): array
    {
        return [
            'heading' => ['type' => 'text', 'label' => 'Heading', 'required' => true],
            'intro' => ['type' => 'text', 'label' => 'Intro Text'],
            'day_label' => ['type' => 'text', 'label' => 'Day Label'],
            'day_one' => ['type' => 'text', 'label' => 'Day One'],
            'day_two' => ['type' => 'text', 'label' => 'Day Two'],
            'day_three' => ['type' => 'text', 'label' => 'Day Three'],
            'day_four' => ['type' => 'text', 'label' => 'Day Four'],
            'time_label' => ['type' => 'text', 'label' => 'Time Label'],
            'time_one' => ['type' => 'text', 'label' => 'Time One'],
            'time_two' => ['type' => 'text', 'label' => 'Time Two'],
            'time_three' => ['type' => 'text', 'label' => 'Time Three'],
            'language_label' => ['type' => 'text', 'label' => 'Language Label'],
            'language_one' => ['type' => 'text', 'label' => 'Language One'],
            'language_two' => ['type' => 'text', 'label' => 'Language Two'],
            'language_three' => ['type' => 'text', 'label' => 'Language Three'],
            'ticket_label' => ['type' => 'text', 'label' => 'Ticket Label'],
            'individual_title' => ['type' => 'text', 'label' => 'Individual Ticket Title'],
            'individual_price' => ['type' => 'text', 'label' => 'Individual Ticket Price'],
            'family_title' => ['type' => 'text', 'label' => 'Family Ticket Title'],
            'family_price' => ['type' => 'text', 'label' => 'Family Ticket Price'],
            'family_badge' => ['type' => 'text', 'label' => 'Family Badge'],
            'selection_label' => ['type' => 'text', 'label' => 'Selection Summary Label'],
            'ticket_summary_label' => ['type' => 'text', 'label' => 'Ticket Summary Label'],
            'total_label' => ['type' => 'text', 'label' => 'Total Label'],
            'total_value' => ['type' => 'text', 'label' => 'Total Value'],
            'saving_note' => ['type' => 'text', 'label' => 'Saving Note'],
            'quantity_label' => ['type' => 'text', 'label' => 'Quantity Label'],
            'quantity_value' => ['type' => 'text', 'label' => 'Quantity Value'],
            'button_text' => ['type' => 'text', 'label' => 'Button Text'],
            'button_link' => ['type' => 'text', 'label' => 'Button Link'],
        ];
    }
}
