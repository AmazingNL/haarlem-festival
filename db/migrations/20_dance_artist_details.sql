-- Detail content and event relations for Dance artist pages.
USE haarlem_festival;

ALTER TABLE dance_artist
    ADD COLUMN IF NOT EXISTS biography TEXT NULL AFTER short_description,
    ADD COLUMN IF NOT EXISTS career_highlights TEXT NULL AFTER biography,
    ADD COLUMN IF NOT EXISTS gallery_images LONGTEXT NULL AFTER career_highlights;

CREATE TABLE IF NOT EXISTS dance_artist_event (
    dance_artist_event_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    dance_artist_id INT UNSIGNED NOT NULL,
    event_id BIGINT UNSIGNED NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (dance_artist_event_id),
    UNIQUE KEY uq_dance_artist_event (dance_artist_id, event_id),
    KEY idx_dance_artist_event_sort (dance_artist_id, sort_order),
    KEY idx_dance_artist_event_event (event_id),

    CONSTRAINT fk_dance_artist_event_artist
        FOREIGN KEY (dance_artist_id)
        REFERENCES dance_artist(dance_artist_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_dance_artist_event_event
        FOREIGN KEY (event_id)
        REFERENCES event(event_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

UPDATE dance_artist
SET
    biography = 'Hardwell is known for high-impact mainstage sets that blend big-room power, electro house pressure, and festival-sized melodies. His Haarlem Dance appearance brings that peak-hour sound into a compact city festival setting.',
    career_highlights = CONCAT(
        'Built an international reputation with explosive big-room sets.', CHAR(10),
        'Known for dramatic builds, punchy drops, and crowd-led moments.', CHAR(10),
        'Returns to the Dance programme across club and back-to-back sessions.'
    ),
    gallery_images = '[{"src":"/assets/images/home/home-dance.jpg","alt":"Hardwell performing at a Dance festival stage"}]'
WHERE slug = 'hardwell';

UPDATE dance_artist
SET
    biography = 'Armin van Buuren brings a trance-driven sound shaped around emotion, momentum, and clean melodic release. His Dance sessions are built for visitors who want a polished late-night set with a euphoric edge.',
    career_highlights = CONCAT(
        'A defining name in modern trance and festival dance music.', CHAR(10),
        'Recognized for long-form sets with emotional build and release.', CHAR(10),
        'Appears in both club and Sunday Dance sessions.'
    ),
    gallery_images = '[{"src":"/assets/images/home/home-dance.jpg","alt":"Armin van Buuren Dance artist stage image"}]'
WHERE slug = 'armin-van-buuren';

UPDATE dance_artist
SET
    biography = 'Martin Garrix pairs bright progressive house with direct festival energy. His Haarlem Dance sets connect radio-ready hooks, sharp drops, and a performance style made for a packed crowd.',
    career_highlights = CONCAT(
        'Known for progressive house anthems and high-energy festival sets.', CHAR(10),
        'Combines melodic hooks with strong mainstage production.', CHAR(10),
        'Featured across Friday, Sunday, and back-to-back Dance sessions.'
    ),
    gallery_images = '[{"src":"/assets/images/home/home-dance.jpg","alt":"Martin Garrix Dance artist stage image"}]'
WHERE slug = 'martin-garrix';

UPDATE dance_artist
SET
    biography = 'Tiesto blends melodic house, EDM, and club momentum into a polished performance style. His Haarlem Dance appearances move between focused club sessions and larger back-to-back festival moments.',
    career_highlights = CONCAT(
        'A long-running global name in electronic dance music.', CHAR(10),
        'Moves easily between club sets and festival-scale sessions.', CHAR(10),
        'Appears in solo and shared Dance programme sessions.'
    ),
    gallery_images = '[{"src":"/assets/images/home/home-dance.jpg","alt":"Tiesto Dance artist stage image"}]'
WHERE slug = 'tiesto';

UPDATE dance_artist
SET
    biography = 'Nicky Romero brings progressive house drive, crisp festival hooks, and a direct connection to the dance floor. His Dance weekend schedule includes solo and back-to-back sets.',
    career_highlights = CONCAT(
        'Known for progressive house with a clean festival sound.', CHAR(10),
        'Builds sets around direct crowd energy and melodic lift.', CHAR(10),
        'Featured in solo and back-to-back Dance sessions.'
    ),
    gallery_images = '[{"src":"/assets/images/home/home-dance.jpg","alt":"Nicky Romero Dance artist stage image"}]'
WHERE slug = 'nicky-romero';

UPDATE dance_artist
SET
    biography = 'Afrojack anchors the Dance lineup with Dutch house force, electro edges, and big-room confidence. His related sessions bring both club focus and back-to-back festival scale.',
    career_highlights = CONCAT(
        'Known for Dutch house, electro house, and bold festival sets.', CHAR(10),
        'Brings a hard-edged sound to the Dance programme.', CHAR(10),
        'Appears in Saturday and back-to-back Dance sessions.'
    ),
    gallery_images = '[{"src":"/assets/images/home/home-dance.jpg","alt":"Afrojack Dance artist stage image"}]'
WHERE slug = 'afrojack';

INSERT INTO dance_artist_event (dance_artist_id, event_id, sort_order)
SELECT da.dance_artist_id, e.event_id, 1
FROM dance_artist da
INNER JOIN event e ON e.slug = 'dance-hardwell'
WHERE da.slug = 'hardwell'
ON DUPLICATE KEY UPDATE sort_order = VALUES(sort_order);

INSERT INTO dance_artist_event (dance_artist_id, event_id, sort_order)
SELECT da.dance_artist_id, e.event_id, 2
FROM dance_artist da
INNER JOIN event e ON e.slug = 'dance-hardwell-martin-garrix-armin-van-buuren'
WHERE da.slug = 'hardwell'
ON DUPLICATE KEY UPDATE sort_order = VALUES(sort_order);

INSERT INTO dance_artist_event (dance_artist_id, event_id, sort_order)
SELECT da.dance_artist_id, e.event_id, 3
FROM dance_artist da
INNER JOIN event e ON e.slug = 'dance-hardwell-sunday'
WHERE da.slug = 'hardwell'
ON DUPLICATE KEY UPDATE sort_order = VALUES(sort_order);

INSERT INTO dance_artist_event (dance_artist_id, event_id, sort_order)
SELECT da.dance_artist_id, e.event_id, 1
FROM dance_artist da
INNER JOIN event e ON e.slug = 'dance-armin-van-buuren-friday'
WHERE da.slug = 'armin-van-buuren'
ON DUPLICATE KEY UPDATE sort_order = VALUES(sort_order);

INSERT INTO dance_artist_event (dance_artist_id, event_id, sort_order)
SELECT da.dance_artist_id, e.event_id, 2
FROM dance_artist da
INNER JOIN event e ON e.slug = 'dance-hardwell-martin-garrix-armin-van-buuren'
WHERE da.slug = 'armin-van-buuren'
ON DUPLICATE KEY UPDATE sort_order = VALUES(sort_order);

INSERT INTO dance_artist_event (dance_artist_id, event_id, sort_order)
SELECT da.dance_artist_id, e.event_id, 3
FROM dance_artist da
INNER JOIN event e ON e.slug = 'dance-armin-van-buuren-sunday'
WHERE da.slug = 'armin-van-buuren'
ON DUPLICATE KEY UPDATE sort_order = VALUES(sort_order);

INSERT INTO dance_artist_event (dance_artist_id, event_id, sort_order)
SELECT da.dance_artist_id, e.event_id, 1
FROM dance_artist da
INNER JOIN event e ON e.slug = 'dance-martin-garrix-friday'
WHERE da.slug = 'martin-garrix'
ON DUPLICATE KEY UPDATE sort_order = VALUES(sort_order);

INSERT INTO dance_artist_event (dance_artist_id, event_id, sort_order)
SELECT da.dance_artist_id, e.event_id, 2
FROM dance_artist da
INNER JOIN event e ON e.slug = 'dance-hardwell-martin-garrix-armin-van-buuren'
WHERE da.slug = 'martin-garrix'
ON DUPLICATE KEY UPDATE sort_order = VALUES(sort_order);

INSERT INTO dance_artist_event (dance_artist_id, event_id, sort_order)
SELECT da.dance_artist_id, e.event_id, 3
FROM dance_artist da
INNER JOIN event e ON e.slug = 'dance-martin-garrix-sunday'
WHERE da.slug = 'martin-garrix'
ON DUPLICATE KEY UPDATE sort_order = VALUES(sort_order);

INSERT INTO dance_artist_event (dance_artist_id, event_id, sort_order)
SELECT da.dance_artist_id, e.event_id, 1
FROM dance_artist da
INNER JOIN event e ON e.slug = 'dance-tiesto-friday'
WHERE da.slug = 'tiesto'
ON DUPLICATE KEY UPDATE sort_order = VALUES(sort_order);

INSERT INTO dance_artist_event (dance_artist_id, event_id, sort_order)
SELECT da.dance_artist_id, e.event_id, 2
FROM dance_artist da
INNER JOIN event e ON e.slug = 'dance-tiesto-world'
WHERE da.slug = 'tiesto'
ON DUPLICATE KEY UPDATE sort_order = VALUES(sort_order);

INSERT INTO dance_artist_event (dance_artist_id, event_id, sort_order)
SELECT da.dance_artist_id, e.event_id, 3
FROM dance_artist da
INNER JOIN event e ON e.slug = 'dance-afrojack-tiesto-nicky-romero'
WHERE da.slug = 'tiesto'
ON DUPLICATE KEY UPDATE sort_order = VALUES(sort_order);

INSERT INTO dance_artist_event (dance_artist_id, event_id, sort_order)
SELECT da.dance_artist_id, e.event_id, 1
FROM dance_artist da
INNER JOIN event e ON e.slug = 'dance-nicky-romero-afrojack'
WHERE da.slug = 'nicky-romero'
ON DUPLICATE KEY UPDATE sort_order = VALUES(sort_order);

INSERT INTO dance_artist_event (dance_artist_id, event_id, sort_order)
SELECT da.dance_artist_id, e.event_id, 2
FROM dance_artist da
INNER JOIN event e ON e.slug = 'dance-nicky-romero-saturday'
WHERE da.slug = 'nicky-romero'
ON DUPLICATE KEY UPDATE sort_order = VALUES(sort_order);

INSERT INTO dance_artist_event (dance_artist_id, event_id, sort_order)
SELECT da.dance_artist_id, e.event_id, 3
FROM dance_artist da
INNER JOIN event e ON e.slug = 'dance-afrojack-tiesto-nicky-romero'
WHERE da.slug = 'nicky-romero'
ON DUPLICATE KEY UPDATE sort_order = VALUES(sort_order);

INSERT INTO dance_artist_event (dance_artist_id, event_id, sort_order)
SELECT da.dance_artist_id, e.event_id, 1
FROM dance_artist da
INNER JOIN event e ON e.slug = 'dance-nicky-romero-afrojack'
WHERE da.slug = 'afrojack'
ON DUPLICATE KEY UPDATE sort_order = VALUES(sort_order);

INSERT INTO dance_artist_event (dance_artist_id, event_id, sort_order)
SELECT da.dance_artist_id, e.event_id, 2
FROM dance_artist da
INNER JOIN event e ON e.slug = 'dance-afrojack-saturday'
WHERE da.slug = 'afrojack'
ON DUPLICATE KEY UPDATE sort_order = VALUES(sort_order);

INSERT INTO dance_artist_event (dance_artist_id, event_id, sort_order)
SELECT da.dance_artist_id, e.event_id, 3
FROM dance_artist da
INNER JOIN event e ON e.slug = 'dance-afrojack-tiesto-nicky-romero'
WHERE da.slug = 'afrojack'
ON DUPLICATE KEY UPDATE sort_order = VALUES(sort_order);
