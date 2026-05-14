<?php
/**
 * Database Status Helper
 *
 * Usage:
 *   php scripts/db-status.php
 */

$host = 'mysql';
$user = 'developer';
$pass = 'secret123';
$db = 'haarlem_festival';
$migrationTable = 'schema_migrations';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = ?");
    $stmt->execute([$db]);
    $dbExists = (int) $stmt->fetchColumn() > 0;

    if (!$dbExists) {
        echo "Database '$db' does not exist.\n";
        echo "Run: php migrate.php up\n";
        exit(1);
    }

    $migrationFiles = glob(__DIR__ . '/../db/migrations/*.sql') ?: [];
    sort($migrationFiles);

    echo "=== DATABASE STATUS ===\n\n";
    echo "Database: $db\n";
    echo "Host: $host\n";
    echo "Connection: OK\n\n";

    $stmt = $pdo->prepare(
        "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = ? AND table_name = ?"
    );
    $stmt->execute([$db, $migrationTable]);
    $hasMigrationTable = (int) $stmt->fetchColumn() > 0;

    $applied = [];
    if ($hasMigrationTable) {
        $stmt = $pdo->query("SELECT filename, checksum, applied_at FROM `{$db}`.`{$migrationTable}` ORDER BY filename");
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $applied[(string) $row['filename']] = [
                'checksum' => (string) $row['checksum'],
                'applied_at' => (string) $row['applied_at'],
            ];
        }
    }

    $legacyChecks = [
        '01_schema.sql' => static function () use ($pdo, $db): bool {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = ? AND table_name = 'page_section'");
            $stmt->execute([$db]);
            return (int) $stmt->fetchColumn() > 0;
        },
        '06_image_caption.sql' => static function () use ($pdo, $db): bool {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = ? AND table_name = 'image' AND column_name = 'caption'");
            $stmt->execute([$db]);
            return (int) $stmt->fetchColumn() > 0;
        },
        '07_page_section_welcome_banner_card.sql' => static function () use ($pdo, $db): bool {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = ? AND table_name = 'page_section' AND column_name = 'section_type' AND column_type LIKE '%welcome_banner_card%'");
            $stmt->execute([$db]);
            return (int) $stmt->fetchColumn() > 0;
        },
        '08_drop_page_section_image_id.sql' => static function () use ($pdo, $db): bool {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = ? AND table_name = 'page_section' AND column_name = 'image_id'");
            $stmt->execute([$db]);
            return (int) $stmt->fetchColumn() === 0;
        },
    ];

    echo "MIGRATION STATUS:\n";
    foreach ($migrationFiles as $file) {
        $name = basename($file);
        if (isset($applied[$name])) {
            $status = 'APPLIED';
            $meta = ' (' . $applied[$name]['applied_at'] . ')';
        } elseif (!$hasMigrationTable && isset($legacyChecks[$name]) && $legacyChecks[$name]()) {
            $status = 'LEGACY-APPLIED';
            $meta = ' (detected from schema state)';
        } else {
            $status = 'PENDING';
            $meta = '';
        }

        echo "  [$status] $name$meta\n";
    }

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA = ?");
    $stmt->execute([$db]);
    $tableCount = (int) $stmt->fetchColumn();

    echo "\nTables in database: $tableCount\n";

    $stmt = $pdo->prepare("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? ORDER BY TABLE_NAME");
    $stmt->execute([$db]);
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (!empty($tables)) {
        echo "\nTABLES:\n";
        foreach ($tables as $table) {
            echo "  - $table\n";
        }
    }

    echo "\nDatabase status check completed.\n";
    exit(0);
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
    exit(1);
}
