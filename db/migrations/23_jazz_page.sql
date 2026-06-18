-- migrate:up
USE haarlem_festival;

-- 1) Allow the new jazz_* section types. This re-lists every existing value
--    (from migration 15) plus the jazz ones, because MySQL ENUM has no "add value".
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
  'history_molen_route_cta',
  'jazz_hero',
  'jazz_intro',
  'jazz_what_to_expect',
  'jazz_featured_artists',
  'jazz_more_artists',
  'jazz_agenda_intro',
  'jazz_agenda_event',
  'jazz_passes',
  'jazz_location_contact'
) NOT NULL;

-- 2) Make sure the Patronaat venue exists; the events below link to it.
INSERT INTO location (name, address, city, capacity)
SELECT 'Patronaat', 'Zijlsingel 2', 'Haarlem', 1500
WHERE NOT EXISTS (SELECT 1 FROM location WHERE name = 'Patronaat');

SET @patronaat_id = (SELECT location_id FROM location WHERE name = 'Patronaat' LIMIT 1);

-- 3) The events behind the bookable parts of the page:
--    one event for the performances, one for the all-access passes.
INSERT INTO event (title, slug, description, start_datetime, end_datetime, location_id, image_id, is_published)
SELECT 'Haarlem Jazz', 'haarlem-jazz', 'Four days of live jazz at Patronaat in Haarlem.',
  '2026-07-23 18:00:00', '2026-07-26 23:00:00', @patronaat_id, NULL, 1
WHERE NOT EXISTS (SELECT 1 FROM event WHERE slug = 'haarlem-jazz');

INSERT INTO event (title, slug, description, start_datetime, end_datetime, location_id, image_id, is_published)
SELECT 'Haarlem Jazz All-Access', 'haarlem-jazz-all-access', 'All-access passes for Haarlem Jazz.',
  '2026-07-23 18:00:00', '2026-07-26 23:00:00', @patronaat_id, NULL, 1
WHERE NOT EXISTS (SELECT 1 FROM event WHERE slug = 'haarlem-jazz-all-access');

SET @jazz_event_id = (SELECT event_id FROM event WHERE slug = 'haarlem-jazz' LIMIT 1);
SET @pass_event_id = (SELECT event_id FROM event WHERE slug = 'haarlem-jazz-all-access' LIMIT 1);

-- 4) One ticket type per performance (price matches the card shown on the page).
--    The SELECT ... FROM event pattern (with NOT EXISTS) mirrors the dance seed.
INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT @jazz_event_id, 'Gumbo Kings', 15.00, 200
WHERE NOT EXISTS (SELECT 1 FROM ticket_type WHERE event_id = @jazz_event_id AND name = 'Gumbo Kings');

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT @jazz_event_id, 'Wicked Jazz Sounds', 10.00, 150
WHERE NOT EXISTS (SELECT 1 FROM ticket_type WHERE event_id = @jazz_event_id AND name = 'Wicked Jazz Sounds');

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT @jazz_event_id, 'Evolve', 15.00, 200
WHERE NOT EXISTS (SELECT 1 FROM ticket_type WHERE event_id = @jazz_event_id AND name = 'Evolve');

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT @jazz_event_id, 'Wouter Hamel', 10.00, 150
WHERE NOT EXISTS (SELECT 1 FROM ticket_type WHERE event_id = @jazz_event_id AND name = 'Wouter Hamel');

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT @jazz_event_id, 'Ntjam Rosie', 15.00, 200
WHERE NOT EXISTS (SELECT 1 FROM ticket_type WHERE event_id = @jazz_event_id AND name = 'Ntjam Rosie');

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT @jazz_event_id, 'Jonna Fraser', 10.00, 150
WHERE NOT EXISTS (SELECT 1 FROM ticket_type WHERE event_id = @jazz_event_id AND name = 'Jonna Fraser');

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT @jazz_event_id, 'Gare du Nord', 15.00, 200
WHERE NOT EXISTS (SELECT 1 FROM ticket_type WHERE event_id = @jazz_event_id AND name = 'Gare du Nord');

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT @jazz_event_id, 'Soul Six', 10.00, 150
WHERE NOT EXISTS (SELECT 1 FROM ticket_type WHERE event_id = @jazz_event_id AND name = 'Soul Six');

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT @jazz_event_id, 'The Nordanians', 15.00, 200
WHERE NOT EXISTS (SELECT 1 FROM ticket_type WHERE event_id = @jazz_event_id AND name = 'The Nordanians');

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT @jazz_event_id, 'Karsu', 10.00, 150
WHERE NOT EXISTS (SELECT 1 FROM ticket_type WHERE event_id = @jazz_event_id AND name = 'Karsu');

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT @jazz_event_id, 'Uncle Sue', 15.00, 200
WHERE NOT EXISTS (SELECT 1 FROM ticket_type WHERE event_id = @jazz_event_id AND name = 'Uncle Sue');

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT @jazz_event_id, 'Myles Sanko', 10.00, 150
WHERE NOT EXISTS (SELECT 1 FROM ticket_type WHERE event_id = @jazz_event_id AND name = 'Myles Sanko');

