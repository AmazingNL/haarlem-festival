<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Services\Implementations\OrderService;
use App\Services\Implementations\ProgramService;

final class ProgramController extends BaseController
{
    private ProgramService $programService;
    private OrderService $orderService;

    public function __construct(ProgramService $programService, OrderService $orderService)
    {
        $this->programService = $programService;
        $this->orderService = $orderService;
    }

    public function index(): void
    {
        $this->ensureSession();
        $userId = $this->isLoggedIn() ? (int) $this->currentUserId() : 0;

        $this->view('program/index', [
            'title' => 'My Program',
            'programItems' => $this->programService->getItems(),
            'paidOrders' => $userId > 0 ? $this->orderService->findPaidOrdersForUser($userId) : [],
            'programTotal' => $this->programService->getTotal(),
            'programCount' => $this->programService->getItemCount(),
            'isLoggedIn' => $this->isLoggedIn(),
            'lastOrderId' => $userId > 0 ? $this->orderService->getLastOrderId($userId) : 0,
            'continueBrowsingUrl' => $this->getProgramReturnUrl('/home'),
            'stripeConfigured' => \App\Support\StripeConfig::isConfigured(),
        ]);
    }

    public function removeItem(): void
    {
        $this->ensureSession();
        $this->verifyCsrf();

        $itemId = trim($this->str('item_id'));
        if ($itemId !== '') {
            $this->programService->removeItem($itemId);
            $this->setSuccessMessage('The item was removed from My Program.');
        }

        $this->redirect('/program');
    }
}
