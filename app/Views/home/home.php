<?php 
$sections = $data['sections'] ?? [];
?>

<?php foreach ($sections as $section): ?>

    <?php if ($section->getSectionType() === 'hero'): ?>
        <section class="hero">
            <h1><?= htmlspecialchars($section->getTitle()) ?></h1>
            <?= $section->getContent() ?>
        </section>

    <?php elseif ($section->getSectionType() === 'text_block'): ?>
        <section class="text-block">
            <h2><?= htmlspecialchars($section->getTitle()) ?></h2>
            <?= $section->getContent() ?>
        </section>

    <?php elseif ($section->getSectionType() === 'image_left'): ?>
        <section class="image-left">
            <img src="/uploads/<?= htmlspecialchars($section->getImagePath()) ?>" alt="">
            <div>
                <h2><?= htmlspecialchars($section->getTitle()) ?></h2>
                <?= $section->getContent() ?>
            </div>
        </section>

    <?php endif; ?>

<?php endforeach; ?>