-- All-access pass ticket types (one per day, plus the full festival pass).
INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT @pass_event_id, 'Day Pass - Thursday', 35.00, 500
WHERE NOT EXISTS (SELECT 1 FROM ticket_type WHERE event_id = @pass_event_id AND name = 'Day Pass - Thursday');

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT @pass_event_id, 'Day Pass - Friday', 35.00, 500
WHERE NOT EXISTS (SELECT 1 FROM ticket_type WHERE event_id = @pass_event_id AND name = 'Day Pass - Friday');

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT @pass_event_id, 'Day Pass - Saturday', 35.00, 500
WHERE NOT EXISTS (SELECT 1 FROM ticket_type WHERE event_id = @pass_event_id AND name = 'Day Pass - Saturday');

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT @pass_event_id, 'Day Pass - Sunday', 35.00, 500
WHERE NOT EXISTS (SELECT 1 FROM ticket_type WHERE event_id = @pass_event_id AND name = 'Day Pass - Sunday');

INSERT INTO ticket_type (event_id, name, price, max_quantity)
SELECT @pass_event_id, 'Full Festival Pass', 85.00, 500
WHERE NOT EXISTS (SELECT 1 FROM ticket_type WHERE event_id = @pass_event_id AND name = 'Full Festival Pass');

-- Look up the ticket type ids so the page sections can link to them.
SET @tt_gumbo      = (SELECT ticket_type_id FROM ticket_type WHERE event_id = @jazz_event_id AND name = 'Gumbo Kings' LIMIT 1);
SET @tt_wicked     = (SELECT ticket_type_id FROM ticket_type WHERE event_id = @jazz_event_id AND name = 'Wicked Jazz Sounds' LIMIT 1);
SET @tt_evolve     = (SELECT ticket_type_id FROM ticket_type WHERE event_id = @jazz_event_id AND name = 'Evolve' LIMIT 1);
SET @tt_wouter     = (SELECT ticket_type_id FROM ticket_type WHERE event_id = @jazz_event_id AND name = 'Wouter Hamel' LIMIT 1);
SET @tt_ntjam      = (SELECT ticket_type_id FROM ticket_type WHERE event_id = @jazz_event_id AND name = 'Ntjam Rosie' LIMIT 1);
SET @tt_jonna      = (SELECT ticket_type_id FROM ticket_type WHERE event_id = @jazz_event_id AND name = 'Jonna Fraser' LIMIT 1);
SET @tt_gare       = (SELECT ticket_type_id FROM ticket_type WHERE event_id = @jazz_event_id AND name = 'Gare du Nord' LIMIT 1);
SET @tt_soulsix    = (SELECT ticket_type_id FROM ticket_type WHERE event_id = @jazz_event_id AND name = 'Soul Six' LIMIT 1);
SET @tt_nordanians = (SELECT ticket_type_id FROM ticket_type WHERE event_id = @jazz_event_id AND name = 'The Nordanians' LIMIT 1);
SET @tt_karsu      = (SELECT ticket_type_id FROM ticket_type WHERE event_id = @jazz_event_id AND name = 'Karsu' LIMIT 1);
SET @tt_unclesue   = (SELECT ticket_type_id FROM ticket_type WHERE event_id = @jazz_event_id AND name = 'Uncle Sue' LIMIT 1);
SET @tt_myles      = (SELECT ticket_type_id FROM ticket_type WHERE event_id = @jazz_event_id AND name = 'Myles Sanko' LIMIT 1);

SET @tt_day_thu = (SELECT ticket_type_id FROM ticket_type WHERE event_id = @pass_event_id AND name = 'Day Pass - Thursday' LIMIT 1);
SET @tt_day_fri = (SELECT ticket_type_id FROM ticket_type WHERE event_id = @pass_event_id AND name = 'Day Pass - Friday' LIMIT 1);
SET @tt_day_sat = (SELECT ticket_type_id FROM ticket_type WHERE event_id = @pass_event_id AND name = 'Day Pass - Saturday' LIMIT 1);
SET @tt_day_sun = (SELECT ticket_type_id FROM ticket_type WHERE event_id = @pass_event_id AND name = 'Day Pass - Sunday' LIMIT 1);
SET @tt_full    = (SELECT ticket_type_id FROM ticket_type WHERE event_id = @pass_event_id AND name = 'Full Festival Pass' LIMIT 1);

