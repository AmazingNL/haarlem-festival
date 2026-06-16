<?php

declare(strict_types=1);

/**
 * Application bootstrap: environment, errors, session.
 * Must not output anything before session_start().
 */
if (!ob_get_level()) {
    ob_start();
}

function app_load_env(string $rootDir): void
{
    $envFile = $rootDir . '/.env';
    if (!is_file($envFile)) {
        return;
    }

    $loaded = false;
    $parsed = parse_ini_file($envFile, false, INI_SCANNER_RAW);
    if (is_array($parsed)) {
        foreach ($parsed as $key => $value) {
            if (!is_string($key) || !is_string($value)) {
                continue;
            }
            $_ENV[$key] = $value;
            putenv($key . '=' . $value);
        }
        $loaded = true;
    }

    if (!$loaded) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES);
        if (is_array($lines)) {
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '' || str_starts_with($line, '#')) {
                    continue;
                }
                if (!str_contains($line, '=')) {
                    continue;
                }
                [$key, $value] = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value, " \t\"'");
                if ($key === '') {
                    continue;
                }
                $_ENV[$key] = $value;
                putenv($key . '=' . $value);
            }
        }
    }

    foreach (['APP_URL', 'APP_DEBUG', 'STRIPE_SECRET_KEY', 'STRIPE_WEBHOOK_SECRET', 'STRIPE_DISPLAY_NAME', 'DB_USER', 'DB_PASS', 'DB_NAME'] as $envKey) {
        $fromEnv = getenv($envKey);
        if ($fromEnv !== false && $fromEnv !== '') {
            $_ENV[$envKey] = (string) $fromEnv;
        }
    }
}

function app_configure_errors(): void
{
    $debug = ($_ENV['APP_DEBUG'] ?? 'false') === 'true';
    error_reporting(E_ALL);
    ini_set('log_errors', '1');
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');

    $logDir = dirname(__DIR__) . '/logs/php';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0775, true);
    }
    if (is_dir($logDir) && is_writable($logDir)) {
        ini_set('error_log', $logDir . '/php-error.log');
    }

    if ($debug) {
        set_error_handler(static function (int $severity, string $message, string $file, int $line): bool {
            if (!(error_reporting() & $severity)) {
                return false;
            }
            error_log(sprintf('PHP %s: %s in %s on line %d', $severity, $message, $file, $line));
            return true;
        });
    }
}

function app_start_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    ini_set('session.use_strict_mode', '1');
    ini_set('session.cookie_httponly', '1');
    session_start();
}

$appRoot = dirname(__DIR__);
app_load_env($appRoot);
app_configure_errors();

if (PHP_SAPI !== 'cli') {
    app_start_session();
}
