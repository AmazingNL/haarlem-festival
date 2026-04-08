USE haarlem_festival;

DELETE ps FROM page_section ps
INNER JOIN page p ON p.page_id = ps.page_id
WHERE p.slug = 'history-molen-de-adriaan';

DELETE FROM page WHERE slug = 'history-molen-de-adriaan';

INSERT INTO page (title, slug, status)
VALUES ('Molen de Adriaan', 'history-molen-de-adriaan', 'published');

SET @history_molen_id = LAST_INSERT_ID();

INSERT INTO page_section (page_id, section_type, content, sort_order, is_published)
VALUES
(
  @history_molen_id,
  'history_molen_hero',
  JSON_OBJECT(
    'back_text', 'Back to History',
    'back_link', '/history',
    'eyebrow', 'Featured Location',
    'heading', 'Molen de Adriaan',
    'subtitle', 'The Iconic Windmill of Haarlem',
    'hero_image', '/assets/images/history/history-molen-hero.jpg'
  ),
  1,
  1
),
(
  @history_molen_id,
  'history_molen_facts',
  JSON_OBJECT(
    'fact_one_label', 'Address',
    'fact_one_value', 'Papentorenvest 1A',
    'fact_two_label', 'Built',
    'fact_two_value', '1779',
    'fact_three_label', 'Open',
    'fact_three_value', 'Mon-Sat 13:00-17:00',
    'fact_four_label', 'Type',
    'fact_four_value', 'Tower Mill'
  ),
  2,
  1
),
(
  @history_molen_id,
  'history_molen_article',
  JSON_OBJECT(
    'heading', 'A Symbol of Dutch Heritage',
    'intro_one', 'Standing proudly on the banks of the Spaarne river, Molen de Adriaan is one of Haarlem''s most photographed landmarks. This magnificent tower mill has become an iconic symbol of the city, offering visitors a unique glimpse into the Netherlands'' rich milling heritage and providing breathtaking panoramic views of Haarlem from its gallery.',
    'intro_two', 'Originally built in 1779, the mill was named after Adriaan de Boois, a wealthy merchant who commissioned its construction. Throughout its history, the windmill has served various purposes - grinding tobacco, chalk, and tanbark used in leather production. Today, it operates as a museum and continues to grind grain on windy days.',
    'gallery_heading', 'Gallery',
    'gallery_one_image', '/assets/images/history/history-molen-machinery.jpg',
    'gallery_one_caption', 'Original wooden gears and milling machinery',
    'gallery_two_image', '/assets/images/history/history-molen-historic.jpg',
    'gallery_two_caption', 'The windmill in its original form, early 20th century',
    'gallery_three_image', '/assets/images/history/history-molen-spaarne.jpg',
    'gallery_three_caption', 'The restored windmill overlooking the Spaarne river',
    'significance_heading', 'Historical Significance',
    'significance_one', 'Molen de Adriaan has witnessed Haarlem''s evolution over nearly 250 years. The mill played a vital role in the city''s industrial economy, particularly during the 19th century when Haarlem was a center for various trades and manufacturing.',
    'significance_two', 'Tragedy struck on April 23, 1932, when a devastating fire destroyed the original windmill. For decades, only the stone base remained - a silent reminder of what had been lost. However, the people of Haarlem never forgot their beloved landmark.',
    'significance_three', 'In 1999, a dedicated foundation began raising funds to rebuild the mill. Through the tireless efforts of volunteers and generous donations from Haarlem residents and businesses, Molen de Adriaan was meticulously reconstructed using traditional methods. The restored mill was officially reopened in 2002, exactly 70 years after the fire.',
    'importance_heading', 'Importance to Haarlem',
    'importance_one', 'Molen de Adriaan represents more than just a historical structure - it embodies the Dutch spirit of perseverance and community. The successful reconstruction demonstrates Haarlem''s commitment to preserving its cultural heritage for future generations.',
    'importance_two', 'Today, the windmill serves as both a museum and an educational center. Visitors can explore five floors of exhibits about the history of windmills in the Netherlands, watch the mill''s mechanisms in action, and enjoy spectacular 360-degree views of Haarlem from the outdoor gallery.',
    'importance_three', 'The mill has become one of Haarlem''s most popular attractions, drawing visitors from around the world who come to experience this quintessentially Dutch icon. Its silhouette against the Haarlem skyline remains one of the city''s most recognizable and beloved images.'
  ),
  3,
  1
),
(
  @history_molen_id,
  'history_molen_sidebar',
  JSON_OBJECT(
    'map_heading', 'Find Molen de Adriaan',
    'map_address', 'Papentorenvest 1A, 2011 AV Haarlem',
    'map_image', '/assets/images/history/history-molen-map.jpg',
    'map_link_text', 'Open in OpenStreetMap ->',
    'map_link_url', 'https://www.openstreetmap.org/search?query=Papentorenvest%201A%20Haarlem',
    'details_heading', 'Location Details',
    'full_address_label', 'Full Address',
    'full_address_value', 'Papentorenvest 1A\n2011 AV Haarlem\nNetherlands',
    'construction_label', 'Original Construction',
    'construction_value', '1779',
    'reconstruction_label', 'Reconstruction',
    'reconstruction_value', '1999-2002',
    'type_label', 'Mill Type',
    'type_value', 'Tower Mill (Stellingmolen)',
    'height_label', 'Height',
    'height_value', 'Approximately 20 meters',
    'opening_label', 'Opening Hours',
    'opening_value', 'Mon-Sat: 13:00 - 17:00\nSun: 12:00 - 17:00\n(March - October)',
    'facts_heading', 'Did You Know?',
    'fact_one', 'The mill was destroyed by fire in 1932',
    'fact_two', 'It took 3 years and EUR 2 million to rebuild',
    'fact_three', 'The sails span over 26 meters across',
    'fact_four', 'It still grinds grain on windy days',
    'fact_five', '5 floors of exhibits await visitors',
    'tour_heading', 'Visit on Our Tour',
    'tour_text', 'Experience Molen de Adriaan with our expert guides who share its remarkable story.',
    'tour_button_text', 'Book Your Adventure',
    'tour_button_link', '/history/book-tour'
  ),
  4,
  1
),
(
  @history_molen_id,
  'history_molen_route_cta',
  JSON_OBJECT(
    'heading', 'Find It on the Route',
    'body', 'Molen de Adriaan is stop #7 on our walking tour, located along the scenic Spaarne river.',
    'button_text', 'Explore Route',
    'button_link', '/history/route-map'
  ),
  5,
  1
);
