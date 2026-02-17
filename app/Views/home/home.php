<?php

declare(strict_types=1);
namespace App\Views\home;

$homePage = $data['pages'] ?? [];
$message = $data['message'] ?? null;
$title = $homePage[0]->title ?? 'Haarlem Festival';
$sectionType = $homePage[0]->section_type ?? null;


?>

<Section>
    <h1 class="title"><?= htmlspecialchars($title)?></h1>
    <?php if ($message): ?>
        <p><?= htmlspecialchars($message) ?>
    </p><?php endif; ?>

    <?php foreach ($homePage as $section): ?>
        <div class="section">
            <h2><?= htmlspecialchars($section->title) ?></h2>
            <p><?= nl2br(htmlspecialchars($section->content)) ?></p>
            <?php if ($section->image_id): ?>
                <img src="/images/<?= htmlspecialchars($section->image_id) ?>" alt="<?= htmlspecialchars($section->title) ?>">
            <?php endif; ?>
            <?php if ($section->button_text && $section->button_link): ?>
                <a href="<?= htmlspecialchars($section->button_link) ?>" class="btn"><?= htmlspecialchars($section->button_text) ?></a>
            <?php endif; ?>
        </div><?php endforeach; ?>

</Section>
