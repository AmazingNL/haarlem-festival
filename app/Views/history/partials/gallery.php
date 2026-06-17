<?php
$galleryCards = [
    [
        'label' => $s['card_one_label'] ?? '',
        'title' => $s['card_one_title'] ?? '',
        'text' => $s['card_one_text'] ?? '',
        'image' => $s['card_one_image'] ?? '',
    ],
    [
        'label' => $s['card_two_label'] ?? '',
        'title' => $s['card_two_title'] ?? '',
        'text' => $s['card_two_text'] ?? '',
        'image' => $s['card_two_image'] ?? '',
    ],
    [
        'label' => $s['card_three_label'] ?? '',
        'title' => $s['card_three_title'] ?? '',
        'text' => $s['card_three_text'] ?? '',
        'image' => $s['card_three_image'] ?? '',
    ],
];
?>
<section class="history-section history-gallery" id="history-gallery">
    <div class="history-container">
        <div class="history-section__heading history-section__heading--center history-section__heading--light">
            <?php if (!empty($s['eyebrow'])): ?>
                <p class="history-eyebrow"><?= htmlspecialchars((string) $s['eyebrow'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>
            <h2><?= htmlspecialchars((string) ($s['heading'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h2>
        </div>

        <div class="history-gallery__grid">
            <?php foreach ($galleryCards as $card): ?>
                <article class="history-gallery__card">
                    <img src="<?= htmlspecialchars((string) $card['image'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars((string) $card['title'], ENT_QUOTES, 'UTF-8') ?>">
                    <div class="history-gallery__card-body">
                        <p class="history-gallery__label"><?= htmlspecialchars((string) $card['label'], ENT_QUOTES, 'UTF-8') ?></p>
                        <h3><?= htmlspecialchars((string) $card['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                        <p><?= $historyText($card['text']) ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
