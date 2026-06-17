<?php
$he       = static fn(string $v): string => htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
$title    = (string) ($s['title']    ?? '');
$subtitle = (string) ($s['subtitle'] ?? '');
?>
<section class="sched-section">
    <div class="sched-inner">

        <?php if ($title !== ''): ?>
            <h2 class="sched-title"><?= $he($title) ?></h2>
        <?php endif; ?>

        <?php if ($subtitle !== ''): ?>
            <p class="sched-subtitle"><?= $he($subtitle) ?></p>
        <?php endif; ?>

        <div class="sched-filters" aria-label="Filter sessions">
            <div class="sched-filter-group">
                <span class="sched-filter-label">Language:</span>
                <button class="sched-filter-pill active" type="button">All</button>
                <button class="sched-filter-pill" type="button">Dutch</button>
                <button class="sched-filter-pill" type="button">English</button>
            </div>
            <div class="sched-filter-group">
                <span class="sched-filter-label">Age:</span>
                <button class="sched-filter-pill" type="button">4+</button>
                <button class="sched-filter-pill" type="button">10+</button>
                <button class="sched-filter-pill" type="button">16+</button>
            </div>
        </div>

        <?php if (!empty($s['content'])): ?>
            <div class="sched-body"><?= \App\Support\Html::clean($s['content'] ?? '') ?></div>
        <?php else: ?>
            <div class="sched-grid">
                <div class="sched-day-col">
                    <h3 class="sched-day-header">Thursday</h3>
                </div>
                <div class="sched-day-col">
                    <h3 class="sched-day-header">Friday</h3>
                </div>
                <div class="sched-day-col">
                    <h3 class="sched-day-header">Saturday</h3>
                </div>
                <div class="sched-day-col">
                    <h3 class="sched-day-header">Sunday</h3>
                </div>
            </div>
        <?php endif; ?>

    </div>
</section>
