<?php
$sections = is_array($sections ?? null) ? array_values($sections) : [];
$events = is_array($events ?? null) ? array_values($events) : [];
$artists = is_array($artists ?? null) ? array_values($artists) : [];
$danceFilters = is_array($danceFilters ?? null) ? $danceFilters : [];
$danceFilterOptions = is_array($danceFilterOptions ?? null) ? $danceFilterOptions : [];
$hasActiveDanceFilters = !empty($hasActiveDanceFilters);
$hasCmsContent = !empty($hasCmsContent);

$text = static function (mixed $value, string $default = ''): string {
    $value = trim(strip_tags((string) $value));
    return $value !== '' ? $value : $default;
};

$imageFrom = static function (mixed $value): string {
    if (is_array($value)) {
        $first = $value[0] ?? '';
        if (is_array($first)) {
            return trim((string) ($first['src'] ?? ''));
        }

        return trim((string) $first);
    }

    return trim((string) $value);
};

$sectionCustomClass = static function (array $section): string {
    return trim((string) ($section['custom_class'] ?? ''));
};

$introCustomClasses = [
    'dance-artists-intro',
    'dance-filters-intro',
    'dance-tickets-intro',
    'dance-practical-info',
];

$findSection = static function (array $types, array $excludedCustomClasses = []) use ($sections, $sectionCustomClass): array {
    foreach ($sections as $section) {
        if (in_array((string) ($section['section_type'] ?? ''), $types, true)) {
            if (in_array($sectionCustomClass($section), $excludedCustomClasses, true)) {
                continue;
            }

            return $section;
        }
    }

    return [];
};

$findSectionByCustomClass = static function (string $customClass) use ($sections, $sectionCustomClass): array {
    foreach ($sections as $section) {
        if (($section['section_type'] ?? '') === 'text_block' && $sectionCustomClass($section) === $customClass) {
            return $section;
        }
    }

    return [];
};

$introFromSection = static function (
    array $section,
    string $fallbackKicker,
    string $fallbackTitle,
    string $fallbackIntro
) use ($text): array {
    return [
        'kicker' => $text($section['sub_title'] ?? $section['eyebrow'] ?? '', $fallbackKicker),
        'title' => $text($section['title'] ?? $section['heading'] ?? '', $fallbackTitle),
        'intro' => $text($section['article'] ?? $section['intro'] ?? $section['introduction'] ?? $section['text'] ?? '', $fallbackIntro),
    ];
};

$heroSection = $findSection(['hero', 'welcome_banner']);
$welcomeSection = $findSection(['text_block', 'feature', 'welcome_banner'], $introCustomClasses);
$artistsIntroSection = $findSectionByCustomClass('dance-artists-intro');
$filtersIntroSection = $findSectionByCustomClass('dance-filters-intro');
$ticketsIntroSection = $findSectionByCustomClass('dance-tickets-intro');
$practicalInfoSection = $findSectionByCustomClass('dance-practical-info');

$heroTitle = $text(
    $heroSection['heading'] ?? $heroSection['title'] ?? $heroSection['title_line_two'] ?? '',
    'Dance!'
);
$heroEyebrow = $text(
    $heroSection['eyebrow'] ?? $heroSection['title_line_one'] ?? '',
    'Haarlem Festival'
);
$heroImage = $imageFrom($heroSection['hero_image'] ?? $heroSection['section_image'] ?? '');
if ($heroImage === '') {
    $heroImage = '/assets/images/home/home-dance.jpg';
}

$welcomeTitle = $text(
    $welcomeSection['heading'] ?? $welcomeSection['title'] ?? '',
    'Welcome to Dance!'
);
$welcomeBody = $text(
    $welcomeSection['article'] ?? $welcomeSection['intro'] ?? $welcomeSection['introduction'] ?? $welcomeSection['text'] ?? $welcomeSection['sub_title'] ?? '',
    'The energy of Haarlem Festival comes alive with DJs, vibrant venues, and unforgettable nights full of music, movement, and atmosphere. Explore the artists, discover the venues, and get ready to plan your festival weekend.'
);

$artistsIntroContent = $introFromSection(
    $artistsIntroSection,
    'Line-up',
    'Meet the Artists',
    'Explore the DJs shaping the Dance weekend and jump into their latest festival session.'
);
$artistsKicker = $artistsIntroContent['kicker'];
$artistsTitle = $artistsIntroContent['title'];
$artistsIntro = $artistsIntroContent['intro'];

