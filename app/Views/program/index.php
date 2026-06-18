<?php
$programItems = is_array($programItems ?? null) ? $programItems : [];
$paidOrders = is_array($paidOrders ?? null) ? $paidOrders : [];
$programTotal = (float) ($programTotal ?? 0);
$isLoggedIn = !empty($isLoggedIn);
$lastOrderId = (int) ($lastOrderId ?? 0);
$continueBrowsingUrl = trim((string) ($continueBrowsingUrl ?? '/home'));
$cartCount = count($programItems);
$paidOrderCount = count($paidOrders);
$latestPaidOrder = $paidOrders[0] ?? null;

$formatMoney = static fn(float $amount): string => 'EUR ' . number_format($amount, 2, '.', ',');
$formatPayment = static fn(?string $provider): string => \App\Support\PaymentProvider::label($provider);
$formatDate = static function (?string $dateTime): string {
    if ($dateTime === null || $dateTime === '') {
        return '-';
    }

    $timestamp = strtotime($dateTime);
    return $timestamp ? date('D d M Y, H:i', $timestamp) : $dateTime;
};
$orderEmail = static function (array $order): string {
    $email = trim((string) ($order['email'] ?? ''));
    if ($email !== '') {
        return $email;
    }

    return trim((string) ($_SESSION['user_email'] ?? ''));
};
$paidBookingCount = static function (array $order): int {
    $items = is_array($order['items'] ?? null) ? $order['items'] : [];
    $count = count($items);
    if ($count > 0) {
        return $count;
    }

    return (float) ($order['total_price'] ?? 0) > 0 ? 1 : 0;
};
$programCategory = static function (array $programItem): string {
    $type = trim((string) ($programItem['type'] ?? ''));
    if ($type === 'history-book-tour') {
        return 'History';
    }
    if ($type === 'yummy-reservation') {
        return 'Yummy';
    }
    if ($type === 'stories-show') {
        return 'Stories';
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
                        <div class="program-panel__badges">
                            <?php if ($cartCount > 0): ?>
                                <span class="program-count-pill"><?= $cartCount ?> to pay</span>
                            <?php endif; ?>
                            <?php if ($paidOrderCount > 0): ?>
                                <span class="program-count-pill program-count-pill--paid"><?= $paidOrderCount ?> paid</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if ($programItems === [] && $paidOrders === []): ?>
                        <article class="program-empty-card">
                            <h3>Your program is empty</h3>
                            <p>Add tickets first. They will appear here and you can check out from this page.</p>
                            <a href="<?= htmlspecialchars($continueBrowsingUrl, ENT_QUOTES, 'UTF-8') ?>" class="program-primary-link">Continue Browsing</a>
                        </article>
                    <?php else: ?>
                        <?php foreach ($programItems as $programItem): ?>
                            <?php
                            $programType = (string) ($programItem['type'] ?? '');
                            $isHistoryBooking = $programType === 'history-book-tour';
                            $isYummyReservation = $programType === 'yummy-reservation';
                            ?>
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
                                            <span><?= ($isHistoryBooking || $isYummyReservation) ? 'Guests' : 'Quantity' ?></span>
                                            <strong><?= htmlspecialchars((string) (($isHistoryBooking || $isYummyReservation) ? ($programItem['ticket_summary_text'] ?? '-') : (string) ($programItem['quantity'] ?? 1)), ENT_QUOTES, 'UTF-8') ?></strong>
                                        </li>
                                        <li>
                                            <span><?= $isHistoryBooking ? 'Location' : 'Type' ?></span>
                                            <strong><?= htmlspecialchars((string) ($isHistoryBooking ? ($programItem['location_name'] ?? 'Bavo Church') : ($programItem['category_label'] ?? 'Festival')), ENT_QUOTES, 'UTF-8') ?></strong>
                                        </li>
                                    </ul>

                                    <?php if ($isYummyReservation): ?>
                                        <ul class="program-detail-list">
                                            <li>
                                                <span>Customer</span>
                                                <strong><?= htmlspecialchars((string) (($programItem['customer_name'] ?? '') !== '' ? $programItem['customer_name'] : '-'), ENT_QUOTES, 'UTF-8') ?></strong>
                                            </li>
                                            <li>
                                                <span>Email</span>
                                                <strong><?= htmlspecialchars((string) (($programItem['customer_email'] ?? '') !== '' ? $programItem['customer_email'] : '-'), ENT_QUOTES, 'UTF-8') ?></strong>
                                            </li>
                                            <li>
                                                <span>Requests</span>
                                                <strong><?= htmlspecialchars((string) (($programItem['special_requests'] ?? '') !== '' ? $programItem['special_requests'] : '-'), ENT_QUOTES, 'UTF-8') ?></strong>
                                            </li>
                                        </ul>
                                    <?php endif; ?>
                                </div>

                                <div class="program-ticket-card__footer">
                                    <p><?= htmlspecialchars((string) ($programItem['selection_text'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
                                    <strong class="program-ticket-card__price"><?= htmlspecialchars($formatMoney((float) ($programItem['total_price'] ?? 0)), ENT_QUOTES, 'UTF-8') ?></strong>
                                </div>
                            </article>
                        <?php endforeach; ?>

                        <?php if ($paidOrders !== []): ?>
                            <?php if ($cartCount > 0): ?>
                                <h3 class="program-paid-section-title">Paid bookings</h3>
                            <?php endif; ?>
                            <?php foreach ($paidOrders as $paidOrder): ?>
                                <?php
                                $orderId = (int) ($paidOrder['order_id'] ?? 0);
                                $orderItems = is_array($paidOrder['items'] ?? null) ? $paidOrder['items'] : [];
                                $primaryItem = $orderItems[0] ?? [];
                                $orderTitle = trim((string) ($primaryItem['title'] ?? ''));
                                if ($orderTitle === '') {
                                    $orderTitle = 'Paid booking';
                                }
                                $bookingCount = $paidBookingCount($paidOrder);
                                $displayEmail = $orderEmail($paidOrder);
                                $isHistoryBooking = (string) ($primaryItem['type'] ?? '') === 'history-book-tour';
                                ?>
                                <article class="program-ticket-card program-ticket-card--paid">
                                    <div class="program-ticket-card__header">
                                        <div>
                                            <p class="program-ticket-card__category">Paid</p>
                                            <h3><?= htmlspecialchars($orderTitle, ENT_QUOTES, 'UTF-8') ?></h3>
                                        </div>
                                    </div>

                                    <?php if ($orderItems !== []): ?>
                                        <?php foreach ($orderItems as $lineItem): ?>
                                            <?php
                                            $lineHistory = (string) ($lineItem['type'] ?? '') === 'history-book-tour';
                                            $lineYummy = (string) ($lineItem['type'] ?? '') === 'yummy-reservation';
                                            ?>
                                            <div class="program-paid-line">
                                                <?php if (trim((string) ($lineItem['title'] ?? '')) !== '' && count($orderItems) > 1): ?>
                                                    <p class="program-paid-line__title"><?= htmlspecialchars((string) $lineItem['title'], ENT_QUOTES, 'UTF-8') ?></p>
                                                <?php endif; ?>
                                                <div class="program-ticket-card__details program-ticket-card__details--compact">
                                                    <ul class="program-detail-list">
                                                        <li>
                                                            <span><?= $lineHistory ? 'Day' : 'Date' ?></span>
                                                            <strong><?= htmlspecialchars((string) ($lineItem['day'] ?? ($lineItem['selection_text'] ?? '-')), ENT_QUOTES, 'UTF-8') ?></strong>
                                                        </li>
                                                        <li>
                                                            <span>Ticket</span>
                                                            <strong><?= htmlspecialchars((string) (($lineItem['ticket_title'] ?? '') !== '' ? $lineItem['ticket_title'] : '-'), ENT_QUOTES, 'UTF-8') ?></strong>
                                                        </li>
                                                        <li>
                                                            <span>Guests</span>
                                                            <strong><?= htmlspecialchars((string) (($lineItem['ticket_summary_text'] ?? '') !== '' ? $lineItem['ticket_summary_text'] : (string) ($lineItem['quantity'] ?? 1)), ENT_QUOTES, 'UTF-8') ?></strong>
                                                        </li>
                                                    </ul>
                                                    <ul class="program-detail-list">
                                                        <li>
                                                            <span>Location</span>
                                                            <strong><?= htmlspecialchars((string) (($lineItem['location_name'] ?? '') !== '' ? $lineItem['location_name'] : 'Haarlem'), ENT_QUOTES, 'UTF-8') ?></strong>
                                                        </li>
                                                        <li>
                                                            <span>Price</span>
                                                            <strong><?= htmlspecialchars($formatMoney((float) ($lineItem['line_total'] ?? $lineItem['total_price'] ?? 0)), ENT_QUOTES, 'UTF-8') ?></strong>
                                                        </li>
                                                        <li>
                                                            <span>Details</span>
                                                            <strong><?= htmlspecialchars((string) (($lineItem['selection_text'] ?? '') !== '' ? $lineItem['selection_text'] : '-'), ENT_QUOTES, 'UTF-8') ?></strong>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                    <div class="program-ticket-card__details program-ticket-card__details--summary">
                                        <ul class="program-detail-list">
                                            <li>
                                                <span>Paid on</span>
                                                <strong><?= htmlspecialchars($formatDate($paidOrder['paid_at'] ?? null), ENT_QUOTES, 'UTF-8') ?></strong>
                                            </li>
                                            <li>
                                                <span>Bookings</span>
                                                <strong><?= $bookingCount ?></strong>
                                            </li>
                                            <li>
                                                <span>Payment</span>
                                                <strong><?= htmlspecialchars($formatPayment((string) ($paidOrder['provider'] ?? '')), ENT_QUOTES, 'UTF-8') ?></strong>
                                            </li>
                                        </ul>

                                        <ul class="program-detail-list">
                                            <li>
                                                <span>Email</span>
                                                <strong><?= htmlspecialchars($displayEmail !== '' ? $displayEmail : 'No email on account', ENT_QUOTES, 'UTF-8') ?></strong>
                                            </li>
                                            <li>
                                                <span>Status</span>
                                                <strong>Paid</strong>
                                            </li>
                                            <li>
                                                <span>Order</span>
                                                <strong>#<?= $orderId ?></strong>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="program-ticket-card__footer">
                                        <div class="program-ticket-card__links">
                                            <a href="/orders/<?= $orderId ?>/success" class="program-inline-link">Open confirmation</a>
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
                        <span><?= $cartCount > 0 ? 'Items to pay' : 'Paid bookings' ?></span>
                        <strong><?= $cartCount > 0 ? $cartCount : $paidOrderCount ?></strong>
                    </div>

                    <?php if ($cartCount > 0): ?>
                        <div class="program-summary-line program-summary-line--secondary">
                            <span>Cart total</span>
                            <strong><?= htmlspecialchars($formatMoney($programTotal), ENT_QUOTES, 'UTF-8') ?></strong>
                        </div>
                    <?php endif; ?>

                    <div class="program-summary-divider" aria-hidden="true"></div>

                    <?php if ($programItems !== []): ?>
                        <?php if ($latestPaidOrder !== null && $isLoggedIn): ?>
                            <div class="program-summary-line program-summary-line--secondary">
                                <span>Paid bookings</span>
                                <strong><?= $paidOrderCount ?></strong>
                            </div>
                            <div class="program-sidebar-actions program-sidebar-actions--compact">
                                <a href="/orders/<?= (int) ($latestPaidOrder['order_id'] ?? 0) ?>/success" class="program-inline-link">Open latest confirmation</a>
                            </div>
                        <?php endif; ?>
                        <?php
        require __DIR__ . '/partials/payment_form.php';
                        ?>
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
