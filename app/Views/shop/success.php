<?php
$order = is_array($order ?? null) ? $order : [];
$orderItems = is_array($orderItems ?? null) ? $orderItems : [];
$paymentLabels = [
    'ideal' => 'iDEAL',
    'card' => 'Credit Card',
    'card-ideal' => 'Credit Card / iDEAL',
    'stripe-ideal' => 'iDEAL',
    'stripe-card' => 'Credit Card',
    'stripe-ideal-card' => 'Credit Card / iDEAL',
];
$providerLabel = $paymentLabels[(string) ($order['provider'] ?? '')] ?? 'Third-party payment';
$deliveryEmail = trim((string) ($order['email'] ?? ''));
$formatMoney = static fn(float $amount): string => 'EUR ' . number_format($amount, 2, '.', ',');
?>

<section class="shop-page">
    <div class="shop-container">
        <article class="shop-card shop-card--success">
            <div class="shop-card__body">
                <p class="shop-eyebrow">Payment complete</p>
                <h1>Your order is ready</h1>
                <p>Order #<?= (int) ($order['order_id'] ?? 0) ?> has been paid successfully.</p>

                <dl class="shop-detail-list">
                    <div>
                        <dt>Total paid</dt>
                        <dd><?= htmlspecialchars($formatMoney((float) ($order['total_price'] ?? 0)), ENT_QUOTES, 'UTF-8') ?></dd>
                    </div>
                    <div>
                        <dt>Bookings</dt>
                        <dd><?= count($orderItems) ?></dd>
                    </div>
                    <div>
                        <dt>Payment method</dt>
                        <dd><?= htmlspecialchars($providerLabel, ENT_QUOTES, 'UTF-8') ?></dd>
                    </div>
                    <div>
                        <dt>Delivery email</dt>
                        <dd><?= htmlspecialchars($deliveryEmail !== '' ? $deliveryEmail : 'No email on account', ENT_QUOTES, 'UTF-8') ?></dd>
                    </div>
                </dl>

                <?php if ($deliveryEmail !== ''): ?>
                    <p class="shop-status-note">
                        Account email: <?= htmlspecialchars($deliveryEmail, ENT_QUOTES, 'UTF-8') ?>.
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
