<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard' ?> - Haarlem Festival</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>

<body>
    <?php
    // Get current path for active state detection
    $currentPath = $_SERVER['REQUEST_URI'] ?? '';
    $currentPath = strtok($currentPath, '?'); // Remove query string

    // Helper function to check if link is active
    function isActive($path, $currentPath) {
        return strpos($currentPath, $path) === 0 ? 'active' : '';
    }
    ?>

    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="p-3">
                <h4 class="text-white mb-4">
                    <i class="bi bi-star-fill text-warning"></i> Admin Panel
                </h4>

                <!-- Sidebar navigation -->
                <nav class="nav flex-column">
                    <a href="/admin/dashboard" class="nav-link admin-nav-link <?= isActive('/admin/dashboard', $currentPath) ?>">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="/admin/users" class="nav-link admin-nav-link <?= isActive('/admin/users', $currentPath) ?>">
                        <i class="bi bi-people"></i>
                        <span>Users</span>
                    </a>
                    <a href="/admin/events" class="nav-link admin-nav-link <?= isActive('/admin/events', $currentPath) ?>">
                        <i class="bi bi-calendar-event"></i>
                        <span>Events</span>
                    </a>
                    <a href="/admin/orders" class="nav-link admin-nav-link <?= isActive('/admin/orders', $currentPath) ?>">
                        <i class="bi bi-cart"></i>
                        <span>Orders</span>
                    </a>
                    <a href="/admin/content" class="nav-link admin-nav-link <?= isActive('/admin/content', $currentPath) ?>">
                        <i class="bi bi-file-text"></i>
                        <span>Content</span>
                    </a>
                </nav>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="admin-main">
            <!-- Top Navbar -->
            <nav class="admin-navbar navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <!-- Mobile toggle button -->
                    <button class="btn btn-link d-lg-none" type="button" id="sidebarToggle">
                        <i class="bi bi-list fs-4"></i>
                    </button>

                    <span class="navbar-brand mb-0 h1"><?= $title ?? 'Dashboard' ?></span>

                    <div class="ms-auto">
                        <span class="text-muted">Admin user controls will be added here</span>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="admin-content">
                <?php
                require $content;
                ?>
            </main>
        </div>
    </div>

    <!-- Backdrop for mobile sidebar -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- Bootstrap 5 JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Sidebar toggle script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const toggleBtn = document.getElementById('sidebarToggle');

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    backdrop.classList.toggle('show');
                });

                backdrop.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    backdrop.classList.remove('show');
                });
            }
        });
    </script>
</body>

</html>
