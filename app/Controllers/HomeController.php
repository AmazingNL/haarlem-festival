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
            $this->ensureSession();
            $page = $this->adminPageService->getPageBySlug('home');
            $sections = $this->pageSectionService->getSectionsByPageId($page->page_id ?? 0);
            $this->view('/home/home', ['sections' => $sections, 'title' => 'Haarlem Festival', 'message' => 'Home page loaded successfully.']);
        } catch (\Exception $e) {
            $this->view('no_page/index', ['error' => 'Failed to load home page: ' . $e->getMessage()]);
        }
    }

    public function yummy(): void
    {
        try {
            $this->renderSlugPage('yummy', 'yummy/index', 'Yummy');
        } catch (\Throwable $e) {
            $this->view('no_page/index', ['error' => 'Something went wrong' . $e]);
        }
    }

    public function stories(): void
    {
        try {
            $this->renderSlugPage('stories', '/stories/index', 'Stories');
        } catch (\Exception $e) {
            $this->view('no_page/index', ['error' => 'Stories page not available']);
        }
    }

    public function ratatouille(): void
    {
        try {
            $this->renderSlugPage('ratatouille', '/ratatouille/index', 'ratatouille');
        } catch (\Exception $e) {
            $this->view('no_page/index', ['error' => 'ratatouille page not available']);
        }
    }

    /**
     * Load a CMS page by slug, merge section JSON, and render the view.
     *
     * @param string $slug  Page slug to look up.
     * @param string $view  View template path.
     * @param string $title Page title passed to the layout.
     */
    private function renderSlugPage(string $slug, string $view, string $title): void
    {
        $page = $this->adminPageService->getPageBySlug($slug);
        if ($page?->page_id === null) {
            $this->view('no_page/index', ['error' => "{$title} page not available"]);
            return;
        }
        $this->renderSections($page->page_id, $view, $title, $page);
    }

    private function renderSections(int $pageId, string $view, string $title, mixed $page): void
    {
        $sections = $this->pageSectionService->getSectionsByPageId($pageId);
        if (empty($sections)) {
            $this->setFlash('error', 'page does not exist');
            $this->redirect('/');
            return;
        }
        $this->view($view, ['section' => $this->mergeSectionContent($sections), 'page' => $page, 'title' => $title]);
    }

    /**
     * Decode and merge each section's JSON content column into its array.
     *
     * @param  array $sections Raw section rows from the repository.
     * @return array           Sections with content fields merged at the top level.
     */
    private function mergeSectionContent(array $sections): array
    {
        return array_map(static function (array $s): array {
            $c = json_decode((string) ($s['content'] ?? ''), true);
            return is_array($c) ? array_merge($s, $c) : $s;
        }, $sections);
    }
}
