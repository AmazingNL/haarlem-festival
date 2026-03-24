<?php
$sections = $sections ?? [];
?>

<div class="dance-page">
    <?php foreach ($sections as $section): ?>
        <?php
        $type = $section['section_type'] ?? '';
        $title = $section['title'] ?? '';
        $contentText = $section['content'] ?? '';
        $buttonText = $section['button_text'] ?? '';
        $buttonLink = $section['button_link'] ?? '#';
        $imagePath = $section['image_path'] ?? '';
        $imageAlt = $section['image_alt'] ?? $title;
        ?>

        <?php if ($type === 'hero'): ?>
            <section class="dance-hero">
                <?php if (!empty($imagePath)): ?>
                    <div class="dance-hero__bg">
                        <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($imageAlt) ?>">
                    </div>
                <?php endif; ?>

                <div class="dance-container dance-hero__content">
                    <h1><?= htmlspecialchars($title) ?></h1>

                    <div class="dance-richtext">
                        <?= $contentText ?>
                    </div>

                    <?php if (!empty($buttonText)): ?>
                        <a href="<?= htmlspecialchars($buttonLink) ?>" class="dance-btn">
                            <?= htmlspecialchars($buttonText) ?>
                        </a>
                    <?php endif; ?>
                </div>
            </section>

        <?php elseif ($type === 'text_block'): ?>
            <section class="dance-intro">
                <div class="dance-container">
                    <h2><?= htmlspecialchars($title) ?></h2>

                    <div class="dance-richtext">
                        <?= $contentText ?>
                    </div>
                </div>
            </section>

        <?php elseif ($type === 'artists'): ?>
            <?php $artists = json_decode($contentText, true) ?: []; ?>
            <section class="dance-artists">
                <div class="dance-container">
                    <div class="dance-section-heading">
                        <h2><?= htmlspecialchars($title) ?></h2>
                    </div>

                    <div class="dance-artists__grid">
                        <?php foreach ($artists as $artist): ?>
                            <article class="dance-artist-card">
                                <div class="dance-artist-card__image">
                                    <img
                                        src="<?= htmlspecialchars($artist['image'] ?? '/assets/images/artist-placeholder.jpg') ?>"
                                        alt="<?= htmlspecialchars($artist['name'] ?? 'Artist') ?>"
                                    >
                                </div>

                                <h3><?= htmlspecialchars($artist['name'] ?? '') ?></h3>
                                <p><?= htmlspecialchars($artist['description'] ?? '') ?></p>

                                <?php if (!empty($artist['link'])): ?>
                                    <a href="<?= htmlspecialchars($artist['link']) ?>" class="dance-link">
                                        View artist
                                    </a>
                                <?php endif; ?>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

        <?php elseif ($type === 'events'): ?>
            <?php $events = json_decode($contentText, true) ?: []; ?>
            <section class="dance-events">
                <div class="dance-container">
                    <div class="dance-section-heading">
                        <h2><?= htmlspecialchars($title) ?></h2>
                    </div>

                    <div class="dance-events__list">
                        <?php foreach ($events as $event): ?>
                            <article class="dance-event-card">
                                <div class="dance-event-card__image">
                                    <img
                                        src="<?= htmlspecialchars($event['image'] ?? '/assets/images/event-placeholder.jpg') ?>"
                                        alt="<?= htmlspecialchars($event['title'] ?? 'Dance event') ?>"
                                    >
                                </div>

                                <div class="dance-event-card__content">
                                    <h3><?= htmlspecialchars($event['title'] ?? '') ?></h3>

                                    <?php if (!empty($event['location'])): ?>
                                        <p class="dance-event-card__meta">
                                            <?= htmlspecialchars($event['location']) ?>
                                        </p>
                                    <?php endif; ?>

                                    <?php if (!empty($event['date'])): ?>
                                        <p class="dance-event-card__meta">
                                            <?= htmlspecialchars($event['date']) ?>
                                        </p>
                                    <?php endif; ?>

                                    <p><?= htmlspecialchars($event['description'] ?? '') ?></p>

                                    <?php if (!empty($event['link'])): ?>
                                        <a href="<?= htmlspecialchars($event['link']) ?>" class="dance-btn dance-btn--dark">
                                            Read more
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

        <?php elseif ($type === 'tickets'): ?>
            <?php $tickets = json_decode($contentText, true) ?: []; ?>
            <section class="dance-tickets">
                <div class="dance-container">
                    <div class="dance-section-heading">
                        <h2><?= htmlspecialchars($title) ?></h2>
                    </div>

                    <div class="dance-tickets__grid">
                        <?php foreach ($tickets as $ticket): ?>
                            <article class="dance-ticket-card">
                                <h3><?= htmlspecialchars($ticket['name'] ?? '') ?></h3>

                                <?php if (!empty($ticket['price'])): ?>
                                    <p class="dance-ticket-card__price">
                                        <?= htmlspecialchars($ticket['price']) ?>
                                    </p>
                                <?php endif; ?>

                                <p><?= htmlspecialchars($ticket['description'] ?? '') ?></p>

                                <?php if (!empty($ticket['link'])): ?>
                                    <a href="<?= htmlspecialchars($ticket['link']) ?>" class="dance-btn">
                                        Buy now
                                    </a>
                                <?php endif; ?>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    <?php endforeach; ?>
</div>