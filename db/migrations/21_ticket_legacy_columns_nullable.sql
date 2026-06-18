-- Reconcile the cross-branch `ticket` table: it carries legacy NOT NULL columns
-- (order_item_id -> order_item, and a direct ticket_type_id) from a parallel branch.
-- Tickets now attach via order_line_id, so these must be nullable. Idempotent.
USE haarlem_festival;

SET @db := DATABASE();

SET @nn := (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = @db AND table_name = 'ticket' AND column_name = 'order_item_id' AND is_nullable = 'NO');
SET @sql := IF(@nn > 0, 'ALTER TABLE ticket MODIFY COLUMN order_item_id BIGINT UNSIGNED NULL', 'SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SET @nn := (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = @db AND table_name = 'ticket' AND column_name = 'ticket_type_id' AND is_nullable = 'NO');
SET @sql := IF(@nn > 0, 'ALTER TABLE ticket MODIFY COLUMN ticket_type_id BIGINT UNSIGNED NULL', 'SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;
