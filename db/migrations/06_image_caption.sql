-- caption column is included in 01_schema.sql for fresh installs.
-- This migration only adds it for legacy databases created before that change.
USE haarlem_festival;

SET @has_caption := (
  SELECT COUNT(*)
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'image'
    AND COLUMN_NAME = 'caption'
);
SET @add_caption_sql := IF(@has_caption = 0,
  'ALTER TABLE image ADD COLUMN caption VARCHAR(255) NULL AFTER alt_text',
  'SELECT 1'
);
PREPARE stmt FROM @add_caption_sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
