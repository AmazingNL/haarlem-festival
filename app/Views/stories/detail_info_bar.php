<?php
$breadcrumbLabel = (string) ($show['breadcrumb_label'] ?? $show['show_title'] ?? '');
$date            = (string) ($show['date']             ?? '');
$time            = (string) ($show['time']             ?? '');
$spotsAvail      = (string) ($show['spots_available']  ?? '');
$spotsTotal      = (string) ($show['spots_total']      ?? '');
$location        = (string) ($show['location']         ?? '');
$sectionId       = (int)    ($show['section_id']       ?? 0);

$spotsLabel = ($spotsAvail !== '' && $spotsTotal !== '')
    ? $he($spotsAvail) . ' / ' . $he($spotsTotal) . ' spots left'
    : ($spotsAvail !== '' ? $he($spotsAvail) . ' spots left' : '');
?>
<div class="sd-info-bar">
    <div class="sd-info-inner">
        <nav class="sd-breadcrumb" aria-label="Breadcrumb">
            <a href="/" class="sd-breadcrumb-link">Home</a>
            <span class="sd-breadcrumb-sep" aria-hidden="true">›</span>
            <a href="/stories" class="sd-breadcrumb-link">Storytelling</a>
            <?php if ($breadcrumbLabel !== ''): ?>
                <span class="sd-breadcrumb-sep" aria-hidden="true">›</span>
                <span class="sd-breadcrumb-current"><?= $he($breadcrumbLabel) ?></span>
            <?php endif; ?>
        </nav>

        <div class="sd-meta-row">
            <?php if ($date !== ''): ?>
                <div class="sd-meta-item">
                    <span class="sd-meta-icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </span>
                    <div class="sd-meta-text">
                        <span class="sd-meta-label">DATE</span>
                        <span class="sd-meta-value"><?= $he($date) ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($time !== ''): ?>
                <div class="sd-meta-item">
                    <span class="sd-meta-icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </span>
                    <div class="sd-meta-text">
                        <span class="sd-meta-label">TIME</span>
                        <span class="sd-meta-value"><?= $he($time) ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($spotsLabel !== ''): ?>
                <div class="sd-meta-item">
                    <span class="sd-meta-icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </span>
                    <div class="sd-meta-text">
                        <span class="sd-meta-label">AVAILABILITY</span>
                        <span class="sd-meta-value"><?= $spotsLabel ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($location !== ''): ?>
                <div class="sd-meta-item">
                    <span class="sd-meta-icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </span>
                    <div class="sd-meta-text">
                        <span class="sd-meta-label">LOCATION</span>
                        <span class="sd-meta-value"><?= $he($location) ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <a href="#sd-booking" class="sd-reserve-btn">Reserve</a>
        </div>
    </div>
</div>
