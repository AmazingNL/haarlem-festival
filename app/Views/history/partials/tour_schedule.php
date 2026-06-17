<?php
$timeFields = ['time_one', 'time_two', 'time_three'];
$defaultGuideDetails = [
    'Thursday' => [
        '10:00' => 'Jan-Willem (Dutch), Frederic (English)',
        '13:00' => 'Jan-Willem (Dutch), Frederic (English)',
        '16:00' => 'Jan-Willem (Dutch), Frederic (English)',
    ],
    'Friday' => [
        '10:00' => 'Annet (Dutch), Williams (English)',
        '13:00' => 'Annet (Dutch), Williams (English), Kim (Chinese)',
        '16:00' => 'Annet (Dutch), Williams (English)',
    ],
    'Saturday' => [
        '10:00' => 'Annet + Jan-Willem (Dutch), Frederic + William (English)',
        '13:00' => 'Annet + Jan-Willem (Dutch), Frederic + William (English), Kim (Mandarin)',
        '16:00' => 'Jan-Willem (Dutch), Frederic (English), Kim (Mandarin)',
    ],
    'Sunday' => [
        '10:00' => 'Lisa + Annet (Dutch), Deirdre + Frederic (English), Kim (Mandarin)',
        '13:00' => 'Lisa + Annet + Jan-Willem (Dutch), Deirdre + Frederic + William (English), Kim + Susan (Mandarin)',
        '16:00' => 'Jan-Willem (Dutch), William (English)',
    ],
];

$dayDefinitions = [
    ['day_field' => 'day_one', 'guide_fields' => ['day_one_time_one_guides', 'day_one_time_two_guides', 'day_one_time_three_guides']],
    ['day_field' => 'day_two', 'guide_fields' => ['day_two_time_one_guides', 'day_two_time_two_guides', 'day_two_time_three_guides']],
    ['day_field' => 'day_three', 'guide_fields' => ['day_three_time_one_guides', 'day_three_time_two_guides', 'day_three_time_three_guides']],
    ['day_field' => 'day_four', 'guide_fields' => ['day_four_time_one_guides', 'day_four_time_two_guides', 'day_four_time_three_guides']],
];

$scheduleDays = [];
foreach ($dayDefinitions as $dayDefinition) {
    $dayLabel = trim((string) ($s[$dayDefinition['day_field']] ?? ''));
    if ($dayLabel === '') {
        continue;
    }

    $rows = [];
    foreach ($timeFields as $index => $timeField) {
        $timeLabel = trim((string) ($s[$timeField] ?? ''));
        if ($timeLabel === '') {
            continue;
        }

        $guideField = $dayDefinition['guide_fields'][$index] ?? '';
        $guideDetail = trim((string) ($s[$guideField] ?? ''));
        if ($guideDetail === '' && isset($defaultGuideDetails[$dayLabel][$timeLabel])) {
            $guideDetail = $defaultGuideDetails[$dayLabel][$timeLabel];
        }

        $rows[] = [
            'time' => $timeLabel,
            'guides' => $guideDetail !== '' ? $guideDetail : 'Guide details will be added soon.',
        ];
    }

    $scheduleDays[] = [
        'label' => $dayLabel,
        'rows' => $rows,
    ];
}

$defaultDay = trim((string) ($s['default_day'] ?? ''));
if ($defaultDay === '' && $scheduleDays !== []) {
    $defaultDay = (string) ($scheduleDays[0]['label'] ?? '');
}
?>

<section class="history-tour-section history-tour-schedule">
    <div class="history-container">
        <div class="history-section__heading history-section__heading--center">
            <h2><?= htmlspecialchars((string) ($s['heading'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h2>
            <?php if (!empty($s['intro'])): ?>
                <p class="history-section__intro"><?= htmlspecialchars((string) $s['intro'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>
        </div>

        <div class="history-tour-schedule__card" data-schedule-widget data-default-day="<?= htmlspecialchars($defaultDay, ENT_QUOTES, 'UTF-8') ?>">
            <div class="history-tour-pill-row history-tour-pill-row--center">
                <?php foreach ($scheduleDays as $scheduleDay): ?>
                    <button
                        type="button"
                        class="history-tour-pill"
                        data-schedule-choice
                        data-value="<?= htmlspecialchars((string) $scheduleDay['label'], ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars((string) $scheduleDay['label'], ENT_QUOTES, 'UTF-8') ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <p class="history-tour-schedule__label"><?= htmlspecialchars((string) ($s['guide_label'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>

            <?php foreach ($scheduleDays as $scheduleDay): ?>
                <div class="history-tour-schedule__panel" data-schedule-panel="<?= htmlspecialchars((string) $scheduleDay['label'], ENT_QUOTES, 'UTF-8') ?>">
                    <div class="history-tour-schedule__times">
                        <?php foreach ($scheduleDay['rows'] as $row): ?>
                            <div class="history-tour-schedule__row">
                                <div class="history-tour-schedule__time"><?= htmlspecialchars((string) $row['time'], ENT_QUOTES, 'UTF-8') ?></div>
                                <div class="history-tour-schedule__detail"><?= htmlspecialchars((string) $row['guides'], ENT_QUOTES, 'UTF-8') ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
