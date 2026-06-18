-- Align Dance events with the official Dance programme.
-- Keeps using the existing event, location, image, and ticket_type tables.
USE haarlem_festival;

UPDATE event
SET is_published = 0
WHERE slug IN ('summer-dance-night', 'dance-xo-club-session');

UPDATE location
SET address = 'Rockplein 6, 2033 KK Haarlem',
    city = 'Haarlem',
    capacity = 200
WHERE LOWER(name) = LOWER('Slachthuis');

INSERT INTO location (name, address, city, capacity)
SELECT 'Slachthuis', 'Rockplein 6, 2033 KK Haarlem', 'Haarlem', 200
WHERE NOT EXISTS (SELECT 1 FROM location WHERE LOWER(name) = LOWER('Slachthuis'));

UPDATE location
SET address = 'Hoge Duin en Daalseweg 2, 2061 AG Bloemendaal',
    city = 'Bloemendaal',
    capacity = 2000
WHERE LOWER(name) = LOWER('Caprera Openluchttheater');

INSERT INTO location (name, address, city, capacity)
SELECT 'Caprera Openluchttheater', 'Hoge Duin en Daalseweg 2, 2061 AG Bloemendaal', 'Bloemendaal', 2000
WHERE NOT EXISTS (SELECT 1 FROM location WHERE LOWER(name) = LOWER('Caprera Openluchttheater'));

UPDATE location
SET address = 'Gedempte Voldersgracht 2, 2011 WD Haarlem',
    city = 'Haarlem',
    capacity = 300
WHERE LOWER(name) = LOWER('Jopenkerk');

INSERT INTO location (name, address, city, capacity)
SELECT 'Jopenkerk', 'Gedempte Voldersgracht 2, 2011 WD Haarlem', 'Haarlem', 300
WHERE NOT EXISTS (SELECT 1 FROM location WHERE LOWER(name) = LOWER('Jopenkerk'));

UPDATE location
SET address = 'Minckelersweg 2, 2031 EM Haarlem',
    city = 'Haarlem',
    capacity = 1500
WHERE LOWER(name) = LOWER('Lichtfabriek');

INSERT INTO location (name, address, city, capacity)
SELECT 'Lichtfabriek', 'Minckelersweg 2, 2031 EM Haarlem', 'Haarlem', 1500
WHERE NOT EXISTS (SELECT 1 FROM location WHERE LOWER(name) = LOWER('Lichtfabriek'));

UPDATE location
SET address = 'Grote Markt 10, 2011 RD Haarlem',
    city = 'Haarlem',
    capacity = 200
WHERE LOWER(name) = LOWER('Puncher comedy club');

INSERT INTO location (name, address, city, capacity)
SELECT 'Puncher comedy club', 'Grote Markt 10, 2011 RD Haarlem', 'Haarlem', 200
WHERE NOT EXISTS (SELECT 1 FROM location WHERE LOWER(name) = LOWER('Puncher comedy club'));

UPDATE location
SET name = 'XO the Club',
    address = 'Grote Markt 8, 2011 RD Haarlem',
    city = 'Haarlem',
    capacity = 1500
WHERE LOWER(name) = LOWER('XO the Club');

INSERT INTO location (name, address, city, capacity)
SELECT 'XO the Club', 'Grote Markt 8, 2011 RD Haarlem', 'Haarlem', 1500
WHERE NOT EXISTS (SELECT 1 FROM location WHERE LOWER(name) = LOWER('XO the Club'));

INSERT INTO image (file_path, alt_text, uploaded_by_user_id)
SELECT '/assets/images/home/home-dance.jpg', 'Crowded dance floor with red lights', 1
WHERE EXISTS (SELECT 1 FROM `user` WHERE user_id = 1)
  AND NOT EXISTS (SELECT 1 FROM image WHERE file_path = '/assets/images/home/home-dance.jpg');

SET @dance_image_id = (
    SELECT image_id
    FROM image
    WHERE file_path = '/assets/images/home/home-dance.jpg'
    LIMIT 1
);

UPDATE event e
INNER JOIN location l ON LOWER(l.name) = LOWER('Lichtfabriek')
SET e.title = 'Nicky Romero / Afrojack Dance Session',
    e.description = 'Official Dance programme: Nicky Romero / Afrojack, Back2Back session at Lichtfabriek.',
    e.start_datetime = '2026-07-24 20:00:00',
    e.end_datetime = '2026-07-25 02:00:00',
    e.location_id = l.location_id,
    e.image_id = @dance_image_id,
    e.is_published = 1
