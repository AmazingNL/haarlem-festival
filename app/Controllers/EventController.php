<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Services\EventCatalogService;
use App\Services\ProgramService;

final class EventController extends BaseController
{
    private EventCatalogService $eventCatalogService;
    private ProgramService $programService;

    // Inject the event catalogue and program services used by the event shop flow.
    public function __construct(EventCatalogService $eventCatalogService, ProgramService $programService)
    {
        $this->eventCatalogService = $eventCatalogService;
        $this->programService = $programService;
    }

    // Show the published festival events and remember this page for the return link.
    public function index(): void
    {
        $tag = trim($this->str('tag'));
        $this->rememberProgramReturnUrl($this->currentUrl());

        $this->view('shop/events', [
            'title' => 'Festival Events',
            'events' => $this->eventCatalogService->getPublishedEvents($tag),
            'activeTag' => $tag,
        ]);
    }

    // Validate the selected ticket type and add the chosen event ticket to My Program.
    public function addToProgram(): void
    {
        $this->ensureSession();

        try {
            $this->verifyCsrf();

            // Read the selected event ticket from the form.
            $eventId = max(0, $this->int('event_id'));
            $ticketTypeId = max(0, $this->int('ticket_type_id'));
            $requestedQuantity = max(1, $this->int('quantity', 1));

            if ($eventId <= 0 || $ticketTypeId <= 0) {
                $this->setErrorMessage('Choose a valid event ticket before adding it to My Program.');
                $this->redirect('/events');
                return;
            }

            $ticketSelection = $this->eventCatalogService->getBookableTicketType($eventId, $ticketTypeId);
            if ($ticketSelection === null) {
                $this->setErrorMessage('This event ticket is not available right now.');
                $this->redirect('/events');
                return;
            }

            $availableSeats = max(0, (int) ($ticketSelection['max_quantity'] ?? 0));
            if ($availableSeats < 1) {
                $this->setErrorMessage('This ticket type is sold out.');
                $this->redirect('/events');
                return;
            }

            $maxQuantity = min(10, $availableSeats);
            $quantity = min($requestedQuantity, $maxQuantity);
            $startDateTime = (string) ($ticketSelection['start_datetime'] ?? '');
            $endDateTime = (string) ($ticketSelection['end_datetime'] ?? '');

            $this->programService->addItem([
                'type' => 'event-ticket',
                'event_id' => (int) ($ticketSelection['event_id'] ?? 0),
                'ticket_type_id' => (int) ($ticketSelection['ticket_type_id'] ?? 0),
                'title' => (string) ($ticketSelection['title'] ?? 'Festival Event'),
                'day' => $this->formatDate($startDateTime),
                'time' => $this->formatTimeRange($startDateTime, $endDateTime),
                'ticket_key' => 'ticket-type-' . (int) ($ticketSelection['ticket_type_id'] ?? 0),
                'ticket_title' => (string) ($ticketSelection['ticket_type_name'] ?? 'Ticket'),
                'quantity' => $quantity,
                'unit_price' => (float) ($ticketSelection['ticket_price'] ?? 0),
                'selection_text' => $this->formatSelectionText($startDateTime, $endDateTime),
                'ticket_summary_text' => (string) ($ticketSelection['ticket_type_name'] ?? 'Ticket'),
                'location_name' => (string) ($ticketSelection['location_name'] ?? 'Haarlem'),
                'category_label' => (string) ($ticketSelection['category_label'] ?? 'Festival'),
                'starts_at' => $startDateTime,
                'ends_at' => $endDateTime,
            ]);

            $this->setSuccessMessage('The event ticket was added to My Program.');
            $this->redirect('/program');
        } catch (\Throwable $e) {
            $this->setErrorMessage('The event ticket could not be added right now.');
            $this->redirect('/events');
        }
    }

    // Format the event start date for display in My Program.
    private function formatDate(string $dateTime): string
    {
        $timestamp = strtotime($dateTime);

        return $timestamp ? date('D d M Y', $timestamp) : '';
    }

    // Format the event start and end time as a readable time range.
    private function formatTimeRange(string $startDateTime, string $endDateTime): string
    {
        $start = strtotime($startDateTime);
        $end = strtotime($endDateTime);

        if (!$start) {
            return '';
        }

        if (!$end) {
            return date('H:i', $start);
        }

        return date('H:i', $start) . ' - ' . date('H:i', $end);
    }

    // Build the summary text shown under the selected event in My Program and checkout.
    private function formatSelectionText(string $startDateTime, string $endDateTime): string
    {
        $date = $this->formatDate($startDateTime);
        $timeRange = $this->formatTimeRange($startDateTime, $endDateTime);

        return trim(implode(' | ', array_filter([$date, $timeRange], static fn(string $value): bool => $value !== '')));
    }
}
