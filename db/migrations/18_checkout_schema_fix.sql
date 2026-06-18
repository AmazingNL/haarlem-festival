-- Idempotent checkout schema fixes (order fields, payment columns, order_line, tickets)

USE haarlem_festival;

SET @db := DATABASE();

-- order.customer fields + provider
SET @sql := IF(
  (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = @db AND table_name = 'order' AND column_name = 'first_name') = 0,
  'ALTER TABLE `order` ADD COLUMN first_name VARCHAR(100) NULL AFTER user_id',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
  (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = @db AND table_name = 'order' AND column_name = 'last_name') = 0,
  'ALTER TABLE `order` ADD COLUMN last_name VARCHAR(100) NULL AFTER first_name',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
  (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = @db AND table_name = 'order' AND column_name = 'email') = 0,
  'ALTER TABLE `order` ADD COLUMN email VARCHAR(255) NULL AFTER last_name',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
  (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = @db AND table_name = 'order' AND column_name = 'phone') = 0,
  'ALTER TABLE `order` ADD COLUMN phone VARCHAR(50) NULL AFTER email',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
  (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = @db AND table_name = 'order' AND column_name = 'provider') = 0,
  'ALTER TABLE `order` ADD COLUMN provider VARCHAR(50) NULL AFTER status',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

CREATE TABLE IF NOT EXISTS order_line (
  order_line_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  order_id BIGINT UNSIGNED NOT NULL,
  item_type VARCHAR(50) NOT NULL DEFAULT 'booking',
  title VARCHAR(255) NOT NULL,
  selection_text VARCHAR(500) NULL,
  ticket_title VARCHAR(255) NULL,
  ticket_summary_text VARCHAR(500) NULL,
  quantity INT UNSIGNED NOT NULL DEFAULT 1,
  unit_price DECIMAL(10,2) NOT NULL,
  line_total DECIMAL(10,2) NOT NULL,
  location_name VARCHAR(255) NULL,
  special_requests TEXT NULL,
  event_id BIGINT UNSIGNED NULL,
  ticket_type_id BIGINT UNSIGNED NULL,
  item_data JSON NULL,
  PRIMARY KEY (order_line_id),
  KEY idx_order_line_order (order_id),
  CONSTRAINT fk_order_line_order
    FOREIGN KEY (order_id)
    REFERENCES `order`(order_id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET @sql := IF(
  (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = @db AND table_name = 'payment' AND column_name = 'provider_payment_id') = 0,
  'ALTER TABLE payment ADD COLUMN provider_payment_id VARCHAR(255) NULL AFTER provider',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
  (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = @db AND table_name = 'payment' AND column_name = 'stripe_session_id') = 0,
  'ALTER TABLE payment ADD COLUMN stripe_session_id VARCHAR(255) NULL AFTER provider_payment_id',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
  (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = @db AND table_name = 'payment' AND column_name = 'currency') = 0,
  'ALTER TABLE payment ADD COLUMN currency CHAR(3) NOT NULL DEFAULT ''EUR'' AFTER amount',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

CREATE TABLE IF NOT EXISTS pending_stripe_checkout (
  stripe_session_id VARCHAR(255) NOT NULL,
  user_id BIGINT UNSIGNED NOT NULL,
  customer_json JSON NOT NULL,
  items_json JSON NOT NULL,
  provider VARCHAR(50) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (stripe_session_id),
  KEY idx_pending_checkout_user (user_id),
  CONSTRAINT fk_pending_checkout_user
    FOREIGN KEY (user_id)
    REFERENCES `user`(user_id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET @fk_exists := (
  SELECT COUNT(*) FROM information_schema.table_constraints
  WHERE table_schema = @db AND table_name = 'order_ticket' AND constraint_name = 'fk_order_ticket_type'
);
SET @sql := IF(@fk_exists > 0,
  'ALTER TABLE order_ticket DROP FOREIGN KEY fk_order_ticket_type',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

ALTER TABLE order_ticket MODIFY COLUMN ticket_type_id BIGINT UNSIGNED NULL;

SET @fk_exists := (
  SELECT COUNT(*) FROM information_schema.table_constraints
  WHERE table_schema = @db AND table_name = 'order_ticket' AND constraint_name = 'fk_order_ticket_type'
);
SET @sql := IF(@fk_exists = 0,
  'ALTER TABLE order_ticket ADD CONSTRAINT fk_order_ticket_type FOREIGN KEY (ticket_type_id) REFERENCES ticket_type(ticket_type_id)',
  'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
