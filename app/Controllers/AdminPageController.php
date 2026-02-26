<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Middleware;
use App\Models\Page;
use App\Services\IAdminPageService;
use App\Services\IPageSectionService;
use App\Services\IUserService;
use App\Models\PageSection;
use App\Models\Image;
use App\Services\IImageService;
use Exception;
use Throwable;

final class AdminPageController extends BaseController
{
    private IAdminPageService $adminPageService;
    private IPageSectionService $pageSectionService;
    private IUserService $userService;
    private IImageService $imageService;

    public function __construct(IAdminPageService $adminPageService, IPageSectionService $pageSectionService, IUserService $userService, IImageService $imageService)
    {
        $this->adminPageService = $adminPageService;
        $this->pageSectionService = $pageSectionService;
        $this->userService = $userService;
        $this->imageService = $imageService;
    }

    public function index(): void
    {
        $this->ensureSession();
        $users = $this->userService->getAllUsers();
        $allPages = $this->adminPageService->getAllPages();
        usort($allPages, fn($a, $b) => strcmp((string)($b->created_at ?? ''), (string)($a->created_at ?? '')));
        $this->view(
            'admin_dashboard/index',
            [
                'allPages'    => $allPages,
                'recentPages' => array_slice($allPages, 0, 5),
                'userCount'   => count($users),
                'title'       => 'Admin Dashboard',
            ],
            layout: 'admin_dashboard'
        );
    }

    public function viewPages()
    {
        // $this->ensureSession();
        // Middleware::requireAdmin();
        $pages = $this->adminPageService->getAllPages();
        $this->view(
            'admin_dashboard/pages',
            ['pages' => $pages],
            'admin_dashboard'
        );
    }
    public function createPageForm(): void
    {
        $this -
            $this->view(
                'admin_dashboard/create_page',
                ['title' => 'Create Page'],
                layout: 'admin_dashboard'
            );
    }

    public function createPage(): void
    {
        try {
            $this->verifyCsrf();
            $this->requireFields(['title', 'slug', 'content']);

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
            $page_id = $this->adminPageService->createPage($pageData);
            if ((int) $page_id <= 0) {
                $this->setFlash('error', 'Page failed to create');
                $this->view(
                    'admin_dashboard/create_page',
                    ['pageData' => $pageData],
                    layout: 'admin_dashboard'
                );
            }
            $this->setFlash('success', 'Page successfully created');
            $this->redirect('/admin/pageSection/' . $page_id . '/pageSectionForm');
        } catch (Exception $e) {
            $this->setFlash('error', 'Page failed to create.');
            $this->view(
                'admin_dashboard/create_page',
                ['pageData' => $pageData],
                layout: 'admin_dashboard'
            );
        }
    }

    public function editPageForm($page_id): void
    {
        $this->ensureSession();
        try {
            $page = $this->adminPageService->getPageById((int) $page_id);
        } catch (Throwable $e) {
            $this->setFlash('error', 'Failed to load page');
            $this->redirect('/admin/dashboard');
            return;
        }

        if ($page === null) {
            $this->setFlash('error', 'Page not found');
            $this->redirect('/admin/dashboard');
            return;
        }
        $this->view(
            'admin_dashboard/edit_page',
            ['page' => $page, 'title' => 'Edit page'],
            'admin_dashboard'
        );
    }
    public function editPage(int $page_id): void
    {
        try {
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
            if ($updated === false) {
                $this->setFlash('error', 'Failed to update page.');
                $this->view(
                    'admin_dashboard/edit_page',
                    ['page' => $pageData],
                    layout: 'admin_dashboard'
                );
                return;
            }
            $this->setFlash('success', 'Page edited successfully');
            $this->redirect('/admin/dashboard');
        } catch (Exception $e) {
            $this->setFlash('error', 'Something went wrong');
            $this->view(
                'admin_dashboard/edit_page',
                ['page' => $pageData, 'title' => 'Edit Page'],
                layout: 'admin_dashboard'
            );
        }
    }

