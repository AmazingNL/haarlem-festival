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

<section class="sbc-section">
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
                <?php if ($price !== ''): ?>
                    <span class="sbc-price-badge"><?= $he($price) ?></span>
                <?php endif; ?>
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

<style>
    .sbc-section {
        background: #f6e9e9;
        padding: 72px 24px;
    }

    .sbc-inner {
        max-width: 860px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 28px;
    }

    /* Header */
    .sbc-header { text-align: center; }

    .sbc-heading {
        font-size: 1.9rem;
        font-weight: 900;
        color: #3b1010;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        margin: 0 0 6px;
    }

    .sbc-subheading {
        font-size: 0.97rem;
        color: #6b3030;
        margin: 0;
    }

    /* Info callout */
    .sbc-callout {
        background: #f0e0c0;
        border-left: 4px solid #b8860b;
        border-radius: 8px;
        padding: 16px 20px;
        font-size: 0.92rem;
        font-style: italic;
        color: #4a3000;
        line-height: 1.7;
    }

    .sbc-callout p { margin: 0; }

    /* Card shell */
    .sbc-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 24px rgba(65, 6, 6, 0.10);
        overflow: hidden;
    }

    /* Dark olive card header */
    .sbc-card-header {
        background: #2d2416;
        padding: 20px 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }

    .sbc-card-title-group { flex: 1; min-width: 0; }

    .sbc-card-title {
        font-size: 1.05rem;
        font-weight: 800;
        color: #fff;
        margin: 0 0 4px;
        line-height: 1.3;
    }

    .sbc-card-desc {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.65);
        margin: 0;
    }

    .sbc-price-badge {
        background: #c8a84b;
        color: #1a0e00;
        font-size: 1.25rem;
        font-weight: 900;
        padding: 6px 18px;
        border-radius: 999px;
        white-space: nowrap;
        flex-shrink: 0;
    }

    /* 4-column detail row */
    .sbc-details {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0;
        border-bottom: 1px solid #f0e8e8;
    }

    .sbc-detail-item {
        padding: 18px 20px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
        border-right: 1px solid #f0e8e8;
    }

    .sbc-detail-item:last-child { border-right: none; }

    .sbc-detail-icon { color: #7f1414; }

    .sbc-detail-label {
        font-size: 0.7rem;
        font-weight: 800;
        color: #7f1414;
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }

    .sbc-detail-value {
        font-size: 0.88rem;
        color: #2d1a1a;
        font-weight: 600;
        line-height: 1.35;
    }

    /* Booking row */
    .sbc-booking-row {
        background: #fdf5ec;
        padding: 22px 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
    }

    .sbc-booking-title {
        font-size: 1rem;
        font-weight: 800;
        color: #2d1a1a;
        margin: 0 0 2px;
    }

    .sbc-booking-sub {
        font-size: 0.82rem;
        color: #7a5a5a;
        margin: 0;
    }

    .sbc-booking-form {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    /* Quantity stepper */
    .sbc-stepper {
        display: flex;
        align-items: center;
        border: 2px solid #7f1414;
        border-radius: 999px;
        overflow: hidden;
    }

    .sbc-stepper-btn {
        background: transparent;
        border: none;
        width: 38px;
        height: 38px;
        font-size: 1.25rem;
        font-weight: 700;
        color: #7f1414;
        cursor: pointer;
        line-height: 1;
        padding: 0;
        transition: background 0.15s ease, color 0.15s ease;
    }

    .sbc-stepper-btn:hover {
        background: #7f1414;
        color: #fff;
    }

    .sbc-stepper-count {
        width: 40px;
        text-align: center;
        border: none;
        border-left: 1px solid #7f1414;
        border-right: 1px solid #7f1414;
        font-size: 0.95rem;
        font-weight: 700;
        color: #2d1a1a;
        padding: 0 4px;
        height: 38px;
        -moz-appearance: textfield;
        background: #fff;
    }

    .sbc-stepper-count::-webkit-outer-spin-button,
    .sbc-stepper-count::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

    /* Add to program button */
    .sbc-add-btn {
        background: #7f1414;
        color: #fff;
        border: none;
        border-radius: 999px;
        padding: 10px 28px;
        font-size: 0.9rem;
        font-weight: 800;
        letter-spacing: 0.05em;
        cursor: pointer;
        transition: background 0.2s ease;
        white-space: nowrap;
        font-family: inherit;
    }

    .sbc-add-btn:hover { background: #c21f1f; }

    @media (max-width: 700px) {
        .sbc-details { grid-template-columns: repeat(2, 1fr); }
        .sbc-detail-item:nth-child(2) { border-right: none; }
        .sbc-detail-item:nth-child(3),
        .sbc-detail-item:nth-child(4) { border-top: 1px solid #f0e8e8; }
        .sbc-detail-item:nth-child(4) { border-right: none; }
    }

    @media (max-width: 480px) {
        .sbc-card-header { flex-direction: column; align-items: flex-start; }
        .sbc-booking-row { flex-direction: column; align-items: flex-start; }
        .sbc-details { grid-template-columns: 1fr 1fr; }
    }
</style>

<script>
(function () {
    var id   = <?= (int) $sectionId ?>;
    var minus = document.getElementById('sbc-minus-' + id);
    var plus  = document.getElementById('sbc-plus-'  + id);
    var qty   = document.getElementById('sbc-qty-'   + id);

    if (!minus || !plus || !qty) return;

    minus.addEventListener('click', function () {
        var v = parseInt(qty.value, 10);
        if (v > 1) qty.value = v - 1;
    });

    plus.addEventListener('click', function () {
        var v = parseInt(qty.value, 10);
        if (v < 20) qty.value = v + 1;
    });
}());
</script>
