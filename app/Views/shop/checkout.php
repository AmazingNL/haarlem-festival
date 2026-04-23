<?php
$programItems = is_array($programItems ?? null) ? $programItems : [];
$programTotal = (float) ($programTotal ?? 0);
$deliveryEmail = trim((string) ($customerEmail ?? ''));
$selectedTickets = 0;
foreach ($programItems as $programItem) {
    $selectedTickets += max(1, (int) ($programItem['quantity'] ?? 1));
}
$formatMoney = static fn(float $amount): string => 'EUR ' . number_format($amount, 2, '.', ',');
?>

<section class="shop-page">
    <div class="shop-container">
        <header class="shop-hero shop-hero--small">
            <p class="shop-eyebrow">Checkout</p>
            <h1>Review and pay</h1>
            <p>Check your booking details below.</p>
        </header>

        <div class="shop-summary-layout">
            <div class="shop-order-list">
                <article class="shop-card">
                    <div class="shop-card__body">
                        <h2>Customer</h2>
                        <dl class="shop-detail-list">
                            <div>
                                <dt>Name</dt>
                                <dd><?= htmlspecialchars((string) ($customerName ?? ''), ENT_QUOTES, 'UTF-8') ?></dd>
                            </div>
                            <div>
                                <dt>Email</dt>
                                <dd><?= htmlspecialchars($deliveryEmail !== '' ? $deliveryEmail : 'Email will be requested during checkout', ENT_QUOTES, 'UTF-8') ?></dd>
                            </div>
                            <div>
                                <dt>Phone</dt>
                                <dd><?= htmlspecialchars((string) (($customerPhone ?? '') !== '' ? $customerPhone : 'Not provided'), ENT_QUOTES, 'UTF-8') ?></dd>
                            </div>
                        </dl>

                        <p class="shop-card__note">
                            After successful payment, you will return to the confirmation page.
                        </p>
                    </div>
                </article>

                <?php foreach ($programItems as $programItem): ?>
                    <article class="shop-card">
                        <div class="shop-card__body">
                            <p class="shop-card__eyebrow"><?= htmlspecialchars((string) ($programItem['location_name'] ?? 'Bavo Church'), ENT_QUOTES, 'UTF-8') ?></p>
                            <h2><?= htmlspecialchars((string) ($programItem['title'] ?? 'Festival Booking'), ENT_QUOTES, 'UTF-8') ?></h2>
                            <p class="shop-card__meta"><?= htmlspecialchars((string) ($programItem['selection_text'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>

                            <dl class="shop-detail-list">
                                <div>
                                    <dt>Ticket type</dt>
                                    <dd><?= htmlspecialchars((string) ($programItem['ticket_summary_text'] ?? ''), ENT_QUOTES, 'UTF-8') ?></dd>
                                </div>
                                <div>
                                    <dt>Quantity</dt>
                                    <dd><?= (int) ($programItem['quantity'] ?? 0) ?></dd>
                                </div>
                                <div>
                                    <dt>Total</dt>
                                    <dd><?= htmlspecialchars($formatMoney((float) ($programItem['total_price'] ?? 0)), ENT_QUOTES, 'UTF-8') ?></dd>
                                </div>
                            </dl>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <aside class="shop-card shop-total-card">
                <div class="shop-card__body">
                    <h2>Program Summary</h2>

                    <div class="shop-summary-line">
                        <span>Total Tickets</span>
                        <strong class="shop-summary-line__value"><?= $selectedTickets ?></strong>
                    </div>

                    <div class="shop-summary-divider" aria-hidden="true"></div>

                    <h3 class="shop-payment-title">Payment Overview</h3>
                    <p class="shop-payment-note">Choose a payment option below. Check Out opens the third-party payment page.</p>

                    <form method="post" action="/checkout/pay" class="shop-payment-form">
                        <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) ($csrf ?? ''), ENT_QUOTES, 'UTF-8') ?>">

                        <fieldset class="shop-payment-options">
                            <legend>Choose a payment method</legend>

                            <label class="shop-payment-option">
                                <input type="radio" name="payment_provider" value="card" required>
                                <span class="shop-payment-option__card">
                                    <span class="shop-payment-option__badge" aria-hidden="true">CC</span>
                                    <span class="shop-payment-option__content">
                                        <strong>Credit Card</strong>
                                        <small>Mastercard, Visa</small>
                                    </span>
                                    <span class="shop-payment-option__indicator" aria-hidden="true"></span>
                                </span>
                            </label>

                            <label class="shop-payment-option">
                                <input type="radio" name="payment_provider" value="ideal" required>
                                <span class="shop-payment-option__card">
                                    <span class="shop-payment-option__badge" aria-hidden="true">iD</span>
                                    <span class="shop-payment-option__content">
                                        <strong>iDEAL</strong>
                                        <small>Pay with your bank</small>
                                    </span>
                                    <span class="shop-payment-option__indicator" aria-hidden="true"></span>
                                </span>
                            </label>
                        </fieldset>

                        <div class="shop-total-banner">
                            <span>Total to be paid</span>
                            <strong><?= htmlspecialchars($formatMoney($programTotal), ENT_QUOTES, 'UTF-8') ?></strong>
                        </div>

                        <?php if ($deliveryEmail !== ''): ?>
                            <p class="shop-payment-help">
                                Account email: <?= htmlspecialchars($deliveryEmail, ENT_QUOTES, 'UTF-8') ?>.
                            </p>
                        <?php endif; ?>

                        <button type="submit" class="shop-button">Check Out</button>
                    </form>
                </div>
            </aside>
        </div>
    </div>
</section>
