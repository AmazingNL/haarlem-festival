<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Models/Enum.php';

/**
 * Load .env into $_ENV (simple version).
 * INI_SCANNER_RAW avoids PHP auto-converting values in unexpected ways.
 */
$envFile = __DIR__ . '/../.env';
if (is_file($envFile)) {
    $env = parse_ini_file($envFile, false, INI_SCANNER_RAW);
    foreach ($env as $k => $v) {
        $_ENV[$k] = $v;
    }
}

$debug = ($_ENV['APP_DEBUG'] ?? 'false') === 'true';
error_reporting(E_ALL);
ini_set('display_errors', $debug ? '1' : '0');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$router = new \App\Core\Router();
$router->dispatch();

