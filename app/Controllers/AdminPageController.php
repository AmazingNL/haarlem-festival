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
        // Allow instantiation without DI: create defaults when none provided
        if ($iAdminPageService === null) {
            $repo = new AdminPageRepository();
            $iAdminPageService = new AdminPageService($repo);
        }

        $this->iAdminPageService = $iAdminPageService;
    }
    public function index(): void
    {
        $pages = $this->iAdminPageService->getAllPages();
        $data = $pages;
        $this->view('admin_dashboard/index', 
        ['page' => $data, 'title' => 'Admin Dashboard'],
        layout:'/auth'
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