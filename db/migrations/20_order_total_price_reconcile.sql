-- Reconcile the cross-branch `order` table with the code: OrderRepository writes
-- total_price and does not set the legacy `amount` column. Idempotent. (order has 0 rows.)
USE haarlem_festival;

SET @db := DATABASE();

SET @has := (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = @db AND table_name = 'order' AND column_name = 'total_price');
SET @sql := IF(@has = 0, 'ALTER TABLE `order` ADD COLUMN total_price DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER phone', 'SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- Legacy `amount` column (from another branch) is NOT NULL with no default; give it a
-- default so inserts that only set total_price succeed.
SET @has := (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = @db AND table_name = 'order' AND column_name = 'amount');
SET @sql := IF(@has > 0, 'ALTER TABLE `order` MODIFY COLUMN amount DECIMAL(10,2) NOT NULL DEFAULT 0', 'SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;
