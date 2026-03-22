<?php

declare(strict_types=1);
namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Page;
use App\Models\User;
use App\Models\Enum\SectionType;
use App\Models\Enum\UserRole;
use App\Services\IAdminPageService;
use App\Services\IPageSectionService;
use App\Services\IUserService;
use App\Models\PageSection;
use App\Models\Image;
use App\Services\IImageService;
use App\ViewModels\SectionFactory;
use Exception;
use Throwable;

final class AdminPageController extends BaseController
{
    private IAdminPageService $adminPageService;
    private IPageSectionService $pageSectionService;
    private IUserService $userService;
    private IImageService $imageService;

    public function __construct(
        IAdminPageService $adminPageService,
        IPageSectionService $pageSectionService,
        IUserService $userService,
        IImageService $imageService,
    ) {
        $this->adminPageService = $adminPageService;
        $this->pageSectionService = $pageSectionService;
        $this->userService = $userService;
        $this->imageService = $imageService;
    }

    public function index(): void
    {
        $this->ensureSession();
        $this->refreshAdminDisplayName();
        $users = $this->userService->getAllUsers();
        $allPages = $this->adminPageService->getAllPages();
        usort($allPages, fn($a, $b) => strcmp((string) ($b->created_at ?? ''), (string) ($a->created_at ?? '')));
        $this->view(
            'admin_dashboard/index',
            [
                'allPages' => $allPages,
                'recentPages' => array_slice($allPages, 0, 5),
                'userCount' => count($users),
                'title' => 'Admin Dashboard',
            ],
            layout: 'admin_dashboard'
        );
    }

    public function viewPages()
    {
        $pages = $this->adminPageService->getAllPages();
        $this->view(
            'admin_dashboard/pages',
            ['pages' => $pages],
            'admin_dashboard'
        );
    }

    // ------------ create page section ----------------------------//
    public function createPageForm(): void
    {
        $this->view(
            'admin_dashboard/create_page',
            ['title' => 'Create Page'],
            layout: 'admin_dashboard'
        );
    }