-- 5) Rebuild the Jazz page and its sections (delete-then-insert keeps this re-runnable).
DELETE ps FROM page_section ps
INNER JOIN page p ON p.page_id = ps.page_id
WHERE p.slug = 'jazz';

DELETE FROM page WHERE slug = 'jazz';

INSERT INTO page (title, slug, status) VALUES ('Jazz', 'jazz', 'published');
SET @jazz_page_id = LAST_INSERT_ID();

INSERT INTO page_section (page_id, section_type, content, sort_order, is_published)
VALUES
(@jazz_page_id, 'jazz_hero', JSON_OBJECT(
  'title_line_one', 'Jazz in',
  'title_line_two', 'Haarlem.',
  'section_image', '/assets/images/jazz/jazz-hero.jpg'
), 1, 1),

(@jazz_page_id, 'jazz_intro', JSON_OBJECT(
  'heading', 'A Vibrant and Must-See Jazz Event',
  'body', '<p>Haarlem Jazz celebrates the vibrant spirit of live music in the heart of the city, bringing together soulful melodies, bold improvisation, and unforgettable performances. Against the atmospheric backdrop of Haarlem''s nightlife, visitors can immerse themselves in a rich blend of classic, contemporary, and experimental jazz. Join us for four days of rhythm, creativity, and connection as we showcase the best of jazz in an inspiring city setting!</p>'
), 2, 1),

(@jazz_page_id, 'jazz_what_to_expect', JSON_OBJECT(
  'heading', 'What to Expect',
  'body', '<p>At Haarlem Jazz, you can expect four days filled with soulful performances, intimate club sessions, and vibrant open-air concerts that bring the entire city to life. From established jazz artists to fresh contemporary acts, the festival offers a rich mix of styles that celebrate the energy and creativity of live music. Join us for a night filled with soul, passion, and blues.</p>',
  'section_image', '/assets/images/jazz/jazz-what-to-expect.jpg',
  'button_text', 'Click here to scroll to the agenda',
  'button_link', '#agenda'
), 3, 1),

(@jazz_page_id, 'jazz_featured_artists', JSON_OBJECT(
  'heading', 'Some Featured Artists',
  'intro', 'Here''s some of our featured artists that you absolutely can not miss! Click on the cards to learn a bit more about them, or go down to our agenda to find more information about every single performing artist! These artists will be sure to knock your socks off with their unique and lovely brand of jazz.',
  'one_name', 'Gare du Nord',
  'one_text', 'Dutch downtempo jazz project blending smooth grooves, jazz, soul, and electronic textures into elegant, late-night lounge music with a cool, cinematic feel.',
  'one_image', '/assets/images/jazz/artist-gare-du-nord.jpg',
  'one_button_text', 'Learn More',
  'one_button_link', '#agenda',
  'two_name', 'The Nordanians',
  'two_text', 'Phenomenal Dutch instrumental group that blends jazz, funk, and soul into some energetic, groove-driven music with a playful and modern edge. You won''t want to miss them!',
  'two_image', '/assets/images/jazz/artist-the-nordanians.jpg',
  'two_button_text', 'Learn More',
  'two_button_link', '#agenda',
  'three_name', 'Uncle Sue',
  'three_text', 'Dutch indie folk band crafting bright melodies, warm harmonies, and feel-good, guitar-driven songs. Their upbeat sound blends folk and pop influences into music made for smiling crowds and lively sing-alongs.',
  'three_image', '/assets/images/jazz/artist-uncle-sue.jpg',
  'three_button_text', 'Learn More',
  'three_button_link', '#agenda',
  'four_name', 'Gumbo Kings',
  'four_text', 'High-energy Dutch band bringing the spirit of New Orleans to the stage with swinging jazz, funky grooves, and infectious rhythms. The Gumbo Kings deliver a mix of jazz, blues, and soul that''ll get you moving!',
  'four_image', '/assets/images/jazz/artist-gumbo-kings.jpg',
  'four_button_text', 'Learn More',
  'four_button_link', '#agenda'
), 4, 1),

