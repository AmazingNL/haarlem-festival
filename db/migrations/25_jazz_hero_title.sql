-- migrate:up
-- Match the design's hero line break: "JAZZ" on the first line, "IN HAARLEM." on the second.
USE haarlem_festival;

UPDATE page_section
SET content = JSON_SET(content, '$.title_line_one', 'Jazz', '$.title_line_two', 'In Haarlem.')
WHERE section_type = 'jazz_hero'
  AND page_id = (SELECT page_id FROM page WHERE slug = 'jazz' LIMIT 1);

-- migrate:down
USE haarlem_festival;

UPDATE page_section
SET content = JSON_SET(content, '$.title_line_one', 'Jazz in', '$.title_line_two', 'Haarlem.')
WHERE section_type = 'jazz_hero'
  AND page_id = (SELECT page_id FROM page WHERE slug = 'jazz' LIMIT 1);
