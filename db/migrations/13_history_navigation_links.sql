-- Fix history page navigation links for databases created before link corrections.
USE haarlem_festival;

UPDATE page_section ps
INNER JOIN page p ON p.page_id = ps.page_id
SET ps.content = JSON_SET(
  COALESCE(ps.content, JSON_OBJECT()),
  '$.primary_button_link', '/history/book-tour',
  '$.secondary_button_link', '/history/route-map'
)
WHERE p.slug = 'history'
  AND ps.section_type = 'history_hero';

UPDATE page_section ps
INNER JOIN page p ON p.page_id = ps.page_id
SET ps.content = JSON_SET(
  COALESCE(ps.content, JSON_OBJECT()),
  '$.one_button_link', '/history/st-bavos-church',
  '$.two_button_link', '/history/molen-de-adriaan'
)
WHERE p.slug = 'history'
  AND ps.section_type = 'history_featured_locations';

UPDATE page_section ps
INNER JOIN page p ON p.page_id = ps.page_id
SET ps.content = JSON_SET(
  COALESCE(ps.content, JSON_OBJECT()),
  '$.venue_one_link', '/history/st-bavos-church',
  '$.venue_seven_link', '/history/molen-de-adriaan',
  '$.button_link', '/history/route-map'
)
WHERE p.slug = 'history'
  AND ps.section_type = 'history_route';

UPDATE page_section ps
INNER JOIN page p ON p.page_id = ps.page_id
SET ps.content = JSON_SET(
  COALESCE(ps.content, JSON_OBJECT()),
  '$.primary_button_link', '/history/book-tour',
  '$.secondary_button_link', '/history/route-map'
)
WHERE p.slug = 'history'
  AND ps.section_type = 'history_cta';

UPDATE page_section ps
INNER JOIN page p ON p.page_id = ps.page_id
SET ps.content = JSON_SET(
  COALESCE(ps.content, JSON_OBJECT()),
  '$.button_link', '/history/route-map'
)
WHERE p.slug = 'history-book-tour'
  AND ps.section_type = 'history_book_tour_route';
