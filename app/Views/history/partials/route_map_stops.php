<?php
$resolveTone = static function (?string $value, string $default): string {
    $tone = trim((string) $value);
    return in_array($tone, ['start', 'break', 'stop', 'end'], true) ? $tone : $default;
};

$routeStops = [
    ['number' => 1, 'title' => $s['stop_one_title'] ?? '', 'map_label' => $s['stop_one_map_label'] ?? '', 'text' => $s['stop_one_text'] ?? '', 'time' => $s['stop_one_time'] ?? '', 'tag' => $s['stop_one_tag'] ?? '', 'tone' => $resolveTone($s['stop_one_tone'] ?? '', 'start')],
    ['number' => 2, 'title' => $s['stop_two_title'] ?? '', 'map_label' => $s['stop_two_map_label'] ?? '', 'text' => $s['stop_two_text'] ?? '', 'time' => $s['stop_two_time'] ?? '', 'tag' => '', 'tone' => $resolveTone($s['stop_two_tone'] ?? '', 'stop')],
    ['number' => 3, 'title' => $s['stop_three_title'] ?? '', 'map_label' => $s['stop_three_map_label'] ?? '', 'text' => $s['stop_three_text'] ?? '', 'time' => $s['stop_three_time'] ?? '', 'tag' => '', 'tone' => $resolveTone($s['stop_three_tone'] ?? '', 'stop')],
    ['number' => 4, 'title' => $s['stop_four_title'] ?? '', 'map_label' => $s['stop_four_map_label'] ?? '', 'text' => $s['stop_four_text'] ?? '', 'time' => $s['stop_four_time'] ?? '', 'tag' => '', 'tone' => $resolveTone($s['stop_four_tone'] ?? '', 'stop')],
    ['number' => 5, 'title' => $s['stop_five_title'] ?? '', 'map_label' => $s['stop_five_map_label'] ?? '', 'text' => $s['stop_five_text'] ?? '', 'time' => $s['stop_five_time'] ?? '', 'tag' => $s['stop_five_tag'] ?? '', 'tone' => $resolveTone($s['stop_five_tone'] ?? '', 'break')],
    ['number' => 6, 'title' => $s['stop_six_title'] ?? '', 'map_label' => $s['stop_six_map_label'] ?? '', 'text' => $s['stop_six_text'] ?? '', 'time' => $s['stop_six_time'] ?? '', 'tag' => '', 'tone' => $resolveTone($s['stop_six_tone'] ?? '', 'stop')],
    ['number' => 7, 'title' => $s['stop_seven_title'] ?? '', 'map_label' => $s['stop_seven_map_label'] ?? '', 'text' => $s['stop_seven_text'] ?? '', 'time' => $s['stop_seven_time'] ?? '', 'tag' => '', 'tone' => $resolveTone($s['stop_seven_tone'] ?? '', 'stop')],
    ['number' => 8, 'title' => $s['stop_eight_title'] ?? '', 'map_label' => $s['stop_eight_map_label'] ?? '', 'text' => $s['stop_eight_text'] ?? '', 'time' => $s['stop_eight_time'] ?? '', 'tag' => '', 'tone' => $resolveTone($s['stop_eight_tone'] ?? '', 'stop')],
    ['number' => 9, 'title' => $s['stop_nine_title'] ?? '', 'map_label' => $s['stop_nine_map_label'] ?? '', 'text' => $s['stop_nine_text'] ?? '', 'time' => $s['stop_nine_time'] ?? '', 'tag' => $s['stop_nine_tag'] ?? '', 'tone' => $resolveTone($s['stop_nine_tone'] ?? '', 'end')],
];
?>

