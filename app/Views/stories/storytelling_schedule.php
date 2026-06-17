<section class="sched-section">
    <div class="sched-inner">
        <?php if (!empty($s['title'])): ?>
            <h2 class="sched-title"><?= htmlspecialchars((string) $s['title'], ENT_QUOTES, 'UTF-8') ?></h2>
        <?php endif; ?>

        <nav class="sched-tabs" aria-label="Schedule days">
            <button class="sched-tab active" data-target="thu">Thursday</button>
            <button class="sched-tab" data-target="fri">Friday</button>
            <button class="sched-tab" data-target="sat">Saturday</button>
            <button class="sched-tab" data-target="sun">Sunday</button>
        </nav>

        <?php if (!empty($s['content'])): ?>
            <div class="sched-body"><?= \App\Support\Html::clean($s['content'] ?? '') ?></div>
        <?php endif; ?>
    </div>
</section>