(@jazz_page_id, 'jazz_more_artists', JSON_OBJECT(
  'intro', 'Plenty more artists will be performing! Just scroll to the Agenda below and get more info on them! Here''s a list of the rest of the performing artists:',
  'artists', 'Soul Six, Ntjam Rosie, Wicked Jazz Sounds, Evolve, Wouter Hamel, Jonna Frazer, Karsu, Chris Allen, Myles Sanko, Ilse Huizinga, Eric Vloeimans and Hotspot, Rilan and The Bombadiers, Han Bennink, Lilith Merlot, Ruis Soundsystem'
), 5, 1),

(@jazz_page_id, 'jazz_agenda_intro', JSON_OBJECT(
  'heading', 'Agenda',
  'intro', 'Interested? Trying to book a specific event or want more information on a specific artist? Use the agenda below! Just click on an event and it''ll show you a few details about the artist and how you can book the event!'
), 6, 1),

(@jazz_page_id, 'jazz_agenda_event', JSON_OBJECT(
  'day', 'Thursday', 'venue', 'Patronaat, Main Hall', 'title', 'Gumbo Kings', 'time_text', '18:00 - 19:00',
  'description', 'Gumbo Kings are a Dutch five-piece band known for blending classic soul and rhythm-and-blues with modern grooves and contemporary production.',
  'price_label', '€15', 'event_id', @jazz_event_id, 'ticket_type_id', @tt_gumbo, 'learn_more_link', '#'
), 7, 1),

(@jazz_page_id, 'jazz_agenda_event', JSON_OBJECT(
  'day', 'Thursday', 'venue', 'Patronaat, Second Hall', 'title', 'Wicked Jazz Sounds', 'time_text', '18:00 - 19:00',
  'description', 'Amsterdam-based jazz collective and platform mixing jazz with hip-hop, soul, funk, and electronic grooves for a fresh, danceable edge.',
  'price_label', '€10', 'event_id', @jazz_event_id, 'ticket_type_id', @tt_wicked, 'learn_more_link', '#'
), 8, 1),

(@jazz_page_id, 'jazz_agenda_event', JSON_OBJECT(
  'day', 'Thursday', 'venue', 'Patronaat, Main Hall', 'title', 'Evolve', 'time_text', '19:30 - 20:30',
  'description', 'A forward-thinking jazz collective known for blending electronic textures with acoustic improvisation and atmospheric soundscapes.',
  'price_label', '€15', 'event_id', @jazz_event_id, 'ticket_type_id', @tt_evolve, 'learn_more_link', '#'
), 9, 1),

(@jazz_page_id, 'jazz_agenda_event', JSON_OBJECT(
  'day', 'Thursday', 'venue', 'Patronaat, Second Hall', 'title', 'Wouter Hamel', 'time_text', '19:30 - 20:30',
  'description', 'Dutch singer-songwriter blending jazz, pop, and vintage crooner vibes into smooth, upbeat songs with catchy melodies and charm.',
  'price_label', '€10', 'event_id', @jazz_event_id, 'ticket_type_id', @tt_wouter, 'learn_more_link', '#'
), 10, 1),

(@jazz_page_id, 'jazz_agenda_event', JSON_OBJECT(
  'day', 'Thursday', 'venue', 'Patronaat, Main Hall', 'title', 'Ntjam Rosie', 'time_text', '21:00 - 22:00',
  'description', 'An acclaimed soul-jazz vocalist whose music combines warm Afro-soul influences with contemporary jazz arrangements.',
  'price_label', '€15', 'event_id', @jazz_event_id, 'ticket_type_id', @tt_ntjam, 'learn_more_link', '#'
), 11, 1),

(@jazz_page_id, 'jazz_agenda_event', JSON_OBJECT(
  'day', 'Thursday', 'venue', 'Patronaat, Second Hall', 'title', 'Jonna Fraser', 'time_text', '21:00 - 22:00',
  'description', 'Amsterdam-based R&B and hip-hop artist known for smooth vocals, catchy hooks, and a modern, melodic sound.',
  'price_label', '€10', 'event_id', @jazz_event_id, 'ticket_type_id', @tt_jonna, 'learn_more_link', '#'
), 12, 1),

(@jazz_page_id, 'jazz_agenda_event', JSON_OBJECT(
  'day', 'Friday', 'venue', 'Patronaat, Main Hall', 'title', 'Gare du Nord', 'time_text', '19:30 - 20:30',
  'description', 'Dutch downtempo jazz project blending smooth grooves, jazz, soul, and electronic textures into late-night lounge music.',
  'price_label', '€15', 'event_id', @jazz_event_id, 'ticket_type_id', @tt_gare, 'learn_more_link', '#'
), 13, 1),

