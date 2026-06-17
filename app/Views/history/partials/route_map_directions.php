<?php
$directionSteps = [
    ['title' => $s['step_one_title'] ?? '', 'text' => $s['step_one_text'] ?? ''],
    ['title' => $s['step_two_title'] ?? '', 'text' => $s['step_two_text'] ?? ''],
    ['title' => $s['step_three_title'] ?? '', 'text' => $s['step_three_text'] ?? ''],
];
?>

<section class="history-route-directions">
    <div class="history-container">
        <div class="history-section__heading history-section__heading--center">
            <h2><?= htmlspecialchars((string) ($s['heading'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h2>
            <?php if (!empty($s['intro'])): ?>
                <p class="history-section__intro"><?= $historyText($s['intro']) ?></p>
            <?php endif; ?>
        </div>

        <div class="history-route-directions__grid">
            <?php foreach ($directionSteps as $index => $step): ?>
                <?php if ($step['title'] === '') continue; ?>
                <article class="history-route-directions__card">
                    <span class="history-route-directions__number"><?= $index + 1 ?></span>
                    <h3><?= htmlspecialchars((string) $step['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                    <p><?= $historyText($step['text']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
