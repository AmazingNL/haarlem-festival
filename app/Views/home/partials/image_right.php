<?php
$paragraphs = $homeParagraphs($s['body'] ?? '');
?>

<section class="home-spotlight home-spotlight--soft">
    <div class="home-container home-spotlight__grid home-spotlight__grid--image-right">
        <div class="home-spotlight__content">
            <h3><?= htmlspecialchars((string) ($s['heading'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h3>
            <?php foreach ($paragraphs as $paragraph): ?>
                <p><?= $homeText($paragraph) ?></p>
            <?php endforeach; ?>

            <?php if (!empty($s['button_text'])): ?>
                <a class="home-button home-button--small" href="<?= $homeUrl($s['button_link']) ?>">
                    <?= htmlspecialchars((string) $s['button_text'], ENT_QUOTES, 'UTF-8') ?>
                </a>
            <?php endif; ?>
        </div>

        <div class="home-spotlight__double-media">
            <img src="<?= htmlspecialchars((string) ($s['image_one'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                alt="<?= htmlspecialchars((string) ($s['image_one_alt'] ?? $s['heading'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
            <img src="<?= htmlspecialchars((string) ($s['image_two'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                alt="<?= htmlspecialchars((string) ($s['image_two_alt'] ?? $s['heading'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </div>
    </div>
</section>
