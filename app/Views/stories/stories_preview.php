<?php
$he    = static fn(string $v): string => htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
$title = (string) ($s['title'] ?? '');
?>
<section class="sp-section">
    <div class="sp-inner">
        <?php if ($title !== ''): ?>
            <div class="sp-heading-wrap">
                <h2 class="sp-title"><?= $he($title) ?></h2>
            </div>
        <?php endif; ?>

        <?php if (!empty($s['content'])): ?>
            <div class="sp-body"><?= \App\Support\Html::clean($s['content'] ?? '') ?></div>
        <?php endif; ?>
    </div>
</section>
