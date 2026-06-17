<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\User;
use App\Repositories\UserRepository;

/**
 * Keeps session user fields in sync with the database and resolves a display name.
 */
final class SessionUser
{
    public static function hydrateFromDatabaseIfNeeded(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE || empty($_SESSION['user_id'])) {
            return;
        }

        try {
            $userId = (int) $_SESSION['user_id'];
            if ($userId <= 0) {
                return;
            }

            $user = (new UserRepository())->findUserById($userId);
            if ($user === null) {
                return;
            }

            self::storeInSession($user);
        } catch (\Throwable $e) {
            error_log('Session user hydration failed: ' . $e->getMessage());
        }
    }

    public static function storeInSession(User $user): void
    {
        $roleValue = $user->role instanceof \App\Models\Enum\UserRole
            ? $user->role->value
            : strtolower((string) $user->role);

        $_SESSION['user_id'] = $user->user_id;
        $_SESSION['user_role'] = $roleValue;
        $_SESSION['user_username'] = trim($user->username);
        $_SESSION['user_first_name'] = trim($user->first_name);
        $_SESSION['user_last_name'] = trim($user->last_name);
        $_SESSION['user_name'] = self::buildFullName($user);
        $_SESSION['user_email'] = trim($user->email);
        $_SESSION['user_phone'] = $user->phone;
    }

    public static function isLoggedIn(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE && !empty($_SESSION['user_id']);
    }

    /** Full name from profile, then username, then email. */
    public static function displayName(): string
    {
        if (!self::isLoggedIn()) {
            return '';
        }

        $fullName = self::buildFullNameFromSession();
        if ($fullName !== '') {
            return $fullName;
        }

        $storedName = trim((string) ($_SESSION['user_name'] ?? ''));
        if ($storedName !== '') {
            return $storedName;
        }

        $username = trim((string) ($_SESSION['user_username'] ?? ''));
        if ($username !== '') {
            return $username;
        }

        return trim((string) ($_SESSION['user_email'] ?? ''));
    }

    public static function email(): string
    {
        if (!self::isLoggedIn()) {
            return '';
        }

        return trim((string) ($_SESSION['user_email'] ?? ''));
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

    private static function buildFullName(User $user): string
    {
        $fullName = self::joinNameParts(trim($user->first_name), trim($user->last_name));
        if ($fullName !== '') {
            return $fullName;
        }

        if ($user->username !== '') {
            return trim($user->username);
        }

        return trim($user->email);
    }

    private static function buildFullNameFromSession(): string
    {
        return self::joinNameParts(
            trim((string) ($_SESSION['user_first_name'] ?? '')),
            trim((string) ($_SESSION['user_last_name'] ?? ''))
        );
    }

    private static function joinNameParts(string $firstName, string $lastName): string
    {
        if ($firstName !== '' && $lastName !== '') {
            return $firstName . ' ' . $lastName;
        }

        return $firstName !== '' ? $firstName : $lastName;
    }
}
