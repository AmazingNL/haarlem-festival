-- Allow order_ticket rows without a ticket_type so every purchase type gets a QR ticket.
-- Guarded: order_ticket does not exist on every environment (and is removed by the
-- order/booking refactor), so each step only runs when the table/constraint is present.
USE haarlem_festival;

SET @db := DATABASE();

SET @has_fk := (SELECT COUNT(*) FROM information_schema.table_constraints
  WHERE constraint_schema = @db AND table_name = 'order_ticket' AND constraint_name = 'fk_order_ticket_type');
SET @sql := IF(@has_fk > 0, 'ALTER TABLE order_ticket DROP FOREIGN KEY fk_order_ticket_type', 'SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SET @has_ot := (SELECT COUNT(*) FROM information_schema.tables
  WHERE table_schema = @db AND table_name = 'order_ticket');
SET @sql := IF(@has_ot > 0, 'ALTER TABLE order_ticket MODIFY COLUMN ticket_type_id BIGINT UNSIGNED NULL', 'SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SET @has_fk := (SELECT COUNT(*) FROM information_schema.table_constraints
  WHERE constraint_schema = @db AND table_name = 'order_ticket' AND constraint_name = 'fk_order_ticket_type');
SET @sql := IF(@has_ot > 0 AND @has_fk = 0,
  'ALTER TABLE order_ticket ADD CONSTRAINT fk_order_ticket_type FOREIGN KEY (ticket_type_id) REFERENCES ticket_type(ticket_type_id)',
  'SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;
