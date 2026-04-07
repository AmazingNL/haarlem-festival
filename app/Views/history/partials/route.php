<?php
$venues = [
    ['number' => '1', 'title' => $s['venue_one_title'] ?? '', 'text' => $s['venue_one_text'] ?? '', 'link' => $s['venue_one_link'] ?? '', 'badge' => ''],
    ['number' => '2', 'title' => $s['venue_two_title'] ?? '', 'text' => $s['venue_two_text'] ?? '', 'link' => $s['venue_two_link'] ?? '', 'badge' => ''],
    ['number' => '3', 'title' => $s['venue_three_title'] ?? '', 'text' => $s['venue_three_text'] ?? '', 'link' => $s['venue_three_link'] ?? '', 'badge' => ''],
    ['number' => '4', 'title' => $s['venue_four_title'] ?? '', 'text' => $s['venue_four_text'] ?? '', 'link' => $s['venue_four_link'] ?? '', 'badge' => ''],
    ['number' => '5', 'title' => $s['venue_five_title'] ?? '', 'text' => $s['venue_five_text'] ?? '', 'link' => $s['venue_five_link'] ?? '', 'badge' => $s['venue_five_badge'] ?? ''],
    ['number' => '6', 'title' => $s['venue_six_title'] ?? '', 'text' => $s['venue_six_text'] ?? '', 'link' => $s['venue_six_link'] ?? '', 'badge' => ''],
    ['number' => '7', 'title' => $s['venue_seven_title'] ?? '', 'text' => $s['venue_seven_text'] ?? '', 'link' => $s['venue_seven_link'] ?? '', 'badge' => ''],
    ['number' => '8', 'title' => $s['venue_eight_title'] ?? '', 'text' => $s['venue_eight_text'] ?? '', 'link' => $s['venue_eight_link'] ?? '', 'badge' => ''],
    ['number' => '9', 'title' => $s['venue_nine_title'] ?? '', 'text' => $s['venue_nine_text'] ?? '', 'link' => $s['venue_nine_link'] ?? '', 'badge' => ''],
];
?>
<section class="history-section history-route" id="history-route">
    <div class="history-container history-route__inner">
        <div class="history-section__heading history-section__heading--center">
            <?php if (!empty($s['eyebrow'])): ?>
                <p class="history-eyebrow"><?= htmlspecialchars((string) $s['eyebrow'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>
            <h2><?= htmlspecialchars((string) ($s['heading'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h2>
            <?php if (!empty($s['intro'])): ?>
                <p class="history-section__intro"><?= $historyText($s['intro']) ?></p>
            <?php endif; ?>
        </div>

        <div class="history-route__grid">
            <?php foreach ($venues as $venue): ?>
                <article class="history-route__card">
                    <div class="history-route__number"><?= htmlspecialchars((string) $venue['number'], ENT_QUOTES, 'UTF-8') ?></div>
                    <div class="history-route__content">
                        <div class="history-route__title-row">
                            <h3><?= htmlspecialchars((string) $venue['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                            <?php if ($venue['badge'] !== ''): ?>
                                <span class="history-route__badge"><?= htmlspecialchars((string) $venue['badge'], ENT_QUOTES, 'UTF-8') ?></span>
                            <?php endif; ?>
                        </div>
                        <p><?= htmlspecialchars((string) $venue['text'], ENT_QUOTES, 'UTF-8') ?></p>
                        <?php if ($venue['link'] !== ''): ?>
                            <a href="<?= $historyUrl($venue['link']) ?>">Learn more</a>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <?php if (!empty($s['button_text'])): ?>
            <a class="history-btn history-btn--primary history-route__button" href="<?= $historyUrl($s['button_link']) ?>">
                <?= htmlspecialchars((string) $s['button_text'], ENT_QUOTES, 'UTF-8') ?>
            </a>
        <?php endif; ?>
    </div>
</section>
