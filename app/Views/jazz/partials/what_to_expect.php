<?php
$image = $jazzImage($s['section_image'] ?? '');
$buttonText = trim((string) ($s['button_text'] ?? ''));
$buttonLink = trim((string) ($s['button_link'] ?? ''));
?>
<section class="jazz-section jazz-expect">
    <div class="jazz-container jazz-expect__grid">
        <div class="jazz-expect__text">
            <h2 class="jazz-section__title"><?= htmlspecialchars((string) ($s['heading'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h2>
            <?php if (!empty($s['body'])): ?>
                <div class="jazz-rich-text"><?= \App\Support\Html::clean((string) $s['body']) ?></div>
            <?php endif; ?>
            <?php if ($buttonText !== ''): ?>
                <a class="jazz-button" href="<?= $jazzUrl($buttonLink, '#agenda') ?>">
                    <?= htmlspecialchars($buttonText, ENT_QUOTES, 'UTF-8') ?>
                </a>
            <?php endif; ?>
        </div>
        <?php if ($image !== ''): ?>
            <div class="jazz-expect__media">
                <img src="<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>"
                    alt="<?= htmlspecialchars((string) ($s['heading'] ?? 'What to expect'), ENT_QUOTES, 'UTF-8') ?>">
            </div>
        <?php endif; ?>
    </div>
</section>
