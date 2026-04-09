USE haarlem_festival;

ALTER TABLE page_section
MODIFY section_type ENUM(
  'cta',
  'text_block',
  'image_text',
  'hero',
  'feature',
  'image_left',
  'image_right',
  'journey',
  'stat',
  'timeline',
  'transport',
  'two_image_row',
  'venue',
  'cards_grid',
  'restaurants_card',
  'welcome_banner',
  'gallery',
  'stories_hero',
  'what_is_stories',
  'stories_preview',
  'storytelling_schedule',
  'haarlem_unique',
  'haarlem_taste',
  'history_hero',
  'history_timeline',
  'history_gallery',
  'history_featured_locations',
  'history_route',
  'history_info',
  'history_cta',
  'history_page_nav',
  'history_book_tour_hero',
  'history_book_tour_booking',
  'history_book_tour_route',
  'history_book_tour_schedule',
  'history_book_tour_pricing',
  'history_book_tour_notice',
  'history_book_tour_alert',
  'history_route_map_hero',
  'history_route_map_stops',
  'history_route_map_directions',
  'history_route_map_cta',
  'history_st_bavo_hero',
  'history_st_bavo_facts',
  'history_st_bavo_article',
  'history_st_bavo_sidebar',
  'history_st_bavo_route_cta',
  'history_molen_hero',
  'history_molen_facts',
  'history_molen_article',
  'history_molen_sidebar',
  'history_molen_route_cta'
) NOT NULL;

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
