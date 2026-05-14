-- ============================================================
-- 07_page_section_welcome_banner_card.sql
-- Add welcome_banner_card enum value for page sections.
-- ============================================================
USE haarlem_festival;

ALTER TABLE page_section
MODIFY COLUMN section_type ENUM(
  'cta',
  'text_block',
  'image_text',
  'restaurant_card',
  'welcome_banner',
  'welcome_banner_card',
  'gallery',
  'haarlem_unique',
  'stories_hero',
  'what_is_stories',
  'stories_preview',
  'storytelling_schedule'
) NOT NULL;
