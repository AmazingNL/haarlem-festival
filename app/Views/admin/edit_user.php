<?php
$uid       = (int)($user->user_id ?? 0);
$firstName = htmlspecialchars((string)($user->first_name ?? ''), ENT_QUOTES, 'UTF-8');
$lastName  = htmlspecialchars((string)($user->last_name  ?? ''), ENT_QUOTES, 'UTF-8');
$email     = htmlspecialchars((string)($user->email      ?? ''), ENT_QUOTES, 'UTF-8');
$username  = htmlspecialchars((string)($user->username   ?? ''), ENT_QUOTES, 'UTF-8');
$roleVal   = $user->role instanceof \App\Models\Enum\UserRole
    ? $user->role->value
    : (string)($user->role ?? 'customer');
$roles = ['admin', 'employee', 'customer'];
?>

<div class="admin-header mb-4">
    <div>
        <h1 class="admin-title">Edit User</h1>
        <p class="muted mb-0">Editing account for <strong><?= $username ?></strong></p>
    </div>
    <div class="admin-actions">
        <a href="/admin/users" class="btn-secondary">← Back to Users</a>
    </div>
</div>

<div class="cards">
    <div class="card">
        <form method="POST" action="/admin/users/<?= $uid ?>/edit" class="admin-form">
            <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf ?? '', ENT_QUOTES, 'UTF-8') ?>">

            <div class="form-grid">
                <div class="field">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" class="form-input"
                           value="<?= $firstName ?>" required>
                </div>

                <div class="field">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" class="form-input"
                           value="<?= $lastName ?>" required>
                </div>

                <div class="field">
                    <label for="role">Role</label>
                    <select id="role" name="role" class="form-select" required>
                        <?php foreach ($roles as $r): ?>
                            <option value="<?= $r ?>" <?= $roleVal === $r ? 'selected' : '' ?>>
                                <?= ucfirst($r) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="field field-full">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-input"
                           value="<?= $email ?>" required>
                </div>

                <div class="field field-full">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-input"
                           value="<?= $username ?>" required>
                </div>

                <div class="field field-full">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" class="form-input"
                           placeholder="Leave blank to keep current password">
                    <span class="hint">Only fill this in if you want to change the password.</span>
                </div>
            </div>

            <div class="form-actions" style="margin-top:1.5rem;">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="/admin/users" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
