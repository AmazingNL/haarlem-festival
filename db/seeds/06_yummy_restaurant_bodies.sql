-- Real CMS-authored Yummy page content recovered from backup.sql (git 8e56207^).
-- Migrations/seeds only create a skeleton (landing cards, restaurant welcome
-- banner + reservation); this restores the welcome banner, Haarlem-taste intro,
-- 'what makes Haarlem unique' block, info cards, About, and Our Chef / galleries
-- so /yummy and the restaurant detail pages render fully.
-- Images referenced below live in public/assets/images/admin/.
-- Idempotent: each insert is guarded on (page_id, sort_order).
USE haarlem_festival;

SET @yummy  = (SELECT page_id FROM page WHERE slug = 'yummy' LIMIT 1);
SET @rat    = (SELECT page_id FROM page WHERE slug = 'ratatouille' LIMIT 1);
SET @bistro = (SELECT page_id FROM page WHERE slug = 'bistro-toujours' LIMIT 1);

-- ======================================================================
--  Yummy landing page  (slug: yummy)
-- ======================================================================
INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT @yummy, 'welcome_banner', NULL, '{\"title\":\"Haarlem’s Culinary Scene\",\"introduction\":\"<p><span>Haarlem&rsquo;s food scene blends old world charm with bold modern flavours. From canal side bistros to Michelin-starred dining rooms, the city invites every visitor to taste its culture one plate at a time.<\\/span><\\/p>\",\"section_image\":\"\",\"section_image_alt_text\":\"\",\"section_image_caption\":\"\",\"button_text\":\"Explore Restaurants\",\"button_link\":\"\\/restaurants_cards\",\"custom_class\":\"\"}', 1, 1
WHERE @yummy IS NOT NULL AND NOT EXISTS (SELECT 1 FROM page_section WHERE page_id = @yummy AND sort_order = 1);

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT @yummy, 'text_block', NULL, '{\"title\":\"Haarlem Food Culture\",\"sub_title\":\"A City Shaped by Flavour\",\"article\":\"<p>Haarlem&rsquo;s food culture is rooted in centuries of craftsmanship and trade. During the Dutch Golden Age, the city flourished as a center for brewing, fishing, and artisanal production, bringing spices, grains, and fresh ingredients from across Europe. These new influences blended naturally with local traditions, shaping a culinary identity that still defines Haarlem today. From historic cheese markets to long-standing breweries, the city&rsquo;s flavours carry gentle echoes of its past. Classic Dutch dishes like stamppot, warm stews, and baked treats remain part of Haarlem&rsquo;s everyday comfort, while ingredients once introduced through global trade continue to add depth and character. Haarlem&rsquo;s kitchens have always balanced tradition with curiosity, and that spirit of discovery still lives in the restaurants and caf&eacute;s throughout the city today.<\\/p>\",\"custom_class\":\"\"}', 2, 1
WHERE @yummy IS NOT NULL AND NOT EXISTS (SELECT 1 FROM page_section WHERE page_id = @yummy AND sort_order = 2);

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT @yummy, 'gallery', NULL, '{\"title\":\"Haarlem’s food culture\",\"section_image\":[\"..\\/..\\/..\\/assets\\/images\\/admin\\/58549e96e9ded7ad19668a7ebe898c87.png\",\"..\\/..\\/..\\/assets\\/images\\/admin\\/bd8a1a7e565ecb21de7471dbb14b02c5.png\",\"..\\/..\\/..\\/assets\\/images\\/admin\\/ecd8bb27ed3e6b85c4cd9a9b1b0c768a.png\",\"..\\/..\\/..\\/assets\\/images\\/admin\\/be2a72fb39e2dba60a70d3c400bc2356.png\",\"..\\/..\\/..\\/assets\\/images\\/admin\\/8f8695b7a35f0b137c2005a1bfac2cad.png\",\"..\\/..\\/..\\/assets\\/images\\/admin\\/97002ff941343028b9e416c46ff52f2c.png\"],\"custom_class\":\"\"}', 3, 1
WHERE @yummy IS NOT NULL AND NOT EXISTS (SELECT 1 FROM page_section WHERE page_id = @yummy AND sort_order = 3);

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT @yummy, 'haarlem_unique', NULL, '{\"title\":\"What Makes Haarlem Unique Today\",\"content\":\"<p><span>Modern Haarlem is a place where old-world charm meets creative cooking. Walk through the city and you\'ll find riverside caf&eacute;s buzzing with conversation, intimate bistros in narrow streets, and elegant restaurants tucked inside historic buildings. Local chefs mix classic Dutch flavours with French, Mediterranean, and Asian inspirations, creating dishes that feel both familiar and adventurous. With Michelin-starred restaurants, refined vegan spots, and cozy neighbourhood eateries, Haarlem celebrates food as both a craft and an experience<\\/span><\\/p>\",\"section_image\":[\"..\\/..\\/..\\/assets\\/images\\/admin\\/4e534de8380c28372d0a2fe97ee621b0.png\",\"..\\/..\\/..\\/assets\\/images\\/admin\\/f407a03b9f9fccf755dd87ee89afff91.png\"],\"custom_class\":\"\"}', 4, 1
WHERE @yummy IS NOT NULL AND NOT EXISTS (SELECT 1 FROM page_section WHERE page_id = @yummy AND sort_order = 4);

