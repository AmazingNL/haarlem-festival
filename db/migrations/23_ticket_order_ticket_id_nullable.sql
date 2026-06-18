-- Tickets are created via order_line_id (checkout refactor). Legacy order_ticket_id
-- must be nullable so inserts like (order_line_id, qr_token, status) succeed.
-- Idempotent.
USE haarlem_festival;

SET @db := DATABASE();

SET @has_fk := (
  SELECT COUNT(*)
  FROM information_schema.table_constraints
  WHERE constraint_schema = @db
    AND table_name = 'ticket'
    AND constraint_name = 'fk_ticket_order_ticket'
);
SET @sql := IF(@has_fk > 0, 'ALTER TABLE ticket DROP FOREIGN KEY fk_ticket_order_ticket', 'SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SET @has_col := (
  SELECT COUNT(*)
  FROM information_schema.columns
  WHERE table_schema = @db
    AND table_name = 'ticket'
    AND column_name = 'order_ticket_id'
);
SET @nn := (
  SELECT COUNT(*)
  FROM information_schema.columns
  WHERE table_schema = @db
    AND table_name = 'ticket'
    AND column_name = 'order_ticket_id'
    AND is_nullable = 'NO'
);
SET @sql := IF(
  @has_col > 0 AND @nn > 0,
  'ALTER TABLE ticket MODIFY COLUMN order_ticket_id BIGINT UNSIGNED NULL',
  'SELECT 1'
);
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;
