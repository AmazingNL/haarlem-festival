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

            $this->view('/home/home', [
                'sections' => $homePage,
                'title' => 'Haarlem Festival',
                'message' => 'Home page loaded successfully.'
            ]);

        } catch (\Exception $e) {
            $this->setFlash('error', 'Failed to load home page: ' . $e->getMessage());
        }
    }

    public function yummy(): void
    {
        try {
            $page = $this->adminPageService->getPageBySlug('yummy');
            $page_id = $page->page_id ?? null;
            if ($page_id === null) {
                $this->view(
                    'no_page/index',
                    ['error' => 'Yummy page not available']
                );
                return;
            }

            $yummy = $this->pageSectionService->getSectionsByPageId($page_id);
            if (empty($yummy)) {
                $this->setFlash('error', 'page does not exist');
                $this->redirect('/');
            }
            $this->view(
                'yummy/index',
                ['section' => $yummy, 'page' => $page, 'title' => 'Yummy']
            );

        } catch (\Throwable $e) {
            $this->view(
                'no_page/index',
                ['error' => 'Yummy page not available']
            );
        }
    }
}

