<section class="no-page" style="padding: 2rem 1rem; max-width: 720px; margin: 0 auto;">
    <h1 style="margin-bottom: 0.75rem;">Page not available</h1>
    <p style="margin-bottom: 1rem;">
        <?= htmlspecialchars((string) ($error ?? 'The requested page is currently unavailable.'), ENT_QUOTES, 'UTF-8') ?>
    </p>
    <a href="/" style="text-decoration: underline;">Back to home</a>
</section>
