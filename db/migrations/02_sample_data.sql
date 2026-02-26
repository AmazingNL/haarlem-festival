-- ============================================================
-- 02_sample_data.sql
-- Sample data for Haarlem Festival DB (NEW schema)
-- Password for all users: password123
-- ============================================================
-- migrate:up
USE haarlem_festival;

SET FOREIGN_KEY_CHECKS = 1;

-- -------------------------
-- Users
-- Password for all: password123
-- -------------------------
-- -------------------------
-- Users
-- Password for all: Test12345!
-- -------------------------
INSERT INTO `user`
(user_id, role, username, email, password_hash, first_name, last_name, phone, profile_image_id, is_active, created_at, updated_at)
VALUES
(1, 'admin',    'admin',  'admin@haarlemfest.test',     '$2y$12$vIvVL6qdxkHgsNXQ6lbzZePy973snNNAnbRP5jegW6O40R6mXHpHG', 'Admin', 'User',     NULL,             NULL, 1, NOW(), NOW()),
(2, 'employee', 'eline',  'employee@haarlemfest.test',  '$2y$12$vIvVL6qdxkHgsNXQ6lbzZePy973snNNAnbRP5jegW6O40R6mXHpHG', 'Eline', 'Scanner',  NULL,             NULL, 1, NOW(), NOW()),
(3, 'customer', 'samj',   'customer1@haarlemfest.test', '$2y$12$vIvVL6qdxkHgsNXQ6lbzZePy973snNNAnbRP5jegW6O40R6mXHpHG', 'Sam',   'Jansen',   '+31 6 11111111', NULL, 1, NOW(), NOW()),
(4, 'customer', 'noordv', 'customer2@haarlemfest.test', '$2y$12$vIvVL6qdxkHgsNXQ6lbzZePy973snNNAnbRP5jegW6O40R6mXHpHG', 'Noor',  'de Vries', '+31 6 22222222', NULL, 1, NOW(), NOW());

-- -------------------------
-- Images
-- -------------------------
INSERT INTO image (file_path, alt_text, uploaded_by_user_id)
VALUES
('/uploads/events/jazz-night.jpg',   'Jazz Night poster', 1),
('/uploads/events/food-tour.jpg',    'Food Tour image',   1),
('/uploads/events/history-walk.jpg', 'History Walk',      1),
('/uploads/profiles/sam.jpg',        'Sam profile photo', 1),

-- (extra images for homepage sections)
('/uploads/home/hero-haarlem.jpg',   'Haarlem hero image', 1),
('/uploads/home/windmill.jpg',       'Windmill Haarlem',   1),
('/uploads/home/church.jpg',         'Church Haarlem',     1),
('/uploads/home/grote-markt.jpg',    'Grote Markt',        1),
('/uploads/home/canal-houses.jpg',   'Canal Houses',       1);

-- attach profile image for Sam (image_id = 4)
UPDATE `user` SET profile_image_id = 4 WHERE user_id = 3;

-- -------------------------
-- CMS Pages (new schema uses status)
-- -------------------------
INSERT INTO page (title, slug, content, status)
VALUES
('Home',    'home',    NULL, 'published'),
('About',   'about',   '<h1>About</h1><p>Festival information and story.</p>', 'published'),
('Contact', 'contact', '<h1>Contact</h1><p>Email us at info@example.com</p>', 'published');

-- -------------------------
-- HOME PAGE SECTIONS (CMS-driven homepage)
-- page_id for home is 1
-- -------------------------

-- Hero (uses main image_id 5)
INSERT INTO page_section (page_id, section_type, title, content, image_id, button_text, button_link, sort_order, is_published)
VALUES
(1, 'hero', 'Visit Haarlem', '<p>Discover events, buy tickets, and build your personal program.</p>', 5, 'Explore Events', '/events', 1, 1);

-- Intro text block
INSERT INTO page_section (page_id, section_type, title, content, image_id, button_text, button_link, sort_order, is_published)
VALUES
(1, 'text_block', 'Welcome to Haarlem', '<p>Haarlem is a charming city with canals, historic streets, and great culture. Explore highlights and plan your visit.</p>', NULL, NULL, NULL, 2, 1);

-- Two image row (gallery section without main image)
INSERT INTO page_section (page_id, section_type, title, content, image_id, button_text, button_link, sort_order, is_published)
VALUES
(1, 'two_image_row', NULL, NULL, NULL, NULL, NULL, 3, 1);

-- Link exactly 2 images to that gallery section
-- Assumes the last insert made section_id = 3 (safe approach below uses LAST_INSERT_ID)
SET @two_img_section_id = LAST_INSERT_ID();

INSERT INTO page_section_image (section_id, image_id, sort_order)
VALUES
(@two_img_section_id, 6, 1),
(@two_img_section_id, 7, 2);

-- Grote Markt (image left)
INSERT INTO page_section (page_id, section_type, title, content, image_id, button_text, button_link, sort_order, is_published)
VALUES
(1, 'image_left', 'Grote Markt', '<p>The heart of Haarlem with terraces, history, and lively atmosphere.</p>', 8, 'Read more', '/locations/grote-markt', 4, 1);

