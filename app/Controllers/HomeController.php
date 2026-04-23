<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Services\IAdminPageService;
use App\Services\IPageSectionService;

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
            $page = $this->adminPageService->getPageBySlug('home');
            $pageId = $page->page_id ?? null;

            if ($pageId === null) {
                $this->view('no_page/index', ['error' => 'Home page not available']);
                return;
            }

            $sections = $this->pageSectionService->getSectionsByPageId($pageId);
            if ($sections === []) {
                $this->view('no_page/index', ['error' => 'Home page not available']);
                return;
            }

            $this->view('home/home', [
                'section' => $this->mergeSectionContent($sections),
                'page' => $page,
                'title' => 'Home',
            ]);
        } catch (\Throwable $e) {
            $this->view('no_page/index', ['error' => 'Home page not available']);
        }
    }

    public function yummy(): void
    {
        try {
            $page = $this->adminPageService->getPageBySlug('yummy');
            $pageId = $page->page_id ?? null;

            if ($pageId === null) {
                $this->view('no_page/index', ['error' => 'Restaurants page not available']);
                return;
            }

            $pageSections = $this->pageSectionService->getSectionsByPageId($pageId);

            $this->view('yummy/index', [
                'section' => $pageSections === [] ? [] : $this->mergeSectionContent($pageSections),
                'page' => $page,
                'title' => 'Restaurants',
                'showFallbackHero' => $pageSections === [],
            ]);
        } catch (\Throwable $e) {
            $this->view('no_page/index', ['error' => 'Restaurants page not available']);
        }
    }

    public function stories(): void
    {
        try {
            $page = $this->adminPageService->getPageBySlug('stories');
            $pageId = $page->page_id ?? null;
            $stories = $pageId === null ? [] : $this->pageSectionService->getSectionsByPageId($pageId);

            if ($stories === []) {
                $this->setErrorMessage('Stories page not available');
                $this->redirect('/');
                return;
            }

            $this->view('/stories/index', [
                'section' => $stories,
                'page' => $page,
                'title' => 'Stories',
            ]);
        } catch (\Throwable $e) {
            $this->view('no_page/index', ['error' => 'Stories page not available']);
        }
    }

    public function ratatouille(): void
    {
        try {
            $page = $this->adminPageService->getPageBySlug('ratatouille');
            $pageId = $page->page_id ?? null;
            $ratatouilleSections = $pageId === null ? [] : $this->pageSectionService->getSectionsByPageId($pageId);

            if ($ratatouilleSections === []) {
                $this->setErrorMessage('Ratatouille page not available');
                $this->redirect('/');
                return;
            }

            $this->view('yummy/ratatouille/index', [
                'section' => $ratatouilleSections,
                'page' => $page,
                'title' => 'Ratatouille',
            ]);
        } catch (\Throwable $e) {
            $this->view('no_page/index', ['error' => 'Ratatouille page not available']);
        }
    }

    private function mergeSectionContent(array $sections): array
    {
        return array_map(
            function (array $section): array {
                $content = json_decode((string) ($section['content'] ?? ''), true);

                if (is_array($content)) {
                    $section = array_merge($section, $content);
                }

                return $section;
            },
            $sections
        );
    }
}
