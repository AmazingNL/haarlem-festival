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

            $page = $this->adminPageService->getPageBySlug('home');
            if ($page === null) {
                $this->view('no_page/index', ['error' => 'Home page not found']);
                return;
            }

            $sections = $this->pageSectionService->getSectionsByPageId((int) $page->page_id);

            $this->view('/home/home', [
                'page' => $page,
                'sections' => $sections,
                'title' => $page->title ?? 'Haarlem Festival',
            ]);
        } catch (\Throwable $e) {
            $this->view('no_page/index', ['error' => 'Failed to load home page']);
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

    public function dance(): void
    {
        try {
            $this->ensureSession();

            $page = $this->adminPageService->getPageBySlug('dance');
            if ($page === null) {
                $this->view('no_page/index', ['error' => 'Dance page not found']);
                return;
            }

            $sections = $this->pageSectionService->getSectionsByPageId((int) $page->page_id);


            $this->view('/dance/index', [
                'page' => $page,
                'sections' => $sections,
                'title' => $page->title ?? 'Dance',
            ]);
        } catch (\Throwable $e) {
            $this->view('no_page/index', ['error' => 'Failed to load Dance page']);
        }
    }

    public function ratatouille(): void
    {
        try {
            $page = $this->adminPageService->getPageBySlug('ratatouille');
            $page_id = $page->page_id ?? null;
            $ratatouille = $this->pageSectionService->getSectionsByPageId($page_id);
            if (empty($ratatouille)) {
                $this->setFlash('error', 'page does not exist');
                $this->redirect('/');
            }
            $this->view(
                '/ratatouille/index',
                ['section' => $ratatouille, 'page' => $page, 'title' => 'ratatouille']
            );

        } catch (\Exception $e) {
            $this->view(
                'no_page/index',
                ['error' => 'ratatouille page not available']
            );
        }
    }
}

