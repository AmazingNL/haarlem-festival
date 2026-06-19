<?php
// The artist's bookable shows ($scheduleEvents = jazz_agenda_event sections on
// this page). Paid shows get an "Add to My Programs" button (posting the section
// id to the existing jazz booking endpoint); free shows just show "Free".
$scheduleHeading = trim((string) ($artist['schedule_heading'] ?? ''));
$scheduleIntro = (string) ($artist['schedule_intro'] ?? '');
$csrfToken = (string) ($csrf ?? '');
?>
<section class="jazz-section jazz-artist__schedule">
    <div class="jazz-container">
        <?php if ($scheduleHeading !== ''): ?>
            <h2 class="jazz-section__title"><?= htmlspecialchars($scheduleHeading, ENT_QUOTES, 'UTF-8') ?></h2>
        <?php endif; ?>
        <?php if (trim($scheduleIntro) !== ''): ?>
            <p class="jazz-section__intro"><?= $jazzText($scheduleIntro) ?></p>
        <?php endif; ?>

        <div class="jazz-artist__schedule-list">
            <?php foreach (($scheduleEvents ?? []) as $event): ?>
                <?php
                $venue = trim((string) ($event['venue'] ?? ''));
                $title = trim((string) ($event['title'] ?? ''));
                $day = trim((string) ($event['day'] ?? ''));
                $timeText = trim((string) ($event['time_text'] ?? ''));
                $price = trim((string) ($event['price'] ?? ''));
                $sectionId = (int) ($event['section_id'] ?? 0);
                $cardImage = $jazzImage($event['image'] ?? '');

                $isPaid = $price !== '' && (float) str_replace(',', '.', $price) > 0;

                $when = trim(implode(', ', array_filter([$day, $timeText])));
                $titleLine = $title;
                if ($when !== '') {
                    $titleLine = trim($title . ' | ' . $when);
                }
                ?>
                <article class="jazz-event jazz-artist__event<?= $cardImage !== '' ? ' jazz-event--has-image' : '' ?>"<?= $cardImage !== '' ? ' style="background-image:url(\'' . htmlspecialchars($cardImage, ENT_QUOTES, 'UTF-8') . '\')"' : '' ?>>
                    <?php if ($venue !== ''): ?>
                        <p class="jazz-event__venue"><?= htmlspecialchars($venue, ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endif; ?>
                    <h3 class="jazz-event__title"><span><?= htmlspecialchars($titleLine, ENT_QUOTES, 'UTF-8') ?></span></h3>
                    <p class="jazz-event__price"><?= $isPaid ? '&euro;' . htmlspecialchars($price, ENT_QUOTES, 'UTF-8') : 'Free' ?></p>

                    <?php if ($isPaid && $sectionId > 0): ?>
                        <div class="jazz-event__actions">
                            <form method="post" action="/jazz/add-to-program" class="jazz-event__form">
                                <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="section_id" value="<?= $sectionId ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="jazz-button jazz-button--add">Add to My Programs</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
