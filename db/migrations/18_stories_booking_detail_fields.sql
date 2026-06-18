-- migrate:up
-- Add slug + detail-page fields to the stories_booking section.
-- If a section already exists it is updated; if not, one is inserted.
USE haarlem_festival;

UPDATE page_section ps
INNER JOIN page p ON p.page_id = ps.page_id
SET ps.content = JSON_SET(
    COALESCE(ps.content, '{}'),
    '$.slug',                 'buurderij-haarlem',
    '$.title',                'Upcoming Story Session',
    '$.subtitle',             'Join us for an intimate storytelling experience',
    '$.show_title',           'Meet the Farmers: Stories from Buurderij Haarlem',
    '$.show_description',     'An evening of stories, sustainability, and seasonal tastings',
    '$.breadcrumb_label',     'Buurderij Haarlem',
    '$.info_text',            '<p><strong>Additional Information</strong><br>Pay As You Like model ensures this space remains accessible to everyone while directly supporting the artists and curators involved. You are invited to contribute an amount that reflects your personal experience and financial comfort. Every contribution helps maintain our gallery and fund future projects for the community.</p>',
    '$.price',                'Pay as you like',
    '$.price_raw',            '0',
    '$.date',                 'Thursday, July 24, 2026',
    '$.time',                 '20:30–21:45 (45 minutes)',
    '$.spots_available',      '18',
    '$.spots_total',          '30',
    '$.location',             'Kweekcafe Haarlem',
    '$.hero_image',           '/assets/images/stories/farmers.jpg',
    '$.story_image',          '/assets/images/stories/pexels-mart-production-8872428.jpg',
    '$.full_description',     'Buurderij Haarlem is a local farmers market where you buy directly from regional producers. You order fresh, seasonal food online — vegetables, bread, meat, dairy, and more — and pick it up at a weekly distribution moment in Haarlem.\n\nThis is more than just shopping; it is about building a sustainable community where everyone benefits: farmers get fair compensation, customers get fresher food, and the environment benefits from reduced transportation and packaging.\n\nJoin us for an intimate storytelling session where the founders and participating farmers share their journey, challenges, and vision for a more sustainable food system in Haarlem and beyond.',
    '$.what_you_experience',  JSON_ARRAY(
        JSON_OBJECT('title', 'Personal stories from local farmers',  'subtitle', 'Hear directly from the producers'),
        JSON_OBJECT('title', 'Q&A with founders',                    'subtitle', 'Ask your burning questions'),
        JSON_OBJECT('title', 'Behind-the-scenes insights',           'subtitle', 'How the distribution system works'),
        JSON_OBJECT('title', 'Seasonal product tasting',             'subtitle', 'Sample fresh local produce')
    ),
    '$.highlights_title',    'Story Highlights',
    '$.highlights_subtitle', 'The local producers are the heart of Buurderij Haarlem. Learn what drives their commitment to sustainable, local food production.',
    '$.highlights',          JSON_ARRAY(
        JSON_OBJECT('image', '/assets/images/stories/pexels-seventov-1083855.jpg',          'tag', 'Organic',   'title', 'Organic Vegetables',   'text', 'From the harvest to your table, seasonal vegetables grown without pesticides. The farmers at Buurderij rotate crops and use compost, treating soil as a living ecosystem.'),
        JSON_OBJECT('image', '/assets/images/stories/pexels-nishantaneja-3019836.jpg',      'tag', 'Artisan',   'title', 'Seasonal Produce',      'text', 'The famous Buurderij are the ultimate fresh comfort food, traditionally served to help of the founders who brought their love for taste, quality and compiling to Haarlem.'),
        JSON_OBJECT('image', '/assets/images/stories/pexels-suzyhazelwood-1995842.jpg',     'tag', 'Dairy',     'title', 'Dairy Products',         'text', 'Fresh milk, cheese, and yogurt from small-scale farms around Haarlem. All products are made on site in traditional ways and delivered within 24 hours of production.')
    ),
    '$.gallery_title',       'The Market\'s Gallery',
    '$.gallery_images',      JSON_ARRAY(
        '/assets/images/stories/pexels-nano-erdozain-120534369-27692380.jpg',
        '/assets/images/stories/img_0301.jpg',
        '/assets/images/stories/img_0310.jpg'
    )
)
WHERE p.slug = 'stories'
  AND ps.section_type = 'stories_booking'
