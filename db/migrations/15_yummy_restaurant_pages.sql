-- Restaurant detail pages + reservation sections + dance event for /events?tag=dance
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
  'restaurant_card',
  'welcome_banner',
  'welcome_banner_card',
  'gallery',
  'reservation',
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

INSERT INTO page (title, slug, status)
SELECT 'Ratatouille Food & Wine', 'ratatouille', 'published'
WHERE NOT EXISTS (SELECT 1 FROM page WHERE slug = 'ratatouille');

INSERT INTO page (title, slug, status)
SELECT 'Bistro Toujours', 'bistro-toujours', 'published'
WHERE NOT EXISTS (SELECT 1 FROM page WHERE slug = 'bistro-toujours');

SET @rat_page_id = (SELECT page_id FROM page WHERE slug = 'ratatouille' LIMIT 1);
SET @bistro_page_id = (SELECT page_id FROM page WHERE slug = 'bistro-toujours' LIMIT 1);

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT @rat_page_id, 'welcome_banner', 'Ratatouille Food & Wine', JSON_OBJECT(
    'title', 'Ratatouille Food & Wine',
    'button_text', 'Book now',
    'button_link', '#reservation',
    'section_image', JSON_ARRAY('/assets/images/yummy/yummy.jpg')
), 1, 1 FROM DUAL
WHERE @rat_page_id IS NOT NULL
  AND NOT EXISTS (
    SELECT 1 FROM page_section WHERE page_id = @rat_page_id AND section_type = 'welcome_banner'
  );

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT @rat_page_id, 'reservation', 'Book your table', JSON_OBJECT(
    'title', 'Book your table',
    'information', 'A reservation fee is charged at checkout via My Program.',
    'date', JSON_ARRAY('Fri 24 Jul 2026', 'Sat 25 Jul 2026', 'Sun 26 Jul 2026'),
    'session', JSON_ARRAY('18:00', '19:30', '21:00'),
    'adultPrice', 25,
    'kidsPrice', 12.5,
    'button_text', 'Add to My Program',
    'button_link', ''
), 20, 1 FROM DUAL
WHERE @rat_page_id IS NOT NULL
  AND NOT EXISTS (
    SELECT 1 FROM page_section WHERE page_id = @rat_page_id AND section_type = 'reservation'
  );

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT @bistro_page_id, 'welcome_banner', 'Bistro Toujours', JSON_OBJECT(
    'title', 'Bistro Toujours',
    'button_text', 'Book now',
    'button_link', '#reservation',
    'section_image', JSON_ARRAY('/assets/images/yummy/yummy.jpg')
), 1, 1 FROM DUAL
WHERE @bistro_page_id IS NOT NULL
  AND NOT EXISTS (
    SELECT 1 FROM page_section WHERE page_id = @bistro_page_id AND section_type = 'welcome_banner'
  );

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT @bistro_page_id, 'reservation', 'Book your table', JSON_OBJECT(
    'title', 'Book your table',
    'information', 'A reservation fee is charged at checkout via My Program.',
    'date', JSON_ARRAY('Fri 24 Jul 2026', 'Sat 25 Jul 2026', 'Sun 26 Jul 2026'),
    'session', JSON_ARRAY('18:00', '19:30', '21:00'),
    'adultPrice', 22,
    'kidsPrice', 11,
    'button_text', 'Add to My Program',
    'button_link', ''
), 20, 1 FROM DUAL
WHERE @bistro_page_id IS NOT NULL
  AND NOT EXISTS (
    SELECT 1 FROM page_section WHERE page_id = @bistro_page_id AND section_type = 'reservation'
  );

INSERT INTO event (title, slug, description, start_datetime, end_datetime, location_id, image_id, is_published)
SELECT 'Summer Dance Night', 'summer-dance-night',
    'An evening of dance performances and DJs in Haarlem.',
    '2026-07-23 20:00:00', '2026-07-23 23:00:00', 1, NULL, 1
WHERE NOT EXISTS (SELECT 1 FROM event WHERE slug = 'summer-dance-night');

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT e.event_id, 'Regular', 22.00, 500
FROM event e
WHERE e.slug = 'summer-dance-night'
  AND NOT EXISTS (
    SELECT 1 FROM ticket_type tt WHERE tt.event_id = e.event_id AND tt.name = 'Regular'
  );