    public function pageSectionForm($page_id)
    {
        $this->ensureSession();
        Middleware::requireAdmin();
        try {
            $pageSection = $this->pageSectionService->getSectionsByPageId((int) $page_id);
            if ($pageSection === null) {
                $this->setFlash('error', 'No page Found');
                $this->redirect('/admin/pages/createPage');
                echo new Exception();
                return;
            }
            $this->view(
                '/admin_dashboard/create_pageSection',
                ['pageSection' => $pageSection, 'page_id' => (int) $page_id],
                'admin_dashboard'
            );
        } catch (Throwable $e) {
            $this->setFlash('error', 'Something went wrong' . $e);
            $this->redirect('/admin/pages/createPage');
        }

    }
    public function createPageSection(int $page_id): void
    {
        $this->ensureSession();
        Middleware::requireAdmin();

        try {
            $section = $this->pageSection($page_id);

            $publicUrl = null;
            if (!empty($_FILES['section_image']) && $_FILES['section_image']['error'] === UPLOAD_ERR_OK) {
                $file = $this->getUploadedFileOrFail('section_image');
                $publicUrl = $this->storeImageFileOrFail($file);
            }

            $this->pageSectionService->createSectionWithImage($section, $publicUrl);

            $this->setFlash('success', 'Section created successful');
            $this->redirect('/admin/dashboard');
        } catch (Throwable $e) {
            $this->setFlash('error', 'Something went wrong: ' . $e->getMessage());
            $this->redirect('/admin/dashboard');
        }
    }
    public function pageSectionList($page_id)
    {
        $this->ensureSession();
        try {
            $pageSection = $this->pageSectionService->getSectionsByPageId((int) $page_id);
            if (empty($pageSection)) {
                $this->setFlash('error', 'No sections Found');
                $this->redirect('/admin/dashboard');
                return;
            }
            $this->view(
                'admin_dashboard/page_section',
                ['pageSection' => $pageSection, 'pageId' => $page_id],
                'admin_dashboard'
            );
        } catch (Throwable $e) {
            $this->setFlash('error', 'Something went wrong' . $e);
            $this->redirect('/admin/pages/' . $page_id . '/editForm');
        }
    }

    public function editSectionForm($section_id)
    {
        $this->ensureSession();
        try {
            $section = $this->pageSectionService->getSectionById((int) $section_id);
            if ($section === null) {
                $this->setFlash('error', 'No section Found');
                $this->redirect('/admin/dashboard');
                return;
            }else
            $this->view(
                'admin_dashboard/edit_page_section',
                ['section' => $section],
                'admin_dashboard'
            );
        } catch (Throwable $e) {
            $this->setFlash('error', 'Something went wrong' . $e);
            $this->redirect('/admin/page_section');
        }
    }
    public function editSection(int $page_id): void
    {
        try {
            $pageSection = $this->pageSection($page_id);
            $updated = $this->pageSectionService->updateSection($pageSection);
            if ($updated === false) {
                $this->setFlash('error', 'Section not saving');
                $this->view(
                    'admin_dashboard/edit_page_section',
                    ['section' => $pageSection],
                    'admin_dashboard'
                );
                return;
            }
            $this->setFlash('success', 'Section updated successfully');
            $this->redirect('/admin/pageSection/' . $page_id . '/pageSectionList');
        } catch (Throwable $e) {
            $this->setFlash('error', 'Something went wrong while saving  '.$e);
            $this->view(
                '/admin_dashboard/create_page',
                ['pageSection' => $pageSection],
                'admin_dashboard'
            );
        }
    }

    private function pageSection(int $page_id): PageSection
    {
        $this->ensureSession();
        $this->verifyCsrf();

        $this->requireFields([
            'section_type',
            'title',
            'content',
            'sort_order',
            'is_published'
        ]);

        $sectionId = (int) ($this->input('section_id', 0) ?? 0);
        $sectionType = $this->str('section_type');
        $title = $this->str('title');
        $content = $this->str('content');

        $rawImage = $this->input('image_id', null);
        $image_id = ($rawImage === null || $rawImage === '') ? null : (int) $rawImage;

        $button_text = ($this->input('button_text', '') !== '') ? $this->str('button_text') : null;
        $button_link = ($this->input('button_link', '') !== '') ? $this->str('button_link') : null;

        $sort_order = (int) ($this->input('sort_order', 0) ?? 0);
        $is_published = (bool) $this->int('is_published', 0) !== null;

        return new PageSection(
            $sectionId,
            $page_id,
            $sectionType,
            $title,
            $content,
            $image_id,
            $button_text,
            $button_link,
            $sort_order,
            $is_published
        );
    }

