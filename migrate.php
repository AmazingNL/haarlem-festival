<?php
// migrate.php - Simple migration runner

if (!isset($argv[1]) || !in_array($argv[1], ['up', 'down'])) {
    die("Usage: php migrate.php [up|down]\n");
}

$action = $argv[1];
$host = 'mysql';  // Docker service name
$user = 'root';
$pass = 'secret123';  // From docker-compose.yml MARIADB_ROOT_PASSWORD
$db = 'haarlem_festival';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    if ($action === 'up') {
        $migrations = glob(__DIR__ . '/db/migration/*.sql');
        sort($migrations);
        
        foreach ($migrations as $file) {
            echo "Running " . basename($file) . "...\n";
            $sql = file_get_contents($file);
            $pdo->exec($sql);
        }
        echo "Migrations completed!\n";
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage() . "\n");
}
?>