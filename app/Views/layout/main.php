<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <title><?= htmlspecialchars((string) ($title ?? 'Haarlem Festival'), ENT_QUOTES, 'UTF-8') ?></title>
    <meta name="description" content="Haarlem Festival - events, programs and city guides">
    <link rel="stylesheet" href="/assets/css/main.css" />
    <link rel="stylesheet" href="/assets/css/home.css" />
    <link rel="stylesheet" href="/assets/css/shop.css" />
    <link rel="stylesheet" href="/assets/css/program.css" />
    <link rel="stylesheet" href="/assets/css/history/index.css" />
    <link rel="stylesheet" href="/assets/css/stories/index.css" />
    <link rel="stylesheet" href="/assets/css/yummy/index.css" />
    <link rel="stylesheet" href="/assets/css/yummy/restaurant_card.css" />
    <link rel="stylesheet" href="/assets/css/yummy/ratatouille.css" />
</head>

<body>
    <?php
    $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $isActive = static function (string $path) use ($currentPath): string {
        return $currentPath === $path ? 'active' : '';
    };
    $isSectionActive = static function (string $path) use ($currentPath): string {
        return $currentPath === $path || str_starts_with($currentPath, $path . '/') ? 'active' : '';
    };
    ?>

    <a class="skip-link" href="#main">Skip to content</a>

    <?php require __DIR__ . '/partials/site_header.php'; ?>

    <main id="main" class="main-content" role="main">
        <?php if (!empty($flash['success'])): ?>
            <div class="alert alert-success mx-3" role="status">
                <?= htmlspecialchars((string) $flash['success'], ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php elseif (!empty($flash['error'])): ?>
            <div class="alert alert-danger mx-3" role="alert">
                <?= htmlspecialchars((string) $flash['error'], ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php if (isset($content) && is_string($content)): ?>
            <?php require $content; ?>
        <?php endif; ?>
    </main>

    <?php require __DIR__ . '/partials/site_footer.php'; ?>

    <script src="/assets/js/navbar.js"></script>
    <script src="/assets/js/home.js"></script>
    <script src="/assets/js/history-book-tour.js"></script>
    <script src="/assets/js/stories.js"></script>
</body>

</html>
