<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\ControllerBase;
use App\Services\AdminService;

class AdminController extends ControllerBase
{
    private AdminService $adminService;

    public function __construct(\PDO $db)
    {
        parent::__construct($db);
        $this->adminService = new AdminService($db);
    }

    public function dashboard(): void
    {
        if (!$this->isAdmin()) {
            $this->redirect('/login');
        }

        // Get dashboard statistics
        $totalUsers = $this->adminService->getTotalUsers();
        $adminCount = $this->adminService->getUserCountByRole('admin');
        $customerCount = $this->adminService->getUserCountByRole('customer');
        $employeeCount = $this->adminService->getUserCountByRole('employee');

        $totalEvents = $this->adminService->getTotalEvents();
        $publishedEvents = $this->adminService->getPublishedEvents();

        $totalOrders = $this->adminService->getTotalOrders();
        $paidOrders = $this->adminService->getPaidOrders();
        $pendingOrders = $this->adminService->getPendingOrders();
        $totalRevenue = $this->adminService->getTotalRevenue();

        $ticketSummary = $this->adminService->getTicketSalesSummary();

        $recentOrders = $this->adminService->getRecentOrders(10);
        $upcomingEvents = $this->adminService->getUpcomingEvents(5);
        $recentUsers = $this->adminService->getRecentUsers(5);

        $data = [
            'title' => 'Admin Dashboard',
            'totalUsers' => $totalUsers,
            'adminCount' => $adminCount,
            'customerCount' => $customerCount,
            'employeeCount' => $employeeCount,
            'totalEvents' => $totalEvents,
            'publishedEvents' => $publishedEvents,
            'totalOrders' => $totalOrders,
            'paidOrders' => $paidOrders,
            'pendingOrders' => $pendingOrders,
            'totalRevenue' => number_format($totalRevenue, 2),
            'ticketSummary' => $ticketSummary,
            'recentOrders' => $recentOrders,
            'upcomingEvents' => $upcomingEvents,
            'recentUsers' => $recentUsers,
        ];

        $this->view('admin/dashboard', $data);
    }

    /**
     * Check if user is admin
     */
    protected function isAdmin(): bool
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }
}
