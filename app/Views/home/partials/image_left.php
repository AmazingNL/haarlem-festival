<?php
$paragraphs = $homeParagraphs($s['body'] ?? '');
?>

<section class="home-spotlight">
    <div class="home-container home-spotlight__grid home-spotlight__grid--image-left">
        <div class="home-spotlight__media">
            <img src="<?= htmlspecialchars((string) ($s['image'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                alt="<?= htmlspecialchars((string) ($s['image_alt'] ?? $s['heading'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </div>

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
    </div>
</section>
