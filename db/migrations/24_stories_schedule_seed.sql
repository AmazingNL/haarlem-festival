-- Seed stories schedule cards from bookable shows (matches assessment mockup).
USE haarlem_festival;

SET @stories_page_id := (
    SELECT page_id FROM page WHERE slug = 'stories' LIMIT 1
);

UPDATE page_section
SET title = 'Storytelling Schedule',
    content = JSON_OBJECT(
        'subtitle', 'LAST WEEKEND OF JULY 2025 · HAARLEM'
    )
WHERE page_id = @stories_page_id
  AND section_type = 'storytelling_schedule'
LIMIT 1;

UPDATE page_section ps
SET ps.content = JSON_SET(
    COALESCE(NULLIF(ps.content, ''), '{}'),
    '$.schedule_day', 'friday',
    '$.schedule_language', 'nl',
    '$.schedule_age', 'All ages',
    '$.schedule_type', 'community stories',
    '$.show_title', 'Meet the Farmers: Stories from Buurderij Haarlem',
    '$.time', '20:30-21:45',
    '$.price', 'Pay as you like',
    '$.location', 'Kweekcafe Haarlem'
)
WHERE ps.page_id = @stories_page_id
  AND ps.section_type = 'stories_booking'
  AND JSON_UNQUOTE(JSON_EXTRACT(ps.content, '$.slug')) = 'buurderij-haarlem';

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT
    @stories_page_id,
    'stories_booking',
    'Winnie de Poeh',
    JSON_OBJECT(
        'slug', 'winnie-de-poeh',
        'title', 'Winnie de Poeh',
        'subtitle', 'Family storytelling session',
        'show_title', 'Winnie de Poeh',
        'show_description', 'Stories for the whole family',
        'schedule_day', 'thursday',
        'schedule_language', 'nl',
        'schedule_age', '4+',
        'schedule_type', 'stories for the whole family',
        'price', '€5',
        'price_raw', '5',
        'date', 'Thursday, July 24, 2025',
        'time', '16:00-17:00',
        'spots_available', '24',
        'spots_total', '30',
        'location', 'Verhalenhuis Haarlem',
        'hero_image', '/assets/images/stories/pexels-cottonbro-7319358.jpg',
        'story_image', '/assets/images/stories/pexels-cottonbro-7319358.jpg',
        'full_description', 'Join us for a warm, playful storytelling session inspired by Winnie de Poeh. Perfect for families with young children.',
        'what_you_experience', JSON_ARRAY(
            JSON_OBJECT('title', 'Interactive storytelling', 'subtitle', 'Performed for the whole family'),
            JSON_OBJECT('title', 'Meet the storyteller', 'subtitle', 'Q&A after the session')
        ),
        'highlights_title', 'Story Highlights',
        'highlights', JSON_ARRAY(
            JSON_OBJECT('image', '/assets/images/stories/pexels-jibarofoto-2774556.jpg', 'tag', 'Family', 'title', 'All ages welcome', 'text', 'A gentle introduction to live storytelling for children and parents.')
        ),
        'gallery_title', 'Gallery',
        'gallery_images', JSON_ARRAY('/assets/images/stories/pexels-cottonbro-7319358.jpg')
    ),
    5,
    1
WHERE @stories_page_id IS NOT NULL
  AND NOT EXISTS (
      SELECT 1 FROM page_section ps
      WHERE ps.page_id = @stories_page_id
        AND ps.section_type = 'stories_booking'
        AND JSON_UNQUOTE(JSON_EXTRACT(ps.content, '$.slug')) = 'winnie-de-poeh'
  );

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT
    @stories_page_id,
    'stories_booking',
    'Live Story Podcast',
    JSON_OBJECT(
        'slug', 'live-story-podcast',
        'title', 'Live Story Podcast',
        'subtitle', 'Recorded with a live audience',
        'show_title', 'Live Story Podcast',
        'show_description', 'Recording podcast with audience',
        'schedule_day', 'thursday',
        'schedule_language', 'en',
        'schedule_age', '15+',
        'schedule_type', 'recording podcast with audience',
        'price', '€12.50',
        'price_raw', '12.50',
        'date', 'Thursday, July 24, 2025',
        'time', '17:30-18:30',
        'spots_available', '40',
        'spots_total', '50',
        'location', 'Verhalenhuis Haarlem',
        'hero_image', '/assets/images/stories/drama emotion.jpg',
        'story_image', '/assets/images/stories/drama emotion.jpg',
        'full_description', 'Experience a live podcast recording where storytellers share bold, contemporary tales with the audience in the room.',
        'what_you_experience', JSON_ARRAY(
            JSON_OBJECT('title', 'Live recording', 'subtitle', 'Be part of the audience'),
            JSON_OBJECT('title', 'Meet the hosts', 'subtitle', 'Conversation after the show')
        ),
        'highlights_title', 'Story Highlights',
        'highlights', JSON_ARRAY(
            JSON_OBJECT('image', '/assets/images/stories/pexels-jibarofoto-2774556.jpg', 'tag', 'Podcast', 'title', 'Behind the mic', 'text', 'See how stories are shaped for audio and performance.')
        ),
        'gallery_title', 'Gallery',
        'gallery_images', JSON_ARRAY('/assets/images/stories/drama emotion.jpg')
    ),
    6,
    1
