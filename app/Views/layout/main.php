<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <title><?= htmlspecialchars((string) ($title ?? 'Haarlem Festival'), ENT_QUOTES, 'UTF-8') ?></title>
    <meta name="description" content="Haarlem Festival - events, programs and city guides">
    <link rel="stylesheet" href="/assets/css/main.css" />
    <link rel="stylesheet" href="/assets/css/home.css" />
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

    <header class="header">
        <nav class="navbar">
            <a href="/home" class="logo-link">
                <span class="logo-text">HA</span>
                <img class="logo-icon" alt="logo" src="/assets/svg/main/Star 3.svg">
                <span class="logo-text">RLEM</span>
            </a>

            <button class="menu-toggle" aria-label="Toggle menu">
                <span class="hamburger"></span>
                <span class="hamburger"></span>
                <span class="hamburger"></span>
            </button>

            <ul class="nav-links">
                <li><a href="/home" class="nav-link <?= $isActive('/home') !== '' || $currentPath === '/' ? 'active' : '' ?>">Home</a></li>
                <li><a href="/stories" class="nav-link <?= $isSectionActive('/stories') ?>">Stories</a></li>
                <li><a href="/history" class="nav-link <?= $isSectionActive('/history') ?>">History</a></li>
                <li><a href="/yummy" class="nav-link <?= $isSectionActive('/yummy') ?>">Restaurants</a></li>
                <li><a href="#" class="nav-link disabled">Dance</a></li>
                <li><a href="#" class="nav-link disabled">Jazz</a></li>
            </ul>

            <div class="navbar-actions">
                <a href="/program" class="program-link <?= $isActive('/program') ?>">My Program</a>

                <div class="icon-actions">
                    <button class="icon-btn" aria-label="Favorites">
                        <img class="icon-svg" alt="favorites icon" src="/assets/svg/main/heart.svg">
                    </button>
                    <button class="icon-btn" aria-label="Search">
                        <img class="icon-svg" alt="search icon" src="/assets/svg/main/search.svg">
                    </button>
                    <button class="icon-btn" aria-label="Location">
                        <img class="icon-svg" alt="location icon" src="/assets/svg/main/location.svg">
                    </button>
                </div>
            </div>
        </nav>
    </header>

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

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-columns">
                <div class="footer-column">
                    <h3 class="footer-title">Culture</h3>
                    <ul class="footer-links">
                        <li><a href="/history" class="footer-link">History of Haarlem</a></li>
                        <li><a href="/stories" class="footer-link">Stories in Haarlem</a></li>
                    </ul>
                </div>

                <div class="footer-column">
                    <h3 class="footer-title">What to do</h3>
                    <ul class="footer-links">
                        <li><a href="#" class="footer-link">Events</a></li>
                        <li><a href="/yummy" class="footer-link">Food and drinks</a></li>
                        <li><a href="#" class="footer-link">Haarlem Tickets</a></li>
                    </ul>
                </div>

                <div class="footer-column">
                    <h3 class="footer-title">Follow Us</h3>
                    <div class="social-links">
                        <a href="#" class="social-link" aria-label="Facebook">
                            <img class="social-icon" alt="Facebook" src="/assets/svg/main/facebook.svg">
                        </a>
                        <a href="#" class="social-link" aria-label="Instagram">
                            <img class="social-icon" alt="Instagram" src="/assets/svg/main/Instagram.svg">
                        </a>
                        <a href="#" class="social-link" aria-label="Youtube">
                            <img class="social-icon" alt="Youtube" src="/assets/svg/main/youtube.svg">
                        </a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p class="footer-copyright">&copy; 2025 Haarlem Marketing. All rights reserved.</p>
                <nav class="footer-nav">
                    <a href="#" class="footer-bottom-link">Privacy Policy</a>
                    <a href="#" class="footer-bottom-link">Terms of Service</a>
                </nav>
            </div>
        </div>
    </footer>

    <script src="/assets/js/navbar.js"></script>
    <script src="/assets/js/history-book-tour.js"></script>
    <script src="/assets/js/stories.js"></script>
</body>

</html>
