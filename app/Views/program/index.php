<?php
$programItems = is_array($programItems ?? null) ? $programItems : [];
$paidOrders = is_array($paidOrders ?? null) ? $paidOrders : [];
$programTotal = (float) ($programTotal ?? 0);
$isLoggedIn = !empty($isLoggedIn);
$lastOrderId = (int) ($lastOrderId ?? 0);
$continueBrowsingUrl = trim((string) ($continueBrowsingUrl ?? '/home'));
$eventCount = count($programItems);
$paidOrderCount = count($paidOrders);
$summaryEventCount = $eventCount > 0 ? $eventCount : $paidOrderCount;
$latestPaidOrder = $paidOrders[0] ?? null;

$formatMoney = static fn(float $amount): string => 'EUR ' . number_format($amount, 2, '.', ',');
$formatPayment = static function (?string $provider): string {
    return match (trim((string) $provider)) {
        'ideal', 'stripe-ideal' => 'iDEAL',
        'card', 'stripe-card' => 'Credit Card',
        'card-ideal', 'stripe-ideal-card' => 'Credit Card / iDEAL',
        default => 'Secure Payment',
    };
};
$formatDate = static function (?string $dateTime): string {
    if ($dateTime === null || $dateTime === '') {
        return '-';
    }

    $timestamp = strtotime($dateTime);
    return $timestamp ? date('D d M Y, H:i', $timestamp) : $dateTime;
};
$programCategory = static function (array $programItem): string {
    $type = trim((string) ($programItem['type'] ?? ''));
    if ($type === 'history-book-tour') {
        return 'History';
    }

    $label = trim((string) ($programItem['category_label'] ?? ''));
        return $label !== '' ? $label : 'Festival';
    };
?>

