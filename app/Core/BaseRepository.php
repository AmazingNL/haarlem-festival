<?php

namespace App\Core;

use App\Config;
use PDO;

class BaseRepository
{
    protected PDO $connection;
    private static ?PDO $sharedConnection = null;

    public function __construct()
    {
        if (self::$sharedConnection === null) {
            // Use Config methods/constants for DB connection details
            $dsn = 'mysql:host=' . Config::DB_SERVER_NAME
                . ';dbname=' . Config::dbName()
                . ';charset=utf8mb4';

            $username = Config::dbUser();
            $password = Config::dbPassword();

            self::$sharedConnection = new PDO($dsn, $username, $password);

            self::$sharedConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$sharedConnection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }

        $this->connection = self::$sharedConnection;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /* ============================
        TRANSACTION HELPERS
       ============================ */

    public function beginTransaction(): void
    {
        if (!$this->connection->inTransaction()) {
            $this->connection->beginTransaction();
        }
    }

    public function commit(): void
    {
        if ($this->connection->inTransaction()) {
            $this->connection->commit();
        }
    }

    public function rollBack(): void
    {
        if ($this->connection->inTransaction()) {
            $this->connection->rollBack();
        }
    }
}
