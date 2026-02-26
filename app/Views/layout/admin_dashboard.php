<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">

    <?php
    $base = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    if ($base === '/' || $base === '.') {
        $base = '';
    }
    ?>
    <!-- Bootstrap CSS (for admin dashboard layout) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="/assets/css/admin_css/edit_page.css">
    <link rel="stylesheet" href="/assets/css/admin_css/create_page.css" />
    <link rel="stylesheet" href="/assets/css/admin_css/create_page_section.css" />
    <link rel="stylesheet" href="/assets/css/main.css" />
    <link rel="stylesheet" href="/assets/css/admin_css/admin_dashboard.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Arimo:wght@400;700&display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@500;700&display=swap" />
</head>

<body>
    <a class="skip-link" href="#main">Skip to content</a>
    <header class="header">
        <!-- navbar here (shared with main layout) -->
        <nav class="navbar">
            <!-- Logo Section -->
            <a href="/" class="logo-link">
                <span class="logo-text">HA</span>
                <img class="logo-icon" alt="logo" src="/assets/svg/main/Star 3.svg">
                <span class="logo-text">RLEM</span>
            </a>

            <!-- Hamburger Menu Button (Mobile) -->
            <button class="menu-toggle" aria-label="Toggle menu">
                <span class="hamburger"></span>
                <span class="hamburger"></span>
                <span class="hamburger"></span>
            </button>



            <!-- Icon Actions (User Icons) -->
            <div class="icon-actions">
                <button class="icon-btn" aria-label="Search">
                    <img class="icon-svg icon-search" alt="search icon" src="/assets/svg/main/search.svg">
                </button>
                <button class="icon-btn" aria-label="Favorites">
                    <img class="icon-svg icon-favorites" alt="favorites icon" src="/assets/svg/main/heart.svg">
                </button>
                <button class="icon-btn" aria-label="location">
                    <img class="icon-svg icon-location" alt="location icon" src="/assets/svg/main/location.svg">
                </button>
            </div>
        </nav>
    </header>
    <main class="main-content container-fluid py-4">
        <?php if (!empty($flash['success'])): ?>
            <div class="alert alert-success" role="status">
                <?= htmlspecialchars((string) $flash['success'], ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php elseif (!empty($flash['error'])): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars((string) $flash['error'], ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <div class="container">
            <div class="row">
                <?php require __DIR__ . '/_admin_sidebar.php'; ?>
                <div class="col-md-9">
                    <?php
                    require $content;
                    ?>
                </div>
            </div>
        </div>
    </main>
    <script>
        window.TINYMCE_API_KEY = "<?= htmlspecialchars($_ENV['TINYMCE_API_KEY'] ?? '', ENT_QUOTES, 'UTF-8') ?>";
    </script>

    <!-- Bootstrap JS bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="/assets/js/admin/tinymce-init.js"></script>

    <footer class="footer mt-4">
        <div class="footer-content container py-4">
            <div class="footer-columns d-flex gap-4">
                <div class="footer-column">
                    <h3 class="footer-title">Culture</h3>
                    <ul class="footer-links list-unstyled">
                        <li><a href="#" class="footer-link">History of Haarlem</a></li>
                        <li><a href="#" class="footer-link">Stories in Haarlem</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3 class="footer-title">What to do</h3>
                    <ul class="footer-links list-unstyled">
                        <li><a href="#" class="footer-link">Events</a></li>
                        <li><a href="#" class="footer-link">Food and drinks</a></li>
                        <li><a href="#" class="footer-link">Haarlem Tickets</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3 class="footer-title">Follow Us</h3>
                    <div class="social-links d-flex gap-2">
                        <a href="#" class="social-link" aria-label="Facebook"><img class="social-icon" alt="Facebook"
                                src="/assets/svg/main/facebook.svg"></a>
                        <a href="#" class="social-link" aria-label="Instagram"><img class="social-icon" alt="Instagram"
                                src="/assets/svg/main/instagram.svg"></a>
                        <a href="#" class="social-link" aria-label="Youtube"><img class="social-icon" alt="Youtube"
                                src="/assets/svg/main/youtube.svg"></a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom d-flex justify-content-between align-items-center mt-3">
                <p class="mb-0">© 2025 Haarlem Marketing. All rights reserved.</p>
                <nav class="footer-nav">
                    <a href="#" class="footer-bottom-link me-3">Privacy Policy</a>
                    <a href="#" class="footer-bottom-link">Terms of Service</a>
                </nav>
            </div>
        </div>
    </footer>

    <script src="/assets/js/navbar.js"></script>

</body>

</html>