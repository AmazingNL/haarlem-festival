<?php
$paragraphs = $homeParagraphs($s['article'] ?? '');
?>

<section class="home-feature">
    <div class="home-container home-feature__inner">
        <h2><?= htmlspecialchars((string) ($s['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h2>

        <div class="home-feature__card">
            <?php foreach ($paragraphs as $paragraph): ?>
                <p><?= $homeText($paragraph) ?></p>
            <?php endforeach; ?>
        </div>

        <?php if (!empty($s['button_text'])): ?>
            <a class="home-button" href="<?= $homeUrl($s['button_link'] ?? '#home-activities', '#home-activities') ?>">
                <?= htmlspecialchars((string) $s['button_text'], ENT_QUOTES, 'UTF-8') ?>
            </a>
        <?php endif; ?>
    </div>
</section>
