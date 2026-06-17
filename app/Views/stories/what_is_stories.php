<section class="wis-section">
    <div class="wis-inner">
        <?php if (!empty($s['title'])): ?>
            <h2 class="wis-title"><?= htmlspecialchars((string) $s['title'], ENT_QUOTES, 'UTF-8') ?></h2>
        <?php endif; ?>
        <?php if (!empty($s['content'])): ?>
            <div class="wis-body"><?= $s['content'] ?></div>
        <?php endif; ?>
    </div>
</section>
