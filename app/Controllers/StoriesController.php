<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Services\Interfaces\IStoriesService;

/**
 * HTTP controller for the stories feature.
 *
 * Handles the stories landing page and the add-to-program booking action.
 * Contains no business logic — all domain work is delegated to IStoriesService.
 */
final class StoriesController extends BaseController
{
    private IStoriesService $storiesService;

    /** @param IStoriesService $storiesService Provides page sections and show data. */
    public function __construct(IStoriesService $storiesService)
    {
        $this->storiesService = $storiesService;
    }

    /**
     * Render the stories landing page.
     *
     * @return void
     */
    public function index(): void
    {
        $this->rememberProgramReturnUrl($this->currentUrl());
        try {
            $section = $this->storiesService->getPageSections('stories');
            if (empty($section)) { $this->setFlash('error', 'Stories page is not available.'); $this->redirect('/'); return; }
            $this->view('/stories/index', ['section' => $section, 'title' => 'Stories']);
        } catch (\Throwable $e) {
            $this->view('no_page/index', ['error' => 'Stories page not available.']);
        }
    }

    /**
     * Render the story detail page for a single storytelling session.
     *
     * @param  string $slug URL slug or numeric section_id.
     * @return void
     */
    public function detail(string $slug): void
    {
        $this->rememberProgramReturnUrl($this->currentUrl());
        $slug = trim($slug);
        if ($slug === '') { $this->redirect('/stories'); return; }
        try {
            $show = ctype_digit($slug)
                ? $this->storiesService->getShowById((int) $slug)
                : $this->storiesService->getShowBySlug($slug);
        } catch (\Throwable $e) {
            $show = null;
        }
        if ($show === null) { $this->abort(404, 'Story not found.'); }
        $this->view('/stories/detail', [
            'show'  => $show,
            'title' => htmlspecialchars((string) ($show['show_title'] ?? 'Story'), ENT_QUOTES, 'UTF-8'),
        ]);
    }

    /**
     * Handle the booking form POST and add a show to the session program cart.
     *
     * @return void
     */
    public function addShowToProgram(): void
    {
        $this->ensureSession();
        $this->verifyCsrf();
        $showId   = $this->int('show_id');
        $quantity = max(1, min(20, $this->int('quantity', 1)));
        if ($showId <= 0) { $this->abort(422, 'Invalid show selection.'); }
        $show = $this->resolveShow($showId);
        $this->pushProgramItem($show, $showId, $quantity);
        $this->setFlash('success', 'The show was added to My Program.');
        $this->redirect('/program');
    }

    /**
     * Fetch and validate the show, redirecting away if not found.
     *
     * @param  int   $showId
     * @return array Merged show data.
     */
    private function resolveShow(int $showId): array
    {
        $show = $this->storiesService->getShowById($showId);
        if ($show !== null) {
            return $show;
        }
        $this->setFlash('error', 'This show is no longer available.');
        $this->redirect('/stories');
        return [];
    }

    /**
     * Write the built item into the session cart.
     *
     * @param  array $show
     * @param  int   $showId
     * @param  int   $quantity
     * @return void
     */
    private function pushProgramItem(array $show, int $showId, int $quantity): void
    {
        $_SESSION['program_items'] ??= [];
        $_SESSION['program_items'][] = $this->buildProgramItem($show, $showId, $quantity);
    }

    /**
     * Build the session item array for a stories show booking.
     *
     * @param  array $show
     * @param  int   $showId
     * @param  int   $quantity
     * @return array
     */
    private function buildProgramItem(array $show, int $showId, int $quantity): array
    {
        $unit = $this->parseMoney((string) ($show['price_raw'] ?? '0'));
        $t    = trim((string) ($show['show_title'] ?? 'Stories Show'));
        $loc  = trim((string) ($show['location']   ?? ''));
        $date = trim((string) ($show['date']       ?? ''));
        return [
            'id' => bin2hex(random_bytes(8)), 'type' => 'stories-show', 'show_id' => $showId,
            'title' => $t, 'ticket_title' => $t, 'category_label' => 'Stories',
            'date' => $date, 'day' => $date, 'time' => trim((string) ($show['time'] ?? '')),
            'location' => $loc, 'location_name' => $loc,
            'price' => $unit, 'quantity' => $quantity, 'total_price' => round($unit * $quantity, 2),
        ];
    }

    /**
     * Parse a price string into a float (e.g. "€10" → 10.0).
     *
     * @param  string $value Raw price string from CMS.
     * @return float
     */
    private function parseMoney(string $value): float
    {
        $raw = str_replace(',', '.', $value);
        if (!preg_match('/\d+(?:\.\d{1,2})?/', $raw, $match)) {
            return 0.0;
        }
        return (float) $match[0];
    }
}
