-- ============================================================
-- 01_schema.sql  (MySQL 8+)
-- Haarlem Festival DB schema (tables + constraints + indexes)
-- ============================================================
-- migrate:up
SET NAMES utf8mb4;
SET time_zone = '+00:00';

-- Create and use DB (optional)
CREATE DATABASE IF NOT EXISTS haarlem_festival
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE haarlem_festival;

-- Drop in dependency order (safe reset)
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS invoice_line;
DROP TABLE IF EXISTS invoice;
DROP TABLE IF EXISTS ticket;
DROP TABLE IF EXISTS order_ticket;
DROP TABLE IF EXISTS payment;
DROP TABLE IF EXISTS `order`;
DROP TABLE IF EXISTS program_item;
DROP TABLE IF EXISTS ticket_type;
DROP TABLE IF EXISTS event;
DROP TABLE IF EXISTS location;
DROP TABLE IF EXISTS page;
DROP TABLE IF EXISTS image;
DROP TABLE IF EXISTS `user`;
SET FOREIGN_KEY_CHECKS = 1;

-- =========================
-- USER
-- =========================
CREATE TABLE `user` (
  user_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  email VARCHAR(255) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  role ENUM('admin','customer','employee') NOT NULL DEFAULT 'customer',
  phone VARCHAR(30) NULL,

  billing_street VARCHAR(255) NULL,
  billing_postal_code VARCHAR(20) NULL,
  billing_city VARCHAR(120) NULL,
  billing_country VARCHAR(120) NULL,

  profile_image_id BIGINT UNSIGNED NULL, -- FK added after IMAGE exists

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (user_id),
  UNIQUE KEY uq_user_email (email)
) ENGINE=InnoDB;

