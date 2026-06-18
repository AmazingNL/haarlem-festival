<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Services\Implementations\Booking\StoriesBookingService;
use App\Services\Implementations\ProgramService;
use App\Services\Interfaces\IStoriesService;

final class StoriesController extends BaseController
{
    public function __construct(
        private IStoriesService $storiesService,
        private StoriesBookingService $storiesBookingService,
        private ProgramService $programService
    ) {
    }

    public function index(): void
    {
        $this->rememberProgramReturnUrl($this->currentUrl());

        try {
            $sections = $this->storiesService->getPageSections('stories');
            if ($sections === []) {
                $this->setFlash('error', 'Stories page is not available.');
                $this->redirect('/');
                return;
            }

            $this->view('stories/index', ['section' => $sections, 'title' => 'Stories']);
        } catch (\Throwable) {
            $this->view('no_page/index', ['error' => 'Stories page not available.']);
        }
    }

    public function detail(string $slug): void
    {
        $this->rememberProgramReturnUrl($this->currentUrl());

        $slug = trim($slug);
        if ($slug === '') {
            $this->redirect('/stories');
            return;
        }

        try {
            $show = ctype_digit($slug)
                ? $this->storiesService->getShowById((int) $slug)
                : $this->storiesService->getShowBySlug($slug);
        } catch (\Throwable) {
            $show = null;
        }

        if ($show === null) {
            $this->abort(404, 'Story not found.');
        }

        $this->view('stories/detail', [
            'show' => $show,
            'title' => htmlspecialchars((string) ($show['show_title'] ?? 'Story'), ENT_QUOTES, 'UTF-8'),
        ]);
    }

    public function addShowToProgram(): void
    {
        $this->ensureSession();
        $this->verifyCsrf();

        $showId = $this->int('show_id');
        $quantity = max(1, min(20, $this->int('quantity', 1)));
        if ($showId <= 0) {
            $this->abort(422, 'Invalid show selection.');
        }

        if ($this->storiesService->getShowById($showId) === null) {
            $this->setFlash('error', 'This show is no longer available.');
            $this->redirect('/stories');
            return;
        }

        $this->programService->addItem(
            $this->storiesBookingService->buildProgramItem($showId, $quantity)
        );
        $this->setFlash('success', 'The show was added to My Program.');
        $this->redirect('/program');
    }
}
