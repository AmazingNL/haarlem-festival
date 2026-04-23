<section class="history-route-map-cta">
    <div class="history-container history-route-map-cta__inner">
        <h2><?= htmlspecialchars((string) ($s['heading'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h2>
        <?php if (!empty($s['body'])): ?>
            <p><?= $historyText($s['body']) ?></p>
        <?php endif; ?>

        <?php if (!empty($s['button_text'])): ?>
            <a class="history-btn history-btn--dark" href="<?= $historyUrl($s['button_link']) ?>">
                <?= htmlspecialchars((string) $s['button_text'], ENT_QUOTES, 'UTF-8') ?>
            </a>
        <?php endif; ?>
    </div>
</section>
