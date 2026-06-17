<?php
$stats = array_filter([
    $s['stat_one'] ?? '',
    $s['stat_two'] ?? '',
    $s['stat_three'] ?? '',
    $s['stat_four'] ?? '',
], static fn(string $value): bool => trim($value) !== '');
?>

<section class="history-tour-hero">
    <div class="history-container history-tour-hero__inner">
        <?php if (!empty($s['eyebrow'])): ?>
            <p class="history-tour-hero__eyebrow"><?= htmlspecialchars((string) $s['eyebrow'], ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <h1 class="history-tour-hero__title"><?= htmlspecialchars((string) ($s['heading'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h1>

        <?php if (!empty($s['intro'])): ?>
            <p class="history-tour-hero__intro"><?= htmlspecialchars((string) $s['intro'], ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
    </div>

    <?php if (!empty($stats)): ?>
        <div class="history-tour-stats">
            <div class="history-container history-tour-stats__inner">
                <?php foreach ($stats as $stat): ?>
                    <span class="history-tour-stats__item"><?= htmlspecialchars((string) $stat, ENT_QUOTES, 'UTF-8') ?></span>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</section>
