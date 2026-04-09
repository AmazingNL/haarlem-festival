<?php
$programCount = 0;
if (session_status() === PHP_SESSION_ACTIVE && !empty($_SESSION['program_items']) && is_array($_SESSION['program_items'])) {
    foreach ($_SESSION['program_items'] as $programItem) {
        $programCount += (int) ($programItem['quantity'] ?? 0);
    }
}

$isLoggedIn = session_status() === PHP_SESSION_ACTIVE && !empty($_SESSION['user_id']);
$authLabel = $isLoggedIn ? 'Logout' : 'Login';
$authHref = $isLoggedIn ? '/logout' : '/loginForm';
$isAuthPage = $currentPath === '/loginForm' || $currentPath === '/registerForm';
$currentTag = strtolower(trim((string) ($_GET['tag'] ?? '')));

$mainNavigation = [
    [
        'label' => 'Home',
        'href' => '/home',
        'active' => $isActive('/home') !== '' || $currentPath === '/',
    ],
    [
        'label' => 'Stories',
        'href' => '/stories',
        'active' => $isSectionActive('/stories') !== '',
    ],
    [
        'label' => 'History',
        'href' => '/history',
        'active' => $isSectionActive('/history') !== '',
    ],
    [
        'label' => 'Restaurants',
        'href' => '/yummy',
        'active' => $isSectionActive('/yummy') !== '',
    ],
    [
        'label' => 'Dance',
        'href' => '/events?tag=dance',
        'active' => $currentPath === '/events' && $currentTag === 'dance',
    ],
    [
        'label' => 'Jazz',
        'href' => '/events?tag=jazz',
        'active' => $currentPath === '/events' && $currentTag === 'jazz',
    ],
];

$headerIcons = [
    [
        'label' => 'Favorites',
        'src' => '/assets/svg/main/heart.svg',
        'alt' => 'favorites icon',
    ],
    [
        'label' => 'Search',
        'src' => '/assets/svg/main/search.svg',
        'alt' => 'search icon',
    ],
    [
        'label' => 'Location',
        'src' => '/assets/svg/main/location.svg',
        'alt' => 'location icon',
    ],
];
?>

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
            <?php foreach ($mainNavigation as $navigationItem): ?>
                <li>
                    <a href="<?= htmlspecialchars($navigationItem['href'], ENT_QUOTES, 'UTF-8') ?>"
                       class="nav-link <?= $navigationItem['active'] ? 'active' : '' ?>">
                        <?= htmlspecialchars($navigationItem['label'], ENT_QUOTES, 'UTF-8') ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="navbar-actions">
            <a href="/program" class="program-link <?= $isSectionActive('/program') || $isSectionActive('/checkout') || $isSectionActive('/orders') ? 'active' : '' ?>">
                My Program
                <?php if ($programCount > 0): ?>
                    <span class="program-link__count"><?= $programCount ?></span>
                <?php endif; ?>
            </a>

            <a href="<?= htmlspecialchars($authHref, ENT_QUOTES, 'UTF-8') ?>" class="auth-link <?= $isAuthPage && !$isLoggedIn ? 'active' : '' ?>">
                <?= htmlspecialchars($authLabel, ENT_QUOTES, 'UTF-8') ?>
            </a>

            <div class="icon-actions">
                <?php foreach ($headerIcons as $icon): ?>
                    <button class="icon-btn" aria-label="<?= htmlspecialchars($icon['label'], ENT_QUOTES, 'UTF-8') ?>">
                        <img class="icon-svg"
                             alt="<?= htmlspecialchars($icon['alt'], ENT_QUOTES, 'UTF-8') ?>"
                             src="<?= htmlspecialchars($icon['src'], ENT_QUOTES, 'UTF-8') ?>">
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </nav>
</header>
