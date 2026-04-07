<section class="history-tour-section history-tour-pricing">
    <div class="history-container">
        <div class="history-section__heading history-section__heading--center">
            <h2><?= htmlspecialchars((string) ($s['heading'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h2>
            <?php if (!empty($s['intro'])): ?>
                <p class="history-section__intro"><?= htmlspecialchars((string) $s['intro'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>
        </div>

        <div class="history-tour-pricing__grid">
            <article class="history-tour-pricing__card">
                <h3><?= htmlspecialchars((string) ($s['left_title'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h3>
                <p class="history-tour-pricing__sub"><?= htmlspecialchars((string) ($s['left_subtitle'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
                <strong class="history-tour-pricing__price"><?= htmlspecialchars((string) ($s['left_price'] ?? ''), ENT_QUOTES, 'UTF-8') ?></strong>
                <ul>
                    <?php foreach (['left_feature_one', 'left_feature_two'] as $field): ?>
                        <?php if (empty($s[$field])) continue; ?>
                        <li><?= htmlspecialchars((string) $s[$field], ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            </article>

            <article class="history-tour-pricing__card history-tour-pricing__card--featured">
                <?php if (!empty($s['right_badge'])): ?>
                    <span class="history-tour-pricing__badge"><?= htmlspecialchars((string) $s['right_badge'], ENT_QUOTES, 'UTF-8') ?></span>
                <?php endif; ?>
                <h3><?= htmlspecialchars((string) ($s['right_title'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h3>
                <p class="history-tour-pricing__sub"><?= htmlspecialchars((string) ($s['right_subtitle'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
                <strong class="history-tour-pricing__price"><?= htmlspecialchars((string) ($s['right_price'] ?? ''), ENT_QUOTES, 'UTF-8') ?></strong>
                <?php if (!empty($s['right_note'])): ?>
                    <p class="history-tour-pricing__note"><?= htmlspecialchars((string) $s['right_note'], ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
                <ul>
                    <?php foreach (['right_feature_one', 'right_feature_two'] as $field): ?>
                        <?php if (empty($s[$field])) continue; ?>
                        <li><?= htmlspecialchars((string) $s[$field], ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            </article>
        </div>
    </div>
</section>