-- =========================
-- IMAGE
-- =========================
CREATE TABLE image (
  image_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  file_path VARCHAR(500) NOT NULL,
  alt_text VARCHAR(255) NULL,
  uploaded_by_user_id BIGINT UNSIGNED NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (image_id),
  KEY idx_image_uploader (uploaded_by_user_id),
  CONSTRAINT fk_image_uploaded_by
    FOREIGN KEY (uploaded_by_user_id) REFERENCES `user`(user_id)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Add USER.profile_image_id FK now that IMAGE exists
ALTER TABLE `user`
  ADD KEY idx_user_profile_image (profile_image_id),
  ADD CONSTRAINT fk_user_profile_image
    FOREIGN KEY (profile_image_id) REFERENCES image(image_id)
    ON DELETE SET NULL ON UPDATE CASCADE;

-- =========================
-- PAGE (CMS)
-- =========================
CREATE TABLE page (
  page_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL,
  content LONGTEXT NOT NULL,
  is_published TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (page_id),
  UNIQUE KEY uq_page_slug (slug)
) ENGINE=InnoDB;

-- =========================
-- LOCATION
-- =========================
CREATE TABLE location (
  location_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  address VARCHAR(255) NOT NULL,
  city VARCHAR(120) NOT NULL,
  capacity INT UNSIGNED NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (location_id),
  KEY idx_location_city (city)
) ENGINE=InnoDB;

-- =========================
-- EVENT
-- =========================
CREATE TABLE event (
  event_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL,
  description LONGTEXT NOT NULL,
  start_datetime DATETIME NOT NULL,
  end_datetime DATETIME NOT NULL,
  location_id BIGINT UNSIGNED NOT NULL,
  image_id BIGINT UNSIGNED NULL,
  is_published TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (event_id),
  UNIQUE KEY uq_event_slug (slug),
  KEY idx_event_location (location_id),
  KEY idx_event_image (image_id),
  KEY idx_event_start (start_datetime),

  CONSTRAINT fk_event_location
    FOREIGN KEY (location_id) REFERENCES location(location_id)
    ON DELETE RESTRICT ON UPDATE CASCADE,

  CONSTRAINT fk_event_image
    FOREIGN KEY (image_id) REFERENCES image(image_id)
    ON DELETE SET NULL ON UPDATE CASCADE,

  CONSTRAINT chk_event_time
    CHECK (end_datetime > start_datetime)
) ENGINE=InnoDB;

-- =========================
-- TICKET_TYPE
-- =========================
CREATE TABLE ticket_type (
  ticket_type_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  event_id BIGINT UNSIGNED NOT NULL,
  name VARCHAR(120) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  vat_rate DECIMAL(5,4) NOT NULL DEFAULT 0.2100, -- 0.2100 = 21%
  max_quantity INT UNSIGNED NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (ticket_type_id),
  KEY idx_ticket_type_event (event_id),
  KEY idx_ticket_type_active (is_active),

  CONSTRAINT fk_ticket_type_event
    FOREIGN KEY (event_id) REFERENCES event(event_id)
    ON DELETE RESTRICT ON UPDATE CASCADE,

  CONSTRAINT chk_ticket_type_price
    CHECK (price >= 0),

  CONSTRAINT chk_ticket_type_vat
    CHECK (vat_rate >= 0 AND vat_rate <= 1),

  CONSTRAINT chk_ticket_type_maxqty
    CHECK (max_quantity > 0)
) ENGINE=InnoDB;

-- =========================
-- ORDER
-- =========================
CREATE TABLE `order` (
  order_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  order_datetime DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  subtotal_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  vat_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  total_price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  currency CHAR(3) NOT NULL DEFAULT 'EUR',

  status ENUM('pending','paid','cancelled','expired') NOT NULL DEFAULT 'pending',
  payment_due_at DATETIME NULL,
  cancelled_at DATETIME NULL,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (order_id),
  KEY idx_order_user (user_id),
  KEY idx_order_status (status),
  KEY idx_order_due (payment_due_at),

  CONSTRAINT fk_order_user
    FOREIGN KEY (user_id) REFERENCES `user`(user_id)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =========================
-- ORDER_TICKET (order lines)
-- =========================
CREATE TABLE order_ticket (
  order_ticket_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  order_id BIGINT UNSIGNED NOT NULL,
  ticket_type_id BIGINT UNSIGNED NOT NULL,
  quantity INT UNSIGNED NOT NULL,

  unit_price_at_purchase DECIMAL(10,2) NOT NULL,
  vat_rate_at_purchase DECIMAL(5,4) NOT NULL,
  line_total_at_purchase DECIMAL(10,2) NOT NULL,

  PRIMARY KEY (order_ticket_id),
  KEY idx_order_ticket_order (order_id),
  KEY idx_order_ticket_type (ticket_type_id),

  CONSTRAINT fk_order_ticket_order
    FOREIGN KEY (order_id) REFERENCES `order`(order_id)
    ON DELETE CASCADE ON UPDATE CASCADE,

  CONSTRAINT fk_order_ticket_type
    FOREIGN KEY (ticket_type_id) REFERENCES ticket_type(ticket_type_id)
    ON DELETE RESTRICT ON UPDATE CASCADE,

  CONSTRAINT chk_order_ticket_qty
    CHECK (quantity > 0)
) ENGINE=InnoDB;

-- =========================
-- TICKET (individual scan tickets)
-- =========================
CREATE TABLE ticket (
  ticket_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  order_ticket_id BIGINT UNSIGNED NOT NULL,
  qr_token CHAR(64) NOT NULL, -- store 64 hex chars (e.g., SHA256), or any 64-length token
  status ENUM('valid','scanned','cancelled') NOT NULL DEFAULT 'valid',
  scanned_at DATETIME NULL,
  scanned_by_user_id BIGINT UNSIGNED NULL,

  PRIMARY KEY (ticket_id),
  UNIQUE KEY uq_ticket_qr (qr_token),
  KEY idx_ticket_order_ticket (order_ticket_id),
  KEY idx_ticket_scanned_by (scanned_by_user_id),
  KEY idx_ticket_status (status),

  CONSTRAINT fk_ticket_order_ticket
    FOREIGN KEY (order_ticket_id) REFERENCES order_ticket(order_ticket_id)
    ON DELETE CASCADE ON UPDATE CASCADE,

  CONSTRAINT fk_ticket_scanned_by
    FOREIGN KEY (scanned_by_user_id) REFERENCES `user`(user_id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =========================
-- PAYMENT
-- =========================
CREATE TABLE payment (
  payment_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  order_id BIGINT UNSIGNED NOT NULL,
  provider VARCHAR(50) NOT NULL, -- e.g., mollie, stripe
  provider_payment_id VARCHAR(255) NULL,
  amount DECIMAL(10,2) NOT NULL,
  currency CHAR(3) NOT NULL DEFAULT 'EUR',
  status ENUM('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  paid_at DATETIME NULL,

  PRIMARY KEY (payment_id),
  KEY idx_payment_order (order_id),
  KEY idx_payment_status (status),

  CONSTRAINT fk_payment_order
    FOREIGN KEY (order_id) REFERENCES `order`(order_id)
    ON DELETE CASCADE ON UPDATE CASCADE,

  CONSTRAINT chk_payment_amount
    CHECK (amount >= 0)
) ENGINE=InnoDB;

-- =========================
-- INVOICE
-- =========================
CREATE TABLE invoice (
  invoice_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  order_id BIGINT UNSIGNED NOT NULL,

  invoice_number VARCHAR(50) NOT NULL,
  invoice_date DATE NOT NULL,

  customer_name VARCHAR(255) NOT NULL,
  customer_email VARCHAR(255) NOT NULL,
  customer_phone VARCHAR(30) NULL,

  billing_street VARCHAR(255) NOT NULL,
  billing_postal_code VARCHAR(20) NOT NULL,
  billing_city VARCHAR(120) NOT NULL,
  billing_country VARCHAR(120) NOT NULL,

  subtotal_amount DECIMAL(10,2) NOT NULL,
  vat_amount DECIMAL(10,2) NOT NULL,
  total_amount DECIMAL(10,2) NOT NULL,
  payment_date DATE NULL,

  PRIMARY KEY (invoice_id),
  UNIQUE KEY uq_invoice_order (order_id),
  UNIQUE KEY uq_invoice_number (invoice_number),

  CONSTRAINT fk_invoice_order
    FOREIGN KEY (order_id) REFERENCES `order`(order_id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =========================
-- INVOICE_LINE
-- =========================
CREATE TABLE invoice_line (
  invoice_line_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  invoice_id BIGINT UNSIGNED NOT NULL,
  description VARCHAR(255) NOT NULL,
  quantity INT UNSIGNED NOT NULL,
  unit_price DECIMAL(10,2) NOT NULL,
  vat_rate DECIMAL(5,4) NOT NULL,
  line_subtotal DECIMAL(10,2) NOT NULL,
  line_vat DECIMAL(10,2) NOT NULL,
  line_total DECIMAL(10,2) NOT NULL,

  PRIMARY KEY (invoice_line_id),
  KEY idx_invoice_line_invoice (invoice_id),

  CONSTRAINT fk_invoice_line_invoice
    FOREIGN KEY (invoice_id) REFERENCES invoice(invoice_id)
    ON DELETE CASCADE ON UPDATE CASCADE,

  CONSTRAINT chk_invoice_line_qty
    CHECK (quantity > 0)
) ENGINE=InnoDB;

-- =========================
-- PROGRAM_ITEM (personal program)
-- =========================
CREATE TABLE program_item (
  program_item_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  event_id BIGINT UNSIGNED NOT NULL,
  source ENUM('ticket','saved') NOT NULL DEFAULT 'saved',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (program_item_id),
  UNIQUE KEY uq_program_user_event (user_id, event_id),
  KEY idx_program_event (event_id),

  CONSTRAINT fk_program_user
    FOREIGN KEY (user_id) REFERENCES `user`(user_id)
    ON DELETE CASCADE ON UPDATE CASCADE,

  CONSTRAINT fk_program_event
    FOREIGN KEY (event_id) REFERENCES event(event_id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;
