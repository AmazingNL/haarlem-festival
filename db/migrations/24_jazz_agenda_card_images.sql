-- migrate:up
-- Give the seeded agenda performance cards a background image. Admins can change
-- each card's image later in the CMS (the "Card Background Image" field).
USE haarlem_festival;

UPDATE page_section
SET content = JSON_SET(content, '$.image', '/assets/images/jazz/jazz-agenda.jpg')
WHERE section_type = 'jazz_agenda_event'
  AND page_id = (SELECT page_id FROM page WHERE slug = 'jazz' LIMIT 1);

-- migrate:down
USE haarlem_festival;

UPDATE page_section
SET content = JSON_REMOVE(content, '$.image')
WHERE section_type = 'jazz_agenda_event'
  AND page_id = (SELECT page_id FROM page WHERE slug = 'jazz' LIMIT 1);
