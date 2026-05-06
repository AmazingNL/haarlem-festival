<?php
$homeUrl = static function (?string $value, string $default = '#'): string {
    $url = trim((string) $value);

    return htmlspecialchars($url !== '' ? $url : $default, ENT_QUOTES, 'UTF-8');
};

$homeText = static function (?string $value): string {
    return nl2br(htmlspecialchars(trim((string) $value), ENT_QUOTES, 'UTF-8'));
};

$homeParagraphs = static function (?string $value): array {
    $text = str_replace(["\r\n", "\r"], "\n", trim((string) $value));
    if ($text === '') {
        return [];
    }

    $paragraphs = preg_split('/\n\s*\n/', $text) ?: [];
    return array_values(array_filter($paragraphs, static fn(string $paragraph): bool => trim($paragraph) !== ''));
};
?>

<div class="home-page">
    <?php foreach (($section ?? []) as $s): ?>
        <?php if (empty($s['is_published'])) continue; ?>

        <?php switch ((string) ($s['section_type'] ?? '')):
            case 'hero':
                require __DIR__ . '/partials/hero.php';
                break;
            case 'feature':
                require __DIR__ . '/partials/feature.php';
                break;
            case 'gallery':
                require __DIR__ . '/partials/gallery.php';
                break;
            case 'image_left':
                require __DIR__ . '/partials/image_left.php';
                break;
            case 'image_right':
                require __DIR__ . '/partials/image_right.php';
                break;
            case 'cards_grid':
                require __DIR__ . '/partials/cards_grid.php';
                break;
            case 'transport':
                require __DIR__ . '/partials/transport.php';
                break;
        endswitch; ?>
    <?php endforeach; ?>
</div>
