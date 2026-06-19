
-- migrate:up
USE haarlem_festival;

SET FOREIGN_KEY_CHECKS = 1;

-- Idempotent: every INSERT uses INSERT IGNORE so re-running the seed (or running
-- it on an already-populated database) skips conflicting rows instead of aborting
-- the whole seed run. On a fresh database the behaviour is identical to before.

-- -------------------------
-- Users
-- Password for all: Test12345!
-- -------------------------
INSERT IGNORE INTO `user`
(user_id, role, username, email, password_hash, first_name, last_name, phone, profile_image_id, is_active, created_at, updated_at)
VALUES
(1, 'admin',    'admin',  'admin@haarlemfest.test',     '$2y$12$vIvVL6qdxkHgsNXQ6lbzZePy973snNNAnbRP5jegW6O40R6mXHpHG', 'Admin', 'User',     NULL,             NULL, 1, NOW(), NOW()),
(2, 'employee', 'eline',  'employee@haarlemfest.test',  '$2y$12$vIvVL6qdxkHgsNXQ6lbzZePy973snNNAnbRP5jegW6O40R6mXHpHG', 'Eline', 'Scanner',  NULL,             NULL, 1, NOW(), NOW()),
(3, 'customer', 'samj',   'customer1@haarlemfest.test', '$2y$12$vIvVL6qdxkHgsNXQ6lbzZePy973snNNAnbRP5jegW6O40R6mXHpHG', 'Sam',   'Jansen',   '+31 6 11111111', NULL, 1, NOW(), NOW()),
(4, 'customer', 'noordv', 'customer2@haarlemfest.test', '$2y$12$vIvVL6qdxkHgsNXQ6lbzZePy973snNNAnbRP5jegW6O40R6mXHpHG', 'Noor',  'de Vries', '+31 6 22222222', NULL, 1, NOW(), NOW());

-- -------------------------
-- Images
-- -------------------------
INSERT IGNORE INTO image (file_path, alt_text, uploaded_by_user_id)
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
-- CMS Pages (home is created by migration 10_home_page_refresh.sql)
-- -------------------------
INSERT INTO page (title, slug, content, status)
SELECT 'About', 'about', '<h1>About</h1><p>Festival information and story.</p>', 'published'
WHERE NOT EXISTS (SELECT 1 FROM page WHERE slug = 'about');

INSERT INTO page (title, slug, content, status)
SELECT 'Contact', 'contact', '<h1>Contact</h1><p>Email us at info@example.com</p>', 'published'
WHERE NOT EXISTS (SELECT 1 FROM page WHERE slug = 'contact');

-- -------------------------
-- Locations
-- -------------------------
INSERT IGNORE INTO location (name, address, city, capacity)
VALUES
('Patronaat',            'Zijlsingel 2',                'Haarlem', 1500),
('Philharmonie',         'Lange Begijnestraat 11',      'Haarlem', 1200),
('Teylers Museum',       'Spaarne 16',                  'Haarlem',  200),
('Jopenkerk',            'Gedempte Voldersgracht 2',    'Haarlem',  400),
('Kenaupark Start',      'Kenaupark',                   'Haarlem',  300);

-- -------------------------
-- Events
-- -------------------------
INSERT IGNORE INTO event (title, slug, description, start_datetime, end_datetime, location_id, image_id, is_published)
VALUES
('Jazz Night Live',     'jazz-night-live',     'An evening of jazz performances.',        '2026-07-24 19:30:00', '2026-07-24 22:30:00', 1, 1, 1),
('Food & Drink Tour',   'food-drink-tour',     'Guided tasting tour through Haarlem.',    '2026-07-25 12:00:00', '2026-07-25 15:00:00', 4, 2, 1),
('Historic City Walk',  'historic-city-walk',  'Learn hidden stories.',                   '2026-07-26 10:00:00', '2026-07-26 12:00:00', 5, 3, 1),
('Museum After Hours',  'museum-after-hours',  'Special evening access.',                 '2026-07-26 18:00:00', '2026-07-26 20:00:00', 3, NULL, 1),
('Classical Matinee',   'classical-matinee',   'Afternoon classical concert.',            '2026-07-27 14:00:00', '2026-07-27 16:00:00', 2, NULL, 1);

-- -------------------------
-- Ticket Types (NEW schema has no vat_rate/is_active)
-- -------------------------
INSERT IGNORE INTO ticket_type (event_id, name, price, max_quantity)
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
INSERT IGNORE INTO `order` (user_id, total_price, status, created_at)
VALUES (3, 92.65, 'paid', '2026-06-01 11:05:00');

INSERT IGNORE INTO order_ticket (order_id, ticket_type_id, quantity, unit_price_at_purchase)
VALUES
(1, 1, 2, 25.00),
(1, 3, 1, 35.00);

-- 2 tickets total (2x Regular + 1x Standard would normally be 3 tickets,
-- but your old seed inserted 2 tickets. We'll keep it simple and insert 2.)
INSERT IGNORE INTO ticket (order_ticket_id, qr_token, status)
VALUES
(1, REPEAT('a',64), 'valid'),
(2, REPEAT('c',64), 'valid');

INSERT IGNORE INTO payment (order_id, provider, amount, status, paid_at)
VALUES
(1, 'stripe', 92.65, 'paid', '2026-06-01 11:07:12');

-- -------------------------
-- Program items (NEW schema has no source)
-- -------------------------
INSERT IGNORE INTO program_item (user_id, event_id)
VALUES
(3, 1),
(3, 2),
(4, 5);
