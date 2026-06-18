-- Order/booking refactor (Stage 1): real restaurant + reservation entities,
-- normalize order_line (reservation link + VAT), attach tickets to order_line,
-- and add invoice fields to order. Idempotent.
USE haarlem_festival;

SET @db := DATABASE();

-- 1. Replace the legacy misnamed `restaurant` table (no name/slug) with a real venue.
SET @needs_rebuild := (
  SELECT COUNT(*) = 0 FROM information_schema.columns
  WHERE table_schema = @db AND table_name = 'restaurant' AND column_name = 'slug'
);
SET @sql := IF(@needs_rebuild, 'DROP TABLE IF EXISTS restaurant', 'SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

CREATE TABLE IF NOT EXISTS restaurant (
  restaurant_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL,
  description TEXT NULL,
  capacity INT UNSIGNED NOT NULL DEFAULT 0,
  location_id BIGINT UNSIGNED NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (restaurant_id),
  UNIQUE KEY uq_restaurant_slug (slug),
  CONSTRAINT fk_restaurant_location
    FOREIGN KEY (location_id) REFERENCES location(location_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Reservation (a restaurant booking).
CREATE TABLE IF NOT EXISTS reservation (
  reservation_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  restaurant_id BIGINT UNSIGNED NOT NULL,
  user_id BIGINT UNSIGNED NOT NULL,
  order_id BIGINT UNSIGNED NULL,
  reservation_date VARCHAR(64) NOT NULL,
  session VARCHAR(64) NOT NULL,
  adult_count INT UNSIGNED NOT NULL DEFAULT 0,
  child_count INT UNSIGNED NOT NULL DEFAULT 0,
  special_requests TEXT NULL,
  status ENUM('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (reservation_id),
  KEY idx_reservation_restaurant (restaurant_id),
  KEY idx_reservation_user (user_id),
  KEY idx_reservation_order (order_id),
  CONSTRAINT fk_reservation_restaurant
    FOREIGN KEY (restaurant_id) REFERENCES restaurant(restaurant_id) ON DELETE CASCADE,
  CONSTRAINT fk_reservation_user
    FOREIGN KEY (user_id) REFERENCES `user`(user_id) ON DELETE CASCADE,
  CONSTRAINT fk_reservation_order
    FOREIGN KEY (order_id) REFERENCES `order`(order_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. order_line: link to a reservation + per-line VAT rate.
SET @has := (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = @db AND table_name = 'order_line' AND column_name = 'reservation_id');
SET @sql := IF(@has = 0, 'ALTER TABLE order_line ADD COLUMN reservation_id BIGINT UNSIGNED NULL AFTER ticket_type_id', 'SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SET @has := (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = @db AND table_name = 'order_line' AND column_name = 'vat_rate');
SET @sql := IF(@has = 0, 'ALTER TABLE order_line ADD COLUMN vat_rate DECIMAL(5,2) NOT NULL DEFAULT 9.00 AFTER line_total', 'SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SET @has := (SELECT COUNT(*) FROM information_schema.table_constraints WHERE constraint_schema = @db AND table_name = 'order_line' AND constraint_name = 'fk_order_line_reservation');
SET @sql := IF(@has = 0, 'ALTER TABLE order_line ADD CONSTRAINT fk_order_line_reservation FOREIGN KEY (reservation_id) REFERENCES reservation(reservation_id) ON DELETE SET NULL', 'SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- 4. ticket: attach to order_line (the unified line table).
SET @has := (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = @db AND table_name = 'ticket' AND column_name = 'order_line_id');
SET @sql := IF(@has = 0, 'ALTER TABLE ticket ADD COLUMN order_line_id BIGINT UNSIGNED NULL AFTER ticket_id', 'SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SET @has := (SELECT COUNT(*) FROM information_schema.table_constraints WHERE constraint_schema = @db AND table_name = 'ticket' AND constraint_name = 'fk_ticket_order_line');
SET @sql := IF(@has = 0, 'ALTER TABLE ticket ADD CONSTRAINT fk_ticket_order_line FOREIGN KEY (order_line_id) REFERENCES order_line(order_line_id) ON DELETE CASCADE', 'SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- 5. order: invoice number + issue date.
SET @has := (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = @db AND table_name = 'order' AND column_name = 'invoice_number');
SET @sql := IF(@has = 0, 'ALTER TABLE `order` ADD COLUMN invoice_number VARCHAR(50) NULL AFTER status', 'SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SET @has := (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = @db AND table_name = 'order' AND column_name = 'invoice_issued_at');
SET @sql := IF(@has = 0, 'ALTER TABLE `order` ADD COLUMN invoice_issued_at DATETIME NULL AFTER invoice_number', 'SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;