<section class="program-page">
    <div class="program-container">
        <div class="program-topbar">
            <a href="<?= htmlspecialchars($continueBrowsingUrl, ENT_QUOTES, 'UTF-8') ?>" class="program-back-link">&lt; Back</a>
            <h1 class="program-page-title">MY PROGRAM</h1>
        </div>

        <div class="program-layout">
            <div class="program-main">
                <section class="program-panel">
                    <div class="program-panel__header">
                        <h2>My Program</h2>
                        <?php if ($summaryEventCount > 0): ?>
                            <span class="program-count-pill"><?= $summaryEventCount ?> Event<?= $summaryEventCount === 1 ? '' : 's' ?></span>
                        <?php endif; ?>
                    </div>

                    <?php if ($programItems === [] && $paidOrders === []): ?>
                        <article class="program-empty-card">
                            <h3>Your program is empty</h3>
                            <p>Add tickets first. They will appear here and you can check out from this page.</p>
                            <a href="<?= htmlspecialchars($continueBrowsingUrl, ENT_QUOTES, 'UTF-8') ?>" class="program-primary-link">Continue Browsing</a>
                        </article>
                    <?php else: ?>
                        <?php foreach ($programItems as $programItem): ?>
                            <?php $isHistoryBooking = (string) ($programItem['type'] ?? '') === 'history-book-tour'; ?>
                            <article class="program-ticket-card">
                                <div class="program-ticket-card__header">
                                    <div>
                                        <p class="program-ticket-card__category"><?= htmlspecialchars($programCategory($programItem), ENT_QUOTES, 'UTF-8') ?></p>
                                        <h3><?= htmlspecialchars((string) ($programItem['title'] ?? 'Festival Booking'), ENT_QUOTES, 'UTF-8') ?></h3>
                                    </div>

                                    <form method="post" action="/program/remove">
                                        <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) ($csrf ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                        <input type="hidden" name="item_id" value="<?= htmlspecialchars((string) ($programItem['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                        <button type="submit" class="program-remove-button">Remove</button>
                                    </form>
                                </div>

                                <div class="program-ticket-card__details">
                                    <ul class="program-detail-list">
                                        <li>
                                            <span><?= $isHistoryBooking ? 'Day' : 'Date' ?></span>
                                            <strong><?= htmlspecialchars((string) ($programItem['day'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></strong>
                                        </li>
                                        <li>
                                            <span><?= $isHistoryBooking ? 'Start Time' : 'Time' ?></span>
                                            <strong><?= htmlspecialchars((string) ($programItem['time'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></strong>
                                        </li>
                                        <li>
                                            <span><?= $isHistoryBooking ? 'Tour Language' : 'Venue' ?></span>
                                            <strong><?= htmlspecialchars((string) ($isHistoryBooking ? ($programItem['language'] ?? '-') : ($programItem['location_name'] ?? 'Haarlem')), ENT_QUOTES, 'UTF-8') ?></strong>
                                        </li>
                                    </ul>

                                    <ul class="program-detail-list">
                                        <li>
                                            <span>Ticket</span>
                                            <strong><?= htmlspecialchars((string) ($programItem['ticket_title'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></strong>
                                        </li>
                                        <li>
                                            <span><?= $isHistoryBooking ? 'Guests' : 'Quantity' ?></span>
                                            <strong><?= htmlspecialchars((string) ($isHistoryBooking ? ($programItem['ticket_summary_text'] ?? '-') : (string) ($programItem['quantity'] ?? 1)), ENT_QUOTES, 'UTF-8') ?></strong>
                                        </li>
                                        <li>
                                            <span><?= $isHistoryBooking ? 'Location' : 'Type' ?></span>
                                            <strong><?= htmlspecialchars((string) ($isHistoryBooking ? ($programItem['location_name'] ?? 'Bavo Church') : ($programItem['category_label'] ?? 'Festival')), ENT_QUOTES, 'UTF-8') ?></strong>
                                        </li>
                                    </ul>
                                </div>

                                <div class="program-ticket-card__footer">
                                    <p><?= htmlspecialchars((string) ($programItem['selection_text'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
                                    <strong class="program-ticket-card__price"><?= htmlspecialchars($formatMoney((float) ($programItem['total_price'] ?? 0)), ENT_QUOTES, 'UTF-8') ?></strong>
                                </div>
                            </article>
                        <?php endforeach; ?>

                        <?php if ($programItems === [] && $paidOrders !== []): ?>
                            <?php foreach ($paidOrders as $paidOrder): ?>
                                <?php
                                $orderId = (int) ($paidOrder['order_id'] ?? 0);
                                $orderItems = is_array($paidOrder['items'] ?? null) ? $paidOrder['items'] : [];
                                $orderTitle = (string) (($orderItems[0]['title'] ?? '') !== '' ? $orderItems[0]['title'] : ('Order #' . $orderId));
                                ?>
                                <article class="program-ticket-card program-ticket-card--paid">
                                    <div class="program-ticket-card__header">
                                        <div>
                                            <p class="program-ticket-card__category">Paid</p>
                                            <h3><?= htmlspecialchars($orderTitle, ENT_QUOTES, 'UTF-8') ?></h3>
                                        </div>
                                    </div>

                                    <div class="program-ticket-card__details">
                                        <ul class="program-detail-list">
                                            <li>
                                                <span>Paid On</span>
                                                <strong><?= htmlspecialchars($formatDate($paidOrder['paid_at'] ?? null), ENT_QUOTES, 'UTF-8') ?></strong>
                                            </li>
                                            <li>
                                                <span>Bookings</span>
                                                <strong><?= count($orderItems) ?></strong>
                                            </li>
                                            <li>
                                                <span>Payment</span>
                                                <strong><?= htmlspecialchars($formatPayment((string) ($paidOrder['provider'] ?? '')), ENT_QUOTES, 'UTF-8') ?></strong>
                                            </li>
                                        </ul>

                                        <ul class="program-detail-list">
                                            <li>
                                                <span>Email</span>
                                                <strong><?= htmlspecialchars((string) ($paidOrder['email'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></strong>
                                            </li>
                                            <li>
                                                <span>Status</span>
                                                <strong>Paid</strong>
                                            </li>
                                            <li>
                                                <span>Location</span>
                                                <strong><?= htmlspecialchars((string) (($orderItems[0]['location_name'] ?? '') !== '' ? $orderItems[0]['location_name'] : 'Bavo Church'), ENT_QUOTES, 'UTF-8') ?></strong>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="program-ticket-card__footer">
                                        <div class="program-ticket-card__links">
                                            <a href="/orders/<?= $orderId ?>/success" class="program-inline-link">Confirmation</a>
                                        </div>
                                        <strong class="program-ticket-card__price"><?= htmlspecialchars($formatMoney((float) ($paidOrder['total_price'] ?? 0)), ENT_QUOTES, 'UTF-8') ?></strong>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </section>
            </div>

            <aside class="program-sidebar">
                <article class="program-summary-card">
                    <h2>Program Summary</h2>

                    <div class="program-summary-line">
                        <span>Total Events</span>
                        <strong><?= $summaryEventCount ?></strong>
                    </div>

                    <div class="program-summary-divider" aria-hidden="true"></div>

                    <?php if ($programItems !== []): ?>
                        <h3>Payment Overview</h3>
                        <p class="program-summary-note">Choose a payment option below. Check Out opens the secure third-party payment page.</p>

                        <form method="post" action="/checkout/pay" class="program-payment-form">
                            <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) ($csrf ?? ''), ENT_QUOTES, 'UTF-8') ?>">

                            <fieldset class="program-payment-options">
                                <legend>Choose a payment method</legend>

                                <label class="program-payment-option">
                                    <input type="radio" name="payment_provider" value="card" required>
                                    <span class="program-payment-option__card">
                                        <span class="program-payment-option__badge">CC</span>
                                        <span class="program-payment-option__content">
                                            <strong>Credit Card</strong>
                                            <small>Mastercard, Visa</small>
                                        </span>
                                        <span class="program-payment-option__indicator" aria-hidden="true"></span>
                                    </span>
                                </label>

                                <label class="program-payment-option">
                                    <input type="radio" name="payment_provider" value="ideal" required>
                                    <span class="program-payment-option__card">
                                        <span class="program-payment-option__badge">iD</span>
                                        <span class="program-payment-option__content">
                                            <strong>iDEAL</strong>
                                            <small>Pay with your bank</small>
                                        </span>
                                        <span class="program-payment-option__indicator" aria-hidden="true"></span>
                                    </span>
                                </label>
                            </fieldset>

                            <div class="program-total-card">
                                <span>Total to be paid</span>
                                <strong><?= htmlspecialchars($formatMoney($programTotal), ENT_QUOTES, 'UTF-8') ?></strong>
                            </div>

                            <button type="submit" class="program-checkout-button">
                                <?= $isLoggedIn ? 'Check Out' : 'Login to Check Out' ?>
                            </button>
                        </form>
                    <?php else: ?>
                        <h3>Payment Overview</h3>
                        <p class="program-summary-note">
                            <?php if ($latestPaidOrder !== null): ?>
                                Your latest booking has already been paid. You can open the confirmation below.
                            <?php else: ?>
                                Add tickets to My Program before checkout.
                            <?php endif; ?>
                        </p>

                        <div class="program-total-card program-total-card--quiet">
                            <span>Total to be paid</span>
                            <strong><?= htmlspecialchars($formatMoney($programTotal), ENT_QUOTES, 'UTF-8') ?></strong>
                        </div>

                        <div class="program-sidebar-actions">
                            <?php if ($latestPaidOrder !== null && $isLoggedIn): ?>
                                <a href="/orders/<?= (int) ($latestPaidOrder['order_id'] ?? 0) ?>/success" class="program-primary-link">Open Confirmation</a>
                            <?php elseif ($lastOrderId > 0 && $isLoggedIn): ?>
                                <a href="/orders/<?= $lastOrderId ?>/success" class="program-primary-link">Open Confirmation</a>
                            <?php else: ?>
                                <a href="<?= htmlspecialchars($continueBrowsingUrl, ENT_QUOTES, 'UTF-8') ?>" class="program-primary-link">Continue Browsing</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </article>
            </aside>
        </div>
    </div>
</section>