    public function createPage(): void
    {
        $pageData = [];
        try {
            $this->verifyCsrf();
            $this->requireFields(['title', 'slug', 'content']);

            $pageData = $this->adminPageService->preparePageData(
                $this->str('title'),
                $this->str('slug'),
                $this->str('content'),
                $this->str('status', 'draft')
            );
            $page_id = $this->adminPageService->createPage($pageData);
            if ((int) $page_id <= 0) {
                $this->setFlash('error', 'Page failed to create');
                $this->view(
                    'admin_dashboard/create_page',
                    ['pageData' => $pageData],
                    layout: 'admin_dashboard'
                );
                return;
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

    //---------------- Edit page section -------------------------//
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

    //----------- POST Edit page ------------//
    public function editPage(int $page_id): void
    {
        $pageData = [];
        try {
            $this->verifyCsrf();
            $this->requireFields(['title', 'content', 'slug']);

            $pageData = $this->adminPageService->preparePageData(
                $this->str('title'),
                $this->str('slug'),
                $this->str('content'),
                $this->str('status', 'draft')
            );

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
    //--------------------------------- Delete section -----------------------//
    public function deletePage($page_id): void
    {
        try {
            $this->ensureSession();
            if ($page_id === 0) {
                $this->setFlash('error', 'Page Not Found');
                $this->redirect('/admin/pages/viewPage');
                return;
            }
            $this->adminPageService->deletePage((int) $page_id);
            $this->setFlash('success', 'Deleted successfully');
            $this->redirect('/admin/pages/viewPage');

        } catch (Throwable $e) {
            $this->setFlash('error', 'Something went wrong  ' . $e);
            $this->redirect('/admin/pages/viewPage');
        }
    }

    //------------------------------------------------ Page section -------------------------------------------------------------------//
    public function pageSectionForm($page_id)
    {
        $this->ensureSession();
        try {
            $pageSection = $this->adminPageService->getPageById((int) $page_id);
            if (empty($pageSection)) {
                $this->setFlash('error', 'No page Found');
                $this->redirect('/admin/pages/viewPage');
                return;
            }
            $this->view(
                '/admin_dashboard/create_Section',
                ['pageSection' => $pageSection, 'page_id' => (int) $page_id],
                'admin_dashboard'
            );
        } catch (Throwable $e) {
            $this->setFlash('error', 'Something went wrong' . $e);
            $this->redirect('/admin/pages/createPage');
        }
    }
    public function createPageSection($page_id): void
    {
        $this->ensureSession();
        $pageId = (int) $page_id;
        if ($pageId <= 0) {
            $this->setFlash('error', 'Invalid page.');
            $this->redirect('/admin/dashboard');
            return;
        }
        try {
            $this->verifyCsrf();
            $section = $this->pageSectionService->buildSectionFromInput($pageId, $_POST, $_FILES);
            $this->pageSectionService->createSection($section);

            $this->setFlash('success', 'Section created successful');
            $this->redirect('/admin/dashboard');
        } catch (Throwable $e) {
            $this->setFlash('error', 'Something went wrong: ' . $e->getMessage());
            $this->redirect('/admin/dashboard');
        }
    }

    public function renderSectionForm(): void
    {
        $this->ensureSession();

        try {
            $sectionType = $this->str('type');
            $sectionField = $this->pageSectionService->resolveSectionFormFields($sectionType);
            require __DIR__ . '/../Views/admin_dashboard/section_partial_view/index.php';
        } catch (\InvalidArgumentException $e) {
            $this->json(['error' => $e->getMessage()], 404);
        }
    }


    //-------------------- page list section ------------//
    public function viewPageSections($page_id)
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

    //--------------- Edit section -----------------//
    public function editSectionForm($section_id)
    {
        $this->ensureSession();
        try {
            $pageSection = $this->pageSectionService->getSectionById((int) $section_id);
            if ($pageSection === null) {
                $this->setFlash('error', 'No section Found');
                $this->redirect('/admin/pageSection/' . $section_id . '/editSectionForm');
                return;
            }
            $sectionData = json_decode((string) $pageSection->content, true);
            $sectionTypeValue = $pageSection->section_type instanceof SectionType
                ? $pageSection->section_type->value : (string) $pageSection->section_type;
            if ($sectionTypeValue === '') {
                $this->setFlash('error', 'No Section Type found');
                $this->redirect('/admin/pageSection/' . $section_id . '/editSectionForm');
                return;
            }
            $sectionClass = SectionFactory::returnSectionClass($sectionTypeValue);
            if ($sectionClass === null) {
                $this->setFlash('error', 'No Section type not supported');
                $this->redirect('/admin/dashboard');
                return;
            }
            $sectionVm = new $sectionClass;
            $sectionField = $sectionVm->getAdminFormFields($sectionVm);

            $this->view(
                'admin_dashboard/edit_section',
                $this->sectionFormData($pageSection, $sectionTypeValue, $sectionField),
                'admin_dashboard'
            );
        } catch (Throwable $e) {
            $this->setFlash('error', 'Something went wrong: ' . $e->getMessage());
            $this->redirect('/admin/page_section');
        }
    }
    //------------- POST Edit section -----------------//
    public function editSection(int|string $sectionId): void
    {
        $sectionId = (int) $sectionId;
        try {
            $this->verifyCsrf();
            $pageSection = $this->pageSectionService->buildSectionFromInput($sectionId, $_POST, $_FILES);
            $updated = $this->pageSectionService->updateSection($pageSection);
            if ($updated === false) {
                $this->setFlash('error', 'Section not saving');
                $this->redirect('/admin/pageSection/' . $sectionId . '/editSectionForm');
                return;
            }
            $this->setFlash('success', 'Section updated successfully');
            $this->redirect('/admin/pageSection/' . $pageSection->page_id . '/viewPageSections');
        } catch (Throwable $e) {
            $this->setFlash('error', 'Something went wrong while saving  ' . $e);
            $this->redirect('/admin/pageSection/' . $sectionId . '/editSectionForm');
        }
    }

    /**
     * *
     * @param PageSection $section
     * 
     */
    private function sectionFormData(PageSection $section, string $sectionType, array $sectionField): array
    {
        $sectionData = json_decode((string) $section->content, true);
        if (!is_array($sectionData)) {
            $sectionData = [];
        }
        return [
            'section_id' => (int) $section->section_id,
            'pageId' => (int) $section->page_id,
            'sectionType' => $sectionType,
            'sortOrder' => (int) $section->sort_order,
            'isPublished' => $section->is_published ? 1 : 0,
            'sectionData' => $sectionData,
            'sectionField' => $sectionField,
        ];
    }
    //------- Delete Section --------------//

    public function deleteSection(int|string $sectionId): void
    {
        $this->ensureSession();
        $sectionId = (int) $sectionId;
        try {
            $section = $this->pageSectionService->getSectionById($sectionId);
            $pageId = (int) $section->page_id;

            $deleted = $this->pageSectionService->deleteSection($sectionId);
            if ($deleted !== true) {
                $this->setFlash('error', 'Can not delete page');
                $this->redirect('/admin/pageSection/' . $pageId . '/viewPageSections');
                return;
            }
            $this->setFlash('success', 'Deleted Successfully');
            $this->redirect('/admin/pageSection/' . $pageId . '/viewPageSections');
        } catch (Throwable $e) {
            $this->setFlash('error', 'Something went wrong');
            $this->redirect('/admin/pages/viewPage');

        }
    }

    //---------- upload image -------//
    public function uploadImage(): void
    {
        try {
            $publicUrl = $this->imageService->storeUploadedImage($_FILES['file']);

            $altText = $this->str('alt_text', '');
            $userId = $_SESSION['user_id'];
            if ($userId === 0) {
                $this->json(['error' => 'Unauthorized'], 401);
                exit;
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
        } catch (\Exception $e) {
            $this->json(['error' => $e->getMessage()], (int) $e->getCode() ?: 400);
        }
    }

    /**
     * @return void
     */
    public function manageUsersPage(): void
    {
        $role = $this->str('role');
        $search = $this->str('search');
        $sort = $this->str('sort', 'date_desc');
        $users = $this->userService->filterUsers($role, $search, $sort);
        $this->view('admin/manage_users', compact('users', 'role', 'search', 'sort') + ['title' => 'Manage Users'], 'admin_dashboard');
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



    // ─── User CRUD ────────────────────────────────────────────────────────────

    /**
     * Show the create-user form.
     *
     * @return void
     */
    public function createUserForm(): void
    {
        $this->ensureSession();
        $this->view('admin/create_user', ['title' => 'Create User'], 'admin_dashboard');
    }

    /**
     * Process the create-user form submission.
     *
     * @return void
     */
    public function createUser(): void
    {
        $this->ensureSession();
        try {
            $this->verifyCsrf();
            $this->requireFields(['first_name', 'last_name', 'email', 'username', 'password', 'role']);
            $user = new User();
            $user->first_name = $this->str('first_name');
            $user->last_name = $this->str('last_name');
            $user->email = $this->str('email');
            $user->username = $this->str('username');
            $user->role = UserRole::from($this->str('role'));
            $this->userService->registerUser($user, $this->str('password'));
            $this->setFlash('success', 'User created successfully.');
            $this->redirect('/admin/users');
        } catch (Throwable $e) {
            $this->setFlash('error', 'Failed to create user: ' . $e->getMessage());
            $this->redirect('/admin/users/create');
        }
    }

    /**
     * Show the edit-user form.
     *
     * @param int $user_id
     * @return void
     */
    public function editUserForm(int $user_id): void
    {
        $this->ensureSession();
        $user = $this->userService->getUserById($user_id);
        if ($user === null) {
            $this->setFlash('error', 'User not found.');
            $this->redirect('/admin/users');
            return;
        }
        $this->view('admin/edit_user', ['user' => $user, 'title' => 'Edit User'], 'admin_dashboard');
    }

    /**
     * Process the edit-user form submission.
     *
     * @param int $user_id
     * @return void
     */
    public function editUser(int $user_id): void
    {
        $this->ensureSession();
        try {
            $this->verifyCsrf();
            $this->requireFields(['first_name', 'last_name', 'email', 'username', 'role']);
            $user = $this->userService->getUserById($user_id);
            if ($user === null) {
                $this->setFlash('error', 'User not found.');
                $this->redirect('/admin/users');
                return;
            }
            $user->first_name = $this->str('first_name');
            $user->last_name = $this->str('last_name');
            $user->email = $this->str('email');
            $user->username = $this->str('username');
            $user->role = UserRole::from($this->str('role'));
            $this->userService->updateUserAdmin($user, $this->str('password'));
            $this->setFlash('success', 'User updated successfully.');
            $this->redirect('/admin/users');
        } catch (Throwable $e) {
            $this->setFlash('error', 'Failed to update user: ' . $e->getMessage());
            $this->redirect('/admin/users/' . $user_id . '/edit');
        }
    }

    /**
     * Delete a user by ID (cannot delete yourself).
     *
     * @param int $user_id
     * @return void
     */
    public function deleteUser(int $user_id): void
    {
        $this->ensureSession();
        try {
            if ($user_id === (int) ($_SESSION['user_id'] ?? 0)) {
                $this->setFlash('error', 'You cannot delete your own account.');
                $this->redirect('/admin/users');
                return;
            }
            $this->userService->deleteUser($user_id);
            $this->setFlash('success', 'User deleted.');
        } catch (Throwable $e) {
            $this->setFlash('error', 'Failed to delete user.');
        }
        $this->redirect('/admin/users');
    }

    /**
     * Refreshes $_SESSION['display_name'] from the DB so the admin navbar
     * always shows the real name, even for sessions created before this key existed.
     */
    private function refreshAdminDisplayName(): void
    {
        $userId = (int) ($_SESSION['user_id'] ?? 0);
        if ($userId <= 0) {
            return;
        }
        $user = $this->userService->getUserById($userId);
        if ($user === null) {
            return;
        }
        $fullName = trim($user->first_name . ' ' . $user->last_name);
        $_SESSION['display_name'] = $fullName ?: $user->username;
    }

}