WHERE e.slug = 'dance-nicky-romero-afrojack';

INSERT INTO event (title, slug, description, start_datetime, end_datetime, location_id, image_id, is_published)
SELECT 'Nicky Romero / Afrojack Dance Session', 'dance-nicky-romero-afrojack',
    'Official Dance programme: Nicky Romero / Afrojack, Back2Back session at Lichtfabriek.',
    '2026-07-24 20:00:00', '2026-07-25 02:00:00', l.location_id, @dance_image_id, 1
FROM location l
WHERE LOWER(l.name) = LOWER('Lichtfabriek')
  AND NOT EXISTS (SELECT 1 FROM event WHERE slug = 'dance-nicky-romero-afrojack');

UPDATE event e
INNER JOIN location l ON LOWER(l.name) = LOWER('Slachthuis')
SET e.title = 'Tiësto Friday Dance Night',
    e.description = 'Official Dance programme: Tiësto, Club session at Slachthuis.',
    e.start_datetime = '2026-07-24 22:00:00',
    e.end_datetime = '2026-07-24 23:30:00',
    e.location_id = l.location_id,
    e.image_id = @dance_image_id,
    e.is_published = 1
WHERE e.slug = 'dance-tiesto-friday';

INSERT INTO event (title, slug, description, start_datetime, end_datetime, location_id, image_id, is_published)
SELECT 'Tiësto Friday Dance Night', 'dance-tiesto-friday',
    'Official Dance programme: Tiësto, Club session at Slachthuis.',
    '2026-07-24 22:00:00', '2026-07-24 23:30:00', l.location_id, @dance_image_id, 1
FROM location l
WHERE LOWER(l.name) = LOWER('Slachthuis')
  AND NOT EXISTS (SELECT 1 FROM event WHERE slug = 'dance-tiesto-friday');

UPDATE event e
INNER JOIN location l ON LOWER(l.name) = LOWER('Jopenkerk')
SET e.title = 'Hardwell Dance Session',
    e.description = 'Official Dance programme: Hardwell, Club session at Jopenkerk.',
    e.start_datetime = '2026-07-24 23:00:00',
    e.end_datetime = '2026-07-25 00:30:00',
    e.location_id = l.location_id,
    e.image_id = @dance_image_id,
    e.is_published = 1
WHERE e.slug = 'dance-hardwell';

INSERT INTO event (title, slug, description, start_datetime, end_datetime, location_id, image_id, is_published)
SELECT 'Hardwell Dance Session', 'dance-hardwell',
    'Official Dance programme: Hardwell, Club session at Jopenkerk.',
    '2026-07-24 23:00:00', '2026-07-25 00:30:00', l.location_id, @dance_image_id, 1
FROM location l
WHERE LOWER(l.name) = LOWER('Jopenkerk')
  AND NOT EXISTS (SELECT 1 FROM event WHERE slug = 'dance-hardwell');

UPDATE event e
INNER JOIN location l ON LOWER(l.name) = LOWER('XO the Club')
SET e.title = 'Armin van Buuren Friday Dance Session',
    e.description = 'Official Dance programme: Armin van Buuren, Club session at XO the Club.',
    e.start_datetime = '2026-07-24 22:00:00',
    e.end_datetime = '2026-07-24 23:30:00',
    e.location_id = l.location_id,
    e.image_id = @dance_image_id,
    e.is_published = 1
WHERE e.slug = 'dance-armin-van-buuren-friday';

INSERT INTO event (title, slug, description, start_datetime, end_datetime, location_id, image_id, is_published)
SELECT 'Armin van Buuren Friday Dance Session', 'dance-armin-van-buuren-friday',
    'Official Dance programme: Armin van Buuren, Club session at XO the Club.',
    '2026-07-24 22:00:00', '2026-07-24 23:30:00', l.location_id, @dance_image_id, 1
FROM location l
WHERE LOWER(l.name) = LOWER('XO the Club')
  AND NOT EXISTS (SELECT 1 FROM event WHERE slug = 'dance-armin-van-buuren-friday');