WHERE @stories_page_id IS NOT NULL
  AND NOT EXISTS (
      SELECT 1 FROM page_section ps
      WHERE ps.page_id = @stories_page_id
        AND ps.section_type = 'stories_booking'
        AND JSON_UNQUOTE(JSON_EXTRACT(ps.content, '$.slug')) = 'live-story-podcast'
  );

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT
    @stories_page_id,
    'stories_booking',
    'Stories with Impact',
    JSON_OBJECT(
        'slug', 'stories-with-impact',
        'title', 'Stories with Impact',
        'subtitle', 'Bold voices, real stories',
        'show_title', 'Stories with Impact',
        'show_description', 'Stories with impact',
        'schedule_day', 'thursday',
        'schedule_language', 'en',
        'schedule_age', '12+',
        'schedule_type', 'stories with impact',
        'price', 'Pay as you like',
        'price_raw', '0',
        'date', 'Thursday, July 24, 2025',
        'time', '19:00-20:15',
        'spots_available', '35',
        'spots_total', '45',
        'location', 'Verhalenhuis Haarlem',
        'hero_image', '/assets/images/stories/Foto-Mister-Anansi-leert-de-wereld-lachen.jpeg',
        'story_image', '/assets/images/stories/Foto-Mister-Anansi-leert-de-wereld-lachen.jpeg',
        'full_description', 'An evening of personal, political, and poetic stories told by performers who use narrative to spark conversation.',
        'what_you_experience', JSON_ARRAY(
            JSON_OBJECT('title', 'Three featured tellers', 'subtitle', 'Different styles and perspectives'),
            JSON_OBJECT('title', 'Open discussion', 'subtitle', 'Share your response with the room')
        ),
        'highlights_title', 'Story Highlights',
        'highlights', JSON_ARRAY(
            JSON_OBJECT('image', '/assets/images/stories/MisterAnansiLeendertJansen-1.jpg', 'tag', 'Impact', 'title', 'Voices that stay with you', 'text', 'Stories chosen for their emotional and social resonance.')
        ),
        'gallery_title', 'Gallery',
        'gallery_images', JSON_ARRAY('/assets/images/stories/Foto-Mister-Anansi-leert-de-wereld-lachen.jpeg')
    ),
    7,
    1
WHERE @stories_page_id IS NOT NULL
  AND NOT EXISTS (
      SELECT 1 FROM page_section ps
      WHERE ps.page_id = @stories_page_id
        AND ps.section_type = 'stories_booking'
        AND JSON_UNQUOTE(JSON_EXTRACT(ps.content, '$.slug')) = 'stories-with-impact'
  );

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT
    @stories_page_id,
    'stories_booking',
    'Mister Anansi',
    JSON_OBJECT(
        'slug', 'mister-anansi',
        'title', 'Mister Anansi',
        'subtitle', 'Friday evening headline session',
        'show_title', 'Mister Anansi',
        'show_description', 'Dutch & English storytelling',
        'schedule_day', 'friday',
        'schedule_language', 'nl',
        'schedule_age', '8+',
        'schedule_type', 'legend and laughter',
        'price', '€10',
        'price_raw', '10',
        'date', 'Friday, July 25, 2025',
        'time', '20:00-21:30',
        'spots_available', '55',
        'spots_total', '70',
        'location', 'Patronaat',
        'hero_image', '/assets/images/stories/MisterAnansiLeendertJansen-1.jpg',
        'story_image', '/assets/images/stories/MisterAnansiLeendertJansen-1.jpg',
        'full_description', 'Mister Anansi returns with tales of wit, trickery, and joy in a bilingual session for festival audiences.',
        'what_you_experience', JSON_ARRAY(
            JSON_OBJECT('title', 'Live performance', 'subtitle', 'Story and music combined')
        ),
        'highlights_title', 'Story Highlights',
        'highlights', JSON_ARRAY(
            JSON_OBJECT('image', '/assets/images/stories/Foto-Mister-Anansi-leert-de-wereld-lachen.jpeg', 'tag', 'Anansi', 'title', 'Festival favourite', 'text', 'One of the most requested storytellers in the programme.')
        ),
        'gallery_title', 'Gallery',
        'gallery_images', JSON_ARRAY('/assets/images/stories/MisterAnansiLeendertJansen-1.jpg')
    ),
    8,
    1
