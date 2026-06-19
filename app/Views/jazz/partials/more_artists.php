<?php
// Artist names are stored as one textarea; split on new lines or commas into chips.
$rawArtists = (string) ($s['artists'] ?? '');
$artistNames = array_values(array_filter(array_map(
    static fn (string $item): string => trim($item),
    preg_split('/[\r\n,]+/', $rawArtists) ?: []
)));
?>
<section class="jazz-section jazz-more-artists">
    <div class="jazz-container">
        <?php if (!empty($s['intro'])): ?>
            <p class="jazz-more-artists__intro"><?= $jazzText($s['intro']) ?></p>
        <?php endif; ?>

        <?php if ($artistNames !== []): ?>
            <ul class="jazz-chips">
                <?php foreach ($artistNames as $name): ?>
                    <li class="jazz-chip"><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</section>
