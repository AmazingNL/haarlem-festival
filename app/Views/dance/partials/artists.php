<?php
$artists = is_array($artists ?? null) ? array_values($artists) : [];

$escapeArtist = static fn(mixed $value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$artistsKicker = trim((string) ($artistsKicker ?? 'Line-up'));
$artistsTitle = trim((string) ($artistsTitle ?? 'Meet the Artists'));
$artistsIntro = trim((string) ($artistsIntro ?? 'Explore the DJs shaping the Dance weekend and jump into their latest festival session.'));
?>

<section class="dance-artists" id="dance-artists" aria-labelledby="dance-artists-title">
    <div class="dance-artists__header">
        <p class="dance-kicker"><?= $escapeArtist($artistsKicker !== '' ? $artistsKicker : 'Line-up') ?></p>
        <h2 id="dance-artists-title"><?= $escapeArtist($artistsTitle !== '' ? $artistsTitle : 'Meet the Artists') ?></h2>
        <p><?= $escapeArtist($artistsIntro !== '' ? $artistsIntro : 'Explore the DJs shaping the Dance weekend and jump into their latest festival session.') ?></p>
    </div>

    <?php if ($artists === []): ?>
        <article class="dance-empty-state">
            <h3>No artists found</h3>
            <p>The Dance artist line-up is not available yet. Please check back later.</p>
        </article>
    <?php else: ?>
        <div class="dance-artist-grid">
            <?php foreach ($artists as $artist): ?>
                <?php
                $slug = preg_replace('/[^a-z0-9_-]/', '', (string) ($artist['slug'] ?? '')) ?? '';
                $cardId = $slug !== '' ? 'artist-' . $slug : '';
                $imagePath = trim((string) ($artist['image_path'] ?? ''));
                if ($imagePath === '') {
                    $imagePath = '/assets/images/home/home-dance.jpg';
                }
                ?>
                <article
                    class="dance-artist-card"
                    <?= $cardId !== '' ? 'id="' . $escapeArtist($cardId) . '"' : '' ?>>
                    <div class="dance-artist-card__image">
                        <img
                            src="<?= $escapeArtist($imagePath) ?>"
                            alt="<?= $escapeArtist($artist['image_alt'] ?? '') ?>"
                            loading="lazy">
                    </div>

                    <div class="dance-artist-card__body">
                        <p class="dance-artist-card__genre">
                            <?= $escapeArtist($artist['genre'] ?? 'Dance') ?>
                        </p>
                        <h3><?= $escapeArtist($artist['name'] ?? 'Dance Artist') ?></h3>
                        <p class="dance-artist-card__description">
                            <?= $escapeArtist($artist['short_description'] ?? '') ?>
                        </p>

                        <div class="dance-artist-card__latest">
                            <span>Latest set</span>
                            <strong><?= $escapeArtist($artist['latest_session_title'] ?? 'Latest session to be announced') ?></strong>
                            <small>
                                <?= $escapeArtist($artist['latest_session_time'] ?? 'Date to be announced') ?>
                                <?php if (!empty($artist['latest_session_venue'])): ?>
                                    &middot; <?= $escapeArtist($artist['latest_session_venue']) ?>
                                <?php endif; ?>
                            </small>
                        </div>

                        <a
                            class="dance-artist-card__link"
                            href="<?= $escapeArtist($artist['view_href'] ?? '/dance#dance-artists') ?>">
                            View Artist
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