-- ======================================================================
--  Ratatouille Food & Wine  (slug: ratatouille)
-- ======================================================================
INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT @rat, 'welcome_banner_card', NULL, '{\"title\":\"Opening Hours\",\"info\":\"<p><span>Tuesday - Sunday<\\/span><\\/p>\\r\\n<p><span>18:00 PM - 22:30 PM<\\/span><\\/p>\",\"custom_class\":\"\"}', 2, 1
WHERE @rat IS NOT NULL AND NOT EXISTS (SELECT 1 FROM page_section WHERE page_id = @rat AND sort_order = 2);

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT @rat, 'welcome_banner_card', NULL, '{\"title\":\"Cuisine\",\"info\":\"<p><span>French, Fish <br>Seafood, European<\\/span><\\/p>\",\"custom_class\":\"\"}', 3, 1
WHERE @rat IS NOT NULL AND NOT EXISTS (SELECT 1 FROM page_section WHERE page_id = @rat AND sort_order = 3);

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT @rat, 'welcome_banner_card', NULL, '{\"title\":\"Address\",\"info\":\"<p><span>Spaarne 96, 2011 CL Haarlem, Nederland<\\/span><\\/p>\",\"custom_class\":\"\"}', 4, 1
WHERE @rat IS NOT NULL AND NOT EXISTS (SELECT 1 FROM page_section WHERE page_id = @rat AND sort_order = 4);

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT @rat, 'welcome_banner_card', NULL, '{\"title\":\"Price\",\"info\":\"<p><span>Adult - 45 euro<\\/span><\\/p>\\r\\n<p><span>Kids - 22.50 euro<\\/span><\\/p>\",\"custom_class\":\"\"}', 5, 1
WHERE @rat IS NOT NULL AND NOT EXISTS (SELECT 1 FROM page_section WHERE page_id = @rat AND sort_order = 5);

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT @rat, 'text_block', NULL, '{\"title\":\"About Ratatouille\",\"sub_title\":\"about\",\"article\":\"<p>Ratatouille Food &amp; Wine is an award-winning Michelin-starred restaurant located in the historic heart of Haarlem. Known for its refined interpretation of modern French cuisine, the restaurant blends creativity, precision, and artistic presentation to deliver a dining experience that is both innovative and deeply rooted in culinary tradition. Each dish is thoughtfully composed, highlighting seasonal ingredients and bold yet balanced flavors designed to leave a lasting impression.<\\/p>\",\"custom_class\":\"\"}', 6, 1
WHERE @rat IS NOT NULL AND NOT EXISTS (SELECT 1 FROM page_section WHERE page_id = @rat AND sort_order = 6);

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT @rat, 'welcome_banner_card', NULL, '{\"title\":\"Signature Highlights\",\"info\":\"<p>Seasonal French-inspired tasting menus<\\/p>\",\"custom_class\":\"\"}', 7, 1
WHERE @rat IS NOT NULL AND NOT EXISTS (SELECT 1 FROM page_section WHERE page_id = @rat AND sort_order = 7);

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT @rat, 'welcome_banner_card', NULL, '{\"title\":\"Signature Highlights\",\"info\":\"<p>Locally sourced ingredients from Dutch farms<\\/p>\",\"custom_class\":\"\"}', 8, 1
WHERE @rat IS NOT NULL AND NOT EXISTS (SELECT 1 FROM page_section WHERE page_id = @rat AND sort_order = 8);

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT @rat, 'welcome_banner_card', NULL, '{\"title\":\"Signature Highlights\",\"info\":\"<p>Wine pairings curated by expert sommeliers<\\/p>\",\"custom_class\":\"\"}', 9, 1
WHERE @rat IS NOT NULL AND NOT EXISTS (SELECT 1 FROM page_section WHERE page_id = @rat AND sort_order = 9);

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT @rat, 'text_block', NULL, '{\"title\":\"Our Chef\",\"sub_title\":\"our chef\",\"article\":\"<p>Ratatouille Food &amp; Wine is led by Michelin-starred chef Jozua Jaring, whose vision shapes every part of our kitchen. Known for his creativity and technical skill, Chef Jaring blends classic French foundations with modern ideas, creating dishes that are both refined and surprising. His approach focuses on balance fresh seasonal ingredients, delicate flavors, and plates that tell a story.<\\/p>\",\"custom_class\":\"chef_section\"}', 10, 1
WHERE @rat IS NOT NULL AND NOT EXISTS (SELECT 1 FROM page_section WHERE page_id = @rat AND sort_order = 10);

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT @rat, 'gallery', NULL, '{\"title\": \"Our Chef\", \"section_image\": [{\"src\": \"/assets/images/admin/2cadb7f15c463c348761e1110b75dfb5.png\", \"alt\": \"Jozua Jaring\", \"caption\": \"Jozua Jaring\"}, {\"src\": \"/assets/images/admin/a13e35b88e9777b6066c20c6ff603801.png\", \"alt\": \"Chef preparing dough\", \"caption\": \"Chef in kitchen\"}], \"custom_class\": \"chef_section\"}', 11, 1
WHERE @rat IS NOT NULL AND NOT EXISTS (SELECT 1 FROM page_section WHERE page_id = @rat AND sort_order = 11);

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT @rat, 'gallery', NULL, '{\"title\": \"Ratatouille Gallery\", \"section_image\": [{\"src\": \"/assets/images/admin/50b7fcf9c294ace5f04de95cf796239f.png\", \"alt\": \"Ratatouille restaurant exterior\", \"caption\": \"Ratatouille restaurant exterior\"}, {\"src\": \"/assets/images/admin/46ce9432d69a52153823de19508c2ec9.png\", \"alt\": \"Ratatouille dining room\", \"caption\": \"Ratatouille dining room\"}, {\"src\": \"/assets/images/admin/9189bbfd792f2e4a3181bd5ed87ff890.png\", \"alt\": \"Ratatouille terrace entrance\", \"caption\": \"Ratatouille terrace entrance\"}], \"custom_class\": \"ratatouille_gallery\"}', 12, 1
WHERE @rat IS NOT NULL AND NOT EXISTS (SELECT 1 FROM page_section WHERE page_id = @rat AND sort_order = 12);