UPDATE event e
INNER JOIN location l ON LOWER(l.name) = LOWER('Puncher comedy club')
SET e.title = 'Martin Garrix Friday Dance Session',
    e.description = 'Official Dance programme: Martin Garrix, Club session at Puncher comedy club.',
    e.start_datetime = '2026-07-24 22:00:00',
    e.end_datetime = '2026-07-24 23:30:00',
    e.location_id = l.location_id,
    e.image_id = @dance_image_id,
    e.is_published = 1
WHERE e.slug = 'dance-martin-garrix-friday';

INSERT INTO event (title, slug, description, start_datetime, end_datetime, location_id, image_id, is_published)
SELECT 'Martin Garrix Friday Dance Session', 'dance-martin-garrix-friday',
    'Official Dance programme: Martin Garrix, Club session at Puncher comedy club.',
    '2026-07-24 22:00:00', '2026-07-24 23:30:00', l.location_id, @dance_image_id, 1
FROM location l
WHERE LOWER(l.name) = LOWER('Puncher comedy club')
  AND NOT EXISTS (SELECT 1 FROM event WHERE slug = 'dance-martin-garrix-friday');

UPDATE event e
INNER JOIN location l ON LOWER(l.name) = LOWER('Caprera Openluchttheater')
SET e.title = 'Hardwell / Martin Garrix / Armin van Buuren Dance Session',
    e.description = 'Official Dance programme: Hardwell / Martin Garrix / Armin van Buuren, Back2Back session at Caprera Openluchttheater.',
    e.start_datetime = '2026-07-25 14:00:00',
    e.end_datetime = '2026-07-25 23:00:00',
    e.location_id = l.location_id,
    e.image_id = @dance_image_id,
    e.is_published = 1
WHERE e.slug = 'dance-hardwell-martin-garrix-armin-van-buuren';

INSERT INTO event (title, slug, description, start_datetime, end_datetime, location_id, image_id, is_published)
SELECT 'Hardwell / Martin Garrix / Armin van Buuren Dance Session', 'dance-hardwell-martin-garrix-armin-van-buuren',
    'Official Dance programme: Hardwell / Martin Garrix / Armin van Buuren, Back2Back session at Caprera Openluchttheater.',
    '2026-07-25 14:00:00', '2026-07-25 23:00:00', l.location_id, @dance_image_id, 1
FROM location l
WHERE LOWER(l.name) = LOWER('Caprera Openluchttheater')
  AND NOT EXISTS (SELECT 1 FROM event WHERE slug = 'dance-hardwell-martin-garrix-armin-van-buuren');

UPDATE event e
INNER JOIN location l ON LOWER(l.name) = LOWER('Jopenkerk')
SET e.title = 'Afrojack Saturday Dance Session',
    e.description = 'Official Dance programme: Afrojack, Club session at Jopenkerk.',
    e.start_datetime = '2026-07-25 22:00:00',
    e.end_datetime = '2026-07-25 23:30:00',
    e.location_id = l.location_id,
    e.image_id = @dance_image_id,
    e.is_published = 1
WHERE e.slug = 'dance-afrojack-saturday';

INSERT INTO event (title, slug, description, start_datetime, end_datetime, location_id, image_id, is_published)
SELECT 'Afrojack Saturday Dance Session', 'dance-afrojack-saturday',
    'Official Dance programme: Afrojack, Club session at Jopenkerk.',
    '2026-07-25 22:00:00', '2026-07-25 23:30:00', l.location_id, @dance_image_id, 1
FROM location l
WHERE LOWER(l.name) = LOWER('Jopenkerk')
  AND NOT EXISTS (SELECT 1 FROM event WHERE slug = 'dance-afrojack-saturday');

UPDATE event e
INNER JOIN location l ON LOWER(l.name) = LOWER('Lichtfabriek')
SET e.title = 'TiëstoWorld Dance Session',
    e.description = 'Official Dance programme: TiëstoWorld session at Lichtfabriek.',
    e.start_datetime = '2026-07-25 21:00:00',
    e.end_datetime = '2026-07-26 01:00:00',
    e.location_id = l.location_id,
    e.image_id = @dance_image_id,
    e.is_published = 1
WHERE e.slug = 'dance-tiesto-world';

