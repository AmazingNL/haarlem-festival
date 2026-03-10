<?php
/* declare(strict_types=1);

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

        if (!$userId || $role !== 'admin') {
            http_response_code(403);
            $accept = strtolower($_SERVER['HTTP_ACCEPT'] ?? '');
            $wantsJson = str_contains($accept, 'application/json');
            if ($wantsJson) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['error' => 'Forbidden'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                exit;
            }

            // In development, print session debug info to help diagnose missing session data
            $debug = (($_ENV['APP_DEBUG'] ?? 'false') === 'true');
            if ($debug) {
                echo '<h1>403 - Forbidden</h1>';
                echo '<pre>SESSION: ' . htmlspecialchars(var_export($_SESSION, true), ENT_QUOTES, 'UTF-8') . '</pre>';
            } else {
                echo '403 - Forbidden';
            }
            exit;
        } 
    }
}
