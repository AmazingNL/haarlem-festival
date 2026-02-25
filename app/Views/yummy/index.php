<?php foreach (($section ?? []) as $s): ?>
    <?php if (empty($s['is_published']))
        continue; ?>

    <?php
    $type = trim((string) ($s['section_type'] ?? ''));
    ?>

    <?php switch ($type):

        case 'welcome_banner':
            require __DIR__ . '/welcome_banner.php';
            require __DIR__ . '/breadcrumb.php';
            break;

        case 'food_culture':
            require __DIR__ . '/food_culture.php';
            break;

        case 'haarlem_unique':
            require __DIR__ . '/haarlem_unique.php';
            break;

        case 'haarlem_taste':
            require __DIR__ . '/haarlem_taste.php';
            break;

        default:
            require __DIR__ . '/sections/generic.php';
            break;

    endswitch; ?>
<?php endforeach; ?>