INSERT INTO event (title, slug, description, start_datetime, end_datetime, location_id, image_id, is_published)
SELECT 'TiëstoWorld Dance Session', 'dance-tiesto-world',
    'Official Dance programme: TiëstoWorld session at Lichtfabriek.',
    '2026-07-25 21:00:00', '2026-07-26 01:00:00', l.location_id, @dance_image_id, 1
FROM location l
WHERE LOWER(l.name) = LOWER('Lichtfabriek')
  AND NOT EXISTS (SELECT 1 FROM event WHERE slug = 'dance-tiesto-world');

UPDATE event e
INNER JOIN location l ON LOWER(l.name) = LOWER('Slachthuis')
SET e.title = 'Nicky Romero Saturday Dance Session',
    e.description = 'Official Dance programme: Nicky Romero, Club session at Slachthuis.',
    e.start_datetime = '2026-07-25 23:00:00',
    e.end_datetime = '2026-07-26 00:30:00',
    e.location_id = l.location_id,
    e.image_id = @dance_image_id,
    e.is_published = 1
WHERE e.slug = 'dance-nicky-romero-saturday';

INSERT INTO event (title, slug, description, start_datetime, end_datetime, location_id, image_id, is_published)
SELECT 'Nicky Romero Saturday Dance Session', 'dance-nicky-romero-saturday',
    'Official Dance programme: Nicky Romero, Club session at Slachthuis.',
    '2026-07-25 23:00:00', '2026-07-26 00:30:00', l.location_id, @dance_image_id, 1
FROM location l
WHERE LOWER(l.name) = LOWER('Slachthuis')
  AND NOT EXISTS (SELECT 1 FROM event WHERE slug = 'dance-nicky-romero-saturday');

UPDATE event e
INNER JOIN location l ON LOWER(l.name) = LOWER('Caprera Openluchttheater')
SET e.title = 'Afrojack / Tiësto / Nicky Romero Dance Session',
    e.description = 'Official Dance programme: Afrojack / Tiësto / Nicky Romero, Back2Back session at Caprera Openluchttheater.',
    e.start_datetime = '2026-07-26 14:00:00',
    e.end_datetime = '2026-07-26 23:00:00',
    e.location_id = l.location_id,
    e.image_id = @dance_image_id,
    e.is_published = 1
WHERE e.slug = 'dance-afrojack-tiesto-nicky-romero';

INSERT INTO event (title, slug, description, start_datetime, end_datetime, location_id, image_id, is_published)
SELECT 'Afrojack / Tiësto / Nicky Romero Dance Session', 'dance-afrojack-tiesto-nicky-romero',
    'Official Dance programme: Afrojack / Tiësto / Nicky Romero, Back2Back session at Caprera Openluchttheater.',
    '2026-07-26 14:00:00', '2026-07-26 23:00:00', l.location_id, @dance_image_id, 1
FROM location l
WHERE LOWER(l.name) = LOWER('Caprera Openluchttheater')
  AND NOT EXISTS (SELECT 1 FROM event WHERE slug = 'dance-afrojack-tiesto-nicky-romero');

UPDATE event e
INNER JOIN location l ON LOWER(l.name) = LOWER('Jopenkerk')
SET e.title = 'Armin van Buuren Sunday Dance Session',
    e.description = 'Official Dance programme: Armin van Buuren, Club session at Jopenkerk.',
    e.start_datetime = '2026-07-26 19:00:00',
    e.end_datetime = '2026-07-26 20:30:00',
    e.location_id = l.location_id,
    e.image_id = @dance_image_id,
    e.is_published = 1
WHERE e.slug = 'dance-armin-van-buuren-sunday';

INSERT INTO event (title, slug, description, start_datetime, end_datetime, location_id, image_id, is_published)
SELECT 'Armin van Buuren Sunday Dance Session', 'dance-armin-van-buuren-sunday',
    'Official Dance programme: Armin van Buuren, Club session at Jopenkerk.',
    '2026-07-26 19:00:00', '2026-07-26 20:30:00', l.location_id, @dance_image_id, 1
FROM location l
WHERE LOWER(l.name) = LOWER('Jopenkerk')
  AND NOT EXISTS (SELECT 1 FROM event WHERE slug = 'dance-armin-van-buuren-sunday');

