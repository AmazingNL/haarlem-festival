<?php
// Reusable admin sidebar partial — included in admin layout so it's present on all admin pages
$current = $_SERVER['REQUEST_URI'] ?? '/';

$isDashboard = ($current === '/admin' || $current === '/admin/dashboard');
$isPages     = str_starts_with($current, '/admin/pages') || str_starts_with($current, '/admin/pageSection');
$isUsers     = str_starts_with($current, '/admin/users');
?>
<aside class="col-md-3 mb-4">
    <div class="admin-sidebar">
        <div class="brand mb-3">
            <a href="/admin/dashboard" style="text-decoration:none; color: var(--color-text-light);">
                <h5 class="m-0">HAARLEM</h5>
                <small style="font-size:0.7rem; opacity:0.6;">Festival 2026 — Admin</small>
            </a>
        </div>

        <nav>
            <ul class="sidebar-nav">
                <li>
                    <a class="sidebar-link <?= $isDashboard ? 'active' : '' ?>" href="/admin/dashboard">
                        Dashboard
                    </a>
                </li>
                <li>
                    <a class="sidebar-link <?= $isPages ? 'active' : '' ?>" href="/admin/pages/viewPage">
                        Pages
                    </a>
                </li>
                <li>
                    <a class="sidebar-link <?= $isUsers ? 'active' : '' ?>" href="/admin/users">
                        Users
                    </a>
                </li>
            </ul>
        </nav>

    </div>
</aside>
