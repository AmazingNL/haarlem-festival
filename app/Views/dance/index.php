<?php
$sections = is_array($sections ?? null) ? array_values($sections) : [];
$events = is_array($events ?? null) ? array_values($events) : [];
$artists = is_array($artists ?? null) ? array_values($artists) : [];
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

$findSection = static function (array $types) use ($sections): array {
    foreach ($sections as $section) {
        if (in_array((string) ($section['section_type'] ?? ''), $types, true)) {
            return $section;
        }
    }

    return [];
};

$heroSection = $findSection(['hero', 'welcome_banner']);
$welcomeSection = $findSection(['text_block', 'feature', 'welcome_banner']);

$heroTitle = $text(
    $heroSection['title'] ?? $heroSection['title_line_two'] ?? '',
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
    $welcomeSection['intro'] ?? $welcomeSection['introduction'] ?? $welcomeSection['text'] ?? '',
    'The energy of Haarlem Festival comes alive with DJs, vibrant venues, and unforgettable nights full of music, movement, and atmosphere. Explore the artists, discover the venues, and get ready to plan your festival weekend.'
);

$quickLinks = [
    ['label' => 'Artists', 'href' => '/dance#dance-artists', 'icon' => 'A'],
    ['label' => 'Venues', 'href' => '/dance#dance-venues', 'icon' => 'V'],
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

    <?php require __DIR__ . '/partials/artists.php'; ?>
    <?php require __DIR__ . '/partials/events.php'; ?>
</div>
