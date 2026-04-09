USE haarlem_festival;

DELETE ps FROM page_section ps
INNER JOIN page p ON p.page_id = ps.page_id
WHERE p.slug = 'history';

DELETE FROM page WHERE slug = 'history';

INSERT INTO page (title, slug, status)
VALUES ('History', 'history', 'published');

SET @history_id = LAST_INSERT_ID();

INSERT INTO page_section (page_id, section_type, content, sort_order, is_published)
VALUES
(
  @history_id,
  'history_hero',
  JSON_OBJECT(
    'title_line_one', 'Discover',
    'title_line_two', 'Haarlem',
    'intro', 'Walk through 800 years of Dutch history in one unforgettable journey. From majestic churches to hidden courtyards, every step reveals a story.',
    'primary_button_text', 'Book Your Adventure',
    'primary_button_link', '#history-cta',
    'secondary_button_text', 'Explore Route',
    'secondary_button_link', '#history-route',
    'hero_image', '/assets/images/history/history-hero-banner.jpg'
  ),
  1,
  1
),
(
  @history_id,
  'history_timeline',
  JSON_OBJECT(
    'eyebrow', 'Our Heritage',
    'heading', 'The History of Haarlem',
    'item_one_title', 'Century of Origins',
    'item_one_text', 'Founded in the 10th century, Haarlem is one of the oldest cities in the Netherlands. Receiving its city rights in 1245, it became a vital center of commerce, art, and culture that would shape Dutch identity for centuries to come.',
    'item_one_text_secondary', 'Just 20 kilometers west of Amsterdam, Haarlem has always stood as a proud rival and often surpassed the capital in cultural achievements during the Dutch Golden Age.',
    'item_one_image', '/assets/images/history/history-century-of-origins.jpg',
    'item_one_label', 'City Center',
    'item_one_caption', 'Grote Markt at Golden Hour',
    'item_two_title', 'The Golden Age',
    'item_two_text', 'The 17th century saw Haarlem flourish as Europe''s artistic capital. Home to legendary painters Frans Hals, Jacob van Ruisdael, and Adriaen van Ostade, the Haarlem School of painting rivaled even Amsterdam in prestige.',
    'item_two_text_secondary', 'The city''s economy thrived on textile production, earning the nickname ''Linen City''. This was also the epicenter of history''s first speculative bubble, the legendary Tulip Mania of 1637.',
    'item_two_image', '/assets/images/history/history-golden-age.jpg',
    'item_two_label', 'Dutch Masters',
    'item_two_caption', 'Frans Hals and Contemporaries',
    'item_three_title', '72-73',
    'item_three_text', 'The Siege of Haarlem during the Eighty Years'' War remains a defining moment in Dutch history. For seven months, citizens heroically resisted Spanish forces in what became a symbol of Dutch determination for independence.',
    'item_three_text_secondary', 'Though the city ultimately fell, this resistance inspired the nation and is still commemorated today as a testament to the indomitable Dutch spirit.',
    'item_three_image', '/assets/images/history/history-siege-of-haarlem.jpg',
    'item_three_label', 'Living Heritage',
    'item_three_caption', 'Market Day Tradition'
  ),
  2,
  1
),
(
  @history_id,
  'history_gallery',
  JSON_OBJECT(
    'eyebrow', 'Visual Journey',
    'heading', 'What Awaits You',
    'card_one_label', 'Hidden Gems',
    'card_one_title', 'Secret Courtyards',
    'card_one_text', 'Discover 17th-century hofjes - peaceful gardens hidden behind ancient wooden gates.',
    'card_one_image', '/assets/images/history/history-hidden-gems.jpg',
    'card_two_label', 'Iconic Landmark',
    'card_two_title', 'Molen de Adriaan',
    'card_two_text', 'Climb the iconic windmill for panoramic views over the historic Spaarne River.',
    'card_two_image', '/assets/images/history/history-molen-sunset.jpg',
    'card_three_label', 'Gothic Marvel',
    'card_three_title', 'Sacred Architecture',
    'card_three_text', 'Stand beneath soaring Gothic arches where Mozart once played the famous Muller organ.',
    'card_three_image', '/assets/images/history/history-sacred-architecture.jpg'
  ),
  3,
  1
),
(
  @history_id,
  'history_featured_locations',
  JSON_OBJECT(
    'eyebrow', 'Must-See Destinations',
    'heading', 'Featured Locations',
    'intro', 'From medieval churches to world-class museums, each stop on our tour reveals centuries of Dutch heritage.',
    'one_label', 'Tour Highlight',
    'one_title', 'Grote Kerk (St. Bavo''s Church)',
    'one_text', 'The magnificent Gothic cathedral has dominated Haarlem''s skyline for over 500 years. Its tower is visible across the region and the church houses the famous Muller organ once played by Mozart.',
    'one_badge', 'Est. 1520',
    'one_image', '/assets/images/history/history-grote-kerk.jpg',
    'one_feature_one', '78m Tower Height',
    'one_feature_two', 'Famous Muller Organ',
    'one_feature_three', 'Gothic Architecture',
    'one_feature_four', 'Frans Hals Tomb',
    'one_button_text', 'Explore This Location',
    'one_button_link', '#history-route',
    'two_label', 'Iconic Landmark',
    'two_title', 'Molen de Adriaan',
    'two_text', 'This iconic Dutch windmill stands proudly on the banks of the Spaarne River. Originally built in 1779, it was reconstructed in 2002 and now serves as a working museum.',
    'two_badge', 'Est. 1779',
    'two_image', '/assets/images/history/history-molen-de-adriaan.jpg',
    'two_feature_one', 'Built 1779',
    'two_feature_two', 'Panoramic Views',
    'two_feature_three', 'Working Museum',
    'two_feature_four', 'Spaarne River',
    'two_button_text', 'Explore This Location',
    'two_button_link', '#history-route'
  ),
  4,
  1
),
(
  @history_id,
  'history_route',
  JSON_OBJECT(
    'eyebrow', 'Complete Walking Route',
    'heading', '9 Remarkable Venues',
    'intro', 'Each stop tells a unique chapter of Haarlem''s rich 800-year story.',
    'venue_one_title', 'Grote Kerk',
    'venue_one_text', '15th-century Gothic cathedral',
    'venue_one_link', '#history-featured',
    'venue_two_title', 'Grote Markt',
    'venue_two_text', 'Historic market square since medieval times',
    'venue_three_title', 'De Hallen',
    'venue_three_text', 'Frans Hals Museum in 17th-century almshouses',
    'venue_four_title', 'Proveniershof',
    'venue_four_text', 'Beautiful hidden courtyard garden',
    'venue_five_title', 'Jopenkerk',
    'venue_five_text', 'Brewery in a converted 15th-century church',
    'venue_five_badge', 'Break',
    'venue_six_title', 'Waalse Kerk',
    'venue_six_text', '16th-century French Reformed church',
    'venue_seven_title', 'Molen de Adriaan',
    'venue_seven_text', 'Iconic windmill on the Spaarne River',
    'venue_eight_title', 'Amsterdamse Poort',
    'venue_eight_text', 'Last remaining medieval city gate',
    'venue_nine_title', 'Hof van Bakenes',
    'venue_nine_text', 'Oldest hofje in Haarlem, founded 1395',
    'button_text', 'Explore Route',
    'button_link', '#history-cta'
  ),
  5,
  1
),
(
  @history_id,
  'history_info',
  JSON_OBJECT(
    'item_one_value', '2.5 Hours',
    'item_one_label', 'Tour Duration',
    'item_two_value', 'Max 12',
    'item_two_label', 'Group Size',
    'item_three_value', 'Bavo Church',
    'item_three_label', 'Start Point',
    'item_four_value', 'Thu - Sun',
    'item_four_label', 'Available Days'
  ),
  6,
  1
),
(
  @history_id,
  'history_cta',
  JSON_OBJECT(
    'eyebrow', 'Your Adventure Awaits',
    'title_line_one', 'Ready to',
    'title_line_two', 'Explore?',
    'body', 'Book your walking tour today and discover why Haarlem has been captivating visitors for centuries. Experience history come alive through our expert-guided tours.',
    'background_image', '/assets/images/history/history-ready-to-explore.jpg',
    'primary_button_text', 'Book Your Adventure',
    'primary_button_link', '#',
    'secondary_button_text', 'Explore Route',
    'secondary_button_link', '#history-route'
  ),
  7,
  1
);
