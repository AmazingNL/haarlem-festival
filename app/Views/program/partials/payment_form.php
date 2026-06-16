<?php
$programTotal = (float) ($programTotal ?? 0);
$isLoggedIn = !empty($isLoggedIn);
$formatMoney = static fn(float $amount): string => 'EUR ' . number_format($amount, 2, '.', ',');
$requiresLogin = !$isLoggedIn;
$submitLabel = $isLoggedIn ? 'Check Out' : 'Login to Check Out';
?>

<h3 class="program-payment-title">Payment Overview</h3>
<?php if (empty($stripeConfigured)): ?>
    <p class="program-payment-note" style="color:#b42318;">
        Payments are not configured. Copy <code>.env.example</code> to <code>.env</code> and set
        <code>STRIPE_SECRET_KEY</code> (your <code>sk_test_...</code> key from Stripe Test mode).
    </p>
<?php else: ?>
    <p class="program-payment-note">
        Choose a payment option below. Check Out opens the secure third-party payment page.
    </p>
<?php endif; ?>

<?php if ($requiresLogin): ?>
    <div class="program-total-card">
        <span>Total to be paid</span>
        <strong><?= htmlspecialchars($formatMoney($programTotal), ENT_QUOTES, 'UTF-8') ?></strong>
    </div>
    <a href="/loginForm?next=/program" class="program-checkout-button">Login to Check Out</a>
<?php else: ?>
    <form method="post" action="/checkout/pay" class="program-payment-form">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) ($csrf ?? ''), ENT_QUOTES, 'UTF-8') ?>">

        <fieldset class="program-payment-options">
            <legend>Choose a payment method</legend>

            <label class="program-payment-option">
                <input type="radio" name="payment_provider" value="card" required>
                <span class="program-payment-option__card">
                    <span class="program-payment-option__badge" aria-hidden="true">CC</span>
                    <span class="program-payment-option__content">
                        <strong>Credit Card</strong>
                        <small>Mastercard, Visa</small>
                    </span>
                    <span class="program-payment-option__indicator" aria-hidden="true"></span>
                </span>
            </label>

            <label class="program-payment-option">
                <input type="radio" name="payment_provider" value="ideal" required checked>
                <span class="program-payment-option__card">
                    <span class="program-payment-option__badge" aria-hidden="true">iD</span>
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

        <button type="submit" class="program-checkout-button"<?= empty($stripeConfigured) ? ' disabled' : '' ?>>
            <?= htmlspecialchars($submitLabel, ENT_QUOTES, 'UTF-8') ?>
        </button>
    </form>
<?php endif; ?>