<section class="history-route-map-section">
    <div class="history-container">
        <h2 class="history-route-map-section__title"><?= htmlspecialchars((string) ($s['heading'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h2>

        <div class="history-route-map-layout">
            <div class="history-route-map-card">
                <div class="history-route-map-card__graphic">
                    <div class="history-route-map-card__river"></div>

                    <svg class="history-route-map-card__line" viewBox="0 0 1000 700" aria-hidden="true" focusable="false">
                        <path d="M145 135 C 190 165, 205 220, 250 235 L 365 235 C 420 235, 465 280, 470 360 L 470 420 C 470 450, 495 470, 525 470 L 590 470 C 640 470, 700 460, 735 380 L 735 305 C 735 260, 760 235, 805 235 L 870 235 C 900 235, 905 310, 865 370 L 820 430 C 805 450, 790 455, 770 455 L 720 455 C 700 455, 675 465, 655 485 L 540 590 C 515 615, 500 620, 475 620" />
                        <path d="M145 135 C 190 165, 205 220, 250 235 L 365 235 C 420 235, 465 280, 470 360 L 470 420 C 470 450, 495 470, 525 470 L 590 470 C 640 470, 700 460, 735 380 L 735 305 C 735 260, 760 235, 805 235 L 870 235 C 900 235, 905 310, 865 370 L 820 430 C 805 450, 790 455, 770 455 L 720 455 C 700 455, 675 465, 655 485 L 540 590 C 515 615, 500 620, 475 620" />
                    </svg>

                    <?php foreach ($routeStops as $stop): ?>
                        <?php if ($stop['title'] === '') continue; ?>
                        <div class="history-route-map-stop history-route-map-stop--<?= $stop['number'] ?>">
                            <span class="history-route-map-stop__dot history-route-map-stop__dot--<?= $stop['tone'] ?>">
                                <?= htmlspecialchars((string) $stop['number'], ENT_QUOTES, 'UTF-8') ?>
                            </span>
                            <?php if ($stop['tag'] !== ''): ?>
                                <span class="history-route-map-stop__tag"><?= htmlspecialchars((string) $stop['tag'], ENT_QUOTES, 'UTF-8') ?></span>
                            <?php endif; ?>
                            <span class="history-route-map-stop__label">
                                <?= htmlspecialchars((string) ($stop['map_label'] !== '' ? $stop['map_label'] : $stop['title']), ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </div>
                    <?php endforeach; ?>

                    <div class="history-route-map-legend">
                        <p><span class="history-route-map-legend__dot history-route-map-legend__dot--start"></span><?= htmlspecialchars((string) ($s['legend_start'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
                        <p><span class="history-route-map-legend__dot history-route-map-legend__dot--break"></span><?= htmlspecialchars((string) ($s['legend_break'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
                        <p><span class="history-route-map-legend__dot history-route-map-legend__dot--stop"></span><?= htmlspecialchars((string) ($s['legend_stop'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                </div>

                <div class="history-route-map-card__summary">
                    <span><?= htmlspecialchars((string) ($s['summary_one'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
                    <span><?= htmlspecialchars((string) ($s['summary_two'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
                    <span><?= htmlspecialchars((string) ($s['summary_three'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            </div>

            <aside class="history-route-map-stops">
                <?php foreach ($routeStops as $stop): ?>
                    <?php if ($stop['title'] === '') continue; ?>
                    <article class="history-route-map-stops__item <?= $stop['tag'] !== '' ? 'has-tag' : '' ?>">
                        <span class="history-route-map-stops__number history-route-map-stops__number--<?= $stop['tone'] ?>">
                            <?= htmlspecialchars((string) $stop['number'], ENT_QUOTES, 'UTF-8') ?>
                        </span>
                        <div class="history-route-map-stops__content">
                            <div class="history-route-map-stops__title-row">
                                <h3><?= htmlspecialchars((string) $stop['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                                <?php if ($stop['tag'] !== ''): ?>
                                    <span class="history-route-map-stops__tag"><?= htmlspecialchars((string) $stop['tag'], ENT_QUOTES, 'UTF-8') ?></span>
                                <?php endif; ?>
                            </div>
                            <p><?= htmlspecialchars((string) $stop['text'], ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                        <strong><?= htmlspecialchars((string) $stop['time'], ENT_QUOTES, 'UTF-8') ?></strong>
                    </article>
                <?php endforeach; ?>
            </aside>
        </div>
    </div>
</section>
