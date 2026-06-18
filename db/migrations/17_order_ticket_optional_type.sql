-- Allow order_ticket rows without a ticket_type so every purchase type gets a QR ticket.
USE haarlem_festival;

SET @db := DATABASE();

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
