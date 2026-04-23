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

DELETE ps FROM page_section ps
INNER JOIN page p ON p.page_id = ps.page_id
WHERE p.slug = 'history-route-map';

DELETE FROM page WHERE slug = 'history-route-map';

INSERT INTO page (title, slug, status)
VALUES ('Route Map', 'history-route-map', 'published');

SET @history_route_map_id = LAST_INSERT_ID();

INSERT INTO page_section (page_id, section_type, content, sort_order, is_published)
VALUES
(
  @history_route_map_id,
  'history_route_map_hero',
  JSON_OBJECT(
    'eyebrow', 'Explore the Route',
    'heading', 'Walking Tour Map',
    'intro', 'Follow our carefully curated route through Haarlem''s most significant historical landmarks.'
  ),
  1,
  1
),
(
  @history_route_map_id,
  'history_route_map_stops',
  JSON_OBJECT(
    'heading', 'Tour Stops',
    'summary_one', '9 venues to visit',
    'summary_two', '2.5 hours (incl. 15-min break)',
    'summary_three', 'Start: Bavo Church',
    'legend_start', 'Start Point',
    'legend_break', 'Break (15 min)',
    'legend_stop', 'Tour Stop / End',
    'stop_one_title', 'Church of St. Bavo',
    'stop_one_map_label', 'St. Bavo',
    'stop_one_text', 'Gothic masterpiece with famous Muller organ',
    'stop_one_time', '20 min',
    'stop_one_tag', 'Start',
    'stop_two_title', 'Grote Markt',
    'stop_two_map_label', 'Grote Markt',
    'stop_two_text', 'Historic main square and heart of Haarlem',
    'stop_two_time', '10 min',
    'stop_three_title', 'De Hallen',
    'stop_three_map_label', 'De Hallen',
    'stop_three_text', 'Contemporary art museum in historic building',
    'stop_three_time', '15 min',
    'stop_four_title', 'Proveniershof',
    'stop_four_map_label', 'Proveniershof',
    'stop_four_text', 'Beautiful 17th-century almshouse courtyard',
    'stop_four_time', '10 min',
    'stop_five_title', 'Jopenkerk',
    'stop_five_map_label', 'Jopenkerk',
    'stop_five_text', 'Historic church turned craft brewery',
    'stop_five_time', '15 min (break)',
    'stop_five_tag', 'Break',
    'stop_six_title', 'Waalse Kerk',
    'stop_six_map_label', 'Waalse Kerk',
    'stop_six_text', 'Charming Walloon church with rich history',
    'stop_six_time', '10 min',
    'stop_seven_title', 'Molen de Adriaan',
    'stop_seven_map_label', 'De Adriaan',
    'stop_seven_text', 'Iconic windmill with panoramic city views',
    'stop_seven_time', '15 min',
    'stop_eight_title', 'Amsterdamse Poort',
    'stop_eight_map_label', 'Amst. Poort',
    'stop_eight_text', 'Medieval city gate and fortification',
    'stop_eight_time', '10 min',
    'stop_nine_title', 'Hof van Bakenes',
    'stop_nine_map_label', 'Hof v. Bakenes',
    'stop_nine_text', 'One of Haarlem''s oldest hofjes from 1395',
    'stop_nine_time', '10 min',
    'stop_nine_tag', 'End'
  ),
  2,
  1
),
(
  @history_route_map_id,
  'history_route_map_directions',
  JSON_OBJECT(
    'heading', 'Walking Directions',
    'intro', 'Follow the route at your own pace or join one of our guided tours for expert commentary.',
    'step_one_title', 'Church of St. Bavo',
    'step_one_text', 'Begin your journey at Haarlem''s main square, in front of the magnificent Church of St. Bavo.',
    'step_two_title', 'Follow the Route',
    'step_two_text', 'Walk the 2.5km route through cobblestone streets, past hidden hofjes and along scenic canals.',
    'step_three_title', 'Hof van Bakenes',
    'step_three_text', 'Conclude your tour at the medieval city gate, a reminder of Haarlem''s fortified past.'
  ),
  3,
  1
),
(
  @history_route_map_id,
  'history_route_map_cta',
  JSON_OBJECT(
    'heading', 'Ready to Walk Through History?',
    'body', 'Book a guided tour with our expert local historians',
    'button_text', 'Book Your Adventure',
    'button_link', '/history/book-tour'
  ),
  4,
  1
);
