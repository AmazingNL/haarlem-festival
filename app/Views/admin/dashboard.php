<!-- Admin Dashboard Homepage -->

<div class="admin-wrapper">
    <!-- Page Header -->
    <div class="admin-panel">
        <div class="admin-header">
            <h1 class="admin-title">Dashboard</h1>
            <div class="admin-actions">
                <p class="muted">Welcome to the Haarlem Festival admin panel</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <!-- Users Card -->
            <div class="stat-card">
                <span class="stat-label">Total Users</span>
                <span class="stat-value">—</span>
                <a href="/admin/users" class="btn-primary" style="text-align: center; text-decoration: none; margin-top: 0.5rem;">
                    Manage Users
                </a>
            </div>

            <!-- Events Card -->
            <div class="stat-card">
                <span class="stat-label">Total Events</span>
                <span class="stat-value">—</span>
                <a href="/admin/events" class="btn-primary" style="text-align: center; text-decoration: none; margin-top: 0.5rem;">
                    Manage Events
                </a>
            </div>

            <!-- Orders Card -->
            <div class="stat-card">
                <span class="stat-label">Total Orders</span>
                <span class="stat-value">—</span>
                <a href="/admin/orders" class="btn-primary" style="text-align: center; text-decoration: none; margin-top: 0.5rem;">
                    View Orders
                </a>
            </div>

            <!-- Content Card -->
            <div class="stat-card">
                <span class="stat-label">Content Pages</span>
                <span class="stat-value">—</span>
                <a href="/admin/content" class="btn-primary" style="text-align: center; text-decoration: none; margin-top: 0.5rem;">
                    Manage Content
                </a>
            </div>
        </div>

        <!-- Quick Actions Section -->
        <div class="card">
            <h3 style="font-family: var(--font-primary); color: var(--color-bg-dark); font-size: 1.25rem; margin-bottom: 1rem;">⚡ Quick Actions</h3>
            <div class="form-actions">
                <button class="btn-primary" disabled>
                    ➕ Add Event
                </button>
                <button class="btn-primary" disabled>
                    👤 Add User
                </button>
                <button class="btn-primary" disabled>
                    📄 Create Content
                </button>
            </div>
            <p class="muted" style="margin-top: 1rem;">
                ℹ️ Quick action buttons will be enabled once the respective modules are implemented
            </p>
        </div>

        <!-- Recent Activity Section -->
        <div class="card">
            <h3 style="font-family: var(--font-primary); color: var(--color-bg-dark); font-size: 1.25rem; margin-bottom: 1rem;">Recent Activity</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>User</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 2rem;">
                            <span class="muted">No recent activity to display</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
