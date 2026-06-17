<?php

declare(strict_types=1);

if (!isset($historyText)) {
    $historyText = static function (?string $value): string {
        return nl2br(htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'));
    };
}

if (!isset($historyUrl)) {
    $historyUrl = static function (?string $value, string $default = '#'): string {
        $url = trim((string) $value);
        if ($url === '' || $url === '#') {
            $url = $default;
        }

        return htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
    };
}
