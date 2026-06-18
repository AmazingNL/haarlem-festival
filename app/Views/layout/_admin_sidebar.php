<?php
// Reusable admin sidebar partial — included in admin layout so it's present on all admin pages
$current = $_SERVER['REQUEST_URI'] ?? '/';

$isDashboard = ($current === '/admin' || $current === '/admin/dashboard');
$isPages     = str_starts_with($current, '/admin/pages') || str_starts_with($current, '/admin/pageSection');
$isUsers     = str_starts_with($current, '/admin/users');
$isOrders    = str_starts_with($current, '/admin/orders');
$isSeats     = str_starts_with($current, '/admin/seats') || str_starts_with($current, '/admin/dance/seats');
$isScan      = str_starts_with($current, '/admin/tickets');
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
                    <a class="sidebar-link <?= $isPages ? 'active' : '' ?>" href="/admin/pages">
                        Pages
                    </a>
                </li>
                <li>
                    <a class="sidebar-link <?= $isUsers ? 'active' : '' ?>" href="/admin/users">
                        Users
                    </a>
                </li>
                <li>
                    <a class="sidebar-link <?= $isOrders ? 'active' : '' ?>" href="/admin/orders">
                        Orders
                    </a>
                </li>
                <li>
                    <a class="sidebar-link <?= $isSeats ? 'active' : '' ?>" href="/admin/seats">
                        Seats
                    </a>
                </li>
            </ul>
        </nav>

    </div>
</aside>
