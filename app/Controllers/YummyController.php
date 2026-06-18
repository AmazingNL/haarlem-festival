<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Services\Interfaces\ICmsService;
use App\Services\Interfaces\IPageSectionService;
use App\Services\Implementations\ProgramService;
use App\Services\Implementations\ReservationEmailService;
use App\Services\Implementations\Booking\RestaurantAvailabilityService;
use App\Services\Implementations\Booking\RestaurantBookingService;
use App\Support\SessionUser;

final class YummyController extends BaseController
{

    private ICmsService $adminPageService;
    private IPageSectionService $pageSectionService;
    private ProgramService $programService;
    private ReservationEmailService $reservationEmailService;
    private RestaurantBookingService $restaurantBookingService;
    private RestaurantAvailabilityService $restaurantAvailability;

    public function __construct(
        ICmsService $adminPageService,
        IPageSectionService $pageSectionService,
        ProgramService $programService,
        ReservationEmailService $reservationEmailService,
        RestaurantBookingService $restaurantBookingService,
        RestaurantAvailabilityService $restaurantAvailability
    )
    {
        $this->adminPageService = $adminPageService;
        $this->pageSectionService = $pageSectionService;
        $this->programService = $programService;
        $this->reservationEmailService = $reservationEmailService;
        $this->restaurantBookingService = $restaurantBookingService;
        $this->restaurantAvailability = $restaurantAvailability;
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
                ['section' => $this->LiveCapacity($pageSection), 'title' => 'Yummy']
            );

        } catch (\Throwable $e) {
            $this->view(
                'no_page/index',
                ['error' => 'Something went wrong' . $e]
            );
        }
    }

    
    //Replace each restaurant card's static "Available Seats" with the live remaining
    // count (real venue capacity minus the busiest booked slot), keeping the total for display.
    /** 
     * @param array<int, array<string, mixed>> $sections
     * @return array<int, array<string, mixed>>
     */
    private function LiveCapacity(array $sections): array
    {
        foreach ($sections as &$section) {
            if (($section['section_type'] ?? '') !== 'restaurant_card') {
                continue;
            }

            $availability = $this->restaurantAvailability->availabilityForLink((string) ($section['button_link'] ?? ''));
            if ($availability['has_venue']) {
                $section['capacity'] = (string) $availability['remaining'];
                $section['capacity_total'] = (string) $availability['capacity'];
            }
        }
        unset($section);

        return $sections;
    }

    public function ratatouille(): void
    {
        $this->restaurantDetail(
            'ratatouille', 
            '/yummy', 
            'yummy/ratatouille/index'
        );
    }

    public function bistroToujours(): void
    {
        $this->restaurantDetail(
            'bistro-toujours',
            '/yummy', 
            'yummy/bistro_toujours/index'
        );
    }

    private function restaurantDetail(string $slug, string $fallbackUrl, string $template): void
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
                ['section' => $sections, 'page' => $page, 'title' => $page->title]
            );

        } catch (\Exception $e) {
            $this->view(
                template: 'no_page/index',
                data: ['error' => $page->title . ' page not available']
            );

        }
    }

    public function bookBistroToujoursReservation(): void
    {
        $this->ensureSession();

        if ($this->isPost()) {
            $this->addReservationToProgram(
                'bistro-toujours', 
                '/yummy/bistro-toujours', 
                'Bistro Toujours'
            );
            return;
        }

        $this->redirect('/yummy/bistro-toujours');
    }

    public function bookReservation(): void
    {
        $this->ensureSession();

        if ($this->isPost()) {
            $this->addReservationToProgram(
                'ratatouille', 
                '/yummy/ratatouille', 
                'Ratatouille Food & Wine');
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
            $item = $this->restaurantBookingService->buildProgramItem(
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
            $emailSent = $this->sendReservationEmail($customer, $item);

            $message = 'Your ' . $locationName . ' reservation was added to My Program.';
            if ($emailSent) {
                $message .= ' A confirmation email has been sent.';
            }
            $this->setSuccessMessage($message);
            $this->redirect('/program');
        } catch (\InvalidArgumentException $e) {
            $this->setErrorMessage($e->getMessage());
            $this->redirect($fallbackUrl);
        } catch (\Throwable $e) {
            $this->setErrorMessage('Your reservation could not be booked right now.');
            $this->redirect($fallbackUrl);
        }
    }

    /**
     * Send the reservation confirmation email as a best-effort side effect: a mail
     * failure must never roll back a reservation that was already added to the program.
     */
    private function sendReservationEmail(array $customer, array $item): bool
    {
        try {
            $this->reservationEmailService->sendReservationAdded($customer, $item);
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}