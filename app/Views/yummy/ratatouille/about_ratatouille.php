<?php

$currentType = trim((string) $s['section_type'] ?? '');
$highlights = array_values(array_filter(
  $welcomeBannerCard ?? [],
  static fn($card): bool => is_array($card) && ($card['title'] === 'Signature Highlights')
));
$title = $s['title'] ?? '';

$text = $s['article'] ?? '';
?>
<section class="ratatouille-copy-section">
      <h2 class="ratatouille-section-title"><?= htmlspecialchars((string) $title, ENT_QUOTES, 'UTF-8') ?></h2>

        <?php if (!empty($text)): ?>
          <p class="ratatouille-body-copy">
            <?= $text ?>
          </p>
        <?php endif; ?>

        <div class="ratatouille-highlights-card">
          <h3>Signature Highlights</h3>

          <div class="ratatouille-highlights-grid">
            <?php if (!empty($highlights)): ?>
              <?php foreach ($highlights as $item): ?>
                <div class="ratatouille-highlight-item">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                  </svg>
                  <span>
                    <?= $item['info'] ?>
                  </span>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
    <hr class="ratatouille-section-rule">
</section>
