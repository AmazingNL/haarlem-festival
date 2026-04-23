<?php
$featuredCards = [
    [
        'label' => $s['one_label'] ?? '',
        'title' => $s['one_title'] ?? '',
        'text' => $s['one_text'] ?? '',
        'badge' => $s['one_badge'] ?? '',
        'image' => $s['one_image'] ?? '',
        'button_text' => $s['one_button_text'] ?? '',
        'button_link' => $s['one_button_link'] ?? '',
        'features' => [
            $s['one_feature_one'] ?? '',
            $s['one_feature_two'] ?? '',
            $s['one_feature_three'] ?? '',
            $s['one_feature_four'] ?? '',
        ],
    ],
    [
        'label' => $s['two_label'] ?? '',
        'title' => $s['two_title'] ?? '',
        'text' => $s['two_text'] ?? '',
        'badge' => $s['two_badge'] ?? '',
        'image' => $s['two_image'] ?? '',
        'button_text' => $s['two_button_text'] ?? '',
        'button_link' => $s['two_button_link'] ?? '',
        'features' => [
            $s['two_feature_one'] ?? '',
            $s['two_feature_two'] ?? '',
            $s['two_feature_three'] ?? '',
            $s['two_feature_four'] ?? '',
        ],
    ],
];
?>
<section class="history-section history-featured" id="history-featured">
    <div class="history-container">
        <div class="history-section__heading history-section__heading--center">
            <?php if (!empty($s['eyebrow'])): ?>
                <p class="history-eyebrow"><?= htmlspecialchars((string) $s['eyebrow'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>
            <h2><?= htmlspecialchars((string) ($s['heading'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h2>
            <?php if (!empty($s['intro'])): ?>
                <p class="history-section__intro"><?= $historyText($s['intro']) ?></p>
            <?php endif; ?>
        </div>

        <div class="history-featured__list">
            <?php foreach ($featuredCards as $card): ?>
                <article class="history-featured__card">
                    <div class="history-featured__image-wrap">
                        <img src="<?= htmlspecialchars((string) $card['image'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars((string) $card['title'], ENT_QUOTES, 'UTF-8') ?>">
                        <?php if ($card['badge'] !== ''): ?>
                            <span class="history-featured__badge"><?= htmlspecialchars((string) $card['badge'], ENT_QUOTES, 'UTF-8') ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="history-featured__content">
                        <p class="history-featured__label"><?= htmlspecialchars((string) $card['label'], ENT_QUOTES, 'UTF-8') ?></p>
                        <h3><?= htmlspecialchars((string) $card['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                        <p><?= $historyText($card['text']) ?></p>

                        <ul class="history-featured__facts">
                            <?php foreach ($card['features'] as $feature): ?>
                                <?php if ($feature === '') continue; ?>
                                <li><?= htmlspecialchars((string) $feature, ENT_QUOTES, 'UTF-8') ?></li>
                            <?php endforeach; ?>
                        </ul>

                        <?php if ($card['button_text'] !== ''): ?>
                            <a class="history-btn history-btn--outline" href="<?= $historyUrl($card['button_link']) ?>">
                                <?= htmlspecialchars((string) $card['button_text'], ENT_QUOTES, 'UTF-8') ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
