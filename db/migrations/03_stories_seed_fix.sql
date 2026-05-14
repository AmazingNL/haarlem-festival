-- ============================================================
-- 03_stories_seed_fix.sql
-- Fixes placeholder image paths in stories page_section content
-- Run this if 03_stories_seed.sql was already run with old data
-- ============================================================
USE haarlem_festival;

-- Delete existing stories sections and page so we can re-insert cleanly
DELETE ps FROM page_section ps
INNER JOIN page p ON p.page_id = ps.page_id
WHERE p.slug = 'stories';

DELETE FROM page WHERE slug = 'stories';

DELETE FROM image WHERE file_path LIKE '/assets/images/stories/%'
  AND uploaded_by_user_id = 1;

-- -------------------------
-- Images for Stories page
-- -------------------------
INSERT INTO image (file_path, alt_text, uploaded_by_user_id) VALUES
('/assets/images/stories/pexels-cottonbro-4911740.jpg',                    'Storytelling performance on stage',        1),
('/assets/images/stories/antonio-molinari-22FwbFrPvpU-unsplash.jpg',       'Audience listening to a story',            1),
('/assets/images/stories/Foto-Mister-Anansi-leert-de-wereld-lachen.jpeg',  'Mister Anansi performance',                1),
('/assets/images/stories/MisterAnansiLeendertJansen-1.jpg',                'Mister Anansi performer Leendert Jansen',  1),
('/assets/images/stories/pexels-cottonbro-7319358.jpg',                    'Performer on stage',                       1),
('/assets/images/stories/pexels-jibarofoto-2774556.jpg',                   'Festival crowd',                           1),
('/assets/images/stories/corrie.jpeg',                                      'Corrie ten Boom',                          1),
('/assets/images/stories/tenboom.jpg',                                      'Ten Boom house',                           1),
('/assets/images/stories/drama emotion.jpg',                                'Dramatic storytelling moment',             1);

SET @img_hero     = LAST_INSERT_ID();     -- pexels-cottonbro-4911740 (hero)
SET @img_audience = @img_hero + 1;
SET @img_anansi1  = @img_hero + 2;
SET @img_anansi2  = @img_hero + 3;
SET @img_stage    = @img_hero + 4;
SET @img_crowd    = @img_hero + 5;
SET @img_corrie   = @img_hero + 6;
SET @img_tenboom  = @img_hero + 7;
SET @img_drama    = @img_hero + 8;

-- -------------------------
-- Landing page: /stories
-- -------------------------
INSERT INTO page (title, slug, status)
VALUES ('Stories', 'stories', 'published');

SET @stories_id = LAST_INSERT_ID();

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
VALUES
(
  @stories_id,
  'stories_hero',
  'Stories matter',
  '<p>Immerse yourself in captivating tales from the heart of Haarlem. Live performances, legends, and voices that stay with you long after the curtain falls.</p>',
  1, 1
),
(
  @stories_id,
  'what_is_stories',
  'The Experience',
  CONCAT(
    '<p>Stories is a unique festival strand celebrating the art of oral storytelling. ',
    'Local and international performers take the stage to weave tales of myth, memory, and meaning — ',
    'inviting audiences of all ages into worlds built entirely from words.</p>',
    '<p>Whether you are discovering a new genre or returning to a childhood favourite, ',
    'Stories offers an unforgettable evening under the Haarlem sky.</p>',
    '<img class="wis-image" src="/assets/images/stories/antonio-molinari-22FwbFrPvpU-unsplash.jpg" alt="Audience at a storytelling event">'
  ),
  2, 1
),
(
  @stories_id,
  'stories_preview',
  'Take a Peek into the Stories',
  CONCAT(
    '<div class="sp-mosaic">',
      '<img src="/assets/images/stories/pexels-cottonbro-7319358.jpg" alt="Performer on stage">',
      '<img src="/assets/images/stories/Foto-Mister-Anansi-leert-de-wereld-lachen.jpeg" alt="Mister Anansi">',
      '<img src="/assets/images/stories/pexels-jibarofoto-2774556.jpg" alt="Festival crowd">',
      '<img src="/assets/images/stories/drama emotion.jpg" alt="Dramatic moment">',
      '<img src="/assets/images/stories/MisterAnansiLeendertJansen-1.jpg" alt="Anansi portrait">',
    '</div>'
  ),
  3, 1
),
(
  @stories_id,
  'storytelling_schedule',
  'Storytelling Schedule',
  CONCAT(
    '<div class="sched-day" data-day="thu">',
      '<div class="sched-cards">',
        '<div class="sched-card"><div class="sched-card-body">',
          '<div class="sched-card-time">20:00 – 21:30</div>',
          '<h3 class="sched-card-title">Mister Anansi</h3>',
          '<div class="sched-card-meta">Patronaat · Dutch &amp; English</div>',
        '</div></div>',
        '<div class="sched-card"><div class="sched-card-body">',
          '<div class="sched-card-time">22:00 – 23:00</div>',
          '<h3 class="sched-card-title">Buurderij Haarlem</h3>',
          '<div class="sched-card-meta">Jopenkerk · Dutch</div>',
        '</div></div>',
      '</div>',
    '</div>',
    '<div class="sched-day" data-day="fri">',
      '<div class="sched-cards">',
        '<div class="sched-card"><div class="sched-card-body">',
          '<div class="sched-card-time">19:30 – 21:00</div>',
          '<h3 class="sched-card-title">The Sea Witch</h3>',
          '<div class="sched-card-meta">Teylers Museum · English</div>',
        '</div></div>',
        '<div class="sched-card"><div class="sched-card-body">',
          '<div class="sched-card-time">21:30 – 23:00</div>',
          '<h3 class="sched-card-title">Corrie ten Boom: Her Story</h3>',
          '<div class="sched-card-meta">Grote Kerk · Dutch &amp; English</div>',
        '</div></div>',
      '</div>',
    '</div>',
    '<div class="sched-day" data-day="sat">',
      '<div class="sched-cards">',
        '<div class="sched-card"><div class="sched-card-body">',
          '<div class="sched-card-time">15:00 – 16:30</div>',
          '<h3 class="sched-card-title">Children\'s Tales</h3>',
          '<div class="sched-card-meta">Kenaupark · Dutch</div>',
        '</div></div>',
        '<div class="sched-card"><div class="sched-card-body">',
          '<div class="sched-card-time">20:30 – 22:00</div>',
          '<h3 class="sched-card-title">Mister Anansi</h3>',
          '<div class="sched-card-meta">Patronaat · Dutch &amp; English</div>',
        '</div></div>',
      '</div>',
    '</div>',
    '<div class="sched-day" data-day="sun">',
      '<div class="sched-cards">',
        '<div class="sched-card"><div class="sched-card-body">',
          '<div class="sched-card-time">14:00 – 15:30</div>',
          '<h3 class="sched-card-title">Closing Stories</h3>',
          '<div class="sched-card-meta">Philharmonie · Dutch &amp; English</div>',
        '</div></div>',
      '</div>',
    '</div>'
  ),
  4, 1
);
