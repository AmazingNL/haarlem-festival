<?php
// Build up to four artist cards from the fixed slots (one_, two_, three_, four_).
$featuredCards = [];
foreach (['one', 'two', 'three', 'four'] as $slot) {
    $name = trim((string) ($s[$slot . '_name'] ?? ''));
    if ($name === '') {
        continue;
    }

    $featuredCards[] = [
        'name' => $name,
        'text' => (string) ($s[$slot . '_text'] ?? ''),
        'image' => $jazzImage($s[$slot . '_image'] ?? ''),
        'button_text' => trim((string) ($s[$slot . '_button_text'] ?? '')),
        'button_link' => (string) ($s[$slot . '_button_link'] ?? ''),
    ];
}
?>
<section class="jazz-section jazz-featured">
    <div class="jazz-container">
        <h2 class="jazz-section__title"><?= htmlspecialchars((string) ($s['heading'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h2>
        <?php if (!empty($s['intro'])): ?>
            <p class="jazz-section__intro"><?= $jazzText($s['intro']) ?></p>
        <?php endif; ?>

        <div class="jazz-featured__grid">
            <?php foreach ($featuredCards as $card): ?>
                <article class="jazz-card">
                    <?php if ($card['image'] !== ''): ?>
                        <div class="jazz-card__media">
                            <img src="<?= htmlspecialchars($card['image'], ENT_QUOTES, 'UTF-8') ?>"
                                alt="<?= htmlspecialchars($card['name'], ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                    <?php endif; ?>
                    <div class="jazz-card__body">
                        <h3 class="jazz-card__title"><?= htmlspecialchars($card['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                        <?php if (trim($card['text']) !== ''): ?>
                            <p class="jazz-card__text"><?= $jazzText($card['text']) ?></p>
                        <?php endif; ?>
                        <?php if ($card['button_text'] !== ''): ?>
                            <a class="jazz-button jazz-button--small" href="<?= $jazzUrl($card['button_link']) ?>">
                                <?= htmlspecialchars($card['button_text'], ENT_QUOTES, 'UTF-8') ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
