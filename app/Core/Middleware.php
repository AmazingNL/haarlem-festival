<?php
declare(strict_types=1);

namespace App\Core;

final class Middleware
{
    public static function requireAdmin(): void
    {
        // For admin pages, we only trust the admin session cookie
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_name('HF_ADMIN');
            session_start();
        }

        $userId = $_SESSION['user_id'] ?? null;
        $role = $_SESSION['role'] ?? null;

/*         if (!$userId || $role !== 'admin') {
            http_response_code(403);
            echo '403 - Forbidden';
            exit;
        } */
    }
}
