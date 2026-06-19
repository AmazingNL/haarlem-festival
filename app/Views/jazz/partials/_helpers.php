<?php

declare(strict_types=1);

// Escape plain text and keep manual line breaks.
if (!isset($jazzText)) {
    $jazzText = static function (?string $value): string {
        return nl2br(htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'));
    };
}

// Escape a link, falling back to a default when empty.
if (!isset($jazzUrl)) {
    $jazzUrl = static function (?string $value, string $default = '#'): string {
        $url = trim((string) $value);
        if ($url === '') {
            $url = $default;
        }

        return htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
    };
}

// Turn an image field (string path, list of paths, or list of {src}) into one URL.
if (!isset($jazzImage)) {
    $jazzImage = static function (mixed $value, string $default = ''): string {
        if (is_array($value)) {
            $first = $value[0] ?? '';
            $value = is_array($first) ? ($first['src'] ?? '') : $first;
        }

        $url = trim((string) $value);

        return $url !== '' ? $url : $default;
    };
}
