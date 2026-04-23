-- ============================================================
-- 04_page_section_restaurant_card_alias.sql
-- Allow both restaurant_card and restaurants_card values
-- so admin-created sections and legacy data can coexist.
-- ============================================================
USE haarlem_festival;

ALTER TABLE page_section
MODIFY COLUMN section_type ENUM(
  'cta',
  'text_block',
  'image_text',
  'restaurant_card',
  'restaurants_card',
  'welcome_banner',
  'gallery',
  'haarlem_unique',
  'stories_hero',
  'what_is_stories',
  'stories_preview',
  'storytelling_schedule'
) NOT NULL;

UPDATE page_section
SET section_type = 'restaurant_card'
WHERE section_type = 'restaurants_card';
