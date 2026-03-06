-- ============================================================
-- 01_drop_all.sql
-- Explicit destructive reset for local/dev usage.
-- ============================================================

SET NAMES utf8mb4;
SET time_zone = '+00:00';

CREATE DATABASE IF NOT EXISTS haarlem_festival
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE haarlem_festival;

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
