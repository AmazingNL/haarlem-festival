<?php
// migrate.php - Schema migrations and seeds runner.

$validActions = ['up', 'seed', 'reset'];
$action = $argv[1] ?? null;
$forceReset = in_array('--force', $argv, true);

if (!$action || !in_array($action, $validActions, true)) {
    die("Usage: php migrate.php [up|seed|reset] [--force]\n");
}

$host = 'mysql';  // Docker service name
$user = 'root';
$pass = 'secret123';  // From docker-compose.yml MARIADB_ROOT_PASSWORD
$db = 'haarlem_festival';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $runSqlFiles = function (string $pattern, string $label) use ($pdo): void {
        $files = glob($pattern);
        sort($files);

        if (empty($files)) {
            echo "No {$label} files found for pattern: {$pattern}\n";
            return;
        }

        foreach ($files as $file) {
            echo "Running {$label} " . basename($file) . "...\n";
            $sql = file_get_contents($file);
            $pdo->exec($sql);
        }
    };

    if ($action === 'up') {
        $runSqlFiles(__DIR__ . '/db/migrations/*.sql', 'migration');
        echo "Schema migrations completed!\n";
        exit(0);
    }

    if ($action === 'seed') {
        $runSqlFiles(__DIR__ . '/db/seeds/*.sql', 'seed');
        echo "Seed scripts completed!\n";
        exit(0);
    }

    if (!$forceReset) {
        die(
            "Refusing destructive reset without confirmation.\n" .
            "Run: php migrate.php reset --force\n"
        );
    }

    $runSqlFiles(__DIR__ . '/db/reset/*.sql', 'reset');
    $runSqlFiles(__DIR__ . '/db/migrations/*.sql', 'migration');
    $runSqlFiles(__DIR__ . '/db/seeds/*.sql', 'seed');
    echo "Reset completed!\n";
} catch (PDOException $e) {
    die('Error: ' . $e->getMessage() . "\n");
}
