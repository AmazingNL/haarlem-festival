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
