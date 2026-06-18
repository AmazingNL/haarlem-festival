<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\User;
use App\Repositories\UserRepository;

/** Stores and reads the logged-in user from the PHP session. */
final class SessionUser
{
    public static function hydrateFromDatabaseIfNeeded(): void
    {
        if (!self::isLoggedIn()) {
            return;
        }

        try {
            $user = (new UserRepository())->findUserById((int) $_SESSION['user_id']);
            if ($user !== null) {
                self::storeInSession($user);
            }
        } catch (\Throwable $e) {
            error_log('Session user hydration failed: ' . $e->getMessage());
        }
    }

    public static function storeInSession(User $user): void
    {
        $role = $user->role instanceof \App\Models\Enum\UserRole
            ? $user->role->value
            : strtolower((string) $user->role);

        $_SESSION['user_id'] = $user->user_id;
        $_SESSION['user_role'] = $role;
        $_SESSION['user_username'] = trim($user->username);
        $_SESSION['user_first_name'] = trim($user->first_name);
        $_SESSION['user_last_name'] = trim($user->last_name);
        $_SESSION['user_name'] = self::fullName($user->first_name, $user->last_name, $user->username, $user->email);
        $_SESSION['user_email'] = trim($user->email);
        $_SESSION['user_phone'] = $user->phone;
    }

    public static function isLoggedIn(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE && !empty($_SESSION['user_id']);
    }

    public static function displayName(): string
    {
        if (!self::isLoggedIn()) {
            return '';
        }

        $fullName = self::fullName(
            (string) ($_SESSION['user_first_name'] ?? ''),
            (string) ($_SESSION['user_last_name'] ?? '')
        );
        if ($fullName !== '') {
            return $fullName;
        }

        $username = trim((string) ($_SESSION['user_username'] ?? ''));
        if ($username !== '') {
            return $username;
        }

        return trim((string) ($_SESSION['user_email'] ?? ''));
    }

    public static function email(): string
    {
        return self::isLoggedIn() ? trim((string) ($_SESSION['user_email'] ?? '')) : '';
    }

    /** Profile fields used at checkout and for reservations. */
    public static function customerData(): array
    {
        return [
            'first_name' => trim((string) ($_SESSION['user_first_name'] ?? '')),
            'last_name' => trim((string) ($_SESSION['user_last_name'] ?? '')),
            'email' => self::email(),
            'phone' => trim((string) ($_SESSION['user_phone'] ?? '')),
        ];
    }

    private static function fullName(string $firstName, string $lastName, string $fallback = '', string $email = ''): string
    {
        $firstName = trim($firstName);
        $lastName = trim($lastName);

        if ($firstName !== '' && $lastName !== '') {
            return $firstName . ' ' . $lastName;
        }

        if ($firstName !== '') {
            return $firstName;
        }

        if ($lastName !== '') {
            return $lastName;
        }

        $fallback = trim($fallback);
        if ($fallback !== '') {
            return $fallback;
        }

        return trim($email);
    }
}
