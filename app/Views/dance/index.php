<?php
$events = is_array($events ?? null) ? array_values($events) : [];
$artists = is_array($artists ?? null) ? array_values($artists) : [];
$danceFilters = is_array($danceFilters ?? null) ? $danceFilters : [];
$danceFilterOptions = is_array($danceFilterOptions ?? null) ? $danceFilterOptions : [];
$hasActiveDanceFilters = !empty($hasActiveDanceFilters);
$hasCmsContent = !empty($hasCmsContent);
$quickLinks = is_array($quickLinks ?? null) ? array_values($quickLinks) : [];
$practicalInfoSection = is_array($practicalInfoSection ?? null) ? $practicalInfoSection : [];
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
    <?php require __DIR__ . '/partials/events.php'; ?>
</div>
