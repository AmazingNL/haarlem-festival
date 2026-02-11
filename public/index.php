<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

// Load .env
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $env = parse_ini_file($envFile);
    foreach ($env as $key => $value) {
        $_ENV[$key] = $value;
    }
}

// Error reporting
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// DB connection
try {
    $host   = $_ENV['DB_HOST'] ?? 'mysql';
    $dbname = $_ENV['DB_NAME'] ?? 'haarlem_festival';
    $user   = $_ENV['DB_USER'] ?? 'root';
    $pass   = $_ENV['DB_PASS'] ?? '';

    $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";

    $GLOBALS['pdo'] = new \PDO($dsn, $user, $pass, [
        \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    ]);
} catch (\PDOException $e) {
    die("Database connection failed. Tried host: {$host}. Error: " . $e->getMessage());
}

// Session
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Dispatch
$router = new \App\Core\Router();
$router->dispatch();
