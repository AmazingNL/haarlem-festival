<div class="split-screen">
    <div class="left-panel">
        <div class="overlay"></div>
        <div class="left-content">
            <h1>Welcome to <br><span>Haarlem</span></h1>
            <p>Sign in to explore everything Haarlem has to offer - from stories and history to restaurants, dance, and jazz.</p>
            <div class="brand-footer">THE CITY OF HAARLEM</div>
        </div>
    </div>

    <div class="right-panel">
        <div class="form-container">
            <a href="/" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Home</a>

            <h2>Welcome Back</h2>
            <p class="subtitle">Sign in to access all of Haarlem's features.</p>

            <?php if (!empty(($flash ?? [])['error'])): ?>
                <div class="error-msg"><?= htmlspecialchars((string) ($flash ?? [])['error'], ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <form action="<?= !empty($isAdminLogin) ? '/admin/login' : '/login' ?>" method="POST">
                <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string) ($csrf ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="next" value="<?= htmlspecialchars((string) ($next ?? ''), ENT_QUOTES, 'UTF-8') ?>">

                <div class="form-group">
                    <label>Email or Username</label>
                    <div class="input-wrapper">
                        <i class="fa-regular fa-envelope input-icon"></i>
                        <input type="text" name="login" placeholder="you@example.com or username" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock input-icon"></i>
                        <input type="password" name="password" id="password" placeholder="********" required>
                        <button type="button" class="toggle-password" aria-label="Toggle password visibility">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-signin">Sign In</button>

                <p class="signup-prompt">Don't have an account? <a href="/registerForm<?= !empty($next) ? '?next=' . urlencode((string) $next) : '' ?>">Create one</a></p>
            </form>
        </div>
    </div>
</div>