UPDATE event e
INNER JOIN location l ON LOWER(l.name) = LOWER('XO the Club')
SET e.title = 'Hardwell Sunday Dance Session',
    e.description = 'Official Dance programme: Hardwell, Club session at XO the Club.',
    e.start_datetime = '2026-07-26 21:00:00',
    e.end_datetime = '2026-07-26 22:30:00',
    e.location_id = l.location_id,
    e.image_id = @dance_image_id,
    e.is_published = 1
WHERE e.slug = 'dance-hardwell-sunday';

INSERT INTO event (title, slug, description, start_datetime, end_datetime, location_id, image_id, is_published)
SELECT 'Hardwell Sunday Dance Session', 'dance-hardwell-sunday',
    'Official Dance programme: Hardwell, Club session at XO the Club.',
    '2026-07-26 21:00:00', '2026-07-26 22:30:00', l.location_id, @dance_image_id, 1
FROM location l
WHERE LOWER(l.name) = LOWER('XO the Club')
  AND NOT EXISTS (SELECT 1 FROM event WHERE slug = 'dance-hardwell-sunday');

UPDATE event e
INNER JOIN location l ON LOWER(l.name) = LOWER('Slachthuis')
SET e.title = 'Martin Garrix Sunday Dance Session',
    e.description = 'Official Dance programme: Martin Garrix, Club session at Slachthuis.',
    e.start_datetime = '2026-07-26 18:00:00',
    e.end_datetime = '2026-07-26 19:30:00',
    e.location_id = l.location_id,
    e.image_id = @dance_image_id,
    e.is_published = 1
WHERE e.slug = 'dance-martin-garrix-sunday';

INSERT INTO event (title, slug, description, start_datetime, end_datetime, location_id, image_id, is_published)
SELECT 'Martin Garrix Sunday Dance Session', 'dance-martin-garrix-sunday',
    'Official Dance programme: Martin Garrix, Club session at Slachthuis.',
    '2026-07-26 18:00:00', '2026-07-26 19:30:00', l.location_id, @dance_image_id, 1
FROM location l
WHERE LOWER(l.name) = LOWER('Slachthuis')
  AND NOT EXISTS (SELECT 1 FROM event WHERE slug = 'dance-martin-garrix-sunday');

UPDATE ticket_type tt
INNER JOIN event e ON e.event_id = tt.event_id
SET tt.name = 'Back2Back', tt.price = 75.00, tt.max_quantity = 1500
WHERE e.slug = 'dance-nicky-romero-afrojack';

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT e.event_id, 'Back2Back', 75.00, 1500
FROM event e
WHERE e.slug = 'dance-nicky-romero-afrojack'
  AND NOT EXISTS (SELECT 1 FROM ticket_type tt WHERE tt.event_id = e.event_id);

UPDATE ticket_type tt
INNER JOIN event e ON e.event_id = tt.event_id
SET tt.name = 'Club', tt.price = 60.00, tt.max_quantity = 200
WHERE e.slug = 'dance-tiesto-friday';

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT e.event_id, 'Club', 60.00, 200
FROM event e
WHERE e.slug = 'dance-tiesto-friday'
  AND NOT EXISTS (SELECT 1 FROM ticket_type tt WHERE tt.event_id = e.event_id);

UPDATE ticket_type tt
INNER JOIN event e ON e.event_id = tt.event_id
SET tt.name = 'Club', tt.price = 60.00, tt.max_quantity = 300
WHERE e.slug = 'dance-hardwell';

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT e.event_id, 'Club', 60.00, 300
FROM event e
WHERE e.slug = 'dance-hardwell'
  AND NOT EXISTS (SELECT 1 FROM ticket_type tt WHERE tt.event_id = e.event_id);

UPDATE ticket_type tt
INNER JOIN event e ON e.event_id = tt.event_id
SET tt.name = 'Club', tt.price = 60.00, tt.max_quantity = 200
WHERE e.slug = 'dance-armin-van-buuren-friday';

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT e.event_id, 'Club', 60.00, 200
FROM event e
WHERE e.slug = 'dance-armin-van-buuren-friday'
  AND NOT EXISTS (SELECT 1 FROM ticket_type tt WHERE tt.event_id = e.event_id);

UPDATE ticket_type tt
INNER JOIN event e ON e.event_id = tt.event_id
SET tt.name = 'Club', tt.price = 60.00, tt.max_quantity = 200
WHERE e.slug = 'dance-martin-garrix-friday';

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT e.event_id, 'Club', 60.00, 200
FROM event e
WHERE e.slug = 'dance-martin-garrix-friday'
  AND NOT EXISTS (SELECT 1 FROM ticket_type tt WHERE tt.event_id = e.event_id);

