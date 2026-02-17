<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Login - Haarlem Festival', ENT_QUOTES, 'UTF-8') ?></title>

    <link rel="stylesheet" href="/assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script defer src="/assets/js/login.js"></script>
</head>
<body>
    <div class="split-screen">
        <div class="left-panel">
            <div class="overlay"></div>
            <div class="left-content">
                <h1>Welcome to <br><span>Haarlem</span></h1>
                <p>Sign in to explore everything Haarlem has to offer—from stories and history to restaurants, dance, and jazz.</p>
                <div class="brand-footer">THE CITY OF HAARLEM</div>
            </div>
        </div>

        <div class="right-panel">
            <div class="form-container">
                <a href="/" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Home</a>

                <h2>Welcome Back</h2>
                <p class="subtitle">Sign in to access all of Haarlem's features.</p>

                <?php if (!empty(($flash ?? [])['error'])): ?>
                    <div class="error-msg"><?= htmlspecialchars(($flash ?? [])['error'], ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>

                <form action="/login" method="POST">
                    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf ?? '', ENT_QUOTES, 'UTF-8') ?>">

                    <div class="form-group">
                        <label>Email or Username</label>
                        <div class="input-wrapper">
                            <i class="fa-regular fa-envelope input-icon"></i>
                            <input type="text" name="email_or_Username" placeholder="you@example.com or username" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-lock input-icon"></i>
                            <input type="password" name="password" id="password" placeholder="••••••••" required>
                            <button type="button" class="toggle-password" aria-label="Toggle password visibility">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                        <div class="forgot-link">
                            <a href="/forgot-password">Forgot password?</a>
                        </div>
                    </div>

                    <button type="submit" class="btn-signin">Sign In</button>

                    <p class="signup-prompt">Don't have an account? <a href="/registerForm">Create one</a></p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
