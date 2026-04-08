USE haarlem_festival;

DELETE ps FROM page_section ps
INNER JOIN page p ON p.page_id = ps.page_id
WHERE p.slug = 'history-st-bavos-church';

DELETE FROM page WHERE slug = 'history-st-bavos-church';

INSERT INTO page (title, slug, status)
VALUES ('St. Bavo''s Church', 'history-st-bavos-church', 'published');

SET @history_st_bavo_id = LAST_INSERT_ID();

INSERT INTO page_section (page_id, section_type, content, sort_order, is_published)
VALUES
(
  @history_st_bavo_id,
  'history_st_bavo_hero',
  JSON_OBJECT(
    'back_text', 'Back to History',
    'back_link', '/history',
    'eyebrow', 'Featured Location',
    'heading', 'Church of St. Bavo',
    'subtitle', 'St. Bavokerk - The Heart of Haarlem',
    'hero_image', '/assets/images/history/history-st-bavo-hero.jpg'
  ),
  1,
  1
),
(
  @history_st_bavo_id,
  'history_st_bavo_facts',
  JSON_OBJECT(
    'fact_one_label', 'Address',
    'fact_one_value', 'Grote Markt 22, Haarlem',
    'fact_two_label', 'Built',
    'fact_two_value', '1370-1520',
    'fact_three_label', 'Open Daily',
    'fact_three_value', '10:00 - 17:00',
    'fact_four_label', 'Style',
    'fact_four_value', 'Gothic'
  ),
  2,
  1
),
(
  @history_st_bavo_id,
  'history_st_bavo_article',
  JSON_OBJECT(
    'heading', 'A Monument to Haarlem''s Golden Age',
    'intro_one', 'Rising majestically above Haarlem''s Grote Markt, the Grote Kerk, officially known as St. Bavokerk, stands as one of the most impressive Gothic churches in the Netherlands. This architectural masterpiece has been the spiritual and cultural heart of Haarlem for over five centuries, witnessing the city''s transformation from a medieval trading hub to a center of Dutch Golden Age prosperity.',
    'intro_two', 'The church''s construction began in 1370 and continued for nearly 150 years, with the distinctive tower completed in 1520. The building showcases the transition from Brabantine Gothic to a uniquely Dutch interpretation of the style, characterized by its soaring wooden vault spanning 25 meters high and intricate brick detailing.',
    'gallery_heading', 'Gallery',
    'gallery_one_image', '/assets/images/history/history-st-bavo-organ.jpg',
    'gallery_one_caption', 'The famous Muller organ, played by Mozart in 1766',
    'gallery_two_image', '/assets/images/history/history-st-bavo-historic.jpg',
    'gallery_two_caption', 'Historic view of the church, 19th century',
    'gallery_three_image', '/assets/images/history/history-st-bavo-exterior.jpg',
    'gallery_three_caption', 'The imposing exterior dominates Haarlem''s main square',
    'significance_heading', 'Historical Significance',
    'significance_one', 'The Grote Kerk holds immense historical importance for both Haarlem and the Netherlands as a whole. Originally a Catholic church dedicated to St. Bavo, patron saint of Haarlem, it transitioned to Protestantism during the Reformation in 1578. This shift marked a turning point in the church''s history, as much of its Catholic ornamentation was removed, though its architectural grandeur remained intact.',
    'significance_two', 'The church''s most celebrated feature is the magnificent Christian Muller organ, installed in 1738. This baroque masterpiece contains 5,068 pipes and is considered one of the finest organs in the world. A young Wolfgang Amadeus Mozart played this very instrument during his visit in 1766, at the age of just ten years old.',
    'significance_three', 'Notable figures buried within the church include Frans Hals, Haarlem''s most famous painter and master of the Dutch Golden Age portrait. The church floor contains approximately 1,500 gravestones, offering a poignant reminder of the generations who called Haarlem home.',
    'importance_heading', 'Importance to Haarlem',
    'importance_one', 'The Grote Kerk is not merely a building - it is the symbol of Haarlem itself. Its distinctive silhouette appears on the city''s coat of arms and has been immortalized in countless paintings, including works by Pieter Saenredam and Gerrit Berckheyde. The church''s tower, visible from nearly every point in the city, has served as a navigational landmark for travelers approaching Haarlem for centuries.',
    'importance_two', 'Today, the church serves both as an active Protestant parish and as a cultural venue hosting concerts, exhibitions, and events. Its exceptional acoustics make it a preferred location for classical music performances, particularly organ recitals that showcase the Muller organ''s extraordinary capabilities.',
    'importance_three', 'As part of our walking tour, the Grote Kerk represents the spiritual and artistic zenith of Haarlem''s heritage - a place where history, faith, and art converge in one of the Netherlands'' most magnificent sacred spaces.'
  ),
  3,
  1
),
(
  @history_st_bavo_id,
  'history_st_bavo_sidebar',
  JSON_OBJECT(
    'map_heading', 'Find Grote Kerk',
    'map_address', 'Grote Markt 22, 2011 RD Haarlem',
    'map_image', '/assets/images/history/history-st-bavo-map.jpg',
    'map_link_text', 'Open in OpenStreetMap ->',
    'map_link_url', 'https://www.openstreetmap.org/?mlat=52.38131&mlon=4.63695#map=18/52.38131/4.63695',
    'details_heading', 'Location Details',
    'full_address_label', 'Full Address',
    'full_address_value', 'Grote Markt 22\n2011 RD Haarlem\nNetherlands',
    'construction_label', 'Construction Period',
    'construction_value', '1370 - 1520 (150 years)',
    'style_label', 'Architectural Style',
    'style_value', 'Brabantine Gothic',
    'purpose_label', 'Original Purpose',
    'purpose_value', 'Catholic Parish Church',
    'function_label', 'Current Function',
    'function_value', 'Protestant Church & Cultural Venue',
    'opening_label', 'Opening Hours',
    'opening_value', 'Mon-Sat: 10:00 - 17:00\nSun: 12:00 - 17:00',
    'facts_heading', 'Did You Know?',
    'fact_one', 'Mozart played the Muller organ here at age 10',
    'fact_two', 'The wooden vault spans 25 meters high',
    'fact_three', 'Frans Hals is buried in the church floor',
    'fact_four', 'The organ contains 5,068 pipes',
    'fact_five', 'The church appears on Haarlem''s coat of arms',
    'tour_heading', 'Visit on Our Tour',
    'tour_text', 'Experience the Grote Kerk with our expert guides who bring its history to life.',
    'tour_button_text', 'Book Your Adventure',
    'tour_button_link', '/history/book-tour'
  ),
  4,
  1
),
(
  @history_st_bavo_id,
  'history_st_bavo_route_cta',
  JSON_OBJECT(
    'heading', 'Find It on the Route',
    'body', 'The Grote Kerk is stop #1 on our walking tour, located in the heart of Haarlem''s historic center.',
    'button_text', 'View Interactive Route Map',
    'button_link', '/history/route-map'
  ),
  5,
  1
);
