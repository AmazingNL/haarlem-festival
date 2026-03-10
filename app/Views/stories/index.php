<?php foreach (($section ?? []) as $s): ?>
    <?php if (empty($s['is_published'])) continue; ?>

    <?php $type = trim((string) ($s['section_type'] ?? '')); ?>

    <?php switch ($type):

        case 'stories_hero':
            require __DIR__ . '/stories_hero.php';
            require __DIR__ . '/breadcrumb.php';
            break;

        case 'what_is_stories':
            require __DIR__ . '/what_is_stories.php';
            break;

        case 'stories_preview':
            require __DIR__ . '/stories_preview.php';
            break;

        case 'storytelling_schedule':
            require __DIR__ . '/storytelling_schedule.php';
            break;

        default:
            break;

    endswitch; ?>
<?php endforeach; ?>
