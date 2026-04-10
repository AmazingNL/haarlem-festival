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
WHERE p.slug = 'history-book-tour';

DELETE FROM page WHERE slug = 'history-book-tour';

INSERT INTO page (title, slug, status)
VALUES ('Book Tour', 'history-book-tour', 'published');

SET @history_book_tour_id = LAST_INSERT_ID();

INSERT INTO page_section (page_id, section_type, content, sort_order, is_published)
VALUES
(
  @history_book_tour_id,
  'history_book_tour_hero',
  JSON_OBJECT(
    'eyebrow', 'Last Weekend of July',
    'heading', 'Book Your Adventure',
    'intro', '2.5-hour guided walking tour through Haarlem''s historic center',
    'stat_one', '2.5 hours',
    'stat_two', '9 locations',
    'stat_three', 'Max 12 per group',
    'stat_four', 'From €17.50'
  ),
  1,
  1
),
(
  @history_book_tour_id,
  'history_book_tour_booking',
  JSON_OBJECT(
    'heading', 'Book Your Adventure',
    'intro', 'Select date, time, language, and ticket type',
    'day_label', 'Select Day',
    'day_one', 'Thursday',
    'day_two', 'Friday',
    'day_three', 'Saturday',
    'day_four', 'Sunday',
    'selected_day', 'Saturday',
    'time_label', 'Select Time',
    'time_one', '10:00',
    'time_two', '13:00',
    'time_three', '16:00',
    'selected_time', '13:00',
    'language_label', 'Select Language',
    'language_one', 'English',
    'language_two', 'Dutch',
    'language_three', 'Mandarin',
    'selected_language', 'English',
    'ticket_label', 'Ticket Type',
    'individual_title', 'Individual',
    'individual_price', '€17.50/person',
    'family_title', 'Family',
    'family_price', '€60 for up to 4',
    'family_badge', 'Best Value',
    'selected_ticket', 'Family',
    'selection_label', 'Selection',
    'ticket_summary_label', 'Ticket',
    'total_label', 'Total',
    'total_value', '€60.00',
    'saving_note', 'Save €10.00 vs individual tickets!',
    'quantity_label', 'Group Size',
    'quantity_value', '1 group',
    'button_text', 'Add to My Program',
    'button_link', '#'
  ),
  2,
  1
),
(
  @history_book_tour_id,
  'history_book_tour_route',
  JSON_OBJECT(
    'heading', 'Tour Route',
    'stop_one_title', 'Church of St. Bavo',
    'stop_one_text', 'Gothic masterpiece with famous Muller organ',
    'stop_one_time', '20 m',
    'stop_two_title', 'Grote Markt',
    'stop_two_text', 'Historic main square and heart of Haarlem',
    'stop_two_time', '15 m',
    'stop_three_title', 'De Hallen',
    'stop_three_text', 'Contemporary art museum in historic building',
    'stop_three_time', '15 m',
    'stop_four_title', 'Proveniershof',
    'stop_four_text', 'Beautiful 17th-century almshouse courtyard',
    'stop_four_time', '10 m',
    'stop_five_title', 'Jopenkerk',
    'stop_five_text', 'Historic church turned craft brewery',
    'stop_five_time', '15 m',
    'stop_five_badge', 'Break',
    'stop_six_title', 'Waalse Kerk',
    'stop_six_text', 'Charming Walloon church with rich history',
    'stop_six_time', '10 m',
    'stop_seven_title', 'Molen de Adriaan',
    'stop_seven_text', 'Iconic windmill with panoramic city views',
    'stop_seven_time', '15 m',
    'stop_eight_title', 'Amsterdamse Poort',
    'stop_eight_text', 'Medieval city gate, built in 1400',
    'stop_eight_time', '10 m',
    'stop_nine_title', 'Hof van Bakenes',
    'stop_nine_text', 'One of Haarlem''s oldest hofjes from 1395',
    'stop_nine_time', '10 m',
    'total_label', 'Total duration',
    'total_value', '2.5 hours',
    'total_note', '5 min break + 1 drink',
    'meeting_title', 'Meeting Point',
    'meeting_text', 'Bavo Church, Grote Markt - Arrive 10 min early',
    'button_text', 'Explore Route',
    'button_link', '/history#history-route'
  ),
  3,
  1
),
(
  @history_book_tour_id,
  'history_book_tour_schedule',
  JSON_OBJECT(
    'heading', 'Full Schedule',
    'intro', 'All available tours during the festival',
    'day_one', 'Thursday',
    'day_two', 'Friday',
    'day_three', 'Saturday',
    'day_four', 'Sunday',
    'guide_label', 'Guides on duty',
    'time_one', '10:00',
    'time_two', '13:00',
    'time_three', '16:00'
  ),
  4,
  1
),
(
  @history_book_tour_id,
  'history_book_tour_pricing',
  JSON_OBJECT(
    'heading', 'Pricing',
    'intro', 'Reservation is mandatory for all tours',
    'left_title', 'Individual',
    'left_subtitle', 'Per person',
    'left_price', '€17.50',
    'left_feature_one', '2.5-hour guided tour',
    'left_feature_two', 'One drink included',
    'right_badge', 'Best Value',
    'right_title', 'Family',
    'right_subtitle', 'Up to 4',
    'right_price', '€60.00',
    'right_note', 'Save €10',
    'right_feature_one', 'Up to 4 family members',
    'right_feature_two', 'Drinks for everyone'
  ),
  5,
  1
),
(
  @history_book_tour_id,
  'history_book_tour_notice',
  JSON_OBJECT(
    'title', 'Important Remarks',
    'body', 'Participants must be minimum 12 years old. No strollers allowed. Groups consist of 12 participants + 1 guide.'
  ),
  6,
  1
),
(
  @history_book_tour_id,
  'history_book_tour_alert',
  JSON_OBJECT(
    'title', 'Reservation Required',
    'body', 'Walk-ins are not accepted. Please arrive 10 minutes before departure at Bavo Church, Grote Markt.'
  ),
  7,
  1
);
