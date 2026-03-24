<?php
$csrf = $data['csrf'] ?? $csrf ?? '';
?>

<div class="admin-header mb-4">
    <div>
        <h1 class="admin-title">Create User</h1>
        <p class="muted mb-0">Add a new user to the platform.</p>
    </div>
    <div class="admin-actions">
        <a href="/admin/users" class="btn-secondary">← Back to Users</a>
    </div>
</div>

<div class="cards">
    <div class="card">
        <form method="POST" action="/admin/users/create" class="admin-form">
            <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">

            <div class="form-grid">
                <div class="field">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" class="form-input"
                           placeholder="Jane" required>
                </div>

                <div class="field">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" class="form-input"
                           placeholder="Doe" required>
                </div>

                <div class="field">
                    <label for="role">Role</label>
                    <select id="role" name="role" class="form-select" required>
                        <option value="customer">Customer</option>
                        <option value="employee">Employee</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="field field-full">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-input"
                           placeholder="jane@example.com" required>
                </div>

                <div class="field field-full">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-input"
                           placeholder="janedoe" required>
                </div>

                <div class="field field-full">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-input"
                           placeholder="Min. 8 characters" required>
                </div>
            </div>

            <div class="form-actions" style="margin-top:1.5rem;">
                <button type="submit" class="btn btn-primary">Create User</button>
                <a href="/admin/users" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
