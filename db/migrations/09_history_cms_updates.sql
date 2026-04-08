USE haarlem_festival;

UPDATE page_section ps
INNER JOIN page p ON p.page_id = ps.page_id
SET ps.content = JSON_SET(
  COALESCE(ps.content, JSON_OBJECT()),
  '$.quick_link_one_label', 'Book Tour',
  '$.quick_link_one_link', '/history/book-tour',
  '$.quick_link_two_label', 'Route Map',
  '$.quick_link_two_link', '/history/route-map',
  '$.quick_link_three_label', 'St. Bavo''s Church',
  '$.quick_link_three_link', '/history/st-bavos-church',
  '$.quick_link_four_label', 'Molen de Adriaan',
  '$.quick_link_four_link', '/history/molen-de-adriaan'
)
WHERE p.slug = 'history'
  AND ps.section_type = 'history_hero';

DELETE ps FROM page_section ps
INNER JOIN page p ON p.page_id = ps.page_id
WHERE p.slug IN (
    'history-book-tour',
    'history-route-map',
    'history-st-bavos-church',
    'history-molen-de-adriaan'
)
AND ps.section_type = 'history_page_nav';

INSERT INTO page_section (page_id, section_type, content, sort_order, is_published)
SELECT
  p.page_id,
  'history_page_nav',
  JSON_OBJECT(
    'book_tour_label', 'Book Tour',
    'book_tour_link', '/history/book-tour',
    'route_map_label', 'Route Map',
    'route_map_link', '/history/route-map',
    'st_bavo_label', 'St. Bavo''s Church',
    'st_bavo_link', '/history/st-bavos-church',
    'molen_label', 'Molen de Adriaan',
    'molen_link', '/history/molen-de-adriaan'
  ),
  1,
  1
FROM page p
WHERE p.slug IN (
    'history-book-tour',
    'history-route-map',
    'history-st-bavos-church',
    'history-molen-de-adriaan'
);

UPDATE page_section ps
INNER JOIN page p ON p.page_id = ps.page_id
SET ps.content = JSON_SET(
  COALESCE(ps.content, JSON_OBJECT()),
  '$.back_text', 'Back to History',
  '$.back_link', '/history'
)
WHERE p.slug = 'history-route-map'
  AND ps.section_type = 'history_route_map_hero';

UPDATE page_section ps
INNER JOIN page p ON p.page_id = ps.page_id
SET ps.content = JSON_SET(
  COALESCE(ps.content, JSON_OBJECT()),
  '$.stop_one_tone', 'start',
  '$.stop_two_tone', 'stop',
  '$.stop_three_tone', 'stop',
  '$.stop_four_tone', 'stop',
  '$.stop_five_tone', 'break',
  '$.stop_six_tone', 'stop',
  '$.stop_seven_tone', 'stop',
  '$.stop_eight_tone', 'stop',
  '$.stop_nine_tone', 'end'
)
WHERE p.slug = 'history-route-map'
  AND ps.section_type = 'history_route_map_stops';
