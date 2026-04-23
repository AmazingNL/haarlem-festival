<?php
$routeStops = [
    ['title' => $s['stop_one_title'] ?? '', 'text' => $s['stop_one_text'] ?? '', 'time' => $s['stop_one_time'] ?? '', 'badge' => ''],
    ['title' => $s['stop_two_title'] ?? '', 'text' => $s['stop_two_text'] ?? '', 'time' => $s['stop_two_time'] ?? '', 'badge' => ''],
    ['title' => $s['stop_three_title'] ?? '', 'text' => $s['stop_three_text'] ?? '', 'time' => $s['stop_three_time'] ?? '', 'badge' => ''],
    ['title' => $s['stop_four_title'] ?? '', 'text' => $s['stop_four_text'] ?? '', 'time' => $s['stop_four_time'] ?? '', 'badge' => ''],
    ['title' => $s['stop_five_title'] ?? '', 'text' => $s['stop_five_text'] ?? '', 'time' => $s['stop_five_time'] ?? '', 'badge' => $s['stop_five_badge'] ?? ''],
    ['title' => $s['stop_six_title'] ?? '', 'text' => $s['stop_six_text'] ?? '', 'time' => $s['stop_six_time'] ?? '', 'badge' => ''],
    ['title' => $s['stop_seven_title'] ?? '', 'text' => $s['stop_seven_text'] ?? '', 'time' => $s['stop_seven_time'] ?? '', 'badge' => ''],
    ['title' => $s['stop_eight_title'] ?? '', 'text' => $s['stop_eight_text'] ?? '', 'time' => $s['stop_eight_time'] ?? '', 'badge' => ''],
    ['title' => $s['stop_nine_title'] ?? '', 'text' => $s['stop_nine_text'] ?? '', 'time' => $s['stop_nine_time'] ?? '', 'badge' => ''],
];
?>

<aside class="history-tour-card history-tour-card--route">
    <h2><?= htmlspecialchars((string) ($s['heading'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h2>

    <div class="history-tour-route">
        <?php foreach ($routeStops as $index => $stop): ?>
            <?php if (trim($stop['title']) === '') continue; ?>
            <article class="history-tour-stop">
                <div class="history-tour-stop__number"><?= $index + 1 ?></div>
                <div class="history-tour-stop__content">
                    <div class="history-tour-stop__title-row">
                        <h3><?= htmlspecialchars((string) $stop['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                        <?php if ($stop['badge'] !== ''): ?>
                            <span class="history-tour-stop__badge"><?= htmlspecialchars((string) $stop['badge'], ENT_QUOTES, 'UTF-8') ?></span>
                        <?php endif; ?>
                    </div>
                    <p><?= htmlspecialchars((string) $stop['text'], ENT_QUOTES, 'UTF-8') ?></p>
                </div>
                <strong class="history-tour-stop__time"><?= htmlspecialchars((string) $stop['time'], ENT_QUOTES, 'UTF-8') ?></strong>
            </article>
        <?php endforeach; ?>

        <div class="history-tour-total">
            <span><?= htmlspecialchars((string) ($s['total_label'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
            <strong><?= htmlspecialchars((string) ($s['total_value'] ?? ''), ENT_QUOTES, 'UTF-8') ?></strong>
            <small><?= htmlspecialchars((string) ($s['total_note'] ?? ''), ENT_QUOTES, 'UTF-8') ?></small>
        </div>
    </div>

    <div class="history-tour-meeting">
        <h3><?= htmlspecialchars((string) ($s['meeting_title'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h3>
        <p><?= $historyText($s['meeting_text'] ?? '') ?></p>

        <?php if (!empty($s['button_text'])): ?>
            <a class="history-btn history-btn--outline" href="<?= $historyUrl($s['button_link']) ?>">
                <?= htmlspecialchars((string) $s['button_text'], ENT_QUOTES, 'UTF-8') ?>
            </a>
        <?php endif; ?>
    </div>
</aside>
