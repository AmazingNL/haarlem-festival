-- migrate:up
-- Jazz performances are now zero-database CMS content: each one is a
-- "jazz_agenda_event" section with its own price, booked without any event or
-- ticket_type row. The per-performance event + ticket types seeded in migration
-- 23 are therefore unused. Deleting the event cascades to its ticket_type rows.
-- (The all-access pass event is kept — the passes section still uses it.)
USE haarlem_festival;

DELETE FROM event WHERE slug = 'haarlem-jazz';

-- migrate:down
USE haarlem_festival;
SELECT 1;
