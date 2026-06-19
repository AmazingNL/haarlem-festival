<?php

declare(strict_types=1);

require __DIR__ . '/../app/bootstrap.php';
require __DIR__ . '/../vendor/autoload.php';

use App\Services\Mailer;

$to = trim((string) ($argv[1] ?? 'test@example.com'));
if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
    fwrite(STDERR, "Usage: php scripts/test_email.php recipient@example.com\n");
    exit(1);
}

echo 'SMTP: ' . ($_ENV['SMTP_HOST'] ?? 'mailpit') . ':' . ($_ENV['SMTP_PORT'] ?? '1025') . "\n";

try {
    (new Mailer())->send(
        $to,
        'Test User',
        'Haarlem Festival — SMTP test',
        '<p>SMTP is working.</p>',
        'SMTP is working.'
    );
    echo "OK — email sent.\n";
} catch (Throwable $e) {
    fwrite(STDERR, 'FAILED: ' . $e->getMessage() . "\n");
    exit(1);
}
