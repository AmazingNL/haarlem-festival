<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Remembers and resolves where to send the user after login or registration.
 */
final class AuthRedirect
{
    private const SESSION_KEY = 'auth_redirect';

    public static function remember(?string $requestedPath): string
    {
        self::ensureSession();

        $path = self::sanitize($requestedPath ?? '');
        if ($path !== '') {
            $_SESSION[self::SESSION_KEY] = $path;
            return $path;
        }

        return self::sanitize((string) ($_SESSION[self::SESSION_KEY] ?? ''));
    }

    public static function targetAfterLogin(string $role, string $requestedNext = ''): string
    {
        if ($role === 'admin') {
            unset($_SESSION[self::SESSION_KEY]);
            return '/admin/dashboard';
        }

        unset($_SESSION['admin']);

        $next = self::sanitize($requestedNext);
        if ($next !== '') {
            unset($_SESSION[self::SESSION_KEY]);
            return $next;
        }

        $saved = self::sanitize((string) ($_SESSION[self::SESSION_KEY] ?? ''));
        if ($saved !== '') {
            unset($_SESSION[self::SESSION_KEY]);
            return $saved;
        }

        if ($role === 'employee') {
            return '/employee/dashboard';
        }

        return '/';
    }

    public static function sanitize(string $path): string
    {
        $path = trim($path);
        if ($path === '' || !str_starts_with($path, '/') || str_starts_with($path, '/admin')) {
            return '';
        }

        return $path;
    }

    private static function ensureSession(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
}
