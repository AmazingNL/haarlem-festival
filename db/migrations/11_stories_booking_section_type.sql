-- migrate:up
USE haarlem_festival;

-- Avoid ENUM truncation: normalize legacy values as VARCHAR first, then apply full enum.
ALTER TABLE page_section MODIFY section_type VARCHAR(80) NOT NULL;

UPDATE page_section SET section_type = 'restaurant_card' WHERE section_type = 'restaurants_card';
UPDATE page_section SET section_type = 'gallery' WHERE section_type = 'home_gallery';

UPDATE page_section SET section_type = 'text_block'
WHERE section_type NOT IN (
  'cta', 'text_block', 'image_text', 'hero', 'feature', 'image_left', 'image_right',
  'journey', 'stat', 'timeline', 'transport', 'two_image_row', 'venue', 'cards_grid',
  'restaurant_card', 'welcome_banner', 'welcome_banner_card', 'gallery', 'reservation',
  'stories_hero', 'what_is_stories', 'stories_preview', 'storytelling_schedule', 'stories_booking',
  'haarlem_unique', 'haarlem_taste', 'history_hero', 'history_timeline', 'history_gallery',
  'history_featured_locations', 'history_route', 'history_info', 'history_cta', 'history_page_nav',
  'history_book_tour_hero', 'history_book_tour_booking', 'history_book_tour_route',
  'history_book_tour_schedule', 'history_book_tour_pricing', 'history_book_tour_notice',
  'history_book_tour_alert', 'history_route_map_hero', 'history_route_map_stops',
  'history_route_map_directions', 'history_route_map_cta', 'history_st_bavo_hero',
  'history_st_bavo_facts', 'history_st_bavo_article', 'history_st_bavo_sidebar',
  'history_st_bavo_route_cta', 'history_molen_hero', 'history_molen_facts',
  'history_molen_article', 'history_molen_sidebar', 'history_molen_route_cta'
);

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
  'stories_booking',
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

-- migrate:down
SELECT 1;
