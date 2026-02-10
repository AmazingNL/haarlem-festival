-- ============================================================
-- 02_sample_data.sql
-- Sample data for Haarlem Festival DB (safe-ish demo dataset)
-- Run AFTER 01_schema.sql
-- ============================================================

USE haarlem_festival;

SET FOREIGN_KEY_CHECKS = 0;
-- Optional: clear tables for re-seeding
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
-- password_hash is placeholder (use bcrypt/argon in app)
-- -------------------------
INSERT INTO `user` (email, password_hash, first_name, last_name, role, phone,
                    billing_street, billing_postal_code, billing_city, billing_country)
VALUES
('admin@haarlemfest.test',    'hash_admin',    'Admin',   'User',     'admin',    NULL, NULL, NULL, NULL, NULL),
('employee@haarlemfest.test', 'hash_employee', 'Eline',   'Scanner',  'employee', NULL, NULL, NULL, NULL, NULL),
('customer1@haarlemfest.test','hash_cust1',    'Sam',     'Jansen',   'customer', '+31 6 11111111', 'Grote Markt 1', '2011RD', 'Haarlem', 'NL'),
('customer2@haarlemfest.test','hash_cust2',    'Noor',    'de Vries', 'customer', '+31 6 22222222', 'Zijlstraat 12', '2011TN', 'Haarlem', 'NL');

-- -------------------------
-- Images (uploaded by admin)
-- -------------------------
INSERT INTO image (file_path, alt_text, uploaded_by_user_id)
VALUES
('/uploads/events/jazz-night.jpg',  'Jazz Night poster', 1),
('/uploads/events/food-tour.jpg',   'Food Tour image',   1),
('/uploads/events/history-walk.jpg','History Walk',      1),
('/uploads/profiles/sam.jpg',       'Sam profile photo', 1);

-- Set profile image for customer1
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
-- Events (5 events example)
-- -------------------------
INSERT INTO event (title, slug, description, start_datetime, end_datetime, location_id, image_id, is_published)
VALUES
('Jazz Night Live',     'jazz-night-live',
 'An evening of jazz performances with local and international artists.',
 '2026-07-24 19:30:00', '2026-07-24 22:30:00', 1, 1, 1),

('Food & Drink Tour',   'food-drink-tour',
 'Guided tasting tour through Haarlem with curated stops.',
 '2026-07-25 12:00:00', '2026-07-25 15:00:00', 4, 2, 1),

('Historic City Walk',  'historic-city-walk',
 'Learn the hidden stories behind Haarlem streets and buildings.',
 '2026-07-26 10:00:00', '2026-07-26 12:00:00', 5, 3, 1),

('Museum After Hours',  'museum-after-hours',
 'Special evening access with short talks and highlights.',
 '2026-07-26 18:00:00', '2026-07-26 20:00:00', 3, NULL, 1),

('Classical Matinee',   'classical-matinee',
 'Afternoon classical concert in the Philharmonie.',
 '2026-07-27 14:00:00', '2026-07-27 16:00:00', 2, NULL, 1);

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
-- Orders (one paid, one pending pay-later)
-- -------------------------
-- Order 1: customer1 paid
INSERT INTO `order` (user_id, order_datetime, subtotal_amount, vat_amount, total_price, currency, status, payment_due_at)
VALUES
(3, '2026-06-01 11:05:00', 85.00, 7.65, 92.65, 'EUR', 'paid', NULL);

-- Order 2: customer2 pending, pay-later due in 24h
INSERT INTO `order` (user_id, order_datetime, subtotal_amount, vat_amount, total_price, currency, status, payment_due_at)
VALUES
(4, '2026-06-02 14:20:00', 35.00, 7.35, 42.35, 'EUR', 'pending', '2026-06-03 14:20:00');

-- -------------------------
-- Order lines
-- -------------------------
-- Order 1 buys: Jazz Regular x2 (2*25), Food Tour Standard x1 (35) => subtotal 85
INSERT INTO order_ticket (order_id, ticket_type_id, quantity, unit_price_at_purchase, vat_rate_at_purchase, line_total_at_purchase)
VALUES
(1, 1, 2, 25.00, 0.0900, 50.00),
(1, 3, 1, 35.00, 0.2100, 35.00);

-- Order 2 buys: Food Tour Standard x1 => subtotal 35
INSERT INTO order_ticket (order_id, ticket_type_id, quantity, unit_price_at_purchase, vat_rate_at_purchase, line_total_at_purchase)
VALUES
(2, 3, 1, 35.00, 0.2100, 35.00);

-- -------------------------
-- Tickets (generate individually)
-- For demo: hard-coded tokens (use secure random tokens in app)
-- OrderTicket 1 qty 2 => 2 tickets
INSERT INTO ticket (order_ticket_id, qr_token, status, scanned_at, scanned_by_user_id)
VALUES
(1, REPEAT('a',64), 'valid',  NULL, NULL),
(1, REPEAT('b',64), 'scanned','2026-07-24 19:40:00', 2);

-- OrderTicket 2 qty 1 => 1 ticket
INSERT INTO ticket (order_ticket_id, qr_token, status)
VALUES
(2, REPEAT('c',64), 'valid');

-- OrderTicket 3 qty 1 => 1 ticket (pending order)
INSERT INTO ticket (order_ticket_id, qr_token, status)
VALUES
(3, REPEAT('d',64), 'valid');

-- -------------------------
-- Payments
-- -------------------------
INSERT INTO payment (order_id, provider, provider_payment_id, amount, currency, status, created_at, paid_at)
VALUES
(1, 'mollie', 'tr_123456789', 92.65, 'EUR', 'paid', '2026-06-01 11:06:00', '2026-06-01 11:07:12');

INSERT INTO payment (order_id, provider, provider_payment_id, amount, currency, status, created_at, paid_at)
VALUES
(2, 'mollie', 'tr_987654321', 42.35, 'EUR', 'pending', '2026-06-02 14:21:00', NULL);

-- -------------------------
-- Invoice for paid order
-- -------------------------
INSERT INTO invoice (
  order_id, invoice_number, invoice_date,
  customer_name, customer_email, customer_phone,
  billing_street, billing_postal_code, billing_city, billing_country,
  subtotal_amount, vat_amount, total_amount, payment_date
) VALUES (
  1, 'HF-2026-000001', '2026-06-01',
  'Sam Jansen', 'customer1@haarlemfest.test', '+31 6 11111111',
  'Grote Markt 1', '2011RD', 'Haarlem', 'NL',
  85.00, 7.65, 92.65, '2026-06-01'
);

-- Invoice lines (match order lines)
INSERT INTO invoice_line (
  invoice_id, description, quantity, unit_price, vat_rate,
  line_subtotal, line_vat, line_total
) VALUES
(1, 'Jazz Night Live - Regular', 2, 25.00, 0.0900, 50.00, 4.50, 54.50),
(1, 'Food & Drink Tour - Standard', 1, 35.00, 0.2100, 35.00, 7.35, 42.35);

-- -------------------------
-- Personal program items
-- -------------------------
-- customer1 has tickets for event 1 and 2 (source=ticket)
INSERT INTO program_item (user_id, event_id, source)
VALUES
(3, 1, 'ticket'),
(3, 2, 'ticket');

-- customer2 saved an event without buying
INSERT INTO program_item (user_id, event_id, source)
VALUES
(4, 5, 'saved');