    public function deletePage($page_id): void
    {
        try {
            $this->ensureSession();
            Middleware::requireAdmin();
            if ($page_id === 0) {
                $this->setFlash('error', 'Page Not Found');
                $this->redirect('/admin/pages/viewPage');
            }
            $this->adminPageService->deletePage((int)$page_id);
            $this->setFlash('success', 'Deleted successfully');
            $this->redirect('/admin/pages/viewPage');

        } catch (Throwable $e) {
            $this->setFlash('error', 'Something went wrong  ' .$e);
            $this->redirect('/admin/pages/viewPage');
        }
    }

    public function uploadImage(): void
    {
        Middleware::requireAdmin(); // starts HF_ADMIN session correctly

        $file = $this->getUploadedFileOrFail('file');
        $publicUrl = $this->storeImageFileOrFail($file);

        // `alt_text` is optional from editors; accept empty string when not provided.
        $altText = $this->str('alt_text', '');
        $userId = $_SESSION['user_id'];
        if ($userId === 0) {
            $this->json(['error' => 'Unauthorized'], 401);
        }

        // SAVE IMAGE TO DB
        $image = new Image(
            image_id: null,
            file_path: $publicUrl,
            alt_text: $altText !== '' ? $altText : null,
            uploaded_by_user_id: $userId,
            created_at: null
        );

        $imageId = $this->imageService->saveImage($image);

        $this->json([
            'location' => $publicUrl,
            'image_id' => $imageId,
            'alt_text' => $altText !== '' ? $altText : null,
        ]);
    }

    public function manageUsersPage(): void
    {
        $this->ensureSession();
        Middleware::requireAdmin();
        $users = $this->userService->getAllUsers();
        $this->view('admin/manage_users', ['users' => $users, 'title' => 'Manage Users'], 'admin_dashboard');
    }

    // minimal stubs for routes referenced in Router
    public function viewEventPage(): void
    {
        $this->view('admin/view_event', [], 'admin_dashboard');
    }

    public function updateEventPage(): void
    {
        $this->view('admin/update_event', [], 'admin_dashboard');
    }

    private function sanitizeCmsHtml(string $html): string
    {
        $html = preg_replace('#<\s*(script|style)\b[^>]*>.*?<\s*/\s*\1\s*>#is', '', $html) ?? '';
        $allowed = '<p><br><b><strong><i><em><u><h1><h2><h3><h4><h5><h6><ul><ol><li><a><img><blockquote><hr><span><div><section><article><figure><figcaption><table><thead><tbody><tr><th><td>';
        $html = strip_tags($html, $allowed);
        $html = preg_replace('/\son\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html) ?? '';
        $html = preg_replace('/\s(href|src)\s*=\s*("|\')\s*javascript:[^"\']*\2/i', ' $1=$2#$2', $html) ?? '';
        return trim($html);
    }

    private function getUploadedFileOrFail(string $key): array
    {
        if (!isset($_FILES[$key]) || $_FILES[$key]['error'] !== UPLOAD_ERR_OK) {
            $this->json(['error' => 'Upload failed'], 400);
            exit; // stop execution after sending JSON
        }

        $file = $_FILES[$key];

        if ($file['size'] > 5 * 1024 * 1024) {
            $this->json(['error' => 'File too large'], 413);
            exit;
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
        ];

        if (!isset($allowed[$mime])) {
            $this->json(['error' => 'Invalid image type'], 415);
            exit;
        }
        $file['_ext'] = $allowed[$mime];

        return $file;
    }

    private function storeImageFileOrFail(array $file): string
    {
        $ext = $file['_ext'] ?? 'png';

        try {
            $name = bin2hex(random_bytes(16)) . '.' . $ext;
        } catch (Throwable $e) {
            $this->json(['error' => 'Failed to generate filename'], 500);
            exit;
        }

        $uploadDir = dirname(__DIR__, 2) . '/public/assets/images/admin/';
        if (!is_dir($uploadDir) || !is_writable($uploadDir)) {
            $this->json(['error' => 'Upload directory not writable'], 500);
            exit;
        }

        $dest = $uploadDir . $name;
        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            $this->json(['error' => 'Could not save file'], 500);
            exit;
        }

        @chmod($dest, 0644);

        return '/assets/images/admin/' . $name;
    }

}