UPDATE ticket_type tt
INNER JOIN event e ON e.event_id = tt.event_id
SET tt.name = 'Back2Back', tt.price = 110.00, tt.max_quantity = 2000
WHERE e.slug = 'dance-hardwell-martin-garrix-armin-van-buuren';

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT e.event_id, 'Back2Back', 110.00, 2000
FROM event e
WHERE e.slug = 'dance-hardwell-martin-garrix-armin-van-buuren'
  AND NOT EXISTS (SELECT 1 FROM ticket_type tt WHERE tt.event_id = e.event_id);

UPDATE ticket_type tt
INNER JOIN event e ON e.event_id = tt.event_id
SET tt.name = 'Club', tt.price = 60.00, tt.max_quantity = 300
WHERE e.slug = 'dance-afrojack-saturday';

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT e.event_id, 'Club', 60.00, 300
FROM event e
WHERE e.slug = 'dance-afrojack-saturday'
  AND NOT EXISTS (SELECT 1 FROM ticket_type tt WHERE tt.event_id = e.event_id);

UPDATE ticket_type tt
INNER JOIN event e ON e.event_id = tt.event_id
SET tt.name = 'TiestoWorld', tt.price = 75.00, tt.max_quantity = 1500
WHERE e.slug = 'dance-tiesto-world';

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT e.event_id, 'TiestoWorld', 75.00, 1500
FROM event e
WHERE e.slug = 'dance-tiesto-world'
  AND NOT EXISTS (SELECT 1 FROM ticket_type tt WHERE tt.event_id = e.event_id);

UPDATE ticket_type tt
INNER JOIN event e ON e.event_id = tt.event_id
SET tt.name = 'Club', tt.price = 60.00, tt.max_quantity = 200
WHERE e.slug = 'dance-nicky-romero-saturday';

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT e.event_id, 'Club', 60.00, 200
FROM event e
WHERE e.slug = 'dance-nicky-romero-saturday'
  AND NOT EXISTS (SELECT 1 FROM ticket_type tt WHERE tt.event_id = e.event_id);

UPDATE ticket_type tt
INNER JOIN event e ON e.event_id = tt.event_id
SET tt.name = 'Back2Back', tt.price = 110.00, tt.max_quantity = 2000
WHERE e.slug = 'dance-afrojack-tiesto-nicky-romero';

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT e.event_id, 'Back2Back', 110.00, 2000
FROM event e
WHERE e.slug = 'dance-afrojack-tiesto-nicky-romero'
  AND NOT EXISTS (SELECT 1 FROM ticket_type tt WHERE tt.event_id = e.event_id);

UPDATE ticket_type tt
INNER JOIN event e ON e.event_id = tt.event_id
SET tt.name = 'Club', tt.price = 60.00, tt.max_quantity = 300
WHERE e.slug = 'dance-armin-van-buuren-sunday';

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT e.event_id, 'Club', 60.00, 300
FROM event e
WHERE e.slug = 'dance-armin-van-buuren-sunday'
  AND NOT EXISTS (SELECT 1 FROM ticket_type tt WHERE tt.event_id = e.event_id);

UPDATE ticket_type tt
INNER JOIN event e ON e.event_id = tt.event_id
SET tt.name = 'Club', tt.price = 90.00, tt.max_quantity = 1500
WHERE e.slug = 'dance-hardwell-sunday';

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT e.event_id, 'Club', 90.00, 1500
FROM event e
WHERE e.slug = 'dance-hardwell-sunday'
  AND NOT EXISTS (SELECT 1 FROM ticket_type tt WHERE tt.event_id = e.event_id);

UPDATE ticket_type tt
INNER JOIN event e ON e.event_id = tt.event_id
SET tt.name = 'Club', tt.price = 60.00, tt.max_quantity = 200
WHERE e.slug = 'dance-martin-garrix-sunday';

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT e.event_id, 'Club', 60.00, 200
FROM event e
WHERE e.slug = 'dance-martin-garrix-sunday'
  AND NOT EXISTS (SELECT 1 FROM ticket_type tt WHERE tt.event_id = e.event_id);