WHERE @stories_page_id IS NOT NULL
  AND NOT EXISTS (
      SELECT 1 FROM page_section ps
      WHERE ps.page_id = @stories_page_id
        AND ps.section_type = 'stories_booking'
        AND JSON_UNQUOTE(JSON_EXTRACT(ps.content, '$.slug')) = 'mister-anansi'
  );

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT
    @stories_page_id,
    'stories_booking',
    'Children''s Tales',
    JSON_OBJECT(
        'slug', 'childrens-tales',
        'title', 'Children''s Tales',
        'subtitle', 'Saturday afternoon family programme',
        'show_title', 'Children''s Tales',
        'show_description', 'Stories for young listeners',
        'schedule_day', 'saturday',
        'schedule_language', 'nl',
        'schedule_age', '4+',
        'schedule_type', 'stories for the whole family',
        'price', '€5',
        'price_raw', '5',
        'date', 'Saturday, July 26, 2025',
        'time', '15:00-16:30',
        'spots_available', '30',
        'spots_total', '40',
        'location', 'Kenaupark',
        'hero_image', '/assets/images/stories/pexels-cottonbro-7319358.jpg',
        'story_image', '/assets/images/stories/pexels-cottonbro-7319358.jpg',
        'full_description', 'A relaxed afternoon of short tales, songs, and participation for children and their families.',
        'what_you_experience', JSON_ARRAY(
            JSON_OBJECT('title', 'Interactive stories', 'subtitle', 'Join in from your seat')
        ),
        'highlights_title', 'Story Highlights',
        'highlights', JSON_ARRAY(
            JSON_OBJECT('image', '/assets/images/stories/pexels-jibarofoto-2774556.jpg', 'tag', 'Family', 'title', 'Outdoor session', 'text', 'Storytelling in the park when weather allows.')
        ),
        'gallery_title', 'Gallery',
        'gallery_images', JSON_ARRAY('/assets/images/stories/pexels-cottonbro-7319358.jpg')
    ),
    9,
    1
WHERE @stories_page_id IS NOT NULL
  AND NOT EXISTS (
      SELECT 1 FROM page_section ps
      WHERE ps.page_id = @stories_page_id
        AND ps.section_type = 'stories_booking'
        AND JSON_UNQUOTE(JSON_EXTRACT(ps.content, '$.slug')) = 'childrens-tales'
  );

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT
    @stories_page_id,
    'stories_booking',
    'Closing Stories',
    JSON_OBJECT(
        'slug', 'closing-stories',
        'title', 'Closing Stories',
        'subtitle', 'Sunday farewell session',
        'show_title', 'Closing Stories',
        'show_description', 'Festival closing storytelling',
        'schedule_day', 'sunday',
        'schedule_language', 'en',
        'schedule_age', '10+',
        'schedule_type', 'festival closing',
        'price', '€8',
        'price_raw', '8',
        'date', 'Sunday, July 27, 2025',
        'time', '14:00-15:30',
        'spots_available', '48',
        'spots_total', '60',
        'location', 'Philharmonie',
        'hero_image', '/assets/images/stories/antonio-molinari-22FwbFrPvpU-unsplash.jpg',
        'story_image', '/assets/images/stories/antonio-molinari-22FwbFrPvpU-unsplash.jpg',
        'full_description', 'Close the festival weekend with a curated selection of stories celebrating Haarlem, community, and memory.',
        'what_you_experience', JSON_ARRAY(
            JSON_OBJECT('title', 'Curated closing programme', 'subtitle', 'Multiple tellers on one stage')
        ),
        'highlights_title', 'Story Highlights',
        'highlights', JSON_ARRAY(
            JSON_OBJECT('image', '/assets/images/stories/antonio-molinari-22FwbFrPvpU-unsplash.jpg', 'tag', 'Closing', 'title', 'Final festival stories', 'text', 'A reflective end to the Stories weekend.')
        ),
        'gallery_title', 'Gallery',
        'gallery_images', JSON_ARRAY('/assets/images/stories/antonio-molinari-22FwbFrPvpU-unsplash.jpg')
    ),
    10,
    1
WHERE @stories_page_id IS NOT NULL
  AND NOT EXISTS (
      SELECT 1 FROM page_section ps
      WHERE ps.page_id = @stories_page_id
        AND ps.section_type = 'stories_booking'
        AND JSON_UNQUOTE(JSON_EXTRACT(ps.content, '$.slug')) = 'closing-stories'
  );
