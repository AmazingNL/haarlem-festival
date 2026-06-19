<?php require __DIR__ . '/partials/_helpers.php'; ?>

<div class="jazz-page">
    <?php
    $sections = is_array($section ?? null) ? array_values($section) : [];

    // Collect every agenda performance first so the day tabs can be built no matter
    // where the performance sections sit in the page order (same idea as Yummy's cards).
    $agendaEvents = [];
    foreach ($sections as $candidate) {
        if (empty($candidate['is_published'])) {
            continue;
        }
        if ((string) ($candidate['section_type'] ?? '') === 'jazz_agenda_event') {
            $agendaEvents[] = $candidate;
        }
    }
    $agendaRendered = false;
    ?>

    <?php foreach ($sections as $s): ?>
        <?php if (empty($s['is_published'])) continue; ?>

        <?php switch ((string) ($s['section_type'] ?? '')):
            case 'jazz_hero':
                require __DIR__ . '/partials/hero.php';
                break;
            case 'jazz_intro':
                require __DIR__ . '/partials/intro.php';
                break;
            case 'jazz_what_to_expect':
                require __DIR__ . '/partials/what_to_expect.php';
                break;
            case 'jazz_featured_artists':
                require __DIR__ . '/partials/featured_artists.php';
                break;
            case 'jazz_more_artists':
                require __DIR__ . '/partials/more_artists.php';
                break;
            case 'jazz_agenda_intro':
                require __DIR__ . '/partials/agenda_intro.php';
                require __DIR__ . '/partials/agenda.php'; // renders the collected $agendaEvents
                $agendaRendered = true;
                break;
            case 'jazz_passes':
                require __DIR__ . '/partials/passes.php';
                break;
            case 'jazz_location_contact':
                require __DIR__ . '/partials/location_contact.php';
                break;
            // 'jazz_agenda_event' sections are collected above and rendered inside the agenda.
        endswitch; ?>
    <?php endforeach; ?>

    <?php if (!$agendaRendered && $agendaEvents !== []): ?>
        <?php require __DIR__ . '/partials/agenda.php'; ?>
    <?php endif; ?>
</div>
