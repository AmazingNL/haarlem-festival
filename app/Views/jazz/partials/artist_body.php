<?php
// Editorial content of an artist page. $artist holds the flattened "jazz_artist"
// section content; the helpers ($jazzText, $jazzImage) come from _helpers.php.
$introHeading = trim((string) ($artist['intro_heading'] ?? ''));
$introBody = (string) ($artist['intro_body'] ?? '');

$careerHeading = trim((string) ($artist['career_heading'] ?? ''));
$careerBody = (string) ($artist['career_body'] ?? '');
$careerImage = $jazzImage($artist['career_image'] ?? '');

$worksHeading = trim((string) ($artist['works_heading'] ?? ''));
$worksIntro = (string) ($artist['works_intro'] ?? '');

// Build the album cards from the fixed slots; skip empty ones.
$albums = [];
foreach (['one', 'two', 'three'] as $slot) {
    $title = trim((string) ($artist['album_' . $slot . '_title'] ?? ''));
    $text = (string) ($artist['album_' . $slot . '_text'] ?? '');
    $image = $jazzImage($artist['album_' . $slot . '_image'] ?? '');
    if ($title === '' && trim($text) === '' && $image === '') {
        continue;
    }
    $albums[] = ['title' => $title, 'text' => $text, 'image' => $image];
}

$listenHeading = trim((string) ($artist['listen_heading'] ?? ''));
$listenIntro = (string) ($artist['listen_intro'] ?? '');
$trackImage = $jazzImage($artist['listen_track_image'] ?? '');
$trackLink = trim((string) ($artist['listen_track_link'] ?? ''));
$trackTitle = trim((string) ($artist['listen_track_title'] ?? ''));
$trackArtist = trim((string) ($artist['listen_track_artist'] ?? ''));
$trackAlbum = trim((string) ($artist['listen_track_album'] ?? ''));
?>

<?php if ($introHeading !== '' || trim($introBody) !== ''): ?>
    <section class="jazz-section jazz-artist__intro">
        <div class="jazz-container">
            <?php if ($introHeading !== ''): ?>
                <h2 class="jazz-section__title"><?= htmlspecialchars($introHeading, ENT_QUOTES, 'UTF-8') ?></h2>
            <?php endif; ?>
            <?php if (trim($introBody) !== ''): ?>
                <p class="jazz-rich-text"><?= $jazzText($introBody) ?></p>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>

<?php if ($careerHeading !== '' || trim($careerBody) !== '' || $careerImage !== ''): ?>
    <section class="jazz-section jazz-artist__career">
        <div class="jazz-container jazz-artist__career-grid">
            <div class="jazz-artist__career-text">
                <?php if ($careerHeading !== ''): ?>
                    <h2 class="jazz-section__title"><?= htmlspecialchars($careerHeading, ENT_QUOTES, 'UTF-8') ?></h2>
                <?php endif; ?>
                <?php if (trim($careerBody) !== ''): ?>
                    <p class="jazz-rich-text"><?= $jazzText($careerBody) ?></p>
                <?php endif; ?>
            </div>
            <?php if ($careerImage !== ''): ?>
                <div class="jazz-artist__career-media">
                    <img src="<?= htmlspecialchars($careerImage, ENT_QUOTES, 'UTF-8') ?>"
                        alt="<?= htmlspecialchars($careerHeading !== '' ? $careerHeading : 'Career highlight', ENT_QUOTES, 'UTF-8') ?>">
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>

<?php if ($worksHeading !== '' || $albums !== []): ?>
    <section class="jazz-section jazz-artist__works">
        <div class="jazz-container">
            <?php if ($worksHeading !== ''): ?>
                <h2 class="jazz-section__title"><?= htmlspecialchars($worksHeading, ENT_QUOTES, 'UTF-8') ?></h2>
            <?php endif; ?>
            <?php if (trim($worksIntro) !== ''): ?>
                <p class="jazz-section__intro"><?= $jazzText($worksIntro) ?></p>
            <?php endif; ?>

            <div class="jazz-artist__albums">
                <?php foreach ($albums as $index => $album): ?>
                    <article class="jazz-album<?= $index % 2 === 1 ? ' jazz-album--reverse' : '' ?>">
                        <?php if ($album['image'] !== ''): ?>
                            <div class="jazz-album__media">
                                <img src="<?= htmlspecialchars($album['image'], ENT_QUOTES, 'UTF-8') ?>"
                                    alt="<?= htmlspecialchars($album['title'] !== '' ? $album['title'] : 'Album cover', ENT_QUOTES, 'UTF-8') ?>">
                            </div>
                        <?php endif; ?>
                        <div class="jazz-album__body">
                            <?php if ($album['title'] !== ''): ?>
                                <h3 class="jazz-album__title"><?= htmlspecialchars($album['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                            <?php endif; ?>
                            <?php if (trim($album['text']) !== ''): ?>
                                <p class="jazz-album__text"><?= $jazzText($album['text']) ?></p>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php if ($listenHeading !== '' || $trackTitle !== '' || $trackImage !== ''): ?>
    <section class="jazz-section jazz-artist__listen">
        <div class="jazz-container">
            <?php if ($listenHeading !== ''): ?>
                <h2 class="jazz-section__title"><?= htmlspecialchars($listenHeading, ENT_QUOTES, 'UTF-8') ?></h2>
            <?php endif; ?>
            <?php if (trim($listenIntro) !== ''): ?>
                <p class="jazz-section__intro"><?= $jazzText($listenIntro) ?></p>
            <?php endif; ?>

            <div class="jazz-listen">
                <?php if ($trackImage !== ''): ?>
                    <?php if ($trackLink !== ''): ?>
                        <a class="jazz-listen__media" href="<?= $jazzUrl($trackLink) ?>" target="_blank" rel="noopener">
                            <img src="<?= htmlspecialchars($trackImage, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($trackTitle !== '' ? $trackTitle : 'Track', ENT_QUOTES, 'UTF-8') ?>">
                            <span class="jazz-listen__play" aria-hidden="true">&#9654;</span>
                        </a>
                    <?php else: ?>
                        <div class="jazz-listen__media">
                            <img src="<?= htmlspecialchars($trackImage, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($trackTitle !== '' ? $trackTitle : 'Track', ENT_QUOTES, 'UTF-8') ?>">
                            <span class="jazz-listen__play" aria-hidden="true">&#9654;</span>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <div class="jazz-listen__info">
                    <?php if ($trackTitle !== ''): ?>
                        <p class="jazz-listen__track">&ldquo;<?= htmlspecialchars($trackTitle, ENT_QUOTES, 'UTF-8') ?>&rdquo;</p>
                    <?php endif; ?>
                    <?php if ($trackArtist !== ''): ?>
                        <p>by <?= htmlspecialchars($trackArtist, ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endif; ?>
                    <?php if ($trackAlbum !== ''): ?>
                        <p>From Album &ldquo;<?= htmlspecialchars($trackAlbum, ENT_QUOTES, 'UTF-8') ?>&rdquo;</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>
