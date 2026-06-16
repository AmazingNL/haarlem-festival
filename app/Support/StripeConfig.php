<?php

declare(strict_types=1);

namespace App\Support;

final class StripeConfig
{
    private static function env(string $key): string
    {
        $value = $_ENV[$key] ?? getenv($key);
        return is_string($value) ? trim($value) : '';
    }

    public static function secretKey(): string
    {
        $key = self::env('STRIPE_SECRET_KEY');
        if ($key === '') {
            throw new \RuntimeException('Stripe secret key is missing.');
        }

        return $key;
    }

    public static function isConfigured(): bool
    {
        $key = self::env('STRIPE_SECRET_KEY');
        if ($key === '' || !str_starts_with($key, 'sk_')) {
            return false;
        }

        $placeholders = ['your_key_here', 'paste', 'xxx', 'your_key'];
        foreach ($placeholders as $placeholder) {
            if (stripos($key, $placeholder) !== false) {
                return false;
            }
        }

        return true;
    }

    public static function appUrl(): string
    {
        $configuredUrl = self::env('APP_URL');
        if ($configuredUrl !== '') {
            return rtrim($configuredUrl, '/');
        }

        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = trim((string) ($_SERVER['HTTP_HOST'] ?? 'localhost'));

        return $scheme . '://' . $host;
    }

    /** Name shown at the top of Stripe Checkout (overrides Stripe Dashboard default). */
    public static function checkoutDisplayName(): string
    {
        $name = self::env('STRIPE_DISPLAY_NAME');
        if ($name !== '') {
            return mb_substr($name, 0, 250);
        }

        return 'Haarlem Festival';
    }
}
