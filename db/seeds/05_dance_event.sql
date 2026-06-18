-- Summer Dance Night event (dance tag for /events?tag=dance)
USE haarlem_festival;

INSERT INTO event (title, slug, description, start_datetime, end_datetime, location_id, image_id, is_published)
SELECT 'Summer Dance Night', 'summer-dance-night',
    'An evening of dance performances and DJs in Haarlem.',
    '2026-07-23 20:00:00', '2026-07-23 23:00:00',
    (SELECT location_id FROM location WHERE name = 'Patronaat' LIMIT 1), NULL, 0
WHERE NOT EXISTS (SELECT 1 FROM event WHERE slug = 'summer-dance-night')
  AND EXISTS (SELECT 1 FROM location WHERE name = 'Patronaat');

UPDATE event
SET is_published = 0
WHERE slug = 'summer-dance-night';

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT e.event_id, 'Regular', 22.00, 500
FROM event e
WHERE e.slug = 'summer-dance-night'
  AND NOT EXISTS (
    SELECT 1 FROM ticket_type tt WHERE tt.event_id = e.event_id AND tt.name = 'Regular'
  );
