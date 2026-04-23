-- ============================================================
-- 04_yummy_restaurant_cards.sql
-- Seed data for Yummy restaurant cards rendered from section content
-- ============================================================
-- migrate:up
USE haarlem_festival;

INSERT INTO page (title, slug, status)
SELECT 'Yummy', 'yummy', 'published'
WHERE NOT EXISTS (
    SELECT 1 FROM page WHERE slug = 'yummy'
);

SET @yummy_page_id = (SELECT page_id FROM page WHERE slug = 'yummy' LIMIT 1);

-- Remove existing card rows before re-seeding to avoid duplicates.
DELETE FROM page_section
WHERE page_id = @yummy_page_id
  AND section_type = 'restaurant_card';

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
VALUES
(
  @yummy_page_id,
  'restaurant_card',
  'Ratatouille',
  JSON_OBJECT(
    'title', 'Ratatouille',
    'introduction', 'Ratatouille Food & Wine is one of Haarlem''s top culinary destinations, offering an unforgettable Michelin-starred experience.',
    'rating', '4.0',
    'capacity', '52',
    'button_text', 'View',
    'button_link', '/ratatouille',
    'cuisine', JSON_ARRAY('Sea Food', 'French', 'European'),
    'section_image', JSON_ARRAY('/assets/images/yummy/yummy.jpg')
  ),
  10,
  1
),
(
  @yummy_page_id,
  'restaurant_card',
  'Bistro Toujours',
  JSON_OBJECT(
    'title', 'Bistro Toujours',
    'introduction', 'Bistro Toujours captures the charm of a classic French bistro while adding its own modern Haarlem identity.',
    'rating', '3.0',
    'capacity', '48',
    'button_text', 'View',
    'button_link', '#',
    'cuisine', JSON_ARRAY('Sea Food', 'Dutch', 'European'),
    'section_image', JSON_ARRAY('/assets/images/yummy/yummy.jpg')
  ),
  20,
  1
),
(
  @yummy_page_id,
  'restaurant_card',
  'New Vegas',
  JSON_OBJECT(
    'title', 'New Vegas',
    'introduction', 'New Vegas brings a fresh and modern twist to vegetarian cuisine. With creative dishes full of color, texture, and flavor.',
    'rating', '3.0',
    'capacity', '36',
    'button_text', 'View',
    'button_link', '#',
    'cuisine', JSON_ARRAY('Vegan'),
    'section_image', JSON_ARRAY('/assets/images/yummy/yummy.jpg')
  ),
  30,
  1
),
(
  @yummy_page_id,
  'restaurant_card',
  'Grand Cafe Brinkman',
  JSON_OBJECT(
    'title', 'Grand Cafe Brinkman',
    'introduction', 'Grand Cafe Brinkman is one of Haarlem''s most iconic gathering places, located right on the Grote Markt.',
    'rating', '3.0',
    'capacity', '100',
    'button_text', 'View',
    'button_link', '#',
    'cuisine', JSON_ARRAY('Modern', 'Dutch'),
    'section_image', JSON_ARRAY('/assets/images/yummy/yummy.jpg')
  ),
  40,
  1
),
(
  @yummy_page_id,
  'restaurant_card',
  'Cafe de Roemer',
  JSON_OBJECT(
    'title', 'Cafe de Roemer',
    'introduction', 'Cafe de Roemer is a warm and inviting cafe-bar offering a mix of seafood and European dishes.',
    'rating', '4.0',
    'capacity', '35',
    'button_text', 'View',
    'button_link', '#',
    'cuisine', JSON_ARRAY('Sea Food', 'Dutch'),
    'section_image', JSON_ARRAY('/assets/images/yummy/yummy.jpg')
  ),
  50,
  1
),
(
  @yummy_page_id,
  'restaurant_card',
  'Restaurant Fris',
  JSON_OBJECT(
    'title', 'Restaurant Fris',
    'introduction', 'Fris brings a fresh and modern twist to vegetarian cuisine. Creative dishes full of color, texture, and flavor.',
    'rating', '3.0',
    'capacity', '45',
    'button_text', 'View',
    'button_link', '#',
    'cuisine', JSON_ARRAY('French', 'Dutch'),
    'section_image', JSON_ARRAY('/assets/images/yummy/yummy.jpg')
  ),
  60,
  1
),
(
  @yummy_page_id,
  'restaurant_card',
  'Restaurant ML',
  JSON_OBJECT(
    'title', 'Restaurant ML',
    'introduction', 'Restaurant ML offers a refined dining experience in the heart of Haarlem, known for its elegant atmosphere and beautifully crafted dishes.',
    'rating', '4.0',
    'capacity', '60',
    'button_text', 'View',
    'button_link', '#',
    'cuisine', JSON_ARRAY('Sea Food', 'Dutch'),
    'section_image', JSON_ARRAY('/assets/images/yummy/yummy.jpg')
  ),
  70,
  1
);

/* -- migrate:down
SET @yummy_page_id = (SELECT page_id FROM page WHERE slug = 'yummy' LIMIT 1);

DELETE FROM page_section
WHERE page_id = @yummy_page_id
  AND section_type IN ('restaurant_card', 'restaurant_card');

DELETE FROM page WHERE slug = 'yummy';
*/

