-- ============================================================
-- 02_sample_data.sql
-- Sample data for Haarlem Festival DB
-- Password for all users: password123
-- ============================================================

USE haarlem_festival;

SET FOREIGN_KEY_CHECKS = 0;
DELETE FROM invoice_line;
DELETE FROM invoice;
DELETE FROM ticket;
DELETE FROM order_ticket;
DELETE FROM payment;
DELETE FROM `order`;
DELETE FROM program_item;
DELETE FROM ticket_type;
DELETE FROM event;
DELETE FROM location;
DELETE FROM page;
DELETE FROM image;
DELETE FROM `user`;
SET FOREIGN_KEY_CHECKS = 1;

-- -------------------------
-- Users
-- Password for all: password123
-- -------------------------
INSERT INTO `user` (username, email, password_hash, first_name, last_name, role, phone,
                    billing_street, billing_postal_code, billing_city, billing_country)
VALUES
('admin', 'admin@haarlemfest.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'User', 'admin', NULL, NULL, NULL, NULL, NULL),
('eline', 'employee@haarlemfest.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Eline', 'Scanner', 'employee', NULL, NULL, NULL, NULL, NULL),
('samj', 'customer1@haarlemfest.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Sam', 'Jansen', 'customer', '+31 6 11111111', 'Grote Markt 1', '2011RD', 'Haarlem', 'NL'),
('noordv', 'customer2@haarlemfest.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Noor', 'de Vries', 'customer', '+31 6 22222222', 'Zijlstraat 12', '2011TN', 'Haarlem', 'NL');

-- -------------------------
-- Images
-- -------------------------
INSERT INTO image (file_path, alt_text, uploaded_by_user_id)
VALUES
('/uploads/events/jazz-night.jpg',  'Jazz Night poster', 1),
('/uploads/events/food-tour.jpg',   'Food Tour image',   1),
('/uploads/events/history-walk.jpg','History Walk',      1),
('/uploads/profiles/sam.jpg',       'Sam profile photo', 1);

UPDATE `user` SET profile_image_id = 4 WHERE user_id = 3;

-- -------------------------
-- CMS Pages
-- -------------------------
INSERT INTO page (title, slug, content, is_published)
VALUES
('Home',    'home',    '<h1>Welcome to Haarlem Festival</h1><p>Discover events, buy tickets, and build your personal program.</p>', 1),
('About',   'about',   '<h1>About</h1><p>Festival information and story.</p>', 1),
('Contact', 'contact', '<h1>Contact</h1><p>Email us at info@example.com</p>', 1);

-- -------------------------
-- Locations
-- -------------------------
INSERT INTO location (name, address, city, capacity)
VALUES
('Patronaat',         'Zijlsingel 2',       'Haarlem', 1500),
('Philharmonie',      'Lange Begijnestraat 11', 'Haarlem', 1200),
('Teylers Museum',    'Spaarne 16',         'Haarlem',  200),
('Jopenkerk',         'Gedempte Voldersgracht 2', 'Haarlem',  400),
('Kenaupark Start',   'Kenaupark',          'Haarlem',  300);

-- -------------------------
-- Events
-- -------------------------
INSERT INTO event (title, slug, description, start_datetime, end_datetime, location_id, image_id, is_published)
VALUES
('Jazz Night Live',     'jazz-night-live', 'An evening of jazz performances.', '2026-07-24 19:30:00', '2026-07-24 22:30:00', 1, 1, 1),
('Food & Drink Tour',   'food-drink-tour', 'Guided tasting tour through Haarlem.', '2026-07-25 12:00:00', '2026-07-25 15:00:00', 4, 2, 1),
('Historic City Walk',  'historic-city-walk', 'Learn hidden stories.', '2026-07-26 10:00:00', '2026-07-26 12:00:00', 5, 3, 1),
('Museum After Hours',  'museum-after-hours', 'Special evening access.', '2026-07-26 18:00:00', '2026-07-26 20:00:00', 3, NULL, 1),
('Classical Matinee',   'classical-matinee', 'Afternoon classical concert.', '2026-07-27 14:00:00', '2026-07-27 16:00:00', 2, NULL, 1);

-- -------------------------
-- Ticket Types
-- -------------------------
INSERT INTO ticket_type (event_id, name, price, vat_rate, max_quantity, is_active)
VALUES
(1, 'Regular', 25.00, 0.0900, 800, 1),
(1, 'VIP',     60.00, 0.0900, 100, 1),
(2, 'Standard',35.00, 0.2100, 200, 1),
(3, 'Adult',   15.00, 0.0900, 250, 1),
(3, 'Student', 10.00, 0.0900, 80,  1),
(4, 'Entry',   18.00, 0.0900, 180, 1),
(5, 'Seat',    30.00, 0.0900, 600, 1);

-- -------------------------
-- Orders, Tickets, Payments, Invoices
-- (Simplified for seeding)
-- -------------------------
INSERT INTO `order` (user_id, order_datetime, subtotal_amount, vat_amount, total_price, currency, status)
VALUES (3, '2026-06-01 11:05:00', 85.00, 7.65, 92.65, 'EUR', 'paid');

INSERT INTO order_ticket (order_id, ticket_type_id, quantity, unit_price_at_purchase, vat_rate_at_purchase, line_total_at_purchase)
VALUES (1, 1, 2, 25.00, 0.0900, 50.00), (1, 3, 1, 35.00, 0.2100, 35.00);

INSERT INTO ticket (order_ticket_id, qr_token, status)
VALUES (1, REPEAT('a',64), 'valid'), (2, REPEAT('c',64), 'valid');

INSERT INTO payment (order_id, provider, amount, status, paid_at)
VALUES (1, 'mollie', 92.65, 'paid', '2026-06-01 11:07:12');

INSERT INTO invoice (order_id, invoice_number, invoice_date, customer_name, customer_email, billing_street, billing_postal_code, billing_city, billing_country, subtotal_amount, vat_amount, total_amount)
VALUES (1, 'HF-2026-000001', '2026-06-01', 'Sam Jansen', 'customer1@haarlemfest.test', 'Grote Markt 1', '2011RD', 'Haarlem', 'NL', 85.00, 7.65, 92.65);

INSERT INTO program_item (user_id, event_id, source)
VALUES (3, 1, 'ticket'), (3, 2, 'ticket'), (4, 5, 'saved');