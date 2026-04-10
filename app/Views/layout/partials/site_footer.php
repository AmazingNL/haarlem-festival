<?php
$isLoggedIn = session_status() === PHP_SESSION_ACTIVE && !empty($_SESSION['user_id']);
$authLink = [
    'label' => $isLoggedIn ? 'Logout' : 'Login',
    'href' => $isLoggedIn ? '/logout' : '/loginForm',
];

$footerColumns = [
    [
        'title' => 'Culture',
        'links' => [
            ['label' => 'History of Haarlem', 'href' => '/history'],
            ['label' => 'Stories in Haarlem', 'href' => '/stories'],
        ],
    ],
    [
        'title' => 'What to do',
        'links' => [
            ['label' => 'Dance', 'href' => '/home#home-dance-tickets'],
            ['label' => 'Jazz', 'href' => '/home#home-jazz-tickets'],
            ['label' => 'My Program', 'href' => '/program'],
            ['label' => 'Food and drinks', 'href' => '/yummy'],
            $authLink,
        ],
    ],
];

$socialLinks = [
    [
        'label' => 'Facebook',
        'href' => '#',
        'src' => '/assets/svg/main/facebook.svg',
    ],
    [
        'label' => 'Instagram',
        'href' => '#',
        'src' => '/assets/svg/main/Instagram.svg',
    ],
    [
        'label' => 'Youtube',
        'href' => '#',
        'src' => '/assets/svg/main/youtube.svg',
    ],
];

$footerBottomLinks = [
    ['label' => 'Privacy Policy', 'href' => '#'],
    ['label' => 'Terms of Service', 'href' => '#'],
];
?>

<footer class="footer">
    <div class="footer-content">
        <div class="footer-columns">
            <?php foreach ($footerColumns as $column): ?>
                <div class="footer-column">
                    <h3 class="footer-title"><?= htmlspecialchars($column['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                    <ul class="footer-links">
                        <?php foreach ($column['links'] as $link): ?>
                            <li>
                                <a href="<?= htmlspecialchars($link['href'], ENT_QUOTES, 'UTF-8') ?>" class="footer-link">
                                    <?= htmlspecialchars($link['label'], ENT_QUOTES, 'UTF-8') ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>

            <div class="footer-column">
                <h3 class="footer-title">Follow Us</h3>
                <div class="social-links">
                    <?php foreach ($socialLinks as $socialLink): ?>
                        <a href="<?= htmlspecialchars($socialLink['href'], ENT_QUOTES, 'UTF-8') ?>"
                           class="social-link"
                           aria-label="<?= htmlspecialchars($socialLink['label'], ENT_QUOTES, 'UTF-8') ?>">
                            <img class="social-icon"
                                 alt="<?= htmlspecialchars($socialLink['label'], ENT_QUOTES, 'UTF-8') ?>"
                                 src="<?= htmlspecialchars($socialLink['src'], ENT_QUOTES, 'UTF-8') ?>">
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p class="footer-copyright">&copy; 2025 Haarlem Marketing. All rights reserved.</p>
            <nav class="footer-nav">
                <?php foreach ($footerBottomLinks as $link): ?>
                    <a href="<?= htmlspecialchars($link['href'], ENT_QUOTES, 'UTF-8') ?>" class="footer-bottom-link">
                        <?= htmlspecialchars($link['label'], ENT_QUOTES, 'UTF-8') ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        </div>
    </div>
</footer>
