<?php

namespace App;

class Config
{
    public const DB_SERVER_NAME = 'mysql';

    public static function dbUser(): string
    {
        return $_ENV['DB_USER'] ?? 'developer';
    }

    public static function dbPassword(): string
    {
        return $_ENV['DB_PASS'] ?? 'secret123';
    }

    public static function dbName(): string
    {
        return $_ENV['DB_NAME'] ?? 'haarlem_festival';
    }
}
