<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Services\ProgramService;

final class ProgramController extends BaseController
{
    private ProgramService $programService;

    // Inject the service that manages the session-based My Program cart.
    public function __construct(ProgramService $programService)
    {
        $this->programService = $programService;
    }

    // Show the My Program page with current items, totals, and recent paid orders.
    public function index(): void
    {
        $this->ensureSession();

        $this->view('program/index', [
            'title' => 'My Program',
            'programItems' => $this->programService->getItems(),
            'paidOrders' => $this->isLoggedIn()
                ? $this->programService->getPaidOrdersForUser((int) $this->currentUserId())
                : [],
            'programTotal' => $this->programService->getTotal(),
            'programCount' => $this->programService->getItemCount(),
            'isLoggedIn' => $this->isLoggedIn(),
            'lastOrderId' => $this->programService->getLastOrderId(),
            'continueBrowsingUrl' => $this->getProgramReturnUrl('/home'),
        ]);
    }

    // Remove one saved item from My Program and return to the list.
    public function removeItem(): void
    {
        $this->ensureSession();
        $this->verifyCsrf();

        $itemId = trim($this->str('item_id'));
        if ($itemId !== '') {
            $this->programService->removeItem($itemId);
            $this->setSuccessMessage('The history booking was removed from My Program.');
        }

        $this->redirect('/program');
    }
}
