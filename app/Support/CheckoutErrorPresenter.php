<?php

declare(strict_types=1);

namespace App\Support;

use Throwable;

/**
 * Turns a checkout/payment exception into a safe, user-facing message.
 *
 * This keeps the exception-to-copy mapping out of ShopController. $phase is
 * either 'start' (opening the Stripe session) or 'complete' (finalising the
 * order), which tailors the fallback wording.
 */
final class CheckoutErrorPresenter
{
    public static function message(Throwable $e, string $phase): string
    {
        $message = $e->getMessage();

        if (stripos($message, 'Stripe secret key') !== false) {
            return 'Payments are not configured. Set STRIPE_SECRET_KEY in .env.';
        }

        if (stripos($message, 'pending_stripe_checkout') !== false) {
            return 'Payment tables are missing. Run: docker compose exec php php /app/migrate.php up';
        }

        if ($phase === 'start' && (stripos($message, 'unit_amount') !== false || stripos($message, 'minimum') !== false)) {
            return 'One of the items has an invalid price. Remove it from My Program and add it again.';
        }

        if ($phase === 'complete') {
            if (stripos($message, 'order_ticket') !== false || stripos($message, 'ticket_type_id') !== false) {
                return 'Ticket tables need updating. Run: docker compose exec php php /app/migrate.php up';
            }

            if (stripos($message, 'Checkout session could not be matched') !== false) {
                return 'We could not match your payment session. Please contact support with your payment confirmation.';
            }

            if (stripos($message, 'amount mismatch') !== false) {
                return 'The paid amount did not match your cart. Contact support if money was taken.';
            }
        }

        if (($_ENV['APP_DEBUG'] ?? 'false') === 'true' && $message !== '') {
            return ($phase === 'complete' ? 'The order could not be completed: ' : 'Payment could not start: ') . $message;
        }

        return $phase === 'complete'
            ? 'The order could not be completed right now.'
            : 'The payment could not be started right now.';
    }
}
