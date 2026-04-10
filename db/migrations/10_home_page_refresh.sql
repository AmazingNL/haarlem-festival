USE haarlem_festival;

INSERT INTO page (title, slug, content, status)
SELECT 'Home', 'home', NULL, 'published'
WHERE NOT EXISTS (
    SELECT 1
    FROM page
    WHERE slug = 'home'
);

SET @home_id = (
    SELECT page_id
    FROM page
    WHERE slug = 'home'
    LIMIT 1
);

UPDATE page_section
SET section_type = 'feature'
WHERE page_id = @home_id
  AND section_type = 'text_block';

UPDATE page_section
SET section_type = 'gallery'
WHERE page_id = @home_id
  AND section_type = 'two_image_row';

UPDATE page_section
SET title = 'Discover Haarlem',
    content = JSON_OBJECT(
        'heading', 'Discover Haarlem',
        'hero_image', '/assets/images/home/home-hero.jpg',
        'hero_image_alt', 'View over Haarlem and the Grote Kerk'
    ),
    sort_order = 1,
    is_published = 1
WHERE page_id = @home_id
  AND section_type = 'hero';

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT
    @home_id,
    'hero',
    'Discover Haarlem',
    JSON_OBJECT(
        'heading', 'Discover Haarlem',
        'hero_image', '/assets/images/home/home-hero.jpg',
        'hero_image_alt', 'View over Haarlem and the Grote Kerk'
    ),
    1,
    1
WHERE NOT EXISTS (
    SELECT 1
    FROM page_section
    WHERE page_id = @home_id
      AND section_type = 'hero'
);

UPDATE page_section
SET title = 'The Heart of Haarlem',
    content = JSON_OBJECT(
        'title', 'The Heart of Haarlem',
        'article', CONCAT(
            'Haarlem is one of the Netherlands'' most charming and historic cities, a place where old-world beauty meets modern culture. Known for its cobblestone streets, iconic windmills, and Golden Age architecture, the city offers a warm and welcoming atmosphere for every visitor.',
            '\n\n',
            'Wander through lively squares, explore boutique shops, discover hidden courtyards, or relax by the Spaarne River. Haarlem is also a city of creativity and taste, home to award-winning restaurants, vibrant markets, and world-class museums such as the Frans Hals Museum.',
            '\n\n',
            'Whether you''re here for food, art, history, or festivals, Haarlem invites you to slow down and enjoy its unique charm. Just minutes from Amsterdam and the Dutch coastline, the city blends convenience with authenticity and stays lively all year round with parades, markets, and music.'
        ),
        'button_text', 'Go To Events',
        'button_link', '#home-activities'
    ),
    sort_order = 2,
    is_published = 1
WHERE page_id = @home_id
  AND section_type = 'feature';

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT
    @home_id,
    'feature',
    'The Heart of Haarlem',
    JSON_OBJECT(
        'title', 'The Heart of Haarlem',
        'article', CONCAT(
            'Haarlem is one of the Netherlands'' most charming and historic cities, a place where old-world beauty meets modern culture. Known for its cobblestone streets, iconic windmills, and Golden Age architecture, the city offers a warm and welcoming atmosphere for every visitor.',
            '\n\n',
            'Wander through lively squares, explore boutique shops, discover hidden courtyards, or relax by the Spaarne River. Haarlem is also a city of creativity and taste, home to award-winning restaurants, vibrant markets, and world-class museums such as the Frans Hals Museum.',
            '\n\n',
            'Whether you''re here for food, art, history, or festivals, Haarlem invites you to slow down and enjoy its unique charm. Just minutes from Amsterdam and the Dutch coastline, the city blends convenience with authenticity and stays lively all year round with parades, markets, and music.'
        ),
        'button_text', 'Go To Events',
        'button_link', '#home-activities'
    ),
    2,
    1
WHERE NOT EXISTS (
    SELECT 1
    FROM page_section
    WHERE page_id = @home_id
      AND section_type = 'feature'
);

UPDATE page_section
SET title = 'Home Gallery',
    content = JSON_OBJECT(
        'item_one_label', 'Molen de Adriaan',
        'item_one_image', '/assets/images/home/home-gallery-molen-de-adriaan.jpg',
        'item_one_alt', 'Molen de Adriaan along the water',
        'item_two_label', 'Grote Kerk',
        'item_two_image', '/assets/images/home/home-gallery-grote-kerk.jpg',
        'item_two_alt', 'Grote Kerk at sunset',
        'item_three_label', 'Old Haarlem',
        'item_three_image', '/assets/images/home/home-gallery-old-town.jpg',
        'item_three_alt', 'Historic Haarlem street and canal',
        'item_four_label', 'The Weigh House',
        'item_four_image', '/assets/images/home/home-gallery-the-weigh-house.jpg',
        'item_four_alt', 'The Weigh House in Haarlem'
    ),
    sort_order = 3,
    is_published = 1
