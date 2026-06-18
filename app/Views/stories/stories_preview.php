<section class="sp-section">
    <div class="sp-inner">
        <?php if (!empty($s['title'])): ?>
            <h2 class="sp-title"><?= htmlspecialchars((string) $s['title'], ENT_QUOTES, 'UTF-8') ?></h2>
        <?php endif; ?>
        <?php if (!empty($s['content'])): ?>
            <div class="sp-body"><?= \App\Support\Html::clean($s['content'] ?? '') ?></div>
        <?php endif; ?>
    </div>
</section>
