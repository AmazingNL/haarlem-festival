<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\IAdminPageService;
use App\Services\IPageSectionService;

final class YummyReservationCatalogService
{
    private const ALLOWED_SLUGS = ['ratatouille', 'bistro-toujours'];

    private IAdminPageService $adminPageService;
    private IPageSectionService $pageSectionService;

    public function __construct(IAdminPageService $adminPageService, IPageSectionService $pageSectionService)
    {
        $this->adminPageService = $adminPageService;
        $this->pageSectionService = $pageSectionService;
    }

    public function buildProgramItem(
        string $pageSlug,
        string $locationName,
        string $date,
        string $session,
        int $adultCount,
        int $childCount,
        string $specialRequests = '',
        array $customer = []
    ): array
    {
        if (!in_array($pageSlug, self::ALLOWED_SLUGS, true)) {
            throw new \InvalidArgumentException('This restaurant reservation is not recognized.');
        }

        $reservation = $this->getReservationSection($pageSlug);
        if ($reservation === []) {
            throw new \InvalidArgumentException('The reservation form is not available right now.');
        }

        $date = trim($date);
        $session = trim($session);
        $adultCount = max(0, min(10, $adultCount));
        $childCount = max(0, min(10, $childCount));
        $dates = $this->normalizeOptions($reservation['date'] ?? []);
        $sessions = $this->normalizeOptions($reservation['session'] ?? []);

        if ($date === '' || $session === '' || !in_array($date, $dates, true) || !in_array($session, $sessions, true)) {
            throw new \InvalidArgumentException('Please choose a valid date and session.');
        }

        if (($adultCount + $childCount) < 1) {
            throw new \InvalidArgumentException('Please choose at least one adult or child.');
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

        $locationName = trim($locationName);
        if ($locationName === '') {
            $locationName = $pageSlug === 'bistro-toujours' ? 'Bistro Toujours' : 'Ratatouille Food & Wine';
        }

        $customerName = trim((string) ($customer['first_name'] ?? '') . ' ' . (string) ($customer['last_name'] ?? ''));
        if ($customerName === '' && trim((string) ($customer['name'] ?? '')) !== '') {
            $customerName = trim((string) $customer['name']);
        }

        return [
            'type' => 'yummy-reservation',
            'page_slug' => $pageSlug,
            'title' => trim((string) ($reservation['title'] ?? 'Restaurant Reservation')),
            'day' => $date,
            'time' => $session,
            'ticket_key' => 'restaurant-reservation',
            'ticket_title' => 'Restaurant reservation',
            'quantity' => 1,
            'unit_price' => $totalPrice,
            'total_price' => $totalPrice,
            'selection_text' => $date . ', ' . $session,
            'ticket_summary_text' => implode(', ', $guestParts),
            'location_name' => $locationName,
            'category_label' => 'Yummy',
            'special_requests' => trim($specialRequests),
            'customer_name' => $customerName,
            'customer_email' => trim((string) ($customer['email'] ?? '')),
            'customer_phone' => trim((string) ($customer['phone'] ?? '')),
            'adult_count' => $adultCount,
            'child_count' => $childCount,
            'adult_price' => $adultPrice,
            'child_price' => $childPrice,
        ];
    }

    public function validateProgramItem(array $item): array
    {
        $pageSlug = trim((string) ($item['page_slug'] ?? ''));
        if ($pageSlug === '') {
            $pageSlug = $this->inferPageSlug((string) ($item['location_name'] ?? ''));
        }

        $built = $this->buildProgramItem(
            $pageSlug,
            trim((string) ($item['location_name'] ?? '')),
            trim((string) ($item['day'] ?? '')),
            trim((string) ($item['time'] ?? '')),
            max(0, (int) ($item['adult_count'] ?? 0)),
            max(0, (int) ($item['child_count'] ?? 0)),
            trim((string) ($item['special_requests'] ?? '')),
            [
                'first_name' => trim((string) ($item['customer_name'] ?? '')),
                'email' => trim((string) ($item['customer_email'] ?? '')),
                'phone' => trim((string) ($item['customer_phone'] ?? '')),
            ]
        );

        $built['id'] = trim((string) ($item['id'] ?? ''));

        return $built;
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

    private function inferPageSlug(string $locationName): string
    {
        $normalized = strtolower($locationName);

        if (str_contains($normalized, 'bistro')) {
            return 'bistro-toujours';
        }

        return 'ratatouille';
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
}
