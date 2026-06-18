-- Dance artist overview data for the Dance landing page.
-- Uses the existing event table for each artist's latest session.
USE haarlem_festival;

CREATE TABLE IF NOT EXISTS dance_artist (
    dance_artist_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    slug VARCHAR(120) NOT NULL,
    name VARCHAR(120) NOT NULL,
    genre VARCHAR(120) NOT NULL,
    short_description TEXT NOT NULL,
    image_path VARCHAR(500) NOT NULL DEFAULT '/assets/images/home/home-dance.jpg',
    image_alt VARCHAR(255) NOT NULL,
    latest_event_id BIGINT UNSIGNED NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_published TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (dance_artist_id),
    UNIQUE KEY uq_dance_artist_slug (slug),
    KEY idx_dance_artist_published_sort (is_published, sort_order, name),
    KEY idx_dance_artist_latest_event (latest_event_id),

    CONSTRAINT fk_dance_artist_latest_event
        FOREIGN KEY (latest_event_id)
        REFERENCES event(event_id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO dance_artist (
    slug,
    name,
    genre,
    short_description,
    image_path,
    image_alt,
    latest_event_id,
    sort_order,
    is_published
)
SELECT
    'hardwell',
    'Hardwell',
    'Big-room / electro house',
    'Hardwell brings high-pressure drops, festival-sized melodies, and a sharp mainstage sound to Haarlem Dance.',
    '/assets/images/home/home-dance.jpg',
    'Hardwell artist card image',
    e.event_id,
    1,
    1
FROM (SELECT 1) seed
LEFT JOIN event e ON e.slug = 'dance-hardwell-sunday'
ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    genre = VALUES(genre),
    short_description = VALUES(short_description),
    image_path = VALUES(image_path),
    image_alt = VALUES(image_alt),
    latest_event_id = VALUES(latest_event_id),
    sort_order = VALUES(sort_order),
    is_published = VALUES(is_published),
    updated_at = NOW();

INSERT INTO dance_artist (
    slug,
    name,
    genre,
    short_description,
    image_path,
    image_alt,
    latest_event_id,
    sort_order,
    is_published
)
SELECT
    'armin-van-buuren',
    'Armin van Buuren',
    'Trance',
    'Armin van Buuren blends euphoric trance hooks with a polished club set built for a late-night Haarlem crowd.',
    '/assets/images/home/home-dance.jpg',
    'Armin van Buuren artist card image',
    e.event_id,
    2,
    1
FROM (SELECT 1) seed
LEFT JOIN event e ON e.slug = 'dance-armin-van-buuren-sunday'
ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    genre = VALUES(genre),
    short_description = VALUES(short_description),
    image_path = VALUES(image_path),
    image_alt = VALUES(image_alt),
    latest_event_id = VALUES(latest_event_id),
    sort_order = VALUES(sort_order),
    is_published = VALUES(is_published),
    updated_at = NOW();

INSERT INTO dance_artist (
    slug,
    name,
    genre,
    short_description,
    image_path,
    image_alt,
    latest_event_id,
    sort_order,
    is_published
)
SELECT
    'martin-garrix',
    'Martin Garrix',
    'Progressive house',
    'Martin Garrix delivers bright progressive house, punchy drops, and a crowd-first set for Dance weekend.',
    '/assets/images/home/home-dance.jpg',
    'Martin Garrix artist card image',
    e.event_id,
    3,
    1
FROM (SELECT 1) seed
LEFT JOIN event e ON e.slug = 'dance-martin-garrix-sunday'
ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    genre = VALUES(genre),
    short_description = VALUES(short_description),
    image_path = VALUES(image_path),
    image_alt = VALUES(image_alt),
    latest_event_id = VALUES(latest_event_id),
    sort_order = VALUES(sort_order),
    is_published = VALUES(is_published),
    updated_at = NOW();

INSERT INTO dance_artist (
    slug,
    name,
    genre,
    short_description,
    image_path,
    image_alt,
    latest_event_id,
    sort_order,
    is_published
)
SELECT
    'tiesto',
    'Tiësto',
    'EDM / melodic house',
    'Tiësto brings melodic builds, club momentum, and a polished festival sound to the Dance programme.',
    '/assets/images/home/home-dance.jpg',
    'Tiësto artist card image',
    e.event_id,
    4,
    1
FROM (SELECT 1) seed
LEFT JOIN event e ON e.slug = 'dance-afrojack-tiesto-nicky-romero'
ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    genre = VALUES(genre),
    short_description = VALUES(short_description),
    image_path = VALUES(image_path),
    image_alt = VALUES(image_alt),
    latest_event_id = VALUES(latest_event_id),
    sort_order = VALUES(sort_order),
    is_published = VALUES(is_published),
    updated_at = NOW();

INSERT INTO dance_artist (
    slug,
    name,
    genre,
    short_description,
    image_path,
    image_alt,
    latest_event_id,
    sort_order,
    is_published
)
SELECT
    'nicky-romero',
    'Nicky Romero',
    'Progressive house',
    'Nicky Romero pairs progressive house energy with crisp hooks and a direct connection to the dance floor.',
    '/assets/images/home/home-dance.jpg',
    'Nicky Romero artist card image',
    e.event_id,
    5,
    1
FROM (SELECT 1) seed
LEFT JOIN event e ON e.slug = 'dance-afrojack-tiesto-nicky-romero'
ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    genre = VALUES(genre),
    short_description = VALUES(short_description),
    image_path = VALUES(image_path),
    image_alt = VALUES(image_alt),
    latest_event_id = VALUES(latest_event_id),
    sort_order = VALUES(sort_order),
    is_published = VALUES(is_published),
    updated_at = NOW();

INSERT INTO dance_artist (
    slug,
    name,
    genre,
    short_description,
    image_path,
    image_alt,
    latest_event_id,
    sort_order,
    is_published
)
SELECT
    'afrojack',
    'Afrojack',
    'Dutch house / electro house',
    'Afrojack anchors the lineup with Dutch house force, electro edges, and a bold Haarlem festival set.',
    '/assets/images/home/home-dance.jpg',
    'Afrojack artist card image',
    e.event_id,
    6,
    1
FROM (SELECT 1) seed
LEFT JOIN event e ON e.slug = 'dance-afrojack-tiesto-nicky-romero'
ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    genre = VALUES(genre),
    short_description = VALUES(short_description),
    image_path = VALUES(image_path),
    image_alt = VALUES(image_alt),
    latest_event_id = VALUES(latest_event_id),
    sort_order = VALUES(sort_order),
    is_published = VALUES(is_published),
    updated_at = NOW();
