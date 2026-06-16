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

    public function __construct(EventCatalogService $eventCatalogService, ProgramService $programService)
    {
        $this->eventCatalogService = $eventCatalogService;
        $this->programService = $programService;
    }

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

    public function addToProgram(): void
    {
        $this->ensureSession();

        try {
            $this->verifyCsrf();

            $item = $this->eventCatalogService->buildProgramItem(
                max(0, $this->int('event_id')),
                max(0, $this->int('ticket_type_id')),
                max(1, $this->int('quantity', 1))
            );

            $this->programService->addItem($item);
            $this->setSuccessMessage('The event ticket was added to My Program.');
            $this->redirect('/program');
        } catch (\InvalidArgumentException $e) {
            $this->setErrorMessage($e->getMessage());
            $this->redirect('/events');
        } catch (\Throwable $e) {
            $this->setErrorMessage('The event ticket could not be added right now.');
            $this->redirect('/events');
        }
    }
}