WHERE page_id = @home_id
  AND section_type = 'gallery';

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT
    @home_id,
    'gallery',
    'Home Gallery',
    JSON_OBJECT(
        'item_one_label', 'Molen de Adriaan',
        'item_one_image', '/assets/images/home/home-gallery-molen-de-adriaan.jpg',
        'item_one_alt', 'Molen de Adriaan along the water',
        'item_two_label', 'Grote Kerk',
        'item_two_image', '/assets/images/home/home-gallery-grote-kerk.jpg',
        'item_two_alt', 'Grote Kerk at sunset',
        'item_three_label', 'Old Haarlem',
        'item_three_image', '/assets/images/home/home-gallery-old-town.jpg',
        'item_three_alt', 'Historic Haarlem street and canal',
        'item_four_label', 'The Weigh House',
        'item_four_image', '/assets/images/home/home-gallery-the-weigh-house.jpg',
        'item_four_alt', 'The Weigh House in Haarlem'
    ),
    3,
    1
WHERE NOT EXISTS (
    SELECT 1
    FROM page_section
    WHERE page_id = @home_id
      AND section_type = 'gallery'
);

UPDATE page_section
SET title = 'Grote Markt, Haarlem',
    content = JSON_OBJECT(
        'heading', 'Grote Markt, Haarlem',
        'body', CONCAT(
            'The Haarlemse Markt is one of the most iconic and long-standing markets in the Netherlands, taking place in the historic Grote Markt, right in the heart of Haarlem''s city centre. With roots dating back centuries, the market has long been a central part of daily life in the city and continues to attract both locals and visitors alike.',
            '\n\n',
            'Held every Saturday, with a smaller version on Mondays, the market offers a wide and colourful mix of stalls. Visitors can browse fresh seasonal fruits and vegetables, cheeses, bread, flowers, clothing, fabrics, and international street food.'
        ),
        'image', '/assets/images/home/home-grote-markt.jpg',
        'image_alt', 'Crowded Grote Markt in Haarlem'
    ),
    sort_order = 4,
    is_published = 1
WHERE page_id = @home_id
  AND section_type = 'image_left';

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT
    @home_id,
    'image_left',
    'Grote Markt, Haarlem',
    JSON_OBJECT(
        'heading', 'Grote Markt, Haarlem',
        'body', CONCAT(
            'The Haarlemse Markt is one of the most iconic and long-standing markets in the Netherlands, taking place in the historic Grote Markt, right in the heart of Haarlem''s city centre. With roots dating back centuries, the market has long been a central part of daily life in the city and continues to attract both locals and visitors alike.',
            '\n\n',
            'Held every Saturday, with a smaller version on Mondays, the market offers a wide and colourful mix of stalls. Visitors can browse fresh seasonal fruits and vegetables, cheeses, bread, flowers, clothing, fabrics, and international street food.'
        ),
        'image', '/assets/images/home/home-grote-markt.jpg',
        'image_alt', 'Crowded Grote Markt in Haarlem'
    ),
    4,
    1
WHERE NOT EXISTS (
    SELECT 1
    FROM page_section
    WHERE page_id = @home_id
      AND section_type = 'image_left'
);

UPDATE page_section
SET title = 'Historic Canal Houses',
    content = JSON_OBJECT(
        'heading', 'Historic Canal Houses',
        'body', CONCAT(
            'Haarlem''s historic canal houses are a defining feature of the city''s character and architectural heritage. Lining the city''s canals, these narrow yet elegant buildings date back to the 16th and 17th centuries, when Haarlem prospered during the Dutch Golden Age.',
            '\n\n',
            'Originally built for merchants, craftsmen, and traders, the canal houses reflect the city''s economic growth. Many still display stepped or bell-shaped gables, wooden beams, and ornate brickwork while remaining carefully preserved for modern use.'
        ),
        'image_one', '/assets/images/home/home-canal-house-one.jpg',
        'image_one_alt', 'Historic canal houses beside the Spaarne River',
        'image_two', '/assets/images/home/home-canal-house-two.jpg',
        'image_two_alt', 'Bridge and canal houses in Haarlem'
    ),
    sort_order = 5,
    is_published = 1
