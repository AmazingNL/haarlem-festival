<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Services\ICmsService;
use App\Services\IPageSectionService;
use App\Services\ProgramService;
use App\Services\ReservationEmailService;
use App\Services\YummyReservationCatalogService;
use App\Support\SessionUser;

final class YummyController extends BaseController
{

    private ICmsService $adminPageService;
    private IPageSectionService $pageSectionService;
    private ProgramService $programService;
    private ReservationEmailService $reservationEmailService;
    private YummyReservationCatalogService $yummyReservationCatalogService;

    public function __construct(
        ICmsService $adminPageService,
        IPageSectionService $pageSectionService,
        ProgramService $programService,
        ReservationEmailService $reservationEmailService,
        YummyReservationCatalogService $yummyReservationCatalogService
    )
    {
        $this->adminPageService = $adminPageService;
        $this->pageSectionService = $pageSectionService;
        $this->programService = $programService;
        $this->reservationEmailService = $reservationEmailService;
        $this->yummyReservationCatalogService = $yummyReservationCatalogService;
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
            $this->view(
                'yummy/index',
                ['section' => $pageSection, 'title' => 'Yummy']
            );

        } catch (\Throwable $e) {
            $this->view(
                'no_page/index',
                ['error' => 'Something went wrong' . $e]
            );
        }
    }

    public function ratatouille(): void
    {
        $this->restaurantDetail('ratatouille', 'Ratatouille', '/yummy', 'yummy/ratatouille/index');
    }

    public function bistroToujours(): void
    {
        $this->restaurantDetail('bistro-toujours', 'Bistro Toujours', '/yummy', 'yummy/bistro_toujours/index');
    }

    private function restaurantDetail(string $slug, string $title, string $fallbackUrl, string $template): void
    {
        try {
            $page = $this->adminPageService->getPageBySlug($slug);
            $page_id = $page->page_id ?? null;
            $sections = $page_id === null ? [] : $this->pageSectionService->getSectionsByPageId((int) $page_id);
            if (empty($sections)) {
                $this->setFlash('error', 'page does not exist');
                $this->redirect($fallbackUrl);
            }
            $this->view(
                $template,
                ['section' => $sections, 'page' => $page, 'title' => $title]
            );

        } catch (\Exception $e) {
            $this->view(
                template: 'no_page/index',
                data: ['error' => $title . ' page not available']
            );

        }
    }

    public function bookBistroToujoursReservation(): void
    {
        $this->ensureSession();

        if ($this->isPost()) {
            $this->addReservationToProgram('bistro-toujours', '/yummy/bistro-toujours', 'Bistro Toujours');
            return;
        }

        $this->redirect('/yummy/bistro-toujours');
    }

    public function bookReservation(): void
    {
        $this->ensureSession();

        if ($this->isPost()) {
            $this->addReservationToProgram('ratatouille', '/yummy/ratatouille', 'Ratatouille Food & Wine');
            return;
        }

        $this->redirect('/yummy/ratatouille');
    }

    private function addReservationToProgram(string $pageSlug, string $fallbackUrl, string $locationName): void
    {
        try {
            $this->verifyCsrf();

            if (!$this->isLoggedIn()) {
                $_SESSION['auth_redirect'] = $fallbackUrl;
                $this->setErrorMessage('Log in before booking your reservation.');
                $this->redirect('/loginForm');
                return;
            }

            $customer = SessionUser::customerData();
            $item = $this->yummyReservationCatalogService->buildProgramItem(
                $pageSlug,
                $locationName,
                $this->str('date'),
                $this->str('session'),
                $this->int('adultCount'),
                $this->int('childCount'),
                $this->str('special_requests'),
                $customer
            );

            $this->programService->addItem($item);
            $this->reservationEmailService->sendReservationAdded($customer, $item);

            $this->setSuccessMessage('Your ' . $locationName . ' reservation was added to My Program. A confirmation email has been sent.');
            $this->redirect('/program');
        } catch (\InvalidArgumentException $e) {
            $this->setErrorMessage($e->getMessage());
            $this->redirect($fallbackUrl);
        } catch (\Throwable $e) {
            $this->setErrorMessage('Your reservation could not be booked right now.');
            $this->redirect($fallbackUrl);
        }
    }
}