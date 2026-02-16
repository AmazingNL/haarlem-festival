<!-- Admin Dashboard Homepage -->

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="mb-3">Dashboard</h1>
            <p class="text-muted">Welcome to the Haarlem Festival admin panel</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4">
        <!-- Users Card -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <i class="bi bi-people fs-1 text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted text-uppercase mb-1">Total Users</h6>
                            <h2 class="mb-0">—</h2>
                        </div>
                    </div>
                    <a href="/admin/users" class="btn btn-outline-primary btn-sm w-100">
                        <i class="bi bi-arrow-right"></i> Manage Users
                    </a>
                </div>
            </div>
        </div>

        <!-- Events Card -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <i class="bi bi-calendar-event fs-1 text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted text-uppercase mb-1">Total Events</h6>
                            <h2 class="mb-0">—</h2>
                        </div>
                    </div>
                    <a href="/admin/events" class="btn btn-outline-success btn-sm w-100">
                        <i class="bi bi-arrow-right"></i> Manage Events
                    </a>
                </div>
            </div>
        </div>

        <!-- Orders Card -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <i class="bi bi-cart fs-1 text-warning"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted text-uppercase mb-1">Total Orders</h6>
                            <h2 class="mb-0">—</h2>
                        </div>
                    </div>
                    <a href="/admin/orders" class="btn btn-outline-warning btn-sm w-100">
                        <i class="bi bi-arrow-right"></i> View Orders
                    </a>
                </div>
            </div>
        </div>

        <!-- Content Card -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <i class="bi bi-file-text fs-1 text-info"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted text-uppercase mb-1">Content Pages</h6>
                            <h2 class="mb-0">—</h2>
                        </div>
                    </div>
                    <a href="/admin/content" class="btn btn-outline-info btn-sm w-100">
                        <i class="bi bi-arrow-right"></i> Manage Content
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-lightning-charge"></i> Quick Actions
                    </h5>
                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-primary" disabled>
                            <i class="bi bi-plus-circle"></i> Add Event
                        </button>
                        <button class="btn btn-success" disabled>
                            <i class="bi bi-person-plus"></i> Add User
                        </button>
                        <button class="btn btn-info" disabled>
                            <i class="bi bi-file-earmark-plus"></i> Create Content
                        </button>
                    </div>
                    <p class="text-muted small mt-3 mb-0">
                        <i class="bi bi-info-circle"></i> Quick action buttons will be enabled once the respective modules are implemented
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