-- Canal Houses (image right)
INSERT INTO page_section (page_id, section_type, title, content, image_id, button_text, button_link, sort_order, is_published)
VALUES
(1, 'image_right', 'Canal Houses', '<p>Walk along the Spaarne and enjoy classic Haarlem architecture.</p>', 9, 'Discover', '/visit/canals', 5, 1);

-- Cards grid (store JSON in content for now)
INSERT INTO page_section (page_id, section_type, title, content, image_id, button_text, button_link, sort_order, is_published)
VALUES
(
  1,
  'cards_grid',
  'What you can do',
  '[
    {"title":"Stories","text":"Local tales and city legends.","link":"/stories"},
    {"title":"History","text":"Museums and historic streets.","link":"/history"},
    {"title":"Restaurants","text":"Food spots you should not miss.","link":"/restaurants"},
    {"title":"Dance","text":"Dance events and nightlife.","link":"/events?tag=dance"},
    {"title":"Jazz","text":"Jazz highlights and concerts.","link":"/events?tag=jazz"}
  ]',
  NULL,
  NULL,
  NULL,
  6,
  1
);

-- Transportation block
INSERT INTO page_section (page_id, section_type, title, content, image_id, button_text, button_link, sort_order, is_published)
VALUES
(1, 'transport', 'Transportation', '<p>Getting around is easy: buses, trains, bikes, and walking routes connect everything.</p>', NULL, 'Plan your route', '/transport', 7, 1);

-- -------------------------
-- Locations
-- -------------------------
INSERT INTO location (name, address, city, capacity)
VALUES
('Patronaat',            'Zijlsingel 2',                'Haarlem', 1500),
('Philharmonie',         'Lange Begijnestraat 11',      'Haarlem', 1200),
('Teylers Museum',       'Spaarne 16',                  'Haarlem',  200),
('Jopenkerk',            'Gedempte Voldersgracht 2',    'Haarlem',  400),
('Kenaupark Start',      'Kenaupark',                   'Haarlem',  300);

-- -------------------------
-- Events
-- -------------------------
INSERT INTO event (title, slug, description, start_datetime, end_datetime, location_id, image_id, is_published)
VALUES
('Jazz Night Live',     'jazz-night-live',     'An evening of jazz performances.',        '2026-07-24 19:30:00', '2026-07-24 22:30:00', 1, 1, 1),
('Food & Drink Tour',   'food-drink-tour',     'Guided tasting tour through Haarlem.',    '2026-07-25 12:00:00', '2026-07-25 15:00:00', 4, 2, 1),
('Historic City Walk',  'historic-city-walk',  'Learn hidden stories.',                   '2026-07-26 10:00:00', '2026-07-26 12:00:00', 5, 3, 1),
('Museum After Hours',  'museum-after-hours',  'Special evening access.',                 '2026-07-26 18:00:00', '2026-07-26 20:00:00', 3, NULL, 1),
('Classical Matinee',   'classical-matinee',   'Afternoon classical concert.',            '2026-07-27 14:00:00', '2026-07-27 16:00:00', 2, NULL, 1);

-- -------------------------
-- Ticket Types (NEW schema has no vat_rate/is_active)
-- -------------------------
INSERT INTO ticket_type (event_id, name, price, max_quantity)
VALUES
(1, 'Regular', 25.00, 800),
(1, 'VIP',     60.00, 100),
(2, 'Standard',35.00, 200),
(3, 'Adult',   15.00, 250),
(3, 'Student', 10.00, 80),
(4, 'Entry',   18.00, 180),
(5, 'Seat',    30.00, 600);

-- -------------------------
-- Orders, Tickets, Payments (NEW simplified order table)
-- -------------------------
INSERT INTO `order` (user_id, total_price, status, created_at)
VALUES (3, 92.65, 'paid', '2026-06-01 11:05:00');

INSERT INTO order_ticket (order_id, ticket_type_id, quantity, unit_price_at_purchase)
VALUES
(1, 1, 2, 25.00),
(1, 3, 1, 35.00);

-- 2 tickets total (2x Regular + 1x Standard would normally be 3 tickets,
-- but your old seed inserted 2 tickets. We'll keep it simple and insert 2.)
INSERT INTO ticket (order_ticket_id, qr_token, status)
VALUES
(1, REPEAT('a',64), 'valid'),
(2, REPEAT('c',64), 'valid');

INSERT INTO payment (order_id, provider, amount, status, paid_at)
VALUES
(1, 'mollie', 92.65, 'paid', '2026-06-01 11:07:12');

-- -------------------------
-- Program items (NEW schema has no source)
-- -------------------------
INSERT INTO program_item (user_id, event_id)
VALUES
(3, 1),
(3, 2),
(4, 5);

/* -- migrate:down
SET FOREIGN_KEY_CHECKS = 0;

DELETE FROM program_item;

DELETE FROM ticket;
DELETE FROM order_ticket;
DELETE FROM payment;
DELETE FROM `order`;

DELETE FROM ticket_type;
DELETE FROM event;
DELETE FROM location;

DELETE FROM page_section_image;
DELETE FROM page_section;
DELETE FROM page;

DELETE FROM image;
DELETE FROM `user`;

SET FOREIGN_KEY_CHECKS = 1;
 */