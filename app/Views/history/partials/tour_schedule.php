<section class="history-tour-section history-tour-schedule">
    <div class="history-container">
        <div class="history-section__heading history-section__heading--center">
            <h2><?= htmlspecialchars((string) ($s['heading'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h2>
            <?php if (!empty($s['intro'])): ?>
                <p class="history-section__intro"><?= htmlspecialchars((string) $s['intro'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>
        </div>

        <div class="history-tour-schedule__card">
            <div class="history-tour-pill-row history-tour-pill-row--center">
                <?php foreach (['day_one', 'day_two', 'day_three', 'day_four'] as $field): ?>
                    <?php if (empty($s[$field])) continue; ?>
                    <span class="history-tour-pill"><?= htmlspecialchars((string) $s[$field], ENT_QUOTES, 'UTF-8') ?></span>
                <?php endforeach; ?>
            </div>

            <p class="history-tour-schedule__label"><?= htmlspecialchars((string) ($s['guide_label'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>

            <div class="history-tour-schedule__times">
                <?php foreach (['time_one', 'time_two', 'time_three'] as $field): ?>
                    <?php if (empty($s[$field])) continue; ?>
                    <div class="history-tour-schedule__time"><?= htmlspecialchars((string) $s[$field], ENT_QUOTES, 'UTF-8') ?></div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
