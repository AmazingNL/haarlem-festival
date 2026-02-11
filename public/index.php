<?php
declare(strict_types=1);

// 1. Load Composer autoloader
require __DIR__ . '/../vendor/autoload.php';

// 2. Load .env (Your original logic)
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $env = parse_ini_file($envFile);
    foreach ($env as $key => $value) {
        $_ENV[$key] = $value;
    }
}

// 3. Enable Error Reporting (To see errors instead of a white screen)
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// 4. Create Database Connection
try {
    // Check if DB_HOST is in .env, otherwise default to 'mysql' (common for this project)
    $host = $_ENV['DB_HOST'] ?? 'mysql'; 
    $dbname = $_ENV['DB_NAME'] ?? 'haarlem_festival';
    $user = $_ENV['DB_USER'] ?? 'root';
    $pass = $_ENV['DB_PASS'] ?? '';

    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    
    $GLOBALS['pdo'] = new \PDO($dsn, $user, $pass, [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
    ]);
} catch (\PDOException $e) {
    // If it fails, this message will tell us EXACTLY which host it tried to reach
    die("Database connection failed. Tried host: " . $host . ". Error: " . $e->getMessage());
}

// 5. Start Session
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// 6. Start the Application
$router = new \App\Core\Router();
$router->dispatch();