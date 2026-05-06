<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Services\IAdminPageService;
use App\Services\IPageSectionService;
use App\Services\IRestaurantService;
use App\Services\IUserService;
use App\Services\ProgramService;
use App\Services\ReservationEmailService;
use App\Models\Restaurant;

final class YummyController extends BaseController
{

    private IRestaurantService $restaurantService;
    private IAdminPageService $adminPageService;
    private IPageSectionService $pageSectionService;
    private ProgramService $programService;
    private ReservationEmailService $reservationEmailService;
    private IUserService $userService;

    public function __construct(
        IRestaurantService $restaurantService,
        IAdminPageService $adminPageService,
        IPageSectionService $pageSectionService,
        ProgramService $programService,
        ReservationEmailService $reservationEmailService,
        IUserService $userService
    )
    {

        $this->restaurantService = $restaurantService;
        $this->adminPageService = $adminPageService;
        $this->pageSectionService = $pageSectionService;
        $this->programService = $programService;
        $this->reservationEmailService = $reservationEmailService;
        $this->userService = $userService;
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

    public function bookReservation()
    {
        $this->ensureSession();

        if ($this->isPost()) {
            $this->addReservationToProgram('ratatouille', '/yummy/ratatouille', 'Ratatouille Food & Wine');
            return;
        }

        try {
            $this->requireFields(['childCount', 'adultCount', 'adultPrice', 'childPrice', 'totalPrice']);

            $childCount = (int) $this->str('childCount');
            $adultCount = (int) $this->str('adultCount');
            $adultPrice = (float) $this->str('adultPrice');
            $childPrice = (float) $this->str('childPrice');
            $totalPrice = (float) $this->str('totalPrice');

            $restaurant = new Restaurant(
                0,           // id (auto-generated)
                0,           // eventId
                0,           // orderId
                $childCount,
                $adultCount,
                $adultPrice,
                $childPrice,
                $totalPrice
            );
            $this->restaurantService->bookReservation($restaurant);
            $this->json(['success' => true, 'message' => 'Reservation booked successfully']);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
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

            $reservation = $this->getReservationSection($pageSlug);
            if ($reservation === []) {
                $this->setErrorMessage('The reservation form is not available right now.');
                $this->redirect($fallbackUrl);
                return;
            }

            $date = trim($this->str('date'));
            $session = trim($this->str('session'));
            $adultCount = max(0, min(10, $this->int('adultCount')));
            $childCount = max(0, min(10, $this->int('childCount')));
            $specialRequests = trim($this->str('special_requests'));

            $dates = $this->normalizeOptions($reservation['date'] ?? []);
            $sessions = $this->normalizeOptions($reservation['session'] ?? []);

            if ($date === '' || $session === '' || !in_array($date, $dates, true) || !in_array($session, $sessions, true)) {
                $this->setErrorMessage('Please choose a valid date and session.');
                $this->redirect($fallbackUrl);
                return;
            }

            if (($adultCount + $childCount) < 1) {
                $this->setErrorMessage('Please choose at least one adult or child.');
                $this->redirect($fallbackUrl);
                return;
            }

            $adultPrice = round((float) ($reservation['adultPrice'] ?? 0), 2);
            $childPrice = round((float) ($reservation['kidsPrice'] ?? 0), 2);
            $totalPrice = round(($adultCount * $adultPrice) + ($childCount * $childPrice), 2);

            $guestParts = [];
            if ($adultCount > 0) {
                $guestParts[] = $adultCount . ' adult' . ($adultCount === 1 ? '' : 's');
            }
            if ($childCount > 0) {
                $guestParts[] = $childCount . ' child' . ($childCount === 1 ? '' : 'ren');
            }

            $customer = $this->getCurrentCustomerData();
            $customerName = trim((string) ($customer['first_name'] ?? '') . ' ' . (string) ($customer['last_name'] ?? ''));

            $item = [
                'type' => 'yummy-reservation',
                'title' => trim((string) ($reservation['title'] ?? 'Ratatouille Reservation')),
                'day' => $date,
                'time' => $session,
                'ticket_key' => 'restaurant-reservation',
                'ticket_title' => 'Restaurant reservation',
                'quantity' => 1,
                'unit_price' => $totalPrice,
                'selection_text' => $date . ', ' . $session,
                'ticket_summary_text' => implode(', ', $guestParts),
                'location_name' => $locationName,
                'category_label' => 'Yummy',
                'special_requests' => $specialRequests,
                'customer_name' => $customerName,
                'customer_email' => (string) ($customer['email'] ?? ''),
                'customer_phone' => (string) ($customer['phone'] ?? ''),
            ];

            $this->programService->addItem($item);

            $this->reservationEmailService->sendReservationAdded($customer, array_merge($item, [
                'adult_count' => $adultCount,
                'child_count' => $childCount,
                'adult_price' => $adultPrice,
                'child_price' => $childPrice,
                'total_price' => $totalPrice,
                'special_requests' => $specialRequests,
            ]));

            $this->setSuccessMessage('Your Ratatouille reservation was added to My Program. A confirmation email has been sent.');
            $this->redirect('/program');
        } catch (\Throwable $e) {
            $this->setErrorMessage('Your reservation could not be booked right now.');
            $this->redirect($fallbackUrl);
        }
    }

    private function getReservationSection(string $pageSlug): array
    {
        $page = $this->adminPageService->getPageBySlug($pageSlug);
        $pageId = $page->page_id ?? null;
        if ($pageId === null) {
            return [];
        }

        foreach ($this->pageSectionService->getSectionsByPageId((int) $pageId) as $section) {
            if (($section['section_type'] ?? '') === 'reservation' && !empty($section['is_published'])) {
                return $section;
            }
        }

        return [];
    }

    private function normalizeOptions(mixed $value): array
    {
        if (!is_array($value)) {
            $value = preg_split('/[,\r\n]+/', (string) $value) ?: [];
        }

        return array_values(array_filter(array_map(
            static fn(mixed $item): string => trim(strip_tags((string) $item)),
            $value
        )));
    }

    private function getCurrentCustomerData(): array
    {
        $customer = [
            'first_name' => (string) ($_SESSION['user_first_name'] ?? ''),
            'last_name' => (string) ($_SESSION['user_last_name'] ?? ''),
            'email' => (string) ($_SESSION['user_email'] ?? ''),
            'phone' => (string) ($_SESSION['user_phone'] ?? ''),
        ];

        if ($customer['email'] !== '') {
            return $customer;
        }

        $userId = $this->currentUserId();
        if ($userId === null) {
            return $customer;
        }

        $user = $this->userService->getUserById($userId);
        if ($user === null) {
            return $customer;
        }

        return [
            'first_name' => (string) $user->first_name,
            'last_name' => (string) $user->last_name,
            'email' => (string) $user->email,
            'phone' => (string) $user->phone,
        ];
    }

}