<section class="history-route-map-hero">
    <?php
    $backText = trim((string) ($s['back_text'] ?? 'Back to History'));
    $backLink = $historyUrl($s['back_link'] ?? '/history', '/history');
    ?>
    <?php if ($backText !== ''): ?>
        <a class="history-route-map-hero__back" href="<?= $backLink ?>"><?= htmlspecialchars($backText, ENT_QUOTES, 'UTF-8') ?></a>
    <?php endif; ?>

    <div class="history-container history-route-map-hero__inner">
        <?php if (!empty($s['eyebrow'])): ?>
            <p class="history-route-map-hero__eyebrow"><?= htmlspecialchars((string) $s['eyebrow'], ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <h1 class="history-route-map-hero__title"><?= htmlspecialchars((string) ($s['heading'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h1>

        <?php if (!empty($s['intro'])): ?>
            <p class="history-route-map-hero__intro"><?= $historyText($s['intro']) ?></p>
        <?php endif; ?>
    </div>
</section>
