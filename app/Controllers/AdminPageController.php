<?php

namespace App\Controllers;
use App\Core\BaseController;
use App\Services\IAdminPageService;
use App\Services\AdminPageService;
use App\Repositories\AdminPageRepository;

final class AdminPageController extends BaseController
{
    private IAdminPageService $iAdminPageService;

    public function __construct(IAdminPageService $iAdminPageService)
    {
        $this->iAdminPageService = $iAdminPageService;
    }
    public function index(): void
    {
        // Dashboard homepage - no DB queries yet, just placeholders
        $this->view('admin/dashboard',
            [
                'title' => 'Dashboard',
                'breadcrumbs' => [
                    ['label' => 'Dashboard', 'url' => null]
                ]
            ],
            layout: 'admin'
        );
    }

    public function dashboardPage(): void
    {
    }

    public function createEventPage(): void
    {
        $this->view('admin/create_event');
    }

    public function editEventPage(): void
    {
        $this->view('admin/edit_event');
    }

    public function manageUsersPage(): void
    {
        $this->view('admin/manage_users');
    }

    public function viewEventPage(): void
    {
        $this->view('admin/view_event');
    }

    public function deleteEventPage(): void
    {
        $this->view('admin/delete_event');
    }

    public function updateEventPage(): void
    {
        $this->view('admin/update_event');
    }
}