WHERE page_id = @home_id
  AND section_type = 'image_right';

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT
    @home_id,
    'image_right',
    'Historic Canal Houses',
    JSON_OBJECT(
        'heading', 'Historic Canal Houses',
        'body', CONCAT(
            'Haarlem''s historic canal houses are a defining feature of the city''s character and architectural heritage. Lining the city''s canals, these narrow yet elegant buildings date back to the 16th and 17th centuries, when Haarlem prospered during the Dutch Golden Age.',
            '\n\n',
            'Originally built for merchants, craftsmen, and traders, the canal houses reflect the city''s economic growth. Many still display stepped or bell-shaped gables, wooden beams, and ornate brickwork while remaining carefully preserved for modern use.'
        ),
        'image_one', '/assets/images/home/home-canal-house-one.jpg',
        'image_one_alt', 'Historic canal houses beside the Spaarne River',
        'image_two', '/assets/images/home/home-canal-house-two.jpg',
        'image_two_alt', 'Bridge and canal houses in Haarlem'
    ),
    5,
    1
WHERE NOT EXISTS (
    SELECT 1
    FROM page_section
    WHERE page_id = @home_id
      AND section_type = 'image_right'
);

UPDATE page_section
SET title = 'What you can do in Haarlem',
    content = JSON_OBJECT(
        'heading', 'What you can do in Haarlem',
        'card_one_title', 'Stories',
        'card_one_text', 'Haarlem is a city full of character, shaped by centuries of history and the people who call it home. Explore personal stories, local legends, hidden alleys, and traditions that give the city its unique charm.',
        'card_one_image', '/assets/images/home/home-stories.jpg',
        'card_one_alt', 'Audience in a theatre in Haarlem',
        'card_one_button_text', 'Read More',
        'card_one_button_link', '/stories',
        'card_two_title', 'History',
        'card_two_text', 'Haarlem is a city rich in history and culture, shaped by centuries of art, trade, and craftsmanship. From medieval beginnings to the Dutch Golden Age, its churches, market squares, and streets tell the story.',
        'card_two_image', '/assets/images/home/home-history.jpg',
        'card_two_alt', 'The Grote Kerk in Haarlem',
        'card_two_button_text', 'Read More',
        'card_two_button_link', '/history',
        'card_three_title', 'Restaurants',
        'card_three_text', 'Enjoy a taste of Haarlem in its vibrant restaurant scene. From elegant Michelin-starred establishments to welcoming neighbourhood cafés, the city offers something memorable for every visitor.',
        'card_three_image', '/assets/images/home/home-restaurants.jpg',
        'card_three_alt', 'Restaurant street scene in Haarlem',
        'card_three_button_text', 'Read More',
        'card_three_button_link', '/yummy',
        'card_four_title', 'Dance!',
        'card_four_text', 'Feel the bass, the lights, and the crowd. Discover the artists, find the venues, and build your own line-up for the most energetic nights of the festival.',
        'card_four_image', '/assets/images/home/home-dance.jpg',
        'card_four_alt', 'Crowded dance floor with red lights',
        'card_four_button_text', 'Read More',
        'card_four_button_link', '/home#home-dance',
        'card_five_title', 'Jazz',
        'card_five_text', 'Every summer, Haarlem comes alive with the sound of jazz. Expect great live music, sunny terraces, and an unforgettable festival atmosphere in the heart of the city.',
        'card_five_image', '/assets/images/home/home-jazz.jpg',
        'card_five_alt', 'Jazz performance on stage in Haarlem',
        'card_five_button_text', 'Read More',
        'card_five_button_link', '/home#home-jazz'
    ),
    sort_order = 6,
    is_published = 1