$filtersIntroContent = $introFromSection(
    $filtersIntroSection,
    'Find Your Session',
    'Filter Dance Sessions',
    'Use the filters to narrow the official Dance programme by date, venue, artist, or session type.'
);
$filtersKicker = $filtersIntroContent['kicker'];
$filtersTitle = $filtersIntroContent['title'];
$filtersIntro = $filtersIntroContent['intro'];

$ticketsIntroContent = $introFromSection(
    $ticketsIntroSection,
    'Tickets',
    'Dance Sessions',
    'Choose your Dance session, select a quantity, and add the tickets to My Program.'
);
$eventsKicker = $ticketsIntroContent['kicker'];
$eventsTitle = $ticketsIntroContent['title'];
$eventsIntro = $ticketsIntroContent['intro'];

$practicalInfoContent = $introFromSection(
    $practicalInfoSection,
    'Practical Info',
    'Practical Dance Info',
    ''
);
$practicalInfoKicker = $practicalInfoContent['kicker'];
$practicalInfoTitle = $practicalInfoContent['title'];
$practicalInfoBody = $practicalInfoContent['intro'];

$quickLinks = [
    ['label' => 'Artists', 'href' => '/dance#dance-artists', 'icon' => 'A'],
    ['label' => 'Filters', 'href' => '/dance#dance-filters', 'icon' => 'F'],
    ['label' => 'Tickets', 'href' => '/dance#dance-tickets', 'icon' => '+'],
];
?>

<div class="dance-page">
    <section class="dance-hero" aria-labelledby="dance-title">
        <img
            class="dance-hero__image"
            src="<?= htmlspecialchars($heroImage, ENT_QUOTES, 'UTF-8') ?>"
            alt="">
        <div class="dance-hero__overlay"></div>
        <div class="dance-hero__content">
            <p><?= htmlspecialchars($heroEyebrow, ENT_QUOTES, 'UTF-8') ?></p>
            <h1 id="dance-title"><?= htmlspecialchars($heroTitle, ENT_QUOTES, 'UTF-8') ?></h1>
        </div>
    </section>

    <nav class="dance-breadcrumb" aria-label="Breadcrumb">
        <a href="/home">Home</a>
        <span aria-hidden="true">&gt;</span>
        <span>Dance</span>
    </nav>

    <section class="dance-welcome" aria-labelledby="dance-welcome-title">
        <p class="dance-kicker">Quick Links</p>
        <h2 id="dance-welcome-title"><?= htmlspecialchars($welcomeTitle, ENT_QUOTES, 'UTF-8') ?></h2>
        <p><?= htmlspecialchars($welcomeBody, ENT_QUOTES, 'UTF-8') ?></p>

        <div class="dance-quick-links" aria-label="Dance quick links">
            <?php foreach ($quickLinks as $index => $quickLink): ?>
                <a
                    class="dance-quick-link<?= $index === 0 ? ' dance-quick-link--primary' : '' ?>"
                    href="<?= htmlspecialchars($quickLink['href'], ENT_QUOTES, 'UTF-8') ?>">
                    <span class="dance-quick-link__icon" aria-hidden="true"><?= htmlspecialchars($quickLink['icon'], ENT_QUOTES, 'UTF-8') ?></span>
                    <span><?= htmlspecialchars($quickLink['label'], ENT_QUOTES, 'UTF-8') ?></span>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if (!$hasCmsContent): ?>
            <p class="dance-fallback-note">
                Dance CMS content can be added later. This starter landing page is shown until the Dance page exists in the CMS.
            </p>
        <?php endif; ?>
    </section>

    <?php if ($practicalInfoSection !== []): ?>
        <section class="dance-welcome dance-practical-info" aria-labelledby="dance-practical-info-title">
            <p class="dance-kicker"><?= htmlspecialchars($practicalInfoKicker, ENT_QUOTES, 'UTF-8') ?></p>
            <h2 id="dance-practical-info-title"><?= htmlspecialchars($practicalInfoTitle, ENT_QUOTES, 'UTF-8') ?></h2>
            <?php if ($practicalInfoBody !== ''): ?>
                <p><?= htmlspecialchars($practicalInfoBody, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>
        </section>
    <?php endif; ?>

    <?php require __DIR__ . '/partials/artists.php'; ?>
    <?php require __DIR__ . '/partials/filters.php'; ?>
    <?php if ($hasActiveDanceFilters): ?>
        <?php
        $eventsEmptyTitle = 'No Dance sessions match your filters';
        $eventsEmptyText = 'Try another date, venue, artist, or session type to find more Dance sessions.';
        ?>
    <?php endif; ?>
    <?php require __DIR__ . '/partials/events.php'; ?>
</div>
