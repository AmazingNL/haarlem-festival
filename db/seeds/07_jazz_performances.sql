-- ============================================================
-- 07_jazz_performances.sql
-- Seed data for the Jazz agenda: Saturday's performances, rendered from
-- jazz_agenda_event section content. Zero-database (no event/ticket rows) — the
-- price lives in the section. Admins can still add/remove performances in the
-- CMS; re-running the seed resets the agenda to this baseline.
-- ============================================================
-- migrate:up
USE haarlem_festival;

SET @jazz_page_id = (SELECT page_id FROM page WHERE slug = 'jazz' LIMIT 1);

-- Remove existing performance rows before re-seeding to avoid duplicates.
DELETE FROM page_section
WHERE page_id = @jazz_page_id
  AND section_type = 'jazz_agenda_event';

INSERT INTO page_section (page_id, section_type, content, sort_order, is_published)
VALUES
(
  @jazz_page_id,
  'jazz_agenda_event',
  JSON_OBJECT(
    'day', 'Saturday',
    'venue', 'Patronaat, Main Hall',
    'title', 'Gare du Nord',
    'time_text', '18:00 - 19:00',
    'description', 'Iconic Dutch-Belgian jazz-lounge band blending smooth jazz, funky grooves, and soul into a stylish, feel-good sound. With a 2001 debut with cult hit Pablo''s Blues and breakthrough album Sex ''n'' Jazz, they''re known for a creative fusion of genres that keeps audiences moving.',
    'price', '15',
    'image', '/assets/images/jazz/gare-du-nord-agenda.jpg',
    'learn_more_link', '#'
  ),
  7,
  1
),
(
  @jazz_page_id,
  'jazz_agenda_event',
  JSON_OBJECT(
    'day', 'Saturday',
    'venue', 'Patronaat, Main Hall',
    'title', 'Soul Six',
    'time_text', '21:00 - 22:00',
    'description', 'Soul/pop band from Haarlem delivering feel-good grooves and uplifting rhythms rooted in classic soul and modern pop. With engaging live energy and warm, danceable tunes, Soul Six brings crowd-friendly performances that blend timeless soul vibes with contemporary charm.',
    'price', '15',
    'image', '/assets/images/jazz/soul-six-agenda.jpg',
    'learn_more_link', '#'
  ),
  8,
  1
);
