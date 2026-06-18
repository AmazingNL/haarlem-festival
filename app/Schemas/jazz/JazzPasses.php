<?php

namespace App\Schemas\jazz;

use App\Schemas\BaseSection;

// All-access passes. The day pass uses one ticket type per day so the "Choose a day"
// dropdown can submit the matching Ticket Type ID to the normal booking endpoint.
final class JazzPasses extends BaseSection
{
    public function __construct(string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct('jazz_passes', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'heading' => ['type' => 'text', 'label' => 'Heading', 'required' => true],
            'intro' => ['type' => 'textarea', 'label' => 'Intro Text'],
            'pass_event_id' => ['type' => 'number', 'label' => 'All-Access Event ID (for booking)'],

            'day_pass_label' => ['type' => 'text', 'label' => 'Day Pass — Label'],
            'day_pass_price' => ['type' => 'text', 'label' => 'Day Pass — Price Label (e.g. €35)'],
            'day_pass_thursday_ticket_id' => ['type' => 'number', 'label' => 'Day Pass — Thursday Ticket Type ID'],
            'day_pass_friday_ticket_id' => ['type' => 'number', 'label' => 'Day Pass — Friday Ticket Type ID'],
            'day_pass_saturday_ticket_id' => ['type' => 'number', 'label' => 'Day Pass — Saturday Ticket Type ID'],
            'day_pass_sunday_ticket_id' => ['type' => 'number', 'label' => 'Day Pass — Sunday Ticket Type ID'],

            'full_pass_label' => ['type' => 'text', 'label' => 'Full Pass — Label'],
            'full_pass_price' => ['type' => 'text', 'label' => 'Full Pass — Price Label (e.g. €85)'],
            'full_pass_ticket_id' => ['type' => 'number', 'label' => 'Full Pass — Ticket Type ID'],
        ];
    }
}
