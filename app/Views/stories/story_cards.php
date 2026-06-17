<?php
$stories = [
    [
        'title'   => 'The Myth of the Grote Kerk',
        'excerpt' => 'For centuries, locals have whispered about the hidden chambers beneath Haarlem\'s most iconic church. What secrets does it hold?',
        'tag'     => 'Legend',
        'image'   => '/assets/images/admin/bbeb1a36eb356048c6592f0c6551db54.jpg',
    ],
    [
        'title'   => 'The Golden Age Merchants',
        'excerpt' => 'During the 17th century, Haarlem\'s canal-side mansions were home to the wealthiest traders in the world. Discover their stories.',
        'tag'     => 'History',
        'image'   => '/assets/images/admin/c56d86a9ae0da6e0d4564358b6cb73f3.jpg',
    ],
    [
        'title'   => 'Frans Hals & the Forgotten Portrait',
        'excerpt' => 'A painting that vanished for 200 years resurfaced in an attic on the Spaarne. The story behind it is stranger than fiction.',
        'tag'     => 'Art',
        'image'   => '/assets/images/admin/9b6d4a6e36ebd72fc86d1d10f2c39660.jpg',
    ],
    [
        'title'   => 'The Tulip Fever of 1637',
        'excerpt' => 'Haarlem was the epicentre of the world\'s first speculative bubble. A single tulip bulb could cost more than a house.',
        'tag'     => 'History',
        'image'   => '/assets/images/admin/bbeb1a36eb356048c6592f0c6551db54.jpg',
    ],
    [
        'title'   => 'The River that Shaped a City',
        'excerpt' => 'The Spaarne is more than a waterway. It has been the lifeblood of Haarlem\'s trade, culture and identity for a thousand years.',
        'tag'     => 'Nature',
        'image'   => '/assets/images/admin/c56d86a9ae0da6e0d4564358b6cb73f3.jpg',
    ],
    [
        'title'   => 'Haunted Alley of the Begijnhof',
        'excerpt' => 'Hidden behind a narrow gate lies one of Haarlem\'s most atmospheric spots — and the ghost story that goes with it.',
        'tag'     => 'Legend',
        'image'   => '/assets/images/admin/9b6d4a6e36ebd72fc86d1d10f2c39660.jpg',
    ],
];
?>

<section class="stories-section" id="stories">
    <div class="stories-section-inner">
        <h2 class="stories-section-title">Featured Stories</h2>
        <div class="stories-grid">
            <?php foreach ($stories as $story): ?>
                <article class="story-card">
                    <div class="story-card-image-wrap">
                        <img
                            src="<?= htmlspecialchars($story['image'], ENT_QUOTES, 'UTF-8') ?>"
                            alt="<?= htmlspecialchars($story['title'], ENT_QUOTES, 'UTF-8') ?>"
                            class="story-card-image"
                            loading="lazy"
                        >
                        <span class="story-card-tag"><?= htmlspecialchars($story['tag'], ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                    <div class="story-card-body">
                        <h3 class="story-card-title"><?= htmlspecialchars($story['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                        <p class="story-card-excerpt"><?= htmlspecialchars($story['excerpt'], ENT_QUOTES, 'UTF-8') ?></p>
                        <a href="#" class="story-card-link">
                            Read more
                            <svg class="icon-explore icon-fixed" viewBox="0 0 24 24">
                                <path d="M8 4l8 8-8 8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
