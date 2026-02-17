<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Services\IAdminPageService;
use App\Services\IPageSectionService;
use App\Services\IUserService;
use App\Models\PageSection;

final class AdminPageController extends BaseController
{
    private IAdminPageService $adminPageService;
    private IPageSectionService $pageSectionService;
    private IUserService $userService;

    public function __construct(IAdminPageService $adminPageService, IPageSectionService $pageSectionService, IUserService $userService)
    {
        $this->adminPageService = $adminPageService;
        $this->pageSectionService = $pageSectionService;
        $this->userService = $userService;
    }

    public function index(): void
    {
        $this->ensureSession();
        $pages = $this->adminPageService->getPublishedPages();
        $this->view('admin_dashboard/index',
        ['page' => $pages, 'title' => 'Admin Dashboard'],
        layout: 'admin_dashboard');
    }

    public function createPageForm(): void
    {
        $this->ensureSession();
        $this->view('admin_dashboard/create_page', 
        ['title' => 'Create Page'], layout: 'admin_dashboard');
    }

    public function createPage(): void
    {
        $this->verifyCsrf();
        $this->requireFields(['title', 'slug']);

        $title = $this->str('title');
        $slug = $this->str('slug');
        $content = $this->sanitizeCmsHtml($this->str('content'));
        $status = $this->str('status', 'draft');

        $pageData = [
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'status' => $status,
        ];

        $id = $this->adminPageService->createPage($pageData);
        if ($id > 0) {
            $this->redirect('/admin/dashboard');
        }

        $this->view('admin_dashboard/create_page', ['title' => 'Create Page', 'error' => 'Failed to create page.'], layout: 'admin_dashboard');
    }

    public function editPage(int $page_id): void
    {
        if ($this->isPost()) {
            $this->verifyCsrf();
            $this->requireFields(['title', 'content', 'slug']);

            $title = $this->str('title');
            $slug = $this->str('slug');
            $content = $this->sanitizeCmsHtml($this->str('content'));
            $status = $this->str('status', 'draft');

            $pageData = [
                'title' => $title,
                'slug' => $slug,
                'content' => $content,
                'status' => $status,
            ];

            $updated = $this->adminPageService->updatePage($page_id, $pageData);
            $this->pageSection($page_id);

            if ($updated) {
                $this->redirect('/admin/dashboard');
            }

            $this->view('admin_dashboard/edit_page',
            ['page' => $pageData, 'title' => 'Edit Page', 'error' => 'Failed to update page.'], layout: 'admin_dashboard');
            return;
        }

        $page = $this->adminPageService->getPageById($page_id);
        if ($page === null) {
            $this->abort(404, 'Page not found');
        }

        $sections = $this->pageSectionService->getSectionsByPageId($page_id);
        $this->view('admin_dashboard/edit_page',
        ['page' => $page->toArray(), 
        'sections' => array_map(fn($s) => $s->toArray(),
        $sections), 'title' => 'Edit Page'], layout: 'admin_dashboard');
    }

    private function pageSection(int $page_id): void
    {
            // handle submitted sections: sections[index][field]
            $sectionsInput = $_POST['sections'] ?? [];
            if (is_array($sectionsInput) && !empty($sectionsInput)) {
                foreach ($sectionsInput as $idx => $s) {
                    $sectionId = isset($s['section_id']) ? (int)$s['section_id'] : 0;
                    $section = new PageSection();
                    $section->section_id = $sectionId;
                    $section->page_id = $page_id;
                    $section->section_type = (string) ($s['section_type'] ?? '');
                    $section->title = isset($s['title']) ? (string)$s['title'] : null;
                    $section->content = isset($s['content']) ? (string)$s['content'] : null;
                    $section->image_id = isset($s['image_id']) && $s['image_id'] !== '' ? (int)$s['image_id'] : null;
                    $section->button_text = isset($s['button_text']) ? (string)$s['button_text'] : null;
                    $section->button_link = isset($s['button_link']) ? (string)$s['button_link'] : null;
                    $section->sort_order = isset($s['sort_order']) ? (int)$s['sort_order'] : 0;
                    $section->is_published = !empty($s['is_published']);

                    try {
                        if ($sectionId > 0) {
                            $this->pageSectionService->updateSection($section);
                        } else {
                            $this->pageSectionService->createSection($section);
                        }
                    } catch (\Throwable $e) {
                        // ignore per-section errors for now; could collect and show later
                    }
                }    }
    }

    public function manageUsersPage(): void
    {
        $this->view('admin/manage_users', [], 'admin_dashboard');
    }

    // minimal stubs for routes referenced in Router
    public function viewEventPage(): void { $this->view('admin/view_event', [], 'admin_dashboard'); }
    public function deleteEventPage(): void { $this->view('admin/delete_event', [], 'admin_dashboard'); }
    public function updateEventPage(): void { $this->view('admin/update_event', [], 'admin_dashboard'); }

    private function sanitizeCmsHtml(string $html): string
    {
        $html = preg_replace('#<\s*(script|style)\b[^>]*>.*?<\s*/\s*\1\s*>#is', '', $html) ?? '';
        $allowed = '<p><br><b><strong><i><em><u><h1><h2><h3><h4><h5><h6><ul><ol><li><a><img><blockquote><hr><span><div><section><article><figure><figcaption><table><thead><tbody><tr><th><td>';
        $html = strip_tags($html, $allowed);
        $html = preg_replace('/\son\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html) ?? '';
        $html = preg_replace('/\s(href|src)\s*=\s*("|\')\s*javascript:[^"\']*\2/i', ' $1=$2#$2', $html) ?? '';
        return trim($html);
    }

}
