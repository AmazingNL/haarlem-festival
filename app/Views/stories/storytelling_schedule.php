<?php
$he       = static fn(string $v): string => htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
$title    = (string) ($s['title']    ?? '');
$subtitle = (string) ($s['subtitle'] ?? '');
$events   = is_array($s['events'] ?? null) ? $s['events'] : [];

$langMap = ['nl' => 'Dutch', 'en' => 'English', 'eng' => 'English'];
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
            <div class="sched-filter-row">
                <span class="sched-filter-label">Filter:</span>
                <div class="sched-filter-group" data-filter="lang">
                    <span class="sched-filter-sublabel">Language:</span>
                    <button class="sched-filter-pill active" type="button" data-value="all">All</button>
                    <button class="sched-filter-pill" type="button" data-value="nl">Dutch</button>
                    <button class="sched-filter-pill" type="button" data-value="en">English</button>
                </div>
            </div>
            <div class="sched-day-tabs" role="tablist" aria-label="Filter by day">
                <button class="sched-day-tab active" type="button" data-value="thursday" role="tab">Thursday</button>
                <button class="sched-day-tab" type="button" data-value="friday" role="tab">Friday</button>
                <button class="sched-day-tab" type="button" data-value="saturday" role="tab">Saturday</button>
                <button class="sched-day-tab" type="button" data-value="sunday" role="tab">Sunday</button>
            </div>
        </div>

        <div class="sched-events" id="sched-events">
            <?php foreach ($events as $ev): ?>
                <?php
                $day      = strtolower(trim((string) ($ev['day']      ?? '')));
                $rawLang  = strtolower(trim((string) ($ev['language'] ?? 'nl')));
                $lang     = ($rawLang === 'eng' || $rawLang === 'en') ? 'en' : 'nl';
                $time     = (string) ($ev['time']     ?? '');
                $evTitle  = (string) ($ev['title']    ?? '');
                $location = (string) ($ev['location'] ?? '');
                $age      = (string) ($ev['age']      ?? '');
                $price    = (string) ($ev['price']    ?? '');
                $type      = strtolower(trim((string) ($ev['type'] ?? '')));
                $slug      = (string) ($ev['slug']     ?? '');
                $langLabel = $langMap[$rawLang] ?? strtoupper($rawLang);
                $detailUrl = $slug !== '' ? '/stories/' . $he($slug) : '';
                ?>
                <article class="sched-card" data-day="<?= $he($day) ?>" data-lang="<?= $he($lang) ?>">
                    <div class="sched-card-top">
                        <p class="sched-card-time"><?= $he($time) ?></p>
                        <h3 class="sched-card-title"><?= $he($evTitle) ?></h3>
                    </div>
                    <div class="sched-card-meta">
                        <?php if ($location !== ''): ?>
                            <div class="sched-card-row">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                <?= $he($location) ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($price !== ''): ?>
                            <div class="sched-card-row">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                <?= $he($price) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="sched-card-tags">
                        <?php if ($age !== ''): ?>
                            <span class="sched-tag"><?= $he($age) ?></span>
                        <?php endif; ?>
                        <span class="sched-tag sched-tag--lang"><?= $he($langLabel) ?></span>
                        <?php if ($type !== ''): ?>
                            <span class="sched-tag sched-tag--type"><?= $he($type) ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if ($detailUrl !== ''): ?>
                        <a href="<?= $detailUrl ?>" class="sched-card-btn">Read Full Story</a>
                    <?php else: ?>
                        <button class="sched-card-btn sched-card-btn--disabled" disabled aria-disabled="true">Read Full Story</button>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>

            <p class="sched-no-results" id="sched-no-results" aria-live="polite" hidden>
                No sessions match the selected filters.
            </p>
        </div>

    </div>
</section>
