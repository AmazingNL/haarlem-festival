<?php
$he = static fn(string $v): string => htmlspecialchars($v, ENT_QUOTES, 'UTF-8');

$sectionId      = (int) ($s['section_id']      ?? 0);
$title          = (string) ($s['title']          ?? '');
$subtitle       = (string) ($s['subtitle']       ?? '');
$infoText       = (string) ($s['info_text']      ?? '');
$showTitle      = (string) ($s['show_title']     ?? '');
$showDesc       = (string) ($s['show_description'] ?? '');
$price          = (string) ($s['price']          ?? '');
$priceRaw       = (string) ($s['price_raw']      ?? '0');
$date           = (string) ($s['date']           ?? '');
$time           = (string) ($s['time']           ?? '');
$spotsAvail     = (string) ($s['spots_available'] ?? '');
$spotsTotal     = (string) ($s['spots_total']    ?? '');
$location       = (string) ($s['location']       ?? '');

$spotsLabel = ($spotsAvail !== '' && $spotsTotal !== '')
    ? $he($spotsAvail) . ' / ' . $he($spotsTotal) . ' spots left'
    : ($spotsAvail !== '' ? $he($spotsAvail) . ' spots left' : '—');
?>

<section class="sbc-section" id="sd-booking">
    <div class="sbc-inner">

        <?php if ($title !== ''): ?>
            <div class="sbc-header">
                <h2 class="sbc-heading"><?= $he($title) ?></h2>
                <?php if ($subtitle !== ''): ?>
                    <p class="sbc-subheading"><?= $he($subtitle) ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($infoText !== ''): ?>
            <div class="sbc-callout"><?= $infoText ?></div>
        <?php endif; ?>

        <div class="sbc-card">

            <div class="sbc-card-header">
                <div class="sbc-card-title-group">
                    <?php if ($showTitle !== ''): ?>
                        <p class="sbc-card-title"><?= $he($showTitle) ?></p>
                    <?php endif; ?>
                    <?php if ($showDesc !== ''): ?>
                        <p class="sbc-card-desc"><?= $he($showDesc) ?></p>
                    <?php endif; ?>
                </div>
                <div class="sbc-card-header-right">
                    <?php if ($price !== ''): ?>
                        <span class="sbc-price-badge"><?= $he($price) ?></span>
                    <?php endif; ?>
                    <?php if (empty($isDetailPage)): ?>
                        <?php
                        $detailSlug = (string) ($s['slug'] ?? '');
                        $detailHref = $detailSlug !== '' ? '/stories/' . $he($detailSlug) : '/stories/' . $sectionId;
                        ?>
                        <a href="<?= $detailHref ?>" class="sbc-detail-link">View Full Story</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="sbc-details">
                <div class="sbc-detail-item">
                    <span class="sbc-detail-icon" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </span>
                    <span class="sbc-detail-label">DATE</span>
                    <span class="sbc-detail-value"><?= $he($date) ?></span>
                </div>
                <div class="sbc-detail-item">
                    <span class="sbc-detail-icon" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </span>
                    <span class="sbc-detail-label">TIME</span>
                    <span class="sbc-detail-value"><?= $he($time) ?></span>
                </div>
                <div class="sbc-detail-item">
                    <span class="sbc-detail-icon" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </span>
                    <span class="sbc-detail-label">AVAILABILITY</span>
                    <span class="sbc-detail-value"><?= $spotsLabel ?></span>
                </div>
                <div class="sbc-detail-item">
                    <span class="sbc-detail-icon" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </span>
                    <span class="sbc-detail-label">LOCATION</span>
                    <span class="sbc-detail-value"><?= $he($location) ?></span>
                </div>
            </div>

            <div class="sbc-booking-row">
                <div class="sbc-booking-text">
                    <p class="sbc-booking-title">Reserve Your Spot</p>
                    <p class="sbc-booking-sub">Click to reserve your seat</p>
                </div>

                <form class="sbc-booking-form" method="post" action="/stories/add-to-program">
                    <input type="hidden" name="_csrf"      value="<?= $he((string) ($csrf ?? '')) ?>">
                    <input type="hidden" name="show_id"    value="<?= $he((string) $sectionId) ?>">
                    <input type="hidden" name="price_raw"  value="<?= $he($priceRaw) ?>">

                    <div class="sbc-stepper" role="group" aria-label="Quantity">
                        <button type="button" class="sbc-stepper-btn" id="sbc-minus-<?= $sectionId ?>" aria-label="Decrease quantity">−</button>
                        <input  type="number" class="sbc-stepper-count" id="sbc-qty-<?= $sectionId ?>"
                                name="quantity" value="1" min="1" max="20"
                                readonly aria-live="polite">
                        <button type="button" class="sbc-stepper-btn" id="sbc-plus-<?= $sectionId ?>" aria-label="Increase quantity">+</button>
                    </div>

                    <button type="submit" class="sbc-add-btn">ADD TO PROGRAM</button>
                </form>
            </div>

        </div>
    </div>
</section>
