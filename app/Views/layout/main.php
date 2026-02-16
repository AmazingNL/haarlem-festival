<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">

    <link rel="stylesheet" href="./assets/css/main.css" />
    <link rel="stylesheet" href="./assets/css/home.css" />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Arimo:wght@400;700&display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@500;700&display=swap" />
</head>

<body>

    <header class="header">
        <nav class="navbar">
            <!-- Logo Section -->
            <a href="#" class="logo-link">
                <span class="logo-text">HA</span>
                <img class="logo-icon" alt="logo" src="./assets/svg/main/Star 3.svg">
                <span class="logo-text">RLEM</span>
            </a>

            <!-- Hamburger Menu Button (Mobile) -->
            <button class="menu-toggle" aria-label="Toggle menu">
                <span class="hamburger"></span>
                <span class="hamburger"></span>
                <span class="hamburger"></span>
            </button>

            <!-- Navigation Links -->
            <ul class="nav-links">
                <li><a href="#" class="nav-link">History</a></li>
                <li><a href="#" class="nav-link">Stories</a></li>
                <li><a href="#" class="nav-link">Restaurants</a></li>
                <li><a href="#" class="nav-link">Jazz</a></li>
                <li><a href="#" class="nav-link">Dance</a></li>
                <li><a href="#" class="nav-link">My Programs</a></li>
            </ul>

            <!-- Icon Actions (User Icons) -->
            <div class="icon-actions">
                <button class="icon-btn" aria-label="Search">
                    <img class="icon-svg icon-search" alt="search icon" src="./assets/svg/main/search.svg">
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

    <!-- Main Content Area -->
    <main class="main-content">
        <div class="main-container">
            <?php 
            // Flash messages injected by BaseController
            $flash = $flash ?? [];
            if (!empty($flash)) {
                if (!empty($flash['success'])) {
                    $s = $flash['success'];
                    if (is_array($s)) {
                        foreach ($s as $m) echo '<div class="flash flash-success">' . htmlspecialchars($m) . '</div>';
                    } else {
                        echo '<div class="flash flash-success">' . htmlspecialchars($s) . '</div>';
                    }
                }
                if (!empty($flash['error'])) {
                    $e = $flash['error'];
                    if (is_array($e)) {
                        foreach ($e as $m) echo '<div class="flash flash-error">' . htmlspecialchars($m) . '</div>';
                    } else {
                        echo '<div class="flash flash-error">' . htmlspecialchars($e) . '</div>';
                    }
                }
            }

            require $content; 
            ?>
        </div>
    </main>

    <footer class="footer">




    <footer class="footer">
        <div class="footer-content">
            <!-- Footer Columns -->
            <div class="footer-columns">
                <!-- Culture Column -->
                <div class="footer-column">
                    <h3 class="footer-title">Culture</h3>
                    <ul class="footer-links">
                        <li><a href="#" class="footer-link">History of Haarlem</a></li>
                        <li><a href="#" class="footer-link">Stories in Haarlem</a></li>
                    </ul>
                </div>

                <!-- What to do Column -->
                <div class="footer-column">
                    <h3 class="footer-title">What to do</h3>
                    <ul class="footer-links">
                        <li><a href="#" class="footer-link">Events</a></li>
                        <li><a href="#" class="footer-link">Food and drinks</a></li>
                        <li><a href="#" class="footer-link">Haarlem Tickets</a></li>
                    </ul>
                </div>

                <!-- Follow Us Column -->
                <div class="footer-column">
                    <h3 class="footer-title">Follow Us</h3>
                    <div class="social-links">
                        <a href="#" class="social-link" aria-label="Facebook">
                            <img class="social-icon" alt="Facebook" src="./assets/svg/main/facebook.svg">
                        </a>
                        <a href="#" class="social-link" aria-label="Instagram">
                            <img class="social-icon" alt="Instagram" src="./assets/svg/main/instagram.svg">
                        </a>
                        <a href="#" class="social-link" aria-label="Youtube">
                            <img class="social-icon" alt="Youtube" src="./assets/svg/main/youtube.svg">
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <p class="footer-copyright">© 2025 Haarlem Marketing. All rights reserved.</p>
                <nav class="footer-nav">
                    <a href="#" class="footer-bottom-link">Privacy Policy</a>
                    <a href="#" class="footer-bottom-link">Terms of Service</a>
                </nav>
            </div>
        </div>
    </footer>


    <script src="./assets/js/navbar.js"></script>
</body>

</html>