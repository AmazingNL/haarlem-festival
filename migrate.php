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

    $migrationTable = 'schema_migrations';

    $migrationFiles = glob(__DIR__ . '/db/migrations/*.sql') ?: [];
    sort($migrationFiles);
    $migrationChecksums = [];
    foreach ($migrationFiles as $file) {
        $migrationChecksums[basename($file)] = md5_file($file) ?: '';
    }

    $migrationTableExists = static function () use ($pdo, $db, $migrationTable): bool {
        $stmt = $pdo->prepare(
            "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = :db AND table_name = :table"
        );
        $stmt->execute([':db' => $db, ':table' => $migrationTable]);
        return (int) $stmt->fetchColumn() > 0;
    };

    $ensureMigrationTable = static function () use ($pdo, $db, $migrationTable): void {
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$db}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        $stmt = $pdo->prepare(
            "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = :db AND table_name = :table"
        );
        $stmt->execute([':db' => $db, ':table' => $migrationTable]);
        $tableExists = (int) $stmt->fetchColumn() > 0;

        if ($tableExists) {
            $stmt = $pdo->prepare(
                "SELECT column_name FROM information_schema.columns WHERE table_schema = :db AND table_name = :table"
            );
            $stmt->execute([':db' => $db, ':table' => $migrationTable]);
            $columns = array_map('strval', $stmt->fetchAll(PDO::FETCH_COLUMN));

            if (!in_array('filename', $columns, true) && in_array('version', $columns, true)) {
                $pdo->exec("ALTER TABLE `{$db}`.`{$migrationTable}` ADD COLUMN filename VARCHAR(255) NULL");
                $pdo->exec("ALTER TABLE `{$db}`.`{$migrationTable}` ADD COLUMN checksum CHAR(32) NULL");
                $pdo->exec("ALTER TABLE `{$db}`.`{$migrationTable}` ADD COLUMN applied_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP");
                $pdo->exec("UPDATE `{$db}`.`{$migrationTable}` SET filename = version, checksum = ''");
                $pdo->exec("ALTER TABLE `{$db}`.`{$migrationTable}` DROP PRIMARY KEY");
                $pdo->exec("ALTER TABLE `{$db}`.`{$migrationTable}` MODIFY version VARCHAR(128) NULL");
                $pdo->exec("ALTER TABLE `{$db}`.`{$migrationTable}` MODIFY filename VARCHAR(255) NOT NULL");
                $pdo->exec("ALTER TABLE `{$db}`.`{$migrationTable}` MODIFY checksum CHAR(32) NOT NULL");
                $pdo->exec("ALTER TABLE `{$db}`.`{$migrationTable}` ADD PRIMARY KEY (filename)");
            }

            if (in_array('filename', $columns, true) && in_array('version', $columns, true)) {
                $pdo->exec("ALTER TABLE `{$db}`.`{$migrationTable}` MODIFY version VARCHAR(128) NULL");
            }
        }

        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS `{$db}`.`{$migrationTable}` (
                filename VARCHAR(255) NOT NULL,
                checksum CHAR(32) NOT NULL,
                applied_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (filename)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );
    };

    $getAppliedMigrations = static function () use ($pdo, $db, $migrationTable): array {
        $stmt = $pdo->query("SELECT filename, checksum FROM `{$db}`.`{$migrationTable}` ORDER BY filename");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = [];
        foreach ($rows as $row) {
            $result[(string) $row['filename']] = (string) $row['checksum'];
        }
        return $result;
    };

    $markMigrationApplied = static function (string $filename, string $checksum) use ($pdo, $db, $migrationTable): void {
        $stmt = $pdo->prepare(
            "INSERT INTO `{$db}`.`{$migrationTable}` (filename, checksum, applied_at)
             VALUES (:filename, :checksum, NOW())
             ON DUPLICATE KEY UPDATE checksum = VALUES(checksum), applied_at = NOW()"
        );
        $stmt->execute([
            ':filename' => $filename,
            ':checksum' => $checksum,
        ]);
    };

    $schemaAlreadyInitialized = static function () use ($pdo, $db): bool {
        $stmt = $pdo->prepare(
            "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = :db AND table_name = 'page_section'"
        );
        $stmt->execute([':db' => $db]);
        return (int) $stmt->fetchColumn() > 0;
    };

    $captionColumnExists = static function () use ($pdo, $db): bool {
        $stmt = $pdo->prepare(
            "SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = :db AND table_name = 'image' AND column_name = 'caption'"
        );
        $stmt->execute([':db' => $db]);
        return (int) $stmt->fetchColumn() > 0;
    };

    $welcomeBannerCardTypeExists = static function () use ($pdo, $db): bool {
        $stmt = $pdo->prepare(
            "SELECT COUNT(*) FROM information_schema.columns
             WHERE table_schema = :db AND table_name = 'page_section' AND column_name = 'section_type'
             AND column_type LIKE '%welcome_banner_card%'"
        );
        $stmt->execute([':db' => $db]);
        return (int) $stmt->fetchColumn() > 0;
    };

    $sectionImageIdExists = static function () use ($pdo, $db): bool {
        $stmt = $pdo->prepare(
            "SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = :db AND table_name = 'page_section' AND column_name = 'image_id'"
        );
        $stmt->execute([':db' => $db]);
        return (int) $stmt->fetchColumn() > 0;
    };

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

    $applyMigrations = static function (array $files, bool $allowBaselineForExistingSchema) use (
        $pdo,
        $ensureMigrationTable,
        $getAppliedMigrations,
        $markMigrationApplied,
        $schemaAlreadyInitialized,
        $captionColumnExists,
        $welcomeBannerCardTypeExists,
        $sectionImageIdExists,
        $migrationChecksums
    ): void {
        $ensureMigrationTable();

        $applied = $getAppliedMigrations();

        $driftChecks = [
            '01_schema.sql' => $schemaAlreadyInitialized,
            '06_image_caption.sql' => $captionColumnExists,
            '07_page_section_welcome_banner_card.sql' => $welcomeBannerCardTypeExists,
            '08_drop_page_section_image_id.sql' => static fn (): bool => !$sectionImageIdExists(),
        ];

        // Fail fast if a migration is marked as applied but expected schema state is missing.
        $driftErrors = [];
        foreach ($applied as $name => $_checksum) {
            if (isset($driftChecks[$name]) && !$driftChecks[$name]()) {
                $driftErrors[] = $name;
            }
        }
        if ($driftErrors !== []) {
            echo "Schema drift detected. The following migrations are marked as applied but schema state does not match:\n";
            foreach ($driftErrors as $name) {
                echo " - {$name}\n";
            }
            echo "Stop and create a backup before proceeding.\n";
            exit(1);
        }

        // Baseline known migrations for legacy databases to avoid re-running destructive/duplicate steps.
        if ($allowBaselineForExistingSchema && $schemaAlreadyInitialized()) {
            $baselineCandidates = [
                '01_schema.sql' => $schemaAlreadyInitialized(),
                '06_image_caption.sql' => $captionColumnExists(),
                '07_page_section_welcome_banner_card.sql' => $welcomeBannerCardTypeExists(),
                '08_drop_page_section_image_id.sql' => !$sectionImageIdExists(),
            ];

            foreach ($baselineCandidates as $name => $canBaseline) {
                if ($canBaseline && !isset($applied[$name]) && isset($migrationChecksums[$name])) {
                    $markMigrationApplied($name, $migrationChecksums[$name]);
                    $applied[$name] = $migrationChecksums[$name];
                    echo "Baselined existing migration {$name}.\n";
                }
            }
        }

        foreach ($files as $file) {
            $name = basename($file);
            $checksum = $migrationChecksums[$name] ?? (md5_file($file) ?: '');

            if (isset($applied[$name])) {
                continue;
            }

            echo "Running migration {$name}...\n";
            $sql = file_get_contents($file);
            if ($sql === false) {
                throw new RuntimeException("Unable to read migration file: {$file}");
            }

            $pdo->exec($sql);
            $markMigrationApplied($name, $checksum);
            $applied[$name] = $checksum;
        }
    };

    if ($action === 'up') {
        $applyMigrations($migrationFiles, true);
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
    $applyMigrations($migrationFiles, false);
    $runSqlFiles(__DIR__ . '/db/seeds/*.sql', 'seed');
    echo "Reset completed!\n";
} catch (PDOException $e) {
    die('Error: ' . $e->getMessage() . "\n");
} catch (RuntimeException $e) {
    die('Error: ' . $e->getMessage() . "\n");
}
