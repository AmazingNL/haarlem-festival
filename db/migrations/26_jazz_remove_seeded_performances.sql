-- migrate:up
-- Agenda performances are managed by admins through the CMS: each one is a
-- "Jazz Agenda Event" section they add or remove in the dashboard. Remove the
-- example performances that migration 23 seeded so the agenda starts empty and
-- is driven entirely by the CMS.
USE haarlem_festival;

DELETE FROM page_section
WHERE section_type = 'jazz_agenda_event'
  AND page_id = (SELECT page_id FROM page WHERE slug = 'jazz' LIMIT 1);

-- migrate:down
-- The example performances are intentionally not restored (they are CMS content
-- now). Re-run migration 23's seed if you want the examples back.
USE haarlem_festival;
SELECT 1;
