<?php

declare(strict_types=1);

namespace App\ViewModels\dance;

final class DancePageViewModel
{
    private const INTRO_CUSTOM_CLASSES = [
        'dance-artists-intro',
        'dance-filters-intro',
        'dance-tickets-intro',
        'dance-practical-info',
    ];

    private array $sections;

    public function __construct(private array $viewData)
    {
        $this->sections = $this->list($viewData['sections'] ?? []);
    }

    public function toArray(): array
    {
        $heroSection = $this->findSection(['hero', 'welcome_banner']);
        $welcomeSection = $this->findSection(['text_block', 'feature', 'welcome_banner'], self::INTRO_CUSTOM_CLASSES);
        $artistsIntroSection = $this->findSectionByCustomClass('dance-artists-intro');
        $filtersIntroSection = $this->findSectionByCustomClass('dance-filters-intro');
        $ticketsIntroSection = $this->findSectionByCustomClass('dance-tickets-intro');
        $practicalInfoSection = $this->findSectionByCustomClass('dance-practical-info');

        $artistsIntroContent = $this->introFromSection(
            $artistsIntroSection,
            'Line-up',
            'Meet the Artists',
            'Explore the DJs shaping the Dance weekend and jump into their latest festival session.'
        );

        $filtersIntroContent = $this->introFromSection(
            $filtersIntroSection,
            'Find Your Session',
            'Filter Dance Sessions',
            'Use the filters to narrow the official Dance programme by date, venue, artist, or session type.'
        );

        $ticketsIntroContent = $this->introFromSection(
            $ticketsIntroSection,
            'Tickets',
            'Dance Sessions',
            'Choose your Dance session, select a quantity, and add the tickets to My Program.'
        );

        $practicalInfoContent = $this->introFromSection(
            $practicalInfoSection,
            'Practical Info',
            'Practical Dance Info',
            ''
        );

        $heroImage = $this->imageFrom($heroSection['hero_image'] ?? $heroSection['section_image'] ?? '');
        if ($heroImage === '') {
            $heroImage = '/assets/images/home/home-dance.jpg';
        }

        $hasActiveDanceFilters = !empty($this->viewData['hasActiveDanceFilters']);

        $prepared = $this->viewData;
        $prepared['sections'] = $this->sections;
        $prepared['events'] = $this->list($prepared['events'] ?? []);
        $prepared['artists'] = $this->list($prepared['artists'] ?? []);
        $prepared['danceFilters'] = is_array($prepared['danceFilters'] ?? null) ? $prepared['danceFilters'] : [];
        $prepared['danceFilterOptions'] = is_array($prepared['danceFilterOptions'] ?? null) ? $prepared['danceFilterOptions'] : [];
        $prepared['hasActiveDanceFilters'] = $hasActiveDanceFilters;
        $prepared['hasCmsContent'] = !empty($prepared['hasCmsContent']);

        return array_merge($prepared, [
            'heroTitle' => $this->text(
                $heroSection['heading'] ?? $heroSection['title'] ?? $heroSection['title_line_two'] ?? '',
                'Dance!'
            ),
            'heroEyebrow' => $this->text(
                $heroSection['eyebrow'] ?? $heroSection['title_line_one'] ?? '',
                'Haarlem Festival'
            ),
            'heroImage' => $heroImage,
            'welcomeTitle' => $this->text(
                $welcomeSection['heading'] ?? $welcomeSection['title'] ?? '',
                'Welcome to Dance!'
            ),
            'welcomeBody' => $this->text(
                $welcomeSection['article'] ?? $welcomeSection['intro'] ?? $welcomeSection['introduction'] ?? $welcomeSection['text'] ?? $welcomeSection['sub_title'] ?? '',
                'The energy of Haarlem Festival comes alive with DJs, vibrant venues, and unforgettable nights full of music, movement, and atmosphere. Explore the artists, discover the venues, and get ready to plan your festival weekend.'
            ),
            'artistsKicker' => $artistsIntroContent['kicker'],
            'artistsTitle' => $artistsIntroContent['title'],
            'artistsIntro' => $artistsIntroContent['intro'],
            'filtersKicker' => $filtersIntroContent['kicker'],
            'filtersTitle' => $filtersIntroContent['title'],
            'filtersIntro' => $filtersIntroContent['intro'],
            'eventsKicker' => $ticketsIntroContent['kicker'],
            'eventsTitle' => $ticketsIntroContent['title'],
            'eventsIntro' => $ticketsIntroContent['intro'],
            'eventsEmptyTitle' => $hasActiveDanceFilters ? 'No Dance sessions match your filters' : 'No Dance events found',
            'eventsEmptyText' => $hasActiveDanceFilters
                ? 'Try another date, venue, artist, or session type to find more Dance sessions.'
                : 'Dance events are not available yet. Please check back later.',
            'practicalInfoSection' => $practicalInfoSection,
            'practicalInfoKicker' => $practicalInfoContent['kicker'],
            'practicalInfoTitle' => $practicalInfoContent['title'],
            'practicalInfoBody' => $practicalInfoContent['intro'],
            'quickLinks' => [
                ['label' => 'Artists', 'href' => '/dance#dance-artists', 'icon' => 'A'],
                ['label' => 'Filters', 'href' => '/dance#dance-filters', 'icon' => 'F'],
                ['label' => 'Tickets', 'href' => '/dance#dance-tickets', 'icon' => '+'],
            ],
        ]);
    }

    private function findSection(array $types, array $excludedCustomClasses = []): array
    {
        foreach ($this->sections as $section) {
            if (!in_array((string) ($section['section_type'] ?? ''), $types, true)) {
                continue;
            }

            if (in_array($this->sectionCustomClass($section), $excludedCustomClasses, true)) {
                continue;
            }

            return $section;
        }

        return [];
    }

    private function findSectionByCustomClass(string $customClass): array
    {
        foreach ($this->sections as $section) {
            if (($section['section_type'] ?? '') === 'text_block' && $this->sectionCustomClass($section) === $customClass) {
                return $section;
            }
        }

        return [];
    }

    private function introFromSection(
        array $section,
        string $fallbackKicker,
        string $fallbackTitle,
        string $fallbackIntro
    ): array {
        return [
            'kicker' => $this->text($section['sub_title'] ?? $section['eyebrow'] ?? '', $fallbackKicker),
            'title' => $this->text($section['title'] ?? $section['heading'] ?? '', $fallbackTitle),
            'intro' => $this->text($section['article'] ?? $section['intro'] ?? $section['introduction'] ?? $section['text'] ?? '', $fallbackIntro),
        ];
    }

    private function text(mixed $value, string $default = ''): string
    {
        $value = trim(strip_tags((string) $value));

        return $value !== '' ? $value : $default;
    }

    private function imageFrom(mixed $value): string
    {
        if (is_array($value)) {
            $first = $value[0] ?? '';
            if (is_array($first)) {
                return trim((string) ($first['src'] ?? ''));
            }

            return trim((string) $first);
        }

        return trim((string) $value);
    }

    private function sectionCustomClass(array $section): string
    {
        return trim((string) ($section['custom_class'] ?? ''));
    }

    private function list(mixed $value): array
    {
        return is_array($value) ? array_values($value) : [];
    }
}
