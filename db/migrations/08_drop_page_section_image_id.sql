-- ============================================================
-- 08_drop_page_section_image_id.sql
-- Remove page_section.image_id and keep section images in content JSON.
-- Safe to run multiple times.
-- ============================================================
USE haarlem_festival;

SET @has_fk := (
  SELECT COUNT(*)
  FROM information_schema.TABLE_CONSTRAINTS
  WHERE CONSTRAINT_SCHEMA = DATABASE()
    AND TABLE_NAME = 'page_section'
    AND CONSTRAINT_NAME = 'fk_section_image'
);
SET @drop_fk_sql := IF(@has_fk > 0,
  'ALTER TABLE page_section DROP FOREIGN KEY fk_section_image',
  'SELECT 1'
);
PREPARE stmt FROM @drop_fk_sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @has_index := (
  SELECT COUNT(*)
  FROM information_schema.STATISTICS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'page_section'
    AND INDEX_NAME = 'fk_section_image'
);
SET @drop_index_sql := IF(@has_index > 0,
  'ALTER TABLE page_section DROP INDEX fk_section_image',
  'SELECT 1'
);
PREPARE stmt FROM @drop_index_sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @has_column := (
  SELECT COUNT(*)
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'page_section'
    AND COLUMN_NAME = 'image_id'
);
SET @drop_column_sql := IF(@has_column > 0,
  'ALTER TABLE page_section DROP COLUMN image_id',
  'SELECT 1'
);
PREPARE stmt FROM @drop_column_sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
