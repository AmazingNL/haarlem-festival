<?php
$heroImage = trim((string) ($s['hero_image'] ?? ''));
$heroStyle = $heroImage !== ''
    ? "background-image: linear-gradient(180deg, rgba(249, 235, 235, 0.05) 0%, rgba(249, 235, 235, 0.55) 55%, rgba(249, 235, 235, 0.92) 100%), url('" . htmlspecialchars($heroImage, ENT_QUOTES, 'UTF-8') . "');"
    : '';
?>

<section class="history-st-bavo-hero" <?= $heroStyle !== '' ? 'style="' . $heroStyle . '"' : '' ?>>
    <div class="history-container history-st-bavo-hero__inner">
        <?php if (!empty($s['back_text'])): ?>
            <a class="history-st-bavo-hero__back" href="<?= $historyUrl($s['back_link'], '/history') ?>">
                <?= htmlspecialchars((string) $s['back_text'], ENT_QUOTES, 'UTF-8') ?>
            </a>
        <?php endif; ?>

        <?php if (!empty($s['eyebrow'])): ?>
            <p class="history-st-bavo-hero__eyebrow"><?= htmlspecialchars((string) $s['eyebrow'], ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <h1 class="history-st-bavo-hero__title"><?= htmlspecialchars((string) ($s['heading'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h1>

        <?php if (!empty($s['subtitle'])): ?>
            <p class="history-st-bavo-hero__subtitle"><?= htmlspecialchars((string) $s['subtitle'], ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
    </div>
</section>
