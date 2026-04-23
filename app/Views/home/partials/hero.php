<?php
$heroImage = trim((string) ($s['hero_image'] ?? ''));
$heroImageAlt = trim((string) ($s['hero_image_alt'] ?? 'Discover Haarlem'));
?>

<section class="home-hero" id="home-top"<?= $heroImage !== '' ? ' style="background-image: url(\'' . htmlspecialchars($heroImage, ENT_QUOTES, 'UTF-8') . '\');"' : '' ?>>
    <div class="home-hero__overlay"></div>
    <?php if ($heroImage !== ''): ?>
        <span class="home-visually-hidden"><?= htmlspecialchars($heroImageAlt, ENT_QUOTES, 'UTF-8') ?></span>
    <?php endif; ?>

    <div class="home-container home-hero__content">
        <h1><?= htmlspecialchars((string) ($s['heading'] ?? 'Discover Haarlem'), ENT_QUOTES, 'UTF-8') ?></h1>
    </div>
</section>