(@jazz_page_id, 'jazz_agenda_event', JSON_OBJECT(
  'day', 'Friday', 'venue', 'Patronaat, Second Hall', 'title', 'Soul Six', 'time_text', '18:00 - 19:00',
  'description', 'A tight soul and funk outfit bringing groove-driven sets full of horns, rhythm, and feel-good energy.',
  'price_label', '€10', 'event_id', @jazz_event_id, 'ticket_type_id', @tt_soulsix, 'learn_more_link', '#'
), 14, 1),

(@jazz_page_id, 'jazz_agenda_event', JSON_OBJECT(
  'day', 'Saturday', 'venue', 'Patronaat, Main Hall', 'title', 'The Nordanians', 'time_text', '19:30 - 20:30',
  'description', 'Phenomenal Dutch instrumental group blending jazz, funk, and soul into energetic, groove-driven music with a modern edge.',
  'price_label', '€15', 'event_id', @jazz_event_id, 'ticket_type_id', @tt_nordanians, 'learn_more_link', '#'
), 15, 1),

(@jazz_page_id, 'jazz_agenda_event', JSON_OBJECT(
  'day', 'Saturday', 'venue', 'Patronaat, Second Hall', 'title', 'Karsu', 'time_text', '21:00 - 22:00',
  'description', 'Singer and pianist weaving jazz, pop, and Anatolian influences into heartfelt, genre-crossing performances.',
  'price_label', '€10', 'event_id', @jazz_event_id, 'ticket_type_id', @tt_karsu, 'learn_more_link', '#'
), 16, 1),

(@jazz_page_id, 'jazz_agenda_event', JSON_OBJECT(
  'day', 'Sunday', 'venue', 'Patronaat, Main Hall', 'title', 'Uncle Sue', 'time_text', '18:00 - 19:00',
  'description', 'Dutch indie folk band crafting bright melodies, warm harmonies, and feel-good, guitar-driven sing-alongs.',
  'price_label', '€15', 'event_id', @jazz_event_id, 'ticket_type_id', @tt_unclesue, 'learn_more_link', '#'
), 17, 1),

(@jazz_page_id, 'jazz_agenda_event', JSON_OBJECT(
  'day', 'Sunday', 'venue', 'Patronaat, Second Hall', 'title', 'Myles Sanko', 'time_text', '21:00 - 22:00',
  'description', 'Soul and jazz vocalist with a rich, classic voice and retro-modern sound that is both stylish and effortlessly catchy.',
  'price_label', '€10', 'event_id', @jazz_event_id, 'ticket_type_id', @tt_myles, 'learn_more_link', '#'
), 18, 1),

(@jazz_page_id, 'jazz_passes', JSON_OBJECT(
  'heading', 'All-Access Passes',
  'intro', 'You can get all-access passes per day or for the whole event! Select what you want below:',
  'pass_event_id', @pass_event_id,
  'day_pass_label', 'All-Access Day Pass',
  'day_pass_price', '€35',
  'day_pass_thursday_ticket_id', @tt_day_thu,
  'day_pass_friday_ticket_id', @tt_day_fri,
  'day_pass_saturday_ticket_id', @tt_day_sat,
  'day_pass_sunday_ticket_id', @tt_day_sun,
  'full_pass_label', 'All-Access Pass for Thu, Fri, Sat',
  'full_pass_price', '€85',
  'full_pass_ticket_id', @tt_full
), 19, 1),

(@jazz_page_id, 'jazz_location_contact', JSON_OBJECT(
  'heading', 'Location and Contact',
  'intro', 'Wanna know the location of the event or want to contact Patronaat? Here''s the location and contact info:',
  'place_name', 'Patronaat',
  'address_line_one', 'Zijlsingel 2',
  'address_line_two', '2013 DN, Haarlem',
  'email', 'info@patronaat.nl',
  'phone_office', '023-517 58 50 (office)',
  'office_hours', 'from 10:00 to 17:00',
  'phone_cash_desk', '023-517 58 58 (cash desk / information)'
), 20, 1);

-- migrate:down
USE haarlem_festival;

DELETE ps FROM page_section ps
INNER JOIN page p ON p.page_id = ps.page_id
WHERE p.slug = 'jazz';

DELETE FROM page WHERE slug = 'jazz';

-- Deleting the events cascades to their ticket types.
DELETE FROM event WHERE slug IN ('haarlem-jazz', 'haarlem-jazz-all-access');

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
