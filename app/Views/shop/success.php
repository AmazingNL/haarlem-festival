<?php

$order = is_array($order ?? null) ? $order : [];

$orderItems = is_array($orderItems ?? null) ? $orderItems : [];

$orderId = (int) ($order['order_id'] ?? 0);

$providerLabel = \App\Support\PaymentProvider::label((string) ($order['provider'] ?? ''));

$deliveryEmail = trim((string) ($order['email'] ?? ''));
if ($deliveryEmail === '') {
    $deliveryEmail = \App\Support\SessionUser::email();
}

$bookingCount = count($orderItems);

if ($bookingCount === 0 && (float) ($order['total_price'] ?? 0) > 0) {

    $bookingCount = 1;

}

$primaryTitle = trim((string) ($orderItems[0]['title'] ?? ''));

$formatMoney = static fn(float $amount): string => 'EUR ' . number_format($amount, 2, '.', ',');

?>



<section class="shop-page">

    <div class="shop-container">

        <article class="shop-card shop-card--success">

            <div class="shop-card__body">

                <p class="shop-eyebrow">Payment complete</p>

                <h1><?= $primaryTitle !== '' ? htmlspecialchars($primaryTitle, ENT_QUOTES, 'UTF-8') : 'Your order is ready' ?></h1>

                <p>

                    Order #<?= $orderId ?> has been paid successfully.

                    <?php if ($primaryTitle !== ''): ?>

                        Your booking is confirmed.

                    <?php endif; ?>

                </p>



                <?php if ($orderItems !== []): ?>

                    <div class="shop-booking-list">

                        <?php foreach ($orderItems as $item): ?>

                            <article class="shop-booking-item">

                                <h2><?= htmlspecialchars((string) ($item['title'] ?? 'Booking'), ENT_QUOTES, 'UTF-8') ?></h2>

                                <?php if (!empty($item['selection_text'])): ?>

                                    <p><?= htmlspecialchars((string) $item['selection_text'], ENT_QUOTES, 'UTF-8') ?></p>

                                <?php endif; ?>

                                <ul class="shop-booking-item__meta">

                                    <?php if (!empty($item['ticket_title'])): ?>

                                        <li><span>Ticket</span><strong><?= htmlspecialchars((string) $item['ticket_title'], ENT_QUOTES, 'UTF-8') ?></strong></li>

                                    <?php endif; ?>

                                    <?php if (!empty($item['ticket_summary_text'])): ?>

                                        <li><span>Guests</span><strong><?= htmlspecialchars((string) $item['ticket_summary_text'], ENT_QUOTES, 'UTF-8') ?></strong></li>

                                    <?php endif; ?>

                                    <?php if (!empty($item['location_name'])): ?>

                                        <li><span>Location</span><strong><?= htmlspecialchars((string) $item['location_name'], ENT_QUOTES, 'UTF-8') ?></strong></li>

                                    <?php endif; ?>

                                    <li><span>Price</span><strong><?= htmlspecialchars($formatMoney((float) ($item['line_total'] ?? $item['total_price'] ?? 0)), ENT_QUOTES, 'UTF-8') ?></strong></li>

                                </ul>

                            </article>

                        <?php endforeach; ?>

                    </div>

                <?php endif; ?>



                <dl class="shop-detail-list">

                    <div>

                        <dt>Total paid</dt>

                        <dd><?= htmlspecialchars($formatMoney((float) ($order['total_price'] ?? 0)), ENT_QUOTES, 'UTF-8') ?></dd>

                    </div>

                    <div>

                        <dt>Bookings</dt>

                        <dd><?= $bookingCount ?></dd>

                    </div>

                    <div>

                        <dt>Payment method</dt>

                        <dd><?= htmlspecialchars($providerLabel, ENT_QUOTES, 'UTF-8') ?></dd>

                    </div>

                    <div>

                        <dt>Confirmation email</dt>

                        <dd><?= htmlspecialchars($deliveryEmail !== '' ? $deliveryEmail : 'No email on account', ENT_QUOTES, 'UTF-8') ?></dd>

                    </div>

                </dl>



                <?php if ($deliveryEmail !== ''): ?>

                    <p class="shop-status-note">

                        We saved this confirmation to your account (<?= htmlspecialchars($deliveryEmail, ENT_QUOTES, 'UTF-8') ?>).

                    </p>

                <?php endif; ?>



                <div class="shop-total-card__actions">

                    <a href="/program" class="shop-button">Open My Program</a>

                    <a href="/home" class="shop-link-button">Back to home</a>

                </div>

            </div>

        </article>

    </div>

</section>

