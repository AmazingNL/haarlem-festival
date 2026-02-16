<?php

declare(strict_types=1);

namespace App\Controllers;
use App\Core\BaseController;
use App\Models\Page;
use App\Services\IPageSectionService;
use App\Services\IAdminPageService;
final class HomeController extends BaseController
{

    private IPageSectionService $pageSectionService;
    private IAdminPageService $adminPageService;
    public function __construct(IPageSectionService $pageSectionService, IAdminPageService $adminPageService)
    {
        $this->pageSectionService = $pageSectionService;
        $this->adminPageService = $adminPageService;
    }
    public function index(): void
    {
        try {
        $this->ensureSession();
        $pages = $this->adminPageService->getPageBySlug('home');
        $page_id = $pages->page_id ?? null;
        $homePage = $this->pageSectionService->getSectionsByPageId($page_id);
        $message = ['message' => 'Home page loaded successfully.'];
        $this->view('home/home', 
        ['pages' => $homePage, 'title' => 'Haarlem Festival', $message],);

        } 
        catch (\Throwable $e) {
            $this->view('home/home', 
            ['pages' => [], 'title' => 'Haarlem Festival', 'message' => 'Failed to load home page. Please try again later.']);
        }
    }
}

