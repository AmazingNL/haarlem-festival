<?php
declare(strict_types=1);

// Load Composer autoloader
require __DIR__ . '/../vendor/autoload.php';

// Load .env (simple version, no library)
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $env = parse_ini_file($envFile);
    foreach ($env as $key => $value) {
        $_ENV[$key] = $value;
    }
}
