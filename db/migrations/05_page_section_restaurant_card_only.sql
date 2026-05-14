-- ============================================================
-- 05_page_section_restaurant_card_only.sql
-- Remove legacy restaurants_card enum value and keep restaurant_card only.
-- ============================================================
USE haarlem_festival;

UPDATE page_section
SET section_type = 'restaurant_card'
WHERE section_type = 'restaurants_card';

ALTER TABLE page_section
MODIFY COLUMN section_type ENUM(
  'cta',
  'text_block',
  'image_text',
  'restaurant_card',
  'welcome_banner',
  'gallery',
  'haarlem_unique',
  'stories_hero',
  'what_is_stories',
  'stories_preview',
  'storytelling_schedule'
) NOT NULL;