WHERE page_id = @home_id
  AND section_type = 'cards_grid';

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT
    @home_id,
    'cards_grid',
    'What you can do in Haarlem',
    JSON_OBJECT(
        'heading', 'What you can do in Haarlem',
        'card_one_title', 'Stories',
        'card_one_text', 'Haarlem is a city full of character, shaped by centuries of history and the people who call it home. Explore personal stories, local legends, hidden alleys, and traditions that give the city its unique charm.',
        'card_one_image', '/assets/images/home/home-stories.jpg',
        'card_one_alt', 'Audience in a theatre in Haarlem',
        'card_one_button_text', 'Read More',
        'card_one_button_link', '/stories',
        'card_two_title', 'History',
        'card_two_text', 'Haarlem is a city rich in history and culture, shaped by centuries of art, trade, and craftsmanship. From medieval beginnings to the Dutch Golden Age, its churches, market squares, and streets tell the story.',
        'card_two_image', '/assets/images/home/home-history.jpg',
        'card_two_alt', 'The Grote Kerk in Haarlem',
        'card_two_button_text', 'Read More',
        'card_two_button_link', '/history',
        'card_three_title', 'Restaurants',
        'card_three_text', 'Enjoy a taste of Haarlem in its vibrant restaurant scene. From elegant Michelin-starred establishments to welcoming neighbourhood cafés, the city offers something memorable for every visitor.',
        'card_three_image', '/assets/images/home/home-restaurants.jpg',
        'card_three_alt', 'Restaurant street scene in Haarlem',
        'card_three_button_text', 'Read More',
        'card_three_button_link', '/yummy',
        'card_four_title', 'Dance!',
        'card_four_text', 'Feel the bass, the lights, and the crowd. Discover the artists, find the venues, and build your own line-up for the most energetic nights of the festival.',
        'card_four_image', '/assets/images/home/home-dance.jpg',
        'card_four_alt', 'Crowded dance floor with red lights',
        'card_four_button_text', 'Read More',
        'card_four_button_link', '/home#home-dance',
        'card_five_title', 'Jazz',
        'card_five_text', 'Every summer, Haarlem comes alive with the sound of jazz. Expect great live music, sunny terraces, and an unforgettable festival atmosphere in the heart of the city.',
        'card_five_image', '/assets/images/home/home-jazz.jpg',
        'card_five_alt', 'Jazz performance on stage in Haarlem',
        'card_five_button_text', 'Read More',
        'card_five_button_link', '/home#home-jazz'
    ),
    6,
    1
WHERE NOT EXISTS (
    SELECT 1
    FROM page_section
    WHERE page_id = @home_id
      AND section_type = 'cards_grid'
);

UPDATE page_section
SET title = 'Transportation',
    content = JSON_OBJECT(
        'heading', 'Transportation',
        'intro', 'Getting around Haarlem is simple, fast, and convenient. The city''s compact layout makes it easy to explore by foot or bike, while a reliable bus network connects every major neighbourhood. Haarlem Central Station is the main transport hub for Amsterdam, Schiphol, Zandvoort, Leiden, and other Dutch cities.',
        'list_intro', 'Travellers can move through the city using several transport options:',
        'item_one', 'Trains: Haarlem Central Station provides fast connections, only 15 minutes to Amsterdam and 10 minutes to Zandvoort Beach.',
        'item_two', 'Buses: A wide network of local and regional buses makes it easy to reach attractions, events, and nearby towns.',
        'item_three', 'Bicycles: Haarlem is a true cycling city with safe bike paths and plenty of rental options.',
        'item_four', 'Walking: Most of the historic centre is walkable, with shops, cafes, museums, and markets all within short distance.',
        'image', '/assets/images/home/home-transport.jpg',
        'image_alt', 'Buses outside Haarlem Central Station',
        'button_text', 'View On Map',
        'button_link', 'https://maps.google.com/?q=Haarlem+Centraal'
    ),
    sort_order = 7,
    is_published = 1
WHERE page_id = @home_id
  AND section_type = 'transport';

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT
    @home_id,
    'transport',
    'Transportation',
    JSON_OBJECT(
        'heading', 'Transportation',
        'intro', 'Getting around Haarlem is simple, fast, and convenient. The city''s compact layout makes it easy to explore by foot or bike, while a reliable bus network connects every major neighbourhood. Haarlem Central Station is the main transport hub for Amsterdam, Schiphol, Zandvoort, Leiden, and other Dutch cities.',
        'list_intro', 'Travellers can move through the city using several transport options:',
        'item_one', 'Trains: Haarlem Central Station provides fast connections, only 15 minutes to Amsterdam and 10 minutes to Zandvoort Beach.',
        'item_two', 'Buses: A wide network of local and regional buses makes it easy to reach attractions, events, and nearby towns.',
        'item_three', 'Bicycles: Haarlem is a true cycling city with safe bike paths and plenty of rental options.',
        'item_four', 'Walking: Most of the historic centre is walkable, with shops, cafes, museums, and markets all within short distance.',
        'image', '/assets/images/home/home-transport.jpg',
        'image_alt', 'Buses outside Haarlem Central Station',
        'button_text', 'View On Map',
        'button_link', 'https://maps.google.com/?q=Haarlem+Centraal'
    ),
    7,
    1
WHERE NOT EXISTS (
    SELECT 1
    FROM page_section
    WHERE page_id = @home_id
      AND section_type = 'transport'
);