-- ======================================================================
--  Bistro Toujours  (slug: bistro-toujours)
-- ======================================================================
INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT @bistro, 'welcome_banner_card', 'Opening Hours', '{\"title\": \"Opening Hours\", \"info\": \"<p>Tuesday - Sunday</p><p>17:30 PM - 22:00 PM</p>\", \"custom_class\": \"\"}', 2, 1
WHERE @bistro IS NOT NULL AND NOT EXISTS (SELECT 1 FROM page_section WHERE page_id = @bistro AND sort_order = 2);

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT @bistro, 'welcome_banner_card', 'Cuisine', '{\"title\": \"Cuisine\", \"info\": \"<p>Dutch, fish and<br>seafood, European</p>\", \"custom_class\": \"\"}', 3, 1
WHERE @bistro IS NOT NULL AND NOT EXISTS (SELECT 1 FROM page_section WHERE page_id = @bistro AND sort_order = 3);

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT @bistro, 'welcome_banner_card', 'Address', '{\"title\": \"Address\", \"info\": \"<p>Oude Groenmarkt 10-12, 2011 HL Haarlem, Nederland</p>\", \"custom_class\": \"\"}', 4, 1
WHERE @bistro IS NOT NULL AND NOT EXISTS (SELECT 1 FROM page_section WHERE page_id = @bistro AND sort_order = 4);

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT @bistro, 'welcome_banner_card', 'Price', '{\"title\":\"Price\",\"info\":\"<p>Adult - 35 euro<\\/p>\\r\\n<p>Kids - 17.50 euro<\\/p>\",\"custom_class\":\"\"}', 5, 1
WHERE @bistro IS NOT NULL AND NOT EXISTS (SELECT 1 FROM page_section WHERE page_id = @bistro AND sort_order = 5);

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT @bistro, 'text_block', 'About Bistro Toujours', '{\"title\": \"About Bistro Toujours\", \"sub_title\": \"about\", \"article\": \"<p>Bistro Toujours is a contemporary urban French bistro located in the heart of Haarlem, offering a dining experience that balances refined cuisine with a relaxed and approachable atmosphere. The restaurant is known for bringing classic French bistro traditions into a modern context, creating dishes that feel familiar while still offering depth, creativity, and quality.</p>\", \"custom_class\": \"\"}', 6, 1
WHERE @bistro IS NOT NULL AND NOT EXISTS (SELECT 1 FROM page_section WHERE page_id = @bistro AND sort_order = 6);

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT @bistro, 'welcome_banner_card', 'Signature Highlights', '{\"title\": \"Signature Highlights\", \"info\": \"<p>Stylish and accessible bistro-style dishes</p>\", \"custom_class\": \"\"}', 7, 1
WHERE @bistro IS NOT NULL AND NOT EXISTS (SELECT 1 FROM page_section WHERE page_id = @bistro AND sort_order = 7);

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT @bistro, 'welcome_banner_card', 'Signature Highlights', '{\"title\": \"Signature Highlights\", \"info\": \"<p>Seasonal French bistro specials</p>\", \"custom_class\": \"\"}', 8, 1
WHERE @bistro IS NOT NULL AND NOT EXISTS (SELECT 1 FROM page_section WHERE page_id = @bistro AND sort_order = 8);

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT @bistro, 'welcome_banner_card', 'Signature Highlights', '{\"title\": \"Signature Highlights\", \"info\": \"<p>Seafood dishes including fish and shellfish</p>\", \"custom_class\": \"\"}', 9, 1
WHERE @bistro IS NOT NULL AND NOT EXISTS (SELECT 1 FROM page_section WHERE page_id = @bistro AND sort_order = 9);

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT @bistro, 'text_block', 'Our Chef', '{\"title\": \"Our Chef\", \"sub_title\": \"our chef\", \"article\": \"<p>The kitchen at Bistro Toujours is led by Chef Jarno Smak, who is responsible for overseeing the restaurant\'s culinary vision and daily kitchen operations. As head chef, he plays a central role in menu development, quality control, and the overall consistency of the dishes served. His leadership ensures that the kitchen maintains high standards while staying true to the bistro concept of approachable, flavour-focused cuisine.</p>\", \"custom_class\": \"chef_section\"}', 10, 1
WHERE @bistro IS NOT NULL AND NOT EXISTS (SELECT 1 FROM page_section WHERE page_id = @bistro AND sort_order = 10);

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT @bistro, 'gallery', 'Our Chef', '{\"title\":\"Our Chef\",\"section_image\":[{\"src\":\"..\\/..\\/..\\/assets\\/images\\/admin\\/13f05d79dbb74d713ae688a443b9173e.png\",\"alt\":\"Fine dining plating at Bistro Toujours\",\"caption\":\"Fine dining\"},{\"src\":\"..\\/..\\/..\\/assets\\/images\\/admin\\/97b1ca747fa0432c79eaa88293f60a9a.png\",\"alt\":\"Chef in kitchen at Bistro Toujours\",\"caption\":\"Chef in kitchen\"}],\"custom_class\":\"chef_section\"}', 11, 1
WHERE @bistro IS NOT NULL AND NOT EXISTS (SELECT 1 FROM page_section WHERE page_id = @bistro AND sort_order = 11);

INSERT INTO page_section (page_id, section_type, title, content, sort_order, is_published)
SELECT @bistro, 'gallery', 'View Images', '{\"title\":\"View Images\",\"section_image\":[{\"src\":\"..\\/..\\/..\\/assets\\/images\\/admin\\/af1139fee93755d988a548027351a985.png\",\"alt\":\"Bistro Toujours terrace entrance\",\"caption\":\"Bistro Toujours terrace entrance\"},{\"src\":\"..\\/..\\/..\\/assets\\/images\\/admin\\/25e40a71c091f67e384014305c7bd12e.png\",\"alt\":\"Bistro Toujours terrace seating\",\"caption\":\"Bistro Toujours terrace seating\"},{\"src\":\"..\\/..\\/..\\/assets\\/images\\/admin\\/5492b10add5b6f51949e03a60bb6d991.png\",\"alt\":\"Bistro Toujours evening terrace\",\"caption\":\"Bistro Toujours evening terrace\"}],\"custom_class\":\"bistro_gallery\"}', 12, 1
WHERE @bistro IS NOT NULL AND NOT EXISTS (SELECT 1 FROM page_section WHERE page_id = @bistro AND sort_order = 12);

