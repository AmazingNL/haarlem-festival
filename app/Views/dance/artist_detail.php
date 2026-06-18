<?php
$artist = is_array($artist ?? null) ? $artist : [];
$events = is_array($artist['related_events'] ?? null) ? array_values($artist['related_events']) : [];
$highlights = is_array($artist['career_highlights'] ?? null) ? array_values($artist['career_highlights']) : [];
$galleryImages = is_array($artist['gallery_images'] ?? null) ? array_values($artist['gallery_images']) : [];

$escape = static fn(mixed $value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

$artistName = trim((string) ($artist['name'] ?? 'Dance Artist'));
$artistGenre = trim((string) ($artist['genre'] ?? 'Dance'));
$artistDescription = trim((string) ($artist['short_description'] ?? ''));
$artistBiography = trim((string) ($artist['biography'] ?? ''));
$artistImage = trim((string) ($artist['image_path'] ?? ''));
if ($artistImage === '') {
    $artistImage = '/assets/images/home/home-dance.jpg';
}
$artistImageAlt = trim((string) ($artist['image_alt'] ?? $artistName . ' artist image'));
?>

<div class="dance-page dance-artist-detail">
    <section class="dance-artist-hero" aria-labelledby="artist-title">
        <img
            class="dance-artist-hero__image"
            src="<?= $escape($artistImage) ?>"
            alt="<?= $escape($artistImageAlt) ?>">
        <div class="dance-artist-hero__overlay"></div>
        <div class="dance-artist-hero__content">
            <p><?= $escape($artistGenre) ?></p>
            <h1 id="artist-title"><?= $escape($artistName) ?></h1>
            <?php if ($artistDescription !== ''): ?>
                <p class="dance-artist-hero__summary"><?= $escape($artistDescription) ?></p>
            <?php endif; ?>
        </div>
    </section>

    <nav class="dance-breadcrumb" aria-label="Breadcrumb">
        <a href="/home">Home</a>
        <span aria-hidden="true">&gt;</span>
        <a href="/dance">Dance</a>
        <span aria-hidden="true">&gt;</span>
        <span><?= $escape($artistName) ?></span>
    </nav>

    <section class="dance-artist-profile" aria-labelledby="artist-profile-title">
        <div class="dance-artist-profile__copy">
            <p class="dance-kicker">Artist Profile</p>
            <h2 id="artist-profile-title">Biography</h2>
            <?php if ($artistBiography !== ''): ?>
                <p><?= $escape($artistBiography) ?></p>
            <?php else: ?>
                <p>Biography details for this artist are not available yet.</p>
            <?php endif; ?>
        </div>

        <aside class="dance-artist-profile__latest" aria-label="Latest set">
            <span>Latest set</span>
            <strong><?= $escape($artist['latest_session_title'] ?? 'Latest session to be announced') ?></strong>
            <small>
                <?= $escape($artist['latest_session_time'] ?? 'Date to be announced') ?>
                <?php if (!empty($artist['latest_session_venue'])): ?>
                    &middot; <?= $escape($artist['latest_session_venue']) ?>
                <?php endif; ?>
            </small>
        </aside>
    </section>

    <?php if ($highlights !== []): ?>
        <section class="dance-artist-highlights" aria-labelledby="artist-highlights-title">
            <p class="dance-kicker">Career</p>
            <h2 id="artist-highlights-title">Career Highlights</h2>
            <ul>
                <?php foreach ($highlights as $highlight): ?>
                    <li><?= $escape($highlight) ?></li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>

    <?php if ($galleryImages !== []): ?>
        <section class="dance-artist-gallery" aria-labelledby="artist-gallery-title">
            <p class="dance-kicker">Gallery</p>
            <h2 id="artist-gallery-title">Artist Gallery</h2>
            <div class="dance-artist-gallery__grid">
                <?php foreach ($galleryImages as $image): ?>
                    <?php
                    $src = trim((string) ($image['src'] ?? ''));
                    if ($src === '') {
                        continue;
                    }
                    ?>
                    <img
                        src="<?= $escape($src) ?>"
                        alt="<?= $escape($image['alt'] ?? 'Dance artist gallery image') ?>"
                        loading="lazy">
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php
    $eventsSectionId = 'artist-related-sessions';
    $eventsKicker = 'Sessions';
    $eventsTitle = 'Related Dance Sessions';
    $eventsIntro = 'Choose a related session, select a quantity, and add the tickets to My Program.';
    $eventsEmptyTitle = 'No related sessions found';
    $eventsEmptyText = 'Related Dance sessions for this artist are not available yet.';
    require __DIR__ . '/partials/events.php';
    ?>
</div>
