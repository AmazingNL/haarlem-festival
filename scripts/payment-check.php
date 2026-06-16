<?php
/**
 * Verify payment configuration and database tables.
 * Run: docker compose exec -T php php /app/scripts/payment-check.php
 */
declare(strict_types=1);

require __DIR__ . '/../app/bootstrap.php';
require __DIR__ . '/../vendor/autoload.php';

use App\Support\StripeConfig;
use Stripe\Stripe;
use Stripe\Checkout\Session;

$ok = true;
$envFile = dirname(__DIR__) . '/.env';

function line(string $status, string $message): void
{
    echo '[' . $status . '] ' . $message . PHP_EOL;
}

if (!is_file($envFile)) {
    line('FAIL', '.env file missing — run: copy .env.example .env');
    $ok = false;
} else {
    line('OK', '.env file exists');
}

if (!StripeConfig::isConfigured()) {
    line('FAIL', 'STRIPE_SECRET_KEY missing or invalid (must start with sk_)');
    $ok = false;
} else {
    $key = StripeConfig::secretKey();
    $mode = str_starts_with($key, 'sk_test_') ? 'test' : (str_starts_with($key, 'sk_live_') ? 'live' : 'unknown');
    line('OK', 'Stripe secret key present (' . $mode . ' mode)');
}

line('INFO', 'APP_URL = ' . StripeConfig::appUrl());

try {
    $pdo = new PDO(
        'mysql:host=mysql;dbname=haarlem_festival;charset=utf8mb4',
        'root',
        'secret123',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $tables = ['pending_stripe_checkout', 'order_line', 'payment', 'order'];
    foreach ($tables as $table) {
        $stmt = $pdo->prepare(
            'SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = ? AND table_name = ?'
        );
        $stmt->execute(['haarlem_festival', $table]);
        if ((int) $stmt->fetchColumn() === 0) {
            line('FAIL', "Table missing: {$table} — run: php migrate.php up");
            $ok = false;
        } else {
            line('OK', "Table exists: {$table}");
        }
    }
} catch (Throwable $e) {
    line('FAIL', 'Database: ' . $e->getMessage());
    $ok = false;
}

if (StripeConfig::isConfigured()) {
    try {
        Stripe::setApiKey(StripeConfig::secretKey());
        $session = Session::create([
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => ['name' => 'Payment check'],
                    'unit_amount' => 500,
                ],
                'quantity' => 1,
            ]],
            'success_url' => StripeConfig::appUrl() . '/checkout/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => StripeConfig::appUrl() . '/checkout/cancel',
        ]);
        line('OK', 'Stripe API accepted checkout session: ' . $session->id);
        line('INFO', 'Checkout URL created successfully (session not opened)');
    } catch (Throwable $e) {
        line('FAIL', 'Stripe API: ' . $e->getMessage());
        $ok = false;
    }
}

echo PHP_EOL;
if ($ok) {
    line('OK', 'Payment setup looks good. Log in, add to My Program, then Check Out.');
    exit(0);
}

line('FAIL', 'Fix the issues above, then run: docker compose up -d php nginx');
exit(1);
