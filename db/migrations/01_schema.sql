-- ============================================================
-- Haarlem Festival Database
-- Clean Restructured Version (Role inside user)
-- ============================================================

SET NAMES utf8mb4;
SET time_zone = '+00:00';

CREATE DATABASE IF NOT EXISTS haarlem_festival
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE haarlem_festival;

-- ============================================================
-- SAFE RESET (drop in correct dependency order)
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS page_section_image;
DROP TABLE IF EXISTS page_section;
DROP TABLE IF EXISTS page;

DROP TABLE IF EXISTS program_item;

DROP TABLE IF EXISTS invoice_line;
DROP TABLE IF EXISTS invoice;

DROP TABLE IF EXISTS ticket;
DROP TABLE IF EXISTS order_ticket;
DROP TABLE IF EXISTS payment;
DROP TABLE IF EXISTS `order`;

DROP TABLE IF EXISTS ticket_type;
DROP TABLE IF EXISTS event;
DROP TABLE IF EXISTS location;

DROP TABLE IF EXISTS image;
DROP TABLE IF EXISTS `user`;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- USER
-- ============================================================

CREATE TABLE `user` (
  user_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,

  role ENUM('admin','customer','employee') 
    NOT NULL DEFAULT 'customer',

  username VARCHAR(50) NULL,
  email VARCHAR(255) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,

  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  phone VARCHAR(30) NULL,

  profile_image_id BIGINT UNSIGNED NULL,

  is_active TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP 
    ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (user_id),
  UNIQUE KEY uq_user_email (email),
  UNIQUE KEY uq_user_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 
COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- IMAGE
-- ============================================================

CREATE TABLE image (
  image_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  file_path VARCHAR(500) NOT NULL,
  alt_text VARCHAR(255) NULL,
  uploaded_by_user_id BIGINT UNSIGNED NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (image_id),

  CONSTRAINT fk_image_uploaded_by
    FOREIGN KEY (uploaded_by_user_id)
    REFERENCES `user`(user_id)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 
COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `user`
  ADD CONSTRAINT fk_user_profile_image
  FOREIGN KEY (profile_image_id)
  REFERENCES image(image_id)
  ON DELETE SET NULL ON UPDATE CASCADE;

-- ============================================================
-- PAGE (CMS)
-- ============================================================

CREATE TABLE page (
  page_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,

  content LONGTEXT NULL,

  status ENUM('draft','published','archived')
    NOT NULL DEFAULT 'draft',

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (page_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 
COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- PAGE SECTION (Homepage layout blocks)
-- ============================================================

CREATE TABLE page_section (
  section_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  page_id BIGINT UNSIGNED NOT NULL,

  section_type VARCHAR(50) NOT NULL,
  title VARCHAR(255) NULL,
  content LONGTEXT NULL,
  image_id BIGINT UNSIGNED NULL,

  button_text VARCHAR(100) NULL,
  button_link VARCHAR(255) NULL,

  sort_order INT NOT NULL DEFAULT 0,
  is_published TINYINT(1) NOT NULL DEFAULT 1,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (section_id),

  CONSTRAINT fk_section_page
    FOREIGN KEY (page_id)
    REFERENCES page(page_id)
    ON DELETE CASCADE ON UPDATE CASCADE,

  CONSTRAINT fk_section_image
    FOREIGN KEY (image_id)
    REFERENCES image(image_id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 
COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- PAGE SECTION IMAGES (Gallery support)
-- ============================================================

CREATE TABLE page_section_image (
  section_id BIGINT UNSIGNED NOT NULL,
  image_id BIGINT UNSIGNED NOT NULL,
  sort_order INT NOT NULL DEFAULT 0,

  PRIMARY KEY (section_id, image_id),

  CONSTRAINT fk_psi_section
    FOREIGN KEY (section_id)
    REFERENCES page_section(section_id)
    ON DELETE CASCADE ON UPDATE CASCADE,

  CONSTRAINT fk_psi_image
    FOREIGN KEY (image_id)
    REFERENCES image(image_id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 
COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- LOCATION
-- ============================================================

CREATE TABLE location (
  location_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  address VARCHAR(255) NOT NULL,
  city VARCHAR(120) NOT NULL,
  capacity INT UNSIGNED NOT NULL,

  PRIMARY KEY (location_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 
COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- EVENT
-- ============================================================

CREATE TABLE event (
  event_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  description LONGTEXT NOT NULL,

  start_datetime DATETIME NOT NULL,
  end_datetime DATETIME NOT NULL,

  location_id BIGINT UNSIGNED NOT NULL,
  image_id BIGINT UNSIGNED NULL,

  is_published TINYINT(1) NOT NULL DEFAULT 0,

  PRIMARY KEY (event_id),

  CONSTRAINT fk_event_location
    FOREIGN KEY (location_id)
    REFERENCES location(location_id),

  CONSTRAINT fk_event_image
    FOREIGN KEY (image_id)
    REFERENCES image(image_id)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 
COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TICKET TYPE
-- ============================================================

CREATE TABLE ticket_type (
  ticket_type_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  event_id BIGINT UNSIGNED NOT NULL,

  name VARCHAR(120) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  max_quantity INT UNSIGNED NOT NULL,

  PRIMARY KEY (ticket_type_id),

  CONSTRAINT fk_ticket_type_event
    FOREIGN KEY (event_id)
    REFERENCES event(event_id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 
COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- ORDER
-- ============================================================

CREATE TABLE `order` (
  order_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,

  total_price DECIMAL(10,2) NOT NULL,
  status ENUM('pending','paid','cancelled','expired')
    NOT NULL DEFAULT 'pending',

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (order_id),

  CONSTRAINT fk_order_user
    FOREIGN KEY (user_id)
    REFERENCES `user`(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 
COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- ORDER TICKET
-- ============================================================

CREATE TABLE order_ticket (
  order_ticket_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  order_id BIGINT UNSIGNED NOT NULL,
  ticket_type_id BIGINT UNSIGNED NOT NULL,

  quantity INT UNSIGNED NOT NULL,
  unit_price_at_purchase DECIMAL(10,2) NOT NULL,

  PRIMARY KEY (order_ticket_id),

  CONSTRAINT fk_order_ticket_order
    FOREIGN KEY (order_id)
    REFERENCES `order`(order_id)
    ON DELETE CASCADE,

  CONSTRAINT fk_order_ticket_type
    FOREIGN KEY (ticket_type_id)
    REFERENCES ticket_type(ticket_type_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 
COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TICKET
-- ============================================================

CREATE TABLE ticket (
  ticket_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  order_ticket_id BIGINT UNSIGNED NOT NULL,

  qr_token CHAR(64) NOT NULL UNIQUE,
  status ENUM('valid','scanned','cancelled')
    NOT NULL DEFAULT 'valid',

  scanned_at DATETIME NULL,

  PRIMARY KEY (ticket_id),

  CONSTRAINT fk_ticket_order_ticket
    FOREIGN KEY (order_ticket_id)
    REFERENCES order_ticket(order_ticket_id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 
COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- PAYMENT
-- ============================================================

CREATE TABLE payment (
  payment_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  order_id BIGINT UNSIGNED NOT NULL,

  provider VARCHAR(50),
  amount DECIMAL(10,2) NOT NULL,

  status ENUM('pending','paid','failed','refunded')
    NOT NULL DEFAULT 'pending',

  paid_at DATETIME NULL,

  PRIMARY KEY (payment_id),

  CONSTRAINT fk_payment_order
    FOREIGN KEY (order_id)
    REFERENCES `order`(order_id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 
COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- PROGRAM ITEM
-- ============================================================

CREATE TABLE program_item (
  program_item_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  event_id BIGINT UNSIGNED NOT NULL,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (program_item_id),

  UNIQUE KEY uq_program_user_event (user_id, event_id),

  CONSTRAINT fk_program_user
    FOREIGN KEY (user_id)
    REFERENCES `user`(user_id)
    ON DELETE CASCADE,

  CONSTRAINT fk_program_event
    FOREIGN KEY (event_id)
    REFERENCES event(event_id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 
COLLATE=utf8mb4_unicode_ci;
