<section class="jazz-section jazz-intro">
    <div class="jazz-container">
        <h2 class="jazz-section__title"><?= htmlspecialchars((string) ($s['heading'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h2>
        <?php if (!empty($s['body'])): ?>
            <div class="jazz-rich-text"><?= \App\Support\Html::clean((string) $s['body']) ?></div>
        <?php endif; ?>
    </div>
</section>
