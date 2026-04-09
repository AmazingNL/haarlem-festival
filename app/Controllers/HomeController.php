<?php declare(strict_types=1);

namespace App\Controllers;
use App\Core\BaseController;
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
            $this->view(
                'no_page/index',
                ['error' => 'Failed to load home page: ' . $e->getMessage()]
            );
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
            $pageSection = $this->pageSectionService->getSectionsByPageId($page_id);
            if (empty($pageSection)) {
                $this->setFlash('error', 'page does not exist');
                $this->redirect('/');
                return;
            }

            $section = array_map(
                function (array $s): array {
                    $content = json_decode((string) ($s['content'] ?? ''), true);
                    if (is_array($content)) {
                        $s = array_merge($s, $content);
                    }
                    return $s;
                },
                $pageSection
            );


            $this->view(
                'yummy/index',
                ['section' => $section, 'page' => $page, 'title' => 'Yummy']
            );

        } catch (\Throwable $e) {
            $this->view(
                'no_page/index',
                ['error' => 'Something went wrong' . $e]
            );
        }
    }

    public function stories(): void
    {
        try {
            $page = $this->adminPageService->getPageBySlug(slug: 'stories');
            $page_id = $page->page_id ?? null;
            $stories = $this->pageSectionService->getSectionsByPageId(pageId: $page_id);
            if (empty($stories)) {
                $this->setFlash(key: 'error', value: 'page does not exist');
                $this->redirect(to: '/');
            }
            $this->view(
                template: '/stories/index',
                data: ['section' => $stories, 'page' => $page, 'title' => 'Stories']
            );
        } catch (\Exception $e) {
            $this->view(
                'no_page/index',
                data: ['error' => 'Stories page not available']
            );
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
                template: 'no_page/index',
                data: ['error' => 'ratatouille page not available']
            );

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
