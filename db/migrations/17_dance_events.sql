-- Dance events for the Dance landing page.
-- Uses the existing location, image, event, and ticket_type tables.
USE haarlem_festival;

INSERT INTO location (name, address, city, capacity)
SELECT 'Slachthuis', 'Rockplein 6', 'Haarlem', 800
WHERE NOT EXISTS (SELECT 1 FROM location WHERE name = 'Slachthuis');

INSERT INTO location (name, address, city, capacity)
SELECT 'Caprera Openluchttheater', 'Hoge Duin en Daalseweg 2', 'Bloemendaal', 1100
WHERE NOT EXISTS (SELECT 1 FROM location WHERE name = 'Caprera Openluchttheater');

INSERT INTO location (name, address, city, capacity)
SELECT 'Jopenkerk', 'Gedempte Voldersgracht 2', 'Haarlem', 400
WHERE NOT EXISTS (SELECT 1 FROM location WHERE name = 'Jopenkerk');

INSERT INTO location (name, address, city, capacity)
SELECT 'XO The Club', 'Grote Markt 8', 'Haarlem', 450
WHERE NOT EXISTS (SELECT 1 FROM location WHERE name = 'XO The Club');

INSERT INTO location (name, address, city, capacity)
SELECT 'Lichtfabriek', 'Minckelersweg 2', 'Haarlem', 900
WHERE NOT EXISTS (SELECT 1 FROM location WHERE name = 'Lichtfabriek');

INSERT INTO image (file_path, alt_text, uploaded_by_user_id)
SELECT '/assets/images/home/home-dance.jpg', 'Crowded dance floor with red lights', 1
WHERE EXISTS (SELECT 1 FROM `user` WHERE user_id = 1)
  AND NOT EXISTS (SELECT 1 FROM image WHERE file_path = '/assets/images/home/home-dance.jpg');

SET @dance_image_id = (
    SELECT image_id FROM image
    WHERE file_path = '/assets/images/home/home-dance.jpg'
    LIMIT 1
);

INSERT INTO event (title, slug, description, start_datetime, end_datetime, location_id, image_id, is_published)
SELECT
    'Nicky Romero / Afrojack Dance Session',
    'dance-nicky-romero-afrojack',
    'A high-energy dance session with Nicky Romero and Afrojack at Lichtfabriek.',
    '2026-07-28 20:00:00',
    '2026-07-28 22:00:00',
    l.location_id,
    @dance_image_id,
    1
FROM location l
WHERE l.name = 'Lichtfabriek'
  AND NOT EXISTS (SELECT 1 FROM event WHERE slug = 'dance-nicky-romero-afrojack');

INSERT INTO event (title, slug, description, start_datetime, end_datetime, location_id, image_id, is_published)
SELECT
    'Tiesto Friday Dance Night',
    'dance-tiesto-friday',
    'A Friday night dance performance by Tiesto at Slachthuis.',
    '2026-07-28 22:00:00',
    '2026-07-28 23:30:00',
    l.location_id,
    @dance_image_id,
    1
FROM location l
WHERE l.name = 'Slachthuis'
  AND NOT EXISTS (SELECT 1 FROM event WHERE slug = 'dance-tiesto-friday');

INSERT INTO event (title, slug, description, start_datetime, end_datetime, location_id, image_id, is_published)
SELECT
    'Hardwell Dance Show',
    'dance-hardwell',
    'Hardwell brings a powerful dance show to Jopenkerk.',
    '2026-07-28 23:00:00',
    '2026-07-29 00:30:00',
    l.location_id,
    @dance_image_id,
    1
FROM location l
WHERE l.name = 'Jopenkerk'
  AND NOT EXISTS (SELECT 1 FROM event WHERE slug = 'dance-hardwell');

INSERT INTO event (title, slug, description, start_datetime, end_datetime, location_id, image_id, is_published)
SELECT
    'Hardwell / Martin Garrix / Armin van Buuren Dance Special',
    'dance-hardwell-martin-garrix-armin-van-buuren',
    'A special collaborative dance session with Hardwell, Martin Garrix, and Armin van Buuren.',
    '2026-07-29 14:00:00',
    '2026-07-29 23:00:00',
    l.location_id,
    @dance_image_id,
    1
FROM location l
WHERE l.name = 'Caprera Openluchttheater'
  AND NOT EXISTS (SELECT 1 FROM event WHERE slug = 'dance-hardwell-martin-garrix-armin-van-buuren');

INSERT INTO event (title, slug, description, start_datetime, end_datetime, location_id, image_id, is_published)
SELECT
    'Late Dance Club Session',
    'dance-xo-club-session',
    'A late dance session at XO The Club with festival DJs.',
    '2026-07-29 23:30:00',
    '2026-07-30 01:00:00',
    l.location_id,
    @dance_image_id,
    1
FROM location l
WHERE l.name = 'XO The Club'
  AND NOT EXISTS (SELECT 1 FROM event WHERE slug = 'dance-xo-club-session');

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT e.event_id, 'Session Ticket', 75.00, 300
FROM event e
WHERE e.slug = 'dance-nicky-romero-afrojack'
  AND NOT EXISTS (
      SELECT 1 FROM ticket_type tt
      WHERE tt.event_id = e.event_id AND tt.name = 'Session Ticket'
  );

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT e.event_id, 'Regular', 60.00, 350
FROM event e
WHERE e.slug = 'dance-tiesto-friday'
  AND NOT EXISTS (
      SELECT 1 FROM ticket_type tt
      WHERE tt.event_id = e.event_id AND tt.name = 'Regular'
  );

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT e.event_id, 'Regular', 60.00, 250
FROM event e
WHERE e.slug = 'dance-hardwell'
  AND NOT EXISTS (
      SELECT 1 FROM ticket_type tt
      WHERE tt.event_id = e.event_id AND tt.name = 'Regular'
  );

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT e.event_id, 'VIP', 110.00, 180
FROM event e
WHERE e.slug = 'dance-hardwell-martin-garrix-armin-van-buuren'
  AND NOT EXISTS (
      SELECT 1 FROM ticket_type tt
      WHERE tt.event_id = e.event_id AND tt.name = 'VIP'
  );

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT e.event_id, 'Session Ticket', 90.00, 220
FROM event e
WHERE e.slug = 'dance-xo-club-session'
  AND NOT EXISTS (
      SELECT 1 FROM ticket_type tt
      WHERE tt.event_id = e.event_id AND tt.name = 'Session Ticket'
  );
