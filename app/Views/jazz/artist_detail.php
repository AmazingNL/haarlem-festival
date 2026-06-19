<?php require __DIR__ . '/partials/_helpers.php'; ?>

<?php
// One artist page = a single "jazz_artist" editorial section plus the bookable
// "jazz_agenda_event" shows that sit on the same page. The hero name is the page title.
$sections = is_array($section ?? null) ? array_values($section) : [];

$artist = [];
$scheduleEvents = [];
foreach ($sections as $s) {
    if (empty($s['is_published'])) {
        continue;
    }
    $type = (string) ($s['section_type'] ?? '');
    if ($type === 'jazz_artist' && $artist === []) {
        $artist = $s;
    } elseif ($type === 'jazz_agenda_event') {
        $scheduleEvents[] = $s;
    }
}

$artistName = trim((string) ($page->title ?? ''));
$heroImage = $jazzImage($artist['hero_image'] ?? '');
$closing = trim((string) ($artist['closing_text'] ?? ''));
?>

<div class="jazz-page jazz-artist">
    <section class="jazz-hero jazz-artist__hero"<?= $heroImage !== '' ? ' style="background-image:url(\'' . htmlspecialchars($heroImage, ENT_QUOTES, 'UTF-8') . '\')"' : '' ?>>
        <div class="jazz-hero__overlay"></div>
        <div class="jazz-hero__inner jazz-artist__hero-inner">
            <a class="jazz-goback" href="/jazz">&#8249; Go Back</a>
            <h1 class="jazz-hero__title"><?= htmlspecialchars($artistName, ENT_QUOTES, 'UTF-8') ?></h1>
        </div>
    </section>

    <?php require __DIR__ . '/partials/artist_body.php'; ?>

    <?php if ($scheduleEvents !== [] || trim((string) ($artist['schedule_heading'] ?? '')) !== ''): ?>
        <?php require __DIR__ . '/partials/artist_schedule.php'; ?>
    <?php endif; ?>

    <?php if ($closing !== ''): ?>
        <section class="jazz-section jazz-artist__closing">
            <div class="jazz-container">
                <p class="jazz-artist__closing-text"><?= htmlspecialchars($closing, ENT_QUOTES, 'UTF-8') ?></p>
            </div>
        </section>
    <?php endif; ?>
</div>
