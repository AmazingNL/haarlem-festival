<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Services\IAdminPageService;
use App\Services\IPageSectionService;
use App\Services\IRestaurantService;
use App\Models\Restaurant;
use App\ViewModels\yummy\BookReservation;

final class YummyController extends BaseController
{

    private IRestaurantService $restaurantService;
    private IAdminPageService $adminPageService;
    private IPageSectionService $pageSectionService;

    public function __construct(
        IRestaurantService $restaurantService,
        IAdminPageService $adminPageService,
        IPageSectionService $pageSectionService
    )
    {

        $this->restaurantService = $restaurantService;
        $this->adminPageService = $adminPageService;
        $this->pageSectionService = $pageSectionService;
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
                ['section' => $pageSection, 'page' => $page, 'title' => 'Yummy']
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
        try {
            $page = $this->adminPageService->getPageBySlug('ratatouille');
            $page_id = $page->page_id ?? null;
            $ratatouille = $this->pageSectionService->getSectionsByPageId($page_id);
            if (empty($ratatouille)) {
                $this->setFlash('error', 'page does not exist');
                $this->redirect('/yummy');
            }
            $this->view(
                'yummy/ratatouille/index',
                ['section' => $ratatouille, 'page' => $page]
            );

        } catch (\Exception $e) {
            $this->view(
                template: 'no_page/index',
                data: ['error' => 'ratatouille page not available']
            );

        }
    }
    public function bookReservation()
    {
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

}