<?php

namespace App\Schemas\jazz;

use App\Schemas\BaseSection;

// All the editorial content of one artist detail page (the hero name comes from
// the page title). The bookable shows are separate "jazz_agenda_event" sections
// on the same page, so the artist page reuses the existing jazz booking flow.
final class JazzArtist extends BaseSection
{
    public function __construct(string $customClass = '', int $sortOrder = 0)
    {
        parent::__construct('jazz_artist', $customClass, $sortOrder);
    }

    public function getAdminFormFields(): array
    {
        return [
            'hero_image' => ['type' => 'image', 'label' => 'Hero Background Image'],

            'intro_heading' => ['type' => 'text', 'label' => 'Intro Heading', 'required' => true],
            'intro_body' => ['type' => 'textarea', 'label' => 'Intro Text'],

            'career_heading' => ['type' => 'text', 'label' => 'Career Highlight Heading'],
            'career_body' => ['type' => 'textarea', 'label' => 'Career Highlight Text'],
            'career_image' => ['type' => 'image', 'label' => 'Career Highlight Image'],

            'works_heading' => ['type' => 'text', 'label' => 'Essential Works Heading'],
            'works_intro' => ['type' => 'textarea', 'label' => 'Essential Works Intro'],

            'album_one_title' => ['type' => 'text', 'label' => 'Album 1 — Title'],
            'album_one_text' => ['type' => 'textarea', 'label' => 'Album 1 — Description'],
            'album_one_image' => ['type' => 'image', 'label' => 'Album 1 — Cover Image'],

            'album_two_title' => ['type' => 'text', 'label' => 'Album 2 — Title'],
            'album_two_text' => ['type' => 'textarea', 'label' => 'Album 2 — Description'],
            'album_two_image' => ['type' => 'image', 'label' => 'Album 2 — Cover Image'],

            'album_three_title' => ['type' => 'text', 'label' => 'Album 3 — Title'],
            'album_three_text' => ['type' => 'textarea', 'label' => 'Album 3 — Description'],
            'album_three_image' => ['type' => 'image', 'label' => 'Album 3 — Cover Image'],

            'listen_heading' => ['type' => 'text', 'label' => 'Listen Heading'],
            'listen_intro' => ['type' => 'textarea', 'label' => 'Listen Intro'],
            'listen_track_image' => ['type' => 'image', 'label' => 'Track Image'],
            'listen_track_link' => ['type' => 'text', 'label' => 'Track Link (play/listen URL)'],
            'listen_track_title' => ['type' => 'text', 'label' => 'Track Title'],
            'listen_track_artist' => ['type' => 'text', 'label' => 'Track Artist'],
            'listen_track_album' => ['type' => 'text', 'label' => 'Track Album'],

            'schedule_heading' => ['type' => 'text', 'label' => 'Schedule Heading'],
            'schedule_intro' => ['type' => 'textarea', 'label' => 'Schedule Intro'],

            'closing_text' => ['type' => 'text', 'label' => 'Closing Tagline'],
        ];
    }
}
