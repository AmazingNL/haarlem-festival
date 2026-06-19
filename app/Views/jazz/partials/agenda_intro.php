<section class="jazz-section jazz-agenda-intro" id="agenda">
    <div class="jazz-container">
        <h2 class="jazz-section__title"><?= htmlspecialchars((string) ($s['heading'] ?? 'Agenda'), ENT_QUOTES, 'UTF-8') ?></h2>
        <?php if (!empty($s['intro'])): ?>
            <p class="jazz-section__intro"><?= $jazzText($s['intro']) ?></p>
        <?php endif; ?>
    </div>
</section>