LIMIT 1;

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT
    p.page_id,
    'stories_booking',
    'Upcoming Story Session',
    JSON_OBJECT(
        'slug',                'buurderij-haarlem',
        'title',               'Upcoming Story Session',
        'subtitle',            'Join us for an intimate storytelling experience',
        'show_title',          'Meet the Farmers: Stories from Buurderij Haarlem',
        'show_description',    'An evening of stories, sustainability, and seasonal tastings',
        'breadcrumb_label',    'Buurderij Haarlem',
        'info_text',           '<p><strong>Additional Information</strong><br>Pay As You Like model ensures this space remains accessible to everyone while directly supporting the artists and curators involved.</p>',
        'price',               'Pay as you like',
        'price_raw',           '0',
        'date',                'Thursday, July 24, 2026',
        'time',                '20:30–21:45 (45 minutes)',
        'spots_available',     '18',
        'spots_total',         '30',
        'location',            'Kweekcafe Haarlem',
        'hero_image',          '/assets/images/stories/farmers.jpg',
        'story_image',         '/assets/images/stories/pexels-mart-production-8872428.jpg',
        'full_description',    'Buurderij Haarlem is a local farmers market where you buy directly from regional producers. Join us for an intimate storytelling session where the founders and participating farmers share their journey, challenges, and vision for a more sustainable food system in Haarlem and beyond.',
        'what_you_experience', JSON_ARRAY(
            JSON_OBJECT('title', 'Personal stories from local farmers', 'subtitle', 'Hear directly from the producers'),
            JSON_OBJECT('title', 'Q&A with founders',                   'subtitle', 'Ask your burning questions'),
            JSON_OBJECT('title', 'Behind-the-scenes insights',          'subtitle', 'How the distribution system works'),
            JSON_OBJECT('title', 'Seasonal product tasting',            'subtitle', 'Sample fresh local produce')
        ),
        'highlights_title',   'Story Highlights',
        'highlights',         JSON_ARRAY(
            JSON_OBJECT('image', '/assets/images/stories/pexels-seventov-1083855.jpg',      'tag', 'Organic', 'title', 'Organic Vegetables', 'text', 'Seasonal vegetables grown without pesticides, rotated crops and compost-based soil care.'),
            JSON_OBJECT('image', '/assets/images/stories/pexels-nishantaneja-3019836.jpg',  'tag', 'Artisan', 'title', 'Seasonal Produce',   'text', 'Fresh seasonal produce sourced directly from Haarlem region farms.'),
            JSON_OBJECT('image', '/assets/images/stories/pexels-suzyhazelwood-1995842.jpg', 'tag', 'Dairy',   'title', 'Dairy Products',     'text', 'Fresh milk, cheese, and yogurt delivered within 24 hours of production.')
        ),
        'gallery_title',      'The Market\'s Gallery',
        'gallery_images',     JSON_ARRAY(
            '/assets/images/stories/pexels-nano-erdozain-120534369-27692380.jpg',
            '/assets/images/stories/img_0301.jpg',
            '/assets/images/stories/img_0310.jpg'
        )
    ),
    6,
    1
FROM page p
WHERE p.slug = 'stories'
  AND NOT EXISTS (
      SELECT 1 FROM page_section ps2
      WHERE ps2.page_id = p.page_id AND ps2.section_type = 'stories_booking'
  )
LIMIT 1;

-- migrate:down
UPDATE page_section ps
INNER JOIN page p ON p.page_id = ps.page_id
SET ps.content = JSON_REMOVE(
    COALESCE(ps.content, '{}'),
    '$.slug', '$.hero_image', '$.story_image', '$.full_description',
    '$.breadcrumb_label', '$.what_you_experience', '$.highlights',
    '$.highlights_title', '$.highlights_subtitle',
    '$.gallery_title', '$.gallery_images'
)
WHERE p.slug = 'stories' AND ps.section_type = 'stories_booking';
