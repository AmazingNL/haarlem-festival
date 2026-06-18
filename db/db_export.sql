/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-12.0.2-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: haarlem_festival
-- ------------------------------------------------------
-- Server version	12.0.2-MariaDB-ubu2404

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Current Database: `haarlem_festival`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `haarlem_festival` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;

USE `haarlem_festival`;

--
-- Table structure for table `dance_artist`
--

DROP TABLE IF EXISTS `dance_artist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `dance_artist` (
  `dance_artist_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(120) NOT NULL,
  `name` varchar(120) NOT NULL,
  `genre` varchar(120) NOT NULL,
  `short_description` text NOT NULL,
  `biography` text DEFAULT NULL,
  `career_highlights` text DEFAULT NULL,
  `gallery_images` longtext DEFAULT NULL,
  `image_path` varchar(500) NOT NULL DEFAULT '/assets/images/home/home-dance.jpg',
  `image_alt` varchar(255) NOT NULL,
  `latest_event_id` bigint(20) unsigned DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`dance_artist_id`),
  UNIQUE KEY `uq_dance_artist_slug` (`slug`),
  KEY `idx_dance_artist_published_sort` (`is_published`,`sort_order`,`name`),
  KEY `idx_dance_artist_latest_event` (`latest_event_id`),
  CONSTRAINT `fk_dance_artist_latest_event` FOREIGN KEY (`latest_event_id`) REFERENCES `event` (`event_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dance_artist`
--

LOCK TABLES `dance_artist` WRITE;
/*!40000 ALTER TABLE `dance_artist` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `dance_artist` VALUES
(1,'hardwell','Hardwell','Big-room / electro house','Hardwell brings high-pressure drops, festival-sized melodies, and a sharp mainstage sound to Haarlem Dance.','Hardwell is known for high-impact mainstage sets that blend big-room power, electro house pressure, and festival-sized melodies. His Haarlem Dance appearance brings that peak-hour sound into a compact city festival setting.','Built an international reputation with explosive big-room sets.\nKnown for dramatic builds, punchy drops, and crowd-led moments.\nReturns to the Dance programme across club and back-to-back sessions.','[\"/assets/images/dance/gallery/hardwell-1.jpg\", \"/assets/images/dance/gallery/hardwell-2.jpg\", \"/assets/images/dance/gallery/hardwell-3.jpg\"]','/assets/images/dance/hardwell.jpg','Hardwell performing at Haarlem Dance',13,1,1,'2026-06-18 19:52:26','2026-06-18 19:52:27'),
(2,'armin-van-buuren','Armin van Buuren','Trance','Armin van Buuren blends euphoric trance hooks with a polished club set built for a late-night Haarlem crowd.','Armin van Buuren brings a trance-driven sound shaped around emotion, momentum, and clean melodic release. His Dance sessions are built for visitors who want a polished late-night set with a euphoric edge.','A defining name in modern trance and festival dance music.\nRecognized for long-form sets with emotional build and release.\nAppears in both club and Sunday Dance sessions.','[\"/assets/images/dance/gallery/armin-van-buuren-1.jpg\", \"/assets/images/dance/gallery/armin-van-buuren-2.jpg\", \"/assets/images/dance/gallery/armin-van-buuren-3.jpg\"]','/assets/images/dance/armin-van-buuren.jpg','Armin van Buuren performing at Haarlem Dance',12,2,1,'2026-06-18 19:52:26','2026-06-18 19:52:27'),
(3,'martin-garrix','Martin Garrix','Progressive house','Martin Garrix delivers bright progressive house, punchy drops, and a crowd-first set for Dance weekend.','Martin Garrix pairs bright progressive house with direct festival energy. His Haarlem Dance sets connect radio-ready hooks, sharp drops, and a performance style made for a packed crowd.','Known for progressive house anthems and high-energy festival sets.\nCombines melodic hooks with strong mainstage production.\nFeatured across Friday, Sunday, and back-to-back Dance sessions.','[\"/assets/images/dance/gallery/martin-garrix-1.jpg\", \"/assets/images/dance/gallery/martin-garrix-2.jpg\", \"/assets/images/dance/gallery/martin-garrix-3.jpg\"]','/assets/images/dance/martin-garrix.jpg','Martin Garrix performing at Haarlem Dance',14,3,1,'2026-06-18 19:52:26','2026-06-18 19:52:27'),
(4,'tiesto','Tiësto','EDM / melodic house','Tiësto brings melodic builds, club momentum, and a polished festival sound to the Dance programme.','Tiesto blends melodic house, EDM, and club momentum into a polished performance style. His Haarlem Dance appearances move between focused club sessions and larger back-to-back festival moments.','A long-running global name in electronic dance music.\nMoves easily between club sets and festival-scale sessions.\nAppears in solo and shared Dance programme sessions.','[\"/assets/images/dance/gallery/tiesto-1.jpg\", \"/assets/images/dance/gallery/tiesto-2.jpg\", \"/assets/images/dance/gallery/tiesto-3.jpg\"]','/assets/images/dance/tiesto.jpg','Tiësto performing at Haarlem Dance',11,4,1,'2026-06-18 19:52:26','2026-06-18 19:52:27'),
(5,'nicky-romero','Nicky Romero','Progressive house','Nicky Romero pairs progressive house energy with crisp hooks and a direct connection to the dance floor.','Nicky Romero brings progressive house drive, crisp festival hooks, and a direct connection to the dance floor. His Dance weekend schedule includes solo and back-to-back sets.','Known for progressive house with a clean festival sound.\nBuilds sets around direct crowd energy and melodic lift.\nFeatured in solo and back-to-back Dance sessions.','[\"/assets/images/dance/gallery/nicky-romero-1.jpg\", \"/assets/images/dance/gallery/nicky-romero-2.jpg\", \"/assets/images/dance/gallery/nicky-romero-3.jpg\"]','/assets/images/dance/nicky-romero.jpg','Nicky Romero performing at Haarlem Dance',11,5,1,'2026-06-18 19:52:26','2026-06-18 19:52:27'),
(6,'afrojack','Afrojack','Dutch house / electro house','Afrojack anchors the lineup with Dutch house force, electro edges, and a bold Haarlem festival set.','Afrojack anchors the Dance lineup with Dutch house force, electro edges, and big-room confidence. His related sessions bring both club focus and back-to-back festival scale.','Known for Dutch house, electro house, and bold festival sets.\nBrings a hard-edged sound to the Dance programme.\nAppears in Saturday and back-to-back Dance sessions.','[\"/assets/images/dance/gallery/afrojack-1.jpg\", \"/assets/images/dance/gallery/afrojack-2.jpg\", \"/assets/images/dance/gallery/afrojack-3.jpg\"]','/assets/images/dance/afrojack.jpg','Afrojack performing at Haarlem Dance',11,6,1,'2026-06-18 19:52:26','2026-06-18 19:52:27');
/*!40000 ALTER TABLE `dance_artist` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `dance_artist_event`
--

DROP TABLE IF EXISTS `dance_artist_event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `dance_artist_event` (
  `dance_artist_event_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dance_artist_id` int(10) unsigned NOT NULL,
  `event_id` bigint(20) unsigned NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`dance_artist_event_id`),
  UNIQUE KEY `uq_dance_artist_event` (`dance_artist_id`,`event_id`),
  KEY `idx_dance_artist_event_sort` (`dance_artist_id`,`sort_order`),
  KEY `idx_dance_artist_event_event` (`event_id`),
  CONSTRAINT `fk_dance_artist_event_artist` FOREIGN KEY (`dance_artist_id`) REFERENCES `dance_artist` (`dance_artist_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_dance_artist_event_event` FOREIGN KEY (`event_id`) REFERENCES `event` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dance_artist_event`
--

LOCK TABLES `dance_artist_event` WRITE;
/*!40000 ALTER TABLE `dance_artist_event` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `dance_artist_event` VALUES
(1,1,3,1,'2026-06-18 19:52:26'),
(2,1,4,2,'2026-06-18 19:52:26'),
(3,1,13,3,'2026-06-18 19:52:26'),
(4,2,6,1,'2026-06-18 19:52:26'),
(5,2,4,2,'2026-06-18 19:52:26'),
(6,2,12,3,'2026-06-18 19:52:26'),
(7,3,7,1,'2026-06-18 19:52:26'),
(8,3,4,2,'2026-06-18 19:52:26'),
(9,3,14,3,'2026-06-18 19:52:27'),
(10,4,2,1,'2026-06-18 19:52:27'),
(11,4,9,2,'2026-06-18 19:52:27'),
(12,4,11,3,'2026-06-18 19:52:27'),
(13,5,1,1,'2026-06-18 19:52:27'),
(14,5,10,2,'2026-06-18 19:52:27'),
(15,5,11,3,'2026-06-18 19:52:27'),
(16,6,1,1,'2026-06-18 19:52:27'),
(17,6,8,2,'2026-06-18 19:52:27'),
(18,6,11,3,'2026-06-18 19:52:27');
/*!40000 ALTER TABLE `dance_artist_event` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `event`
--

DROP TABLE IF EXISTS `event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `event` (
  `event_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `location_id` bigint(20) unsigned NOT NULL,
  `image_id` bigint(20) unsigned DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`event_id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `fk_event_location` (`location_id`),
  KEY `fk_event_image` (`image_id`),
  CONSTRAINT `fk_event_image` FOREIGN KEY (`image_id`) REFERENCES `image` (`image_id`) ON DELETE SET NULL,
  CONSTRAINT `fk_event_location` FOREIGN KEY (`location_id`) REFERENCES `location` (`location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event`
--

LOCK TABLES `event` WRITE;
/*!40000 ALTER TABLE `event` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `event` VALUES
(1,'Nicky Romero / Afrojack Dance Session','dance-nicky-romero-afrojack','Official Dance programme: Nicky Romero / Afrojack, Back2Back session at Lichtfabriek.','2026-07-24 20:00:00','2026-07-25 02:00:00',5,NULL,1),
(2,'Tiësto Friday Dance Night','dance-tiesto-friday','Official Dance programme: Tiësto, Club session at Slachthuis.','2026-07-24 22:00:00','2026-07-24 23:30:00',1,NULL,1),
(3,'Hardwell Dance Session','dance-hardwell','Official Dance programme: Hardwell, Club session at Jopenkerk.','2026-07-24 23:00:00','2026-07-25 00:30:00',3,NULL,1),
(4,'Hardwell / Martin Garrix / Armin van Buuren Dance Session','dance-hardwell-martin-garrix-armin-van-buuren','Official Dance programme: Hardwell / Martin Garrix / Armin van Buuren, Back2Back session at Caprera Openluchttheater.','2026-07-25 14:00:00','2026-07-25 23:00:00',2,NULL,1),
(5,'Late Dance Club Session','dance-xo-club-session','A late dance session at XO The Club with festival DJs.','2026-07-29 23:30:00','2026-07-30 01:00:00',4,NULL,0),
(6,'Armin van Buuren Friday Dance Session','dance-armin-van-buuren-friday','Official Dance programme: Armin van Buuren, Club session at XO the Club.','2026-07-24 22:00:00','2026-07-24 23:30:00',4,NULL,1),
(7,'Martin Garrix Friday Dance Session','dance-martin-garrix-friday','Official Dance programme: Martin Garrix, Club session at Puncher comedy club.','2026-07-24 22:00:00','2026-07-24 23:30:00',6,NULL,1),
(8,'Afrojack Saturday Dance Session','dance-afrojack-saturday','Official Dance programme: Afrojack, Club session at Jopenkerk.','2026-07-25 22:00:00','2026-07-25 23:30:00',3,NULL,1),
(9,'TiëstoWorld Dance Session','dance-tiesto-world','Official Dance programme: TiëstoWorld session at Lichtfabriek.','2026-07-25 21:00:00','2026-07-26 01:00:00',5,NULL,1),
(10,'Nicky Romero Saturday Dance Session','dance-nicky-romero-saturday','Official Dance programme: Nicky Romero, Club session at Slachthuis.','2026-07-25 23:00:00','2026-07-26 00:30:00',1,NULL,1),
(11,'Afrojack / Tiësto / Nicky Romero Dance Session','dance-afrojack-tiesto-nicky-romero','Official Dance programme: Afrojack / Tiësto / Nicky Romero, Back2Back session at Caprera Openluchttheater.','2026-07-26 14:00:00','2026-07-26 23:00:00',2,NULL,1),
(12,'Armin van Buuren Sunday Dance Session','dance-armin-van-buuren-sunday','Official Dance programme: Armin van Buuren, Club session at Jopenkerk.','2026-07-26 19:00:00','2026-07-26 20:30:00',3,NULL,1),
(13,'Hardwell Sunday Dance Session','dance-hardwell-sunday','Official Dance programme: Hardwell, Club session at XO the Club.','2026-07-26 21:00:00','2026-07-26 22:30:00',4,NULL,1),
(14,'Martin Garrix Sunday Dance Session','dance-martin-garrix-sunday','Official Dance programme: Martin Garrix, Club session at Slachthuis.','2026-07-26 18:00:00','2026-07-26 19:30:00',1,NULL,1),
(15,'Jazz Night Live','jazz-night-live','An evening of jazz performances.','2026-07-24 19:30:00','2026-07-24 22:30:00',1,1,1),
(16,'Food & Drink Tour','food-drink-tour','Guided tasting tour through Haarlem.','2026-07-25 12:00:00','2026-07-25 15:00:00',4,2,1),
(17,'Historic City Walk','historic-city-walk','Learn hidden stories.','2026-07-26 10:00:00','2026-07-26 12:00:00',5,3,1),
(18,'Museum After Hours','museum-after-hours','Special evening access.','2026-07-26 18:00:00','2026-07-26 20:00:00',3,NULL,1),
(19,'Classical Matinee','classical-matinee','Afternoon classical concert.','2026-07-27 14:00:00','2026-07-27 16:00:00',2,NULL,1),
(20,'Summer Dance Night','summer-dance-night','An evening of dance performances and DJs in Haarlem.','2026-07-23 20:00:00','2026-07-23 23:00:00',7,NULL,1);
/*!40000 ALTER TABLE `event` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `image`
--

DROP TABLE IF EXISTS `image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `image` (
  `image_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `file_path` varchar(500) NOT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `uploaded_by_user_id` bigint(20) unsigned NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`image_id`),
  KEY `fk_image_uploaded_by` (`uploaded_by_user_id`),
  CONSTRAINT `fk_image_uploaded_by` FOREIGN KEY (`uploaded_by_user_id`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `image`
--

LOCK TABLES `image` WRITE;
/*!40000 ALTER TABLE `image` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `image` VALUES
(1,'/uploads/events/jazz-night.jpg','Jazz Night poster',NULL,1,'2026-06-18 19:52:52'),
(2,'/uploads/events/food-tour.jpg','Food Tour image',NULL,1,'2026-06-18 19:52:52'),
(3,'/uploads/events/history-walk.jpg','History Walk',NULL,1,'2026-06-18 19:52:52'),
(4,'/uploads/profiles/sam.jpg','Sam profile photo',NULL,1,'2026-06-18 19:52:52'),
(5,'/uploads/home/hero-haarlem.jpg','Haarlem hero image',NULL,1,'2026-06-18 19:52:52'),
(6,'/uploads/home/windmill.jpg','Windmill Haarlem',NULL,1,'2026-06-18 19:52:52'),
(7,'/uploads/home/church.jpg','Church Haarlem',NULL,1,'2026-06-18 19:52:52'),
(8,'/uploads/home/grote-markt.jpg','Grote Markt',NULL,1,'2026-06-18 19:52:52'),
(9,'/uploads/home/canal-houses.jpg','Canal Houses',NULL,1,'2026-06-18 19:52:52');
/*!40000 ALTER TABLE `image` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `location`
--

DROP TABLE IF EXISTS `location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `location` (
  `location_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(120) NOT NULL,
  `capacity` int(10) unsigned NOT NULL,
  PRIMARY KEY (`location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `location`
--

LOCK TABLES `location` WRITE;
/*!40000 ALTER TABLE `location` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `location` VALUES
(1,'Slachthuis','Rockplein 6, 2033 KK Haarlem','Haarlem',200),
(2,'Caprera Openluchttheater','Hoge Duin en Daalseweg 2, 2061 AG Bloemendaal','Bloemendaal',2000),
(3,'Jopenkerk','Gedempte Voldersgracht 2, 2011 WD Haarlem','Haarlem',300),
(4,'XO the Club','Grote Markt 8, 2011 RD Haarlem','Haarlem',1500),
(5,'Lichtfabriek','Minckelersweg 2, 2031 EM Haarlem','Haarlem',1500),
(6,'Puncher comedy club','Grote Markt 10, 2011 RD Haarlem','Haarlem',200),
(7,'Patronaat','Zijlsingel 2','Haarlem',1500),
(8,'Philharmonie','Lange Begijnestraat 11','Haarlem',1200),
(9,'Teylers Museum','Spaarne 16','Haarlem',200),
(10,'Jopenkerk','Gedempte Voldersgracht 2','Haarlem',400),
(11,'Kenaupark Start','Kenaupark','Haarlem',300);
/*!40000 ALTER TABLE `location` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `order`
--

DROP TABLE IF EXISTS `order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `order` (
  `order_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','paid','cancelled','expired') NOT NULL DEFAULT 'pending',
  `invoice_number` varchar(50) DEFAULT NULL,
  `invoice_issued_at` datetime DEFAULT NULL,
  `provider` varchar(50) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`order_id`),
  KEY `fk_order_user` (`user_id`),
  CONSTRAINT `fk_order_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order`
--

LOCK TABLES `order` WRITE;
/*!40000 ALTER TABLE `order` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `order` VALUES
(1,3,NULL,NULL,NULL,NULL,92.65,'paid',NULL,NULL,NULL,'2026-06-01 11:05:00');
/*!40000 ALTER TABLE `order` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `order_line`
--

DROP TABLE IF EXISTS `order_line`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_line` (
  `order_line_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `item_type` varchar(50) NOT NULL DEFAULT 'booking',
  `title` varchar(255) NOT NULL,
  `selection_text` varchar(500) DEFAULT NULL,
  `ticket_title` varchar(255) DEFAULT NULL,
  `ticket_summary_text` varchar(500) DEFAULT NULL,
  `quantity` int(10) unsigned NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL,
  `line_total` decimal(10,2) NOT NULL,
  `vat_rate` decimal(5,2) NOT NULL DEFAULT 9.00,
  `location_name` varchar(255) DEFAULT NULL,
  `special_requests` text DEFAULT NULL,
  `event_id` bigint(20) unsigned DEFAULT NULL,
  `ticket_type_id` bigint(20) unsigned DEFAULT NULL,
  `reservation_id` bigint(20) unsigned DEFAULT NULL,
  `item_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`item_data`)),
  PRIMARY KEY (`order_line_id`),
  KEY `idx_order_line_order` (`order_id`),
  KEY `fk_order_line_reservation` (`reservation_id`),
  CONSTRAINT `fk_order_line_order` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_order_line_reservation` FOREIGN KEY (`reservation_id`) REFERENCES `reservation` (`reservation_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_line`
--

LOCK TABLES `order_line` WRITE;
/*!40000 ALTER TABLE `order_line` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `order_line` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `order_ticket`
--

DROP TABLE IF EXISTS `order_ticket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_ticket` (
  `order_ticket_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `ticket_type_id` bigint(20) unsigned DEFAULT NULL,
  `quantity` int(10) unsigned NOT NULL,
  `unit_price_at_purchase` decimal(10,2) NOT NULL,
  PRIMARY KEY (`order_ticket_id`),
  KEY `fk_order_ticket_order` (`order_id`),
  KEY `fk_order_ticket_type` (`ticket_type_id`),
  CONSTRAINT `fk_order_ticket_order` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_order_ticket_type` FOREIGN KEY (`ticket_type_id`) REFERENCES `ticket_type` (`ticket_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_ticket`
--

LOCK TABLES `order_ticket` WRITE;
/*!40000 ALTER TABLE `order_ticket` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `order_ticket` VALUES
(1,1,1,2,25.00),
(2,1,3,1,35.00);
/*!40000 ALTER TABLE `order_ticket` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `page`
--

DROP TABLE IF EXISTS `page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `page` (
  `page_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext DEFAULT NULL,
  `status` enum('draft','published','archived') NOT NULL DEFAULT 'draft',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`page_id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page`
--

LOCK TABLES `page` WRITE;
/*!40000 ALTER TABLE `page` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `page` VALUES
(1,'Stories','stories',NULL,'published','2026-06-18 19:52:24','2026-06-18 19:52:24'),
(2,'History','history',NULL,'published','2026-06-18 19:52:24','2026-06-18 19:52:24'),
(3,'Book Tour','history-book-tour',NULL,'published','2026-06-18 19:52:24','2026-06-18 19:52:24'),
(4,'Route Map','history-route-map',NULL,'published','2026-06-18 19:52:24','2026-06-18 19:52:24'),
(5,'St. Bavo\'s Church','history-st-bavos-church',NULL,'published','2026-06-18 19:52:24','2026-06-18 19:52:24'),
(6,'Molen de Adriaan','history-molen-de-adriaan',NULL,'published','2026-06-18 19:52:24','2026-06-18 19:52:24'),
(7,'Home','home',NULL,'published','2026-06-18 19:52:24','2026-06-18 19:52:24'),
(8,'Yummy','yummy',NULL,'published','2026-06-18 19:52:25','2026-06-18 19:52:25'),
(9,'Ratatouille Food & Wine','ratatouille',NULL,'published','2026-06-18 19:52:25','2026-06-18 19:52:25'),
(10,'Bistro Toujours','bistro-toujours',NULL,'published','2026-06-18 19:52:25','2026-06-18 19:52:25'),
(11,'About','about','<h1>About</h1><p>Festival information and story.</p>','published','2026-06-18 19:52:52','2026-06-18 19:52:52'),
(12,'Contact','contact','<h1>Contact</h1><p>Email us at info@example.com</p>','published','2026-06-18 19:52:52','2026-06-18 19:52:52');
/*!40000 ALTER TABLE `page` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `page_section`
--

DROP TABLE IF EXISTS `page_section`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `page_section` (
  `section_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` bigint(20) unsigned NOT NULL,
  `section_type` enum('cta','text_block','image_text','hero','feature','image_left','image_right','journey','stat','timeline','transport','two_image_row','venue','cards_grid','restaurant_card','welcome_banner','welcome_banner_card','gallery','reservation','stories_hero','what_is_stories','stories_preview','storytelling_schedule','stories_booking','haarlem_unique','haarlem_taste','history_hero','history_timeline','history_gallery','history_featured_locations','history_route','history_info','history_cta','history_page_nav','history_book_tour_hero','history_book_tour_booking','history_book_tour_route','history_book_tour_schedule','history_book_tour_pricing','history_book_tour_notice','history_book_tour_alert','history_route_map_hero','history_route_map_stops','history_route_map_directions','history_route_map_cta','history_st_bavo_hero','history_st_bavo_facts','history_st_bavo_article','history_st_bavo_sidebar','history_st_bavo_route_cta','history_molen_hero','history_molen_facts','history_molen_article','history_molen_sidebar','history_molen_route_cta') NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `button_text` varchar(100) DEFAULT NULL,
  `button_link` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `setting_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`setting_json`)),
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`section_id`),
  KEY `fk_section_page` (`page_id`),
  CONSTRAINT `fk_section_page` FOREIGN KEY (`page_id`) REFERENCES `page` (`page_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_section`
--

LOCK TABLES `page_section` WRITE;
/*!40000 ALTER TABLE `page_section` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `page_section` VALUES
(1,1,'stories_hero','Stories matter','<p>Immerse yourself in captivating tales from the heart of Haarlem. Live performances, legends, and voices that stay with you long after the curtain falls.</p>',NULL,NULL,1,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(2,1,'what_is_stories','The Experience','<p>Stories is a unique festival strand celebrating the art of oral storytelling. Local and international performers take the stage to weave tales of myth, memory, and meaning — inviting audiences of all ages into worlds built entirely from words.</p><p>Whether you are discovering a new genre or returning to a childhood favourite, Stories offers an unforgettable evening under the Haarlem sky.</p><img class=\"wis-image\" src=\"/assets/images/stories/antonio-molinari-22FwbFrPvpU-unsplash.jpg\" alt=\"Audience at a storytelling event\">',NULL,NULL,2,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(3,1,'stories_preview','Take a Peek into the Stories','<div class=\"sp-mosaic\"><img src=\"/assets/images/stories/pexels-cottonbro-7319358.jpg\" alt=\"Performer on stage\"><img src=\"/assets/images/stories/Foto-Mister-Anansi-leert-de-wereld-lachen.jpeg\" alt=\"Mister Anansi\"><img src=\"/assets/images/stories/pexels-jibarofoto-2774556.jpg\" alt=\"Festival crowd\"><img src=\"/assets/images/stories/drama emotion.jpg\" alt=\"Dramatic moment\"><img src=\"/assets/images/stories/MisterAnansiLeendertJansen-1.jpg\" alt=\"Anansi portrait\"></div>',NULL,NULL,3,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(4,1,'storytelling_schedule','Storytelling Schedule','<div class=\"sched-day\" data-day=\"thu\"><div class=\"sched-cards\"><div class=\"sched-card\"><div class=\"sched-card-body\"><div class=\"sched-card-time\">20:00 – 21:30</div><h3 class=\"sched-card-title\">Mister Anansi</h3><div class=\"sched-card-meta\">Patronaat · Dutch &amp; English</div></div></div><div class=\"sched-card\"><div class=\"sched-card-body\"><div class=\"sched-card-time\">22:00 – 23:00</div><h3 class=\"sched-card-title\">Buurderij Haarlem</h3><div class=\"sched-card-meta\">Jopenkerk · Dutch</div></div></div></div></div><div class=\"sched-day\" data-day=\"fri\"><div class=\"sched-cards\"><div class=\"sched-card\"><div class=\"sched-card-body\"><div class=\"sched-card-time\">19:30 – 21:00</div><h3 class=\"sched-card-title\">The Sea Witch</h3><div class=\"sched-card-meta\">Teylers Museum · English</div></div></div><div class=\"sched-card\"><div class=\"sched-card-body\"><div class=\"sched-card-time\">21:30 – 23:00</div><h3 class=\"sched-card-title\">Corrie ten Boom: Her Story</h3><div class=\"sched-card-meta\">Grote Kerk · Dutch &amp; English</div></div></div></div></div><div class=\"sched-day\" data-day=\"sat\"><div class=\"sched-cards\"><div class=\"sched-card\"><div class=\"sched-card-body\"><div class=\"sched-card-time\">15:00 – 16:30</div><h3 class=\"sched-card-title\">Children\'s Tales</h3><div class=\"sched-card-meta\">Kenaupark · Dutch</div></div></div><div class=\"sched-card\"><div class=\"sched-card-body\"><div class=\"sched-card-time\">20:30 – 22:00</div><h3 class=\"sched-card-title\">Mister Anansi</h3><div class=\"sched-card-meta\">Patronaat · Dutch &amp; English</div></div></div></div></div><div class=\"sched-day\" data-day=\"sun\"><div class=\"sched-cards\"><div class=\"sched-card\"><div class=\"sched-card-body\"><div class=\"sched-card-time\">14:00 – 15:30</div><h3 class=\"sched-card-title\">Closing Stories</h3><div class=\"sched-card-meta\">Philharmonie · Dutch &amp; English</div></div></div></div></div>',NULL,NULL,4,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(5,2,'history_hero',NULL,'{\"title_line_one\": \"Discover\", \"title_line_two\": \"Haarlem\", \"intro\": \"Walk through 800 years of Dutch history in one unforgettable journey. From majestic churches to hidden courtyards, every step reveals a story.\", \"primary_button_text\": \"Book Your Adventure\", \"primary_button_link\": \"/history/book-tour\", \"secondary_button_text\": \"Explore Route\", \"secondary_button_link\": \"/history/route-map\", \"hero_image\": \"/assets/images/history/history-hero-banner.jpg\", \"quick_link_one_label\": \"Book Tour\", \"quick_link_one_link\": \"/history/book-tour\", \"quick_link_two_label\": \"Route Map\", \"quick_link_two_link\": \"/history/route-map\", \"quick_link_three_label\": \"St. Bavo\'s Church\", \"quick_link_three_link\": \"/history/st-bavos-church\", \"quick_link_four_label\": \"Molen de Adriaan\", \"quick_link_four_link\": \"/history/molen-de-adriaan\"}',NULL,NULL,1,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(6,2,'history_timeline',NULL,'{\"eyebrow\": \"Our Heritage\", \"heading\": \"The History of Haarlem\", \"item_one_title\": \"Century of Origins\", \"item_one_text\": \"Founded in the 10th century, Haarlem is one of the oldest cities in the Netherlands. Receiving its city rights in 1245, it became a vital center of commerce, art, and culture that would shape Dutch identity for centuries to come.\", \"item_one_text_secondary\": \"Just 20 kilometers west of Amsterdam, Haarlem has always stood as a proud rival and often surpassed the capital in cultural achievements during the Dutch Golden Age.\", \"item_one_image\": \"/assets/images/history/history-century-of-origins.jpg\", \"item_one_label\": \"City Center\", \"item_one_caption\": \"Grote Markt at Golden Hour\", \"item_two_title\": \"The Golden Age\", \"item_two_text\": \"The 17th century saw Haarlem flourish as Europe\'s artistic capital. Home to legendary painters Frans Hals, Jacob van Ruisdael, and Adriaen van Ostade, the Haarlem School of painting rivaled even Amsterdam in prestige.\", \"item_two_text_secondary\": \"The city\'s economy thrived on textile production, earning the nickname \'Linen City\'. This was also the epicenter of history\'s first speculative bubble, the legendary Tulip Mania of 1637.\", \"item_two_image\": \"/assets/images/history/history-golden-age.jpg\", \"item_two_label\": \"Dutch Masters\", \"item_two_caption\": \"Frans Hals and Contemporaries\", \"item_three_title\": \"72-73\", \"item_three_text\": \"The Siege of Haarlem during the Eighty Years\' War remains a defining moment in Dutch history. For seven months, citizens heroically resisted Spanish forces in what became a symbol of Dutch determination for independence.\", \"item_three_text_secondary\": \"Though the city ultimately fell, this resistance inspired the nation and is still commemorated today as a testament to the indomitable Dutch spirit.\", \"item_three_image\": \"/assets/images/history/history-siege-of-haarlem.jpg\", \"item_three_label\": \"Living Heritage\", \"item_three_caption\": \"Market Day Tradition\"}',NULL,NULL,2,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(7,2,'history_gallery',NULL,'{\"eyebrow\": \"Visual Journey\", \"heading\": \"What Awaits You\", \"card_one_label\": \"Hidden Gems\", \"card_one_title\": \"Secret Courtyards\", \"card_one_text\": \"Discover 17th-century hofjes - peaceful gardens hidden behind ancient wooden gates.\", \"card_one_image\": \"/assets/images/history/history-hidden-gems.jpg\", \"card_two_label\": \"Iconic Landmark\", \"card_two_title\": \"Molen de Adriaan\", \"card_two_text\": \"Climb the iconic windmill for panoramic views over the historic Spaarne River.\", \"card_two_image\": \"/assets/images/history/history-molen-sunset.jpg\", \"card_three_label\": \"Gothic Marvel\", \"card_three_title\": \"Sacred Architecture\", \"card_three_text\": \"Stand beneath soaring Gothic arches where Mozart once played the famous Muller organ.\", \"card_three_image\": \"/assets/images/history/history-sacred-architecture.jpg\"}',NULL,NULL,3,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(8,2,'history_featured_locations',NULL,'{\"eyebrow\": \"Must-See Destinations\", \"heading\": \"Featured Locations\", \"intro\": \"From medieval churches to world-class museums, each stop on our tour reveals centuries of Dutch heritage.\", \"one_label\": \"Tour Highlight\", \"one_title\": \"Grote Kerk (St. Bavo\'s Church)\", \"one_text\": \"The magnificent Gothic cathedral has dominated Haarlem\'s skyline for over 500 years. Its tower is visible across the region and the church houses the famous Muller organ once played by Mozart.\", \"one_badge\": \"Est. 1520\", \"one_image\": \"/assets/images/history/history-grote-kerk.jpg\", \"one_feature_one\": \"78m Tower Height\", \"one_feature_two\": \"Famous Muller Organ\", \"one_feature_three\": \"Gothic Architecture\", \"one_feature_four\": \"Frans Hals Tomb\", \"one_button_text\": \"Explore This Location\", \"one_button_link\": \"/history/st-bavos-church\", \"two_label\": \"Iconic Landmark\", \"two_title\": \"Molen de Adriaan\", \"two_text\": \"This iconic Dutch windmill stands proudly on the banks of the Spaarne River. Originally built in 1779, it was reconstructed in 2002 and now serves as a working museum.\", \"two_badge\": \"Est. 1779\", \"two_image\": \"/assets/images/history/history-molen-de-adriaan.jpg\", \"two_feature_one\": \"Built 1779\", \"two_feature_two\": \"Panoramic Views\", \"two_feature_three\": \"Working Museum\", \"two_feature_four\": \"Spaarne River\", \"two_button_text\": \"Explore This Location\", \"two_button_link\": \"/history/molen-de-adriaan\"}',NULL,NULL,4,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(9,2,'history_route',NULL,'{\"eyebrow\": \"Complete Walking Route\", \"heading\": \"9 Remarkable Venues\", \"intro\": \"Each stop tells a unique chapter of Haarlem\'s rich 800-year story.\", \"venue_one_title\": \"Grote Kerk\", \"venue_one_text\": \"15th-century Gothic cathedral\", \"venue_one_link\": \"/history/st-bavos-church\", \"venue_two_title\": \"Grote Markt\", \"venue_two_text\": \"Historic market square since medieval times\", \"venue_three_title\": \"De Hallen\", \"venue_three_text\": \"Frans Hals Museum in 17th-century almshouses\", \"venue_four_title\": \"Proveniershof\", \"venue_four_text\": \"Beautiful hidden courtyard garden\", \"venue_five_title\": \"Jopenkerk\", \"venue_five_text\": \"Brewery in a converted 15th-century church\", \"venue_five_badge\": \"Break\", \"venue_six_title\": \"Waalse Kerk\", \"venue_six_text\": \"16th-century French Reformed church\", \"venue_seven_title\": \"Molen de Adriaan\", \"venue_seven_text\": \"Iconic windmill on the Spaarne River\", \"venue_seven_link\": \"/history/molen-de-adriaan\", \"venue_eight_title\": \"Amsterdamse Poort\", \"venue_eight_text\": \"Last remaining medieval city gate\", \"venue_nine_title\": \"Hof van Bakenes\", \"venue_nine_text\": \"Oldest hofje in Haarlem, founded 1395\", \"button_text\": \"Explore Route\", \"button_link\": \"/history/route-map\"}',NULL,NULL,5,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(10,2,'history_info',NULL,'{\"item_one_value\": \"2.5 Hours\", \"item_one_label\": \"Tour Duration\", \"item_two_value\": \"Max 12\", \"item_two_label\": \"Group Size\", \"item_three_value\": \"Bavo Church\", \"item_three_label\": \"Start Point\", \"item_four_value\": \"Thu - Sun\", \"item_four_label\": \"Available Days\"}',NULL,NULL,6,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(11,2,'history_cta',NULL,'{\"eyebrow\": \"Your Adventure Awaits\", \"title_line_one\": \"Ready to\", \"title_line_two\": \"Explore?\", \"body\": \"Book your walking tour today and discover why Haarlem has been captivating visitors for centuries. Experience history come alive through our expert-guided tours.\", \"background_image\": \"/assets/images/history/history-ready-to-explore.jpg\", \"primary_button_text\": \"Book Your Adventure\", \"primary_button_link\": \"/history/book-tour\", \"secondary_button_text\": \"Explore Route\", \"secondary_button_link\": \"/history/route-map\"}',NULL,NULL,7,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(12,3,'history_book_tour_hero',NULL,'{\"eyebrow\": \"Last Weekend of July\", \"heading\": \"Book Your Adventure\", \"intro\": \"2.5-hour guided walking tour through Haarlem\'s historic center\", \"stat_one\": \"2.5 hours\", \"stat_two\": \"9 locations\", \"stat_three\": \"Max 12 per group\", \"stat_four\": \"From €17.50\"}',NULL,NULL,1,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(13,3,'history_book_tour_booking',NULL,'{\"heading\": \"Book Your Adventure\", \"intro\": \"Select date, time, language, and ticket type\", \"day_label\": \"Select Day\", \"day_one\": \"Thursday\", \"day_two\": \"Friday\", \"day_three\": \"Saturday\", \"day_four\": \"Sunday\", \"time_label\": \"Select Time\", \"time_one\": \"10:00\", \"time_two\": \"13:00\", \"time_three\": \"16:00\", \"language_label\": \"Select Language\", \"language_one\": \"English\", \"language_two\": \"Dutch\", \"language_three\": \"Mandarin\", \"ticket_label\": \"Ticket Type\", \"individual_title\": \"Individual\", \"individual_price\": \"€17.50/person\", \"family_title\": \"Family\", \"family_price\": \"€60 for up to 4\", \"family_badge\": \"Best Value\", \"selection_label\": \"Selection\", \"ticket_summary_label\": \"Ticket\", \"total_label\": \"Total\", \"total_value\": \"€60.00\", \"saving_note\": \"Save €10.00 vs individual tickets!\", \"quantity_label\": \"Group Size\", \"quantity_value\": \"1 group\", \"button_text\": \"Add to My Program\", \"button_link\": \"#\"}',NULL,NULL,2,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:25'),
(14,3,'history_book_tour_route',NULL,'{\"heading\": \"Tour Route\", \"stop_one_title\": \"Church of St. Bavo\", \"stop_one_text\": \"Gothic masterpiece with famous Muller organ\", \"stop_one_time\": \"20 m\", \"stop_two_title\": \"Grote Markt\", \"stop_two_text\": \"Historic main square and heart of Haarlem\", \"stop_two_time\": \"15 m\", \"stop_three_title\": \"De Hallen\", \"stop_three_text\": \"Contemporary art museum in historic building\", \"stop_three_time\": \"15 m\", \"stop_four_title\": \"Proveniershof\", \"stop_four_text\": \"Beautiful 17th-century almshouse courtyard\", \"stop_four_time\": \"10 m\", \"stop_five_title\": \"Jopenkerk\", \"stop_five_text\": \"Historic church turned craft brewery\", \"stop_five_time\": \"15 m\", \"stop_five_badge\": \"Break\", \"stop_six_title\": \"Waalse Kerk\", \"stop_six_text\": \"Charming Walloon church with rich history\", \"stop_six_time\": \"10 m\", \"stop_seven_title\": \"Molen de Adriaan\", \"stop_seven_text\": \"Iconic windmill with panoramic city views\", \"stop_seven_time\": \"15 m\", \"stop_eight_title\": \"Amsterdamse Poort\", \"stop_eight_text\": \"Medieval city gate, built in 1400\", \"stop_eight_time\": \"10 m\", \"stop_nine_title\": \"Hof van Bakenes\", \"stop_nine_text\": \"One of Haarlem\'s oldest hofjes from 1395\", \"stop_nine_time\": \"10 m\", \"total_label\": \"Total duration\", \"total_value\": \"2.5 hours\", \"total_note\": \"5 min break + 1 drink\", \"meeting_title\": \"Meeting Point\", \"meeting_text\": \"Bavo Church, Grote Markt - Arrive 10 min early\", \"button_text\": \"Explore Route\", \"button_link\": \"/history/route-map\"}',NULL,NULL,3,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(15,3,'history_book_tour_schedule',NULL,'{\"heading\": \"Full Schedule\", \"intro\": \"All available tours during the festival\", \"day_one\": \"Thursday\", \"day_two\": \"Friday\", \"day_three\": \"Saturday\", \"day_four\": \"Sunday\", \"guide_label\": \"Guides on duty\", \"time_one\": \"10:00\", \"time_two\": \"13:00\", \"time_three\": \"16:00\", \"default_day\": \"Thursday\", \"day_one_time_one_guides\": \"Jan-Willem (Dutch), Frederic (English)\", \"day_one_time_two_guides\": \"Jan-Willem (Dutch), Frederic (English)\", \"day_one_time_three_guides\": \"Jan-Willem (Dutch), Frederic (English)\", \"day_two_time_one_guides\": \"Annet (Dutch), Williams (English)\", \"day_two_time_two_guides\": \"Annet (Dutch), Williams (English), Kim (Chinese)\", \"day_two_time_three_guides\": \"Annet (Dutch), Williams (English)\", \"day_three_time_one_guides\": \"Annet + Jan-Willem (Dutch), Frederic + William (English)\", \"day_three_time_two_guides\": \"Annet + Jan-Willem (Dutch), Frederic + William (English), Kim (Mandarin)\", \"day_three_time_three_guides\": \"Jan-Willem (Dutch), Frederic (English), Kim (Mandarin)\", \"day_four_time_one_guides\": \"Lisa + Annet (Dutch), Deirdre + Frederic (English), Kim (Mandarin)\", \"day_four_time_two_guides\": \"Lisa + Annet + Jan-Willem (Dutch), Deirdre + Frederic + William (English), Kim + Susan (Mandarin)\", \"day_four_time_three_guides\": \"Jan-Willem (Dutch), William (English)\"}',NULL,NULL,4,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:25'),
(16,3,'history_book_tour_pricing',NULL,'{\"heading\": \"Pricing\", \"intro\": \"Reservation is mandatory for all tours\", \"left_title\": \"Individual\", \"left_subtitle\": \"Per person\", \"left_price\": \"€17.50\", \"left_feature_one\": \"2.5-hour guided tour\", \"left_feature_two\": \"One drink included\", \"right_badge\": \"Best Value\", \"right_title\": \"Family\", \"right_subtitle\": \"Up to 4\", \"right_price\": \"€60.00\", \"right_note\": \"Save €10\", \"right_feature_one\": \"Up to 4 family members\", \"right_feature_two\": \"Drinks for everyone\"}',NULL,NULL,5,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(17,3,'history_book_tour_notice',NULL,'{\"title\": \"Important Remarks\", \"body\": \"Participants must be minimum 12 years old. No strollers allowed. Groups consist of 12 participants + 1 guide.\"}',NULL,NULL,6,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(18,3,'history_book_tour_alert',NULL,'{\"title\": \"Reservation Required\", \"body\": \"Walk-ins are not accepted. Please arrive 10 minutes before departure at Bavo Church, Grote Markt.\"}',NULL,NULL,7,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(19,4,'history_route_map_hero',NULL,'{\"eyebrow\": \"Explore the Route\", \"heading\": \"Walking Tour Map\", \"intro\": \"Follow our carefully curated route through Haarlem\'s most significant historical landmarks.\", \"back_text\": \"Back to History\", \"back_link\": \"/history\"}',NULL,NULL,1,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(20,4,'history_route_map_stops',NULL,'{\"heading\": \"Tour Stops\", \"summary_one\": \"9 venues to visit\", \"summary_two\": \"2.5 hours (incl. 15-min break)\", \"summary_three\": \"Start: Bavo Church\", \"legend_start\": \"Start Point\", \"legend_break\": \"Break (15 min)\", \"legend_stop\": \"Tour Stop / End\", \"stop_one_title\": \"Church of St. Bavo\", \"stop_one_map_label\": \"St. Bavo\", \"stop_one_text\": \"Gothic masterpiece with famous Muller organ\", \"stop_one_time\": \"20 min\", \"stop_one_tag\": \"Start\", \"stop_two_title\": \"Grote Markt\", \"stop_two_map_label\": \"Grote Markt\", \"stop_two_text\": \"Historic main square and heart of Haarlem\", \"stop_two_time\": \"10 min\", \"stop_three_title\": \"De Hallen\", \"stop_three_map_label\": \"De Hallen\", \"stop_three_text\": \"Contemporary art museum in historic building\", \"stop_three_time\": \"15 min\", \"stop_four_title\": \"Proveniershof\", \"stop_four_map_label\": \"Proveniershof\", \"stop_four_text\": \"Beautiful 17th-century almshouse courtyard\", \"stop_four_time\": \"10 min\", \"stop_five_title\": \"Jopenkerk\", \"stop_five_map_label\": \"Jopenkerk\", \"stop_five_text\": \"Historic church turned craft brewery\", \"stop_five_time\": \"15 min (break)\", \"stop_five_tag\": \"Break\", \"stop_six_title\": \"Waalse Kerk\", \"stop_six_map_label\": \"Waalse Kerk\", \"stop_six_text\": \"Charming Walloon church with rich history\", \"stop_six_time\": \"10 min\", \"stop_seven_title\": \"Molen de Adriaan\", \"stop_seven_map_label\": \"De Adriaan\", \"stop_seven_text\": \"Iconic windmill with panoramic city views\", \"stop_seven_time\": \"15 min\", \"stop_eight_title\": \"Amsterdamse Poort\", \"stop_eight_map_label\": \"Amst. Poort\", \"stop_eight_text\": \"Medieval city gate and fortification\", \"stop_eight_time\": \"10 min\", \"stop_nine_title\": \"Hof van Bakenes\", \"stop_nine_map_label\": \"Hof v. Bakenes\", \"stop_nine_text\": \"One of Haarlem\'s oldest hofjes from 1395\", \"stop_nine_time\": \"10 min\", \"stop_nine_tag\": \"End\", \"stop_one_tone\": \"start\", \"stop_two_tone\": \"stop\", \"stop_three_tone\": \"stop\", \"stop_four_tone\": \"stop\", \"stop_five_tone\": \"break\", \"stop_six_tone\": \"stop\", \"stop_seven_tone\": \"stop\", \"stop_eight_tone\": \"stop\", \"stop_nine_tone\": \"end\"}',NULL,NULL,2,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(21,4,'history_route_map_directions',NULL,'{\"heading\": \"Walking Directions\", \"intro\": \"Follow the route at your own pace or join one of our guided tours for expert commentary.\", \"step_one_title\": \"Church of St. Bavo\", \"step_one_text\": \"Begin your journey at Haarlem\'s main square, in front of the magnificent Church of St. Bavo.\", \"step_two_title\": \"Follow the Route\", \"step_two_text\": \"Walk the 2.5km route through cobblestone streets, past hidden hofjes and along scenic canals.\", \"step_three_title\": \"Hof van Bakenes\", \"step_three_text\": \"Conclude your tour at the medieval city gate, a reminder of Haarlem\'s fortified past.\"}',NULL,NULL,3,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(22,4,'history_route_map_cta',NULL,'{\"heading\": \"Ready to Walk Through History?\", \"body\": \"Book a guided tour with our expert local historians\", \"button_text\": \"Book Your Adventure\", \"button_link\": \"/history/book-tour\"}',NULL,NULL,4,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(23,5,'history_st_bavo_hero',NULL,'{\"back_text\": \"Back to History\", \"back_link\": \"/history\", \"eyebrow\": \"Featured Location\", \"heading\": \"Church of St. Bavo\", \"subtitle\": \"St. Bavokerk - The Heart of Haarlem\", \"hero_image\": \"/assets/images/history/history-st-bavo-hero.jpg\"}',NULL,NULL,1,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(24,5,'history_st_bavo_facts',NULL,'{\"fact_one_label\": \"Address\", \"fact_one_value\": \"Grote Markt 22, Haarlem\", \"fact_two_label\": \"Built\", \"fact_two_value\": \"1370-1520\", \"fact_three_label\": \"Open Daily\", \"fact_three_value\": \"10:00 - 17:00\", \"fact_four_label\": \"Style\", \"fact_four_value\": \"Gothic\"}',NULL,NULL,2,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(25,5,'history_st_bavo_article',NULL,'{\"heading\": \"A Monument to Haarlem\'s Golden Age\", \"intro_one\": \"Rising majestically above Haarlem\'s Grote Markt, the Grote Kerk, officially known as St. Bavokerk, stands as one of the most impressive Gothic churches in the Netherlands. This architectural masterpiece has been the spiritual and cultural heart of Haarlem for over five centuries, witnessing the city\'s transformation from a medieval trading hub to a center of Dutch Golden Age prosperity.\", \"intro_two\": \"The church\'s construction began in 1370 and continued for nearly 150 years, with the distinctive tower completed in 1520. The building showcases the transition from Brabantine Gothic to a uniquely Dutch interpretation of the style, characterized by its soaring wooden vault spanning 25 meters high and intricate brick detailing.\", \"gallery_heading\": \"Gallery\", \"gallery_one_image\": \"/assets/images/history/history-st-bavo-organ.jpg\", \"gallery_one_caption\": \"The famous Muller organ, played by Mozart in 1766\", \"gallery_two_image\": \"/assets/images/history/history-st-bavo-historic.jpg\", \"gallery_two_caption\": \"Historic view of the church, 19th century\", \"gallery_three_image\": \"/assets/images/history/history-st-bavo-exterior.jpg\", \"gallery_three_caption\": \"The imposing exterior dominates Haarlem\'s main square\", \"significance_heading\": \"Historical Significance\", \"significance_one\": \"The Grote Kerk holds immense historical importance for both Haarlem and the Netherlands as a whole. Originally a Catholic church dedicated to St. Bavo, patron saint of Haarlem, it transitioned to Protestantism during the Reformation in 1578. This shift marked a turning point in the church\'s history, as much of its Catholic ornamentation was removed, though its architectural grandeur remained intact.\", \"significance_two\": \"The church\'s most celebrated feature is the magnificent Christian Muller organ, installed in 1738. This baroque masterpiece contains 5,068 pipes and is considered one of the finest organs in the world. A young Wolfgang Amadeus Mozart played this very instrument during his visit in 1766, at the age of just ten years old.\", \"significance_three\": \"Notable figures buried within the church include Frans Hals, Haarlem\'s most famous painter and master of the Dutch Golden Age portrait. The church floor contains approximately 1,500 gravestones, offering a poignant reminder of the generations who called Haarlem home.\", \"importance_heading\": \"Importance to Haarlem\", \"importance_one\": \"The Grote Kerk is not merely a building - it is the symbol of Haarlem itself. Its distinctive silhouette appears on the city\'s coat of arms and has been immortalized in countless paintings, including works by Pieter Saenredam and Gerrit Berckheyde. The church\'s tower, visible from nearly every point in the city, has served as a navigational landmark for travelers approaching Haarlem for centuries.\", \"importance_two\": \"Today, the church serves both as an active Protestant parish and as a cultural venue hosting concerts, exhibitions, and events. Its exceptional acoustics make it a preferred location for classical music performances, particularly organ recitals that showcase the Muller organ\'s extraordinary capabilities.\", \"importance_three\": \"As part of our walking tour, the Grote Kerk represents the spiritual and artistic zenith of Haarlem\'s heritage - a place where history, faith, and art converge in one of the Netherlands\' most magnificent sacred spaces.\"}',NULL,NULL,3,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(26,5,'history_st_bavo_sidebar',NULL,'{\"map_heading\": \"Find Grote Kerk\", \"map_address\": \"Grote Markt 22, 2011 RD Haarlem\", \"map_image\": \"/assets/images/history/history-st-bavo-map.jpg\", \"map_link_text\": \"Open in OpenStreetMap ->\", \"map_link_url\": \"https://www.openstreetmap.org/?mlat=52.38131&mlon=4.63695#map=18/52.38131/4.63695\", \"details_heading\": \"Location Details\", \"full_address_label\": \"Full Address\", \"full_address_value\": \"Grote Markt 22\\n2011 RD Haarlem\\nNetherlands\", \"construction_label\": \"Construction Period\", \"construction_value\": \"1370 - 1520 (150 years)\", \"style_label\": \"Architectural Style\", \"style_value\": \"Brabantine Gothic\", \"purpose_label\": \"Original Purpose\", \"purpose_value\": \"Catholic Parish Church\", \"function_label\": \"Current Function\", \"function_value\": \"Protestant Church & Cultural Venue\", \"opening_label\": \"Opening Hours\", \"opening_value\": \"Mon-Sat: 10:00 - 17:00\\nSun: 12:00 - 17:00\", \"facts_heading\": \"Did You Know?\", \"fact_one\": \"Mozart played the Muller organ here at age 10\", \"fact_two\": \"The wooden vault spans 25 meters high\", \"fact_three\": \"Frans Hals is buried in the church floor\", \"fact_four\": \"The organ contains 5,068 pipes\", \"fact_five\": \"The church appears on Haarlem\'s coat of arms\", \"tour_heading\": \"Visit on Our Tour\", \"tour_text\": \"Experience the Grote Kerk with our expert guides who bring its history to life.\", \"tour_button_text\": \"Book Your Adventure\", \"tour_button_link\": \"/history/book-tour\"}',NULL,NULL,4,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(27,5,'history_st_bavo_route_cta',NULL,'{\"heading\": \"Find It on the Route\", \"body\": \"The Grote Kerk is stop #1 on our walking tour, located in the heart of Haarlem\'s historic center.\", \"button_text\": \"View Interactive Route Map\", \"button_link\": \"/history/route-map\"}',NULL,NULL,5,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(28,6,'history_molen_hero',NULL,'{\"back_text\": \"Back to History\", \"back_link\": \"/history\", \"eyebrow\": \"Featured Location\", \"heading\": \"Molen de Adriaan\", \"subtitle\": \"The Iconic Windmill of Haarlem\", \"hero_image\": \"/assets/images/history/history-molen-hero.jpg\"}',NULL,NULL,1,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(29,6,'history_molen_facts',NULL,'{\"fact_one_label\": \"Address\", \"fact_one_value\": \"Papentorenvest 1A\", \"fact_two_label\": \"Built\", \"fact_two_value\": \"1779\", \"fact_three_label\": \"Open\", \"fact_three_value\": \"Mon-Sat 13:00-17:00\", \"fact_four_label\": \"Type\", \"fact_four_value\": \"Tower Mill\"}',NULL,NULL,2,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(30,6,'history_molen_article',NULL,'{\"heading\": \"A Symbol of Dutch Heritage\", \"intro_one\": \"Standing proudly on the banks of the Spaarne river, Molen de Adriaan is one of Haarlem\'s most photographed landmarks. This magnificent tower mill has become an iconic symbol of the city, offering visitors a unique glimpse into the Netherlands\' rich milling heritage and providing breathtaking panoramic views of Haarlem from its gallery.\", \"intro_two\": \"Originally built in 1779, the mill was named after Adriaan de Boois, a wealthy merchant who commissioned its construction. Throughout its history, the windmill has served various purposes - grinding tobacco, chalk, and tanbark used in leather production. Today, it operates as a museum and continues to grind grain on windy days.\", \"gallery_heading\": \"Gallery\", \"gallery_one_image\": \"/assets/images/history/history-molen-machinery.jpg\", \"gallery_one_caption\": \"Original wooden gears and milling machinery\", \"gallery_two_image\": \"/assets/images/history/history-molen-historic.jpg\", \"gallery_two_caption\": \"The windmill in its original form, early 20th century\", \"gallery_three_image\": \"/assets/images/history/history-molen-spaarne.jpg\", \"gallery_three_caption\": \"The restored windmill overlooking the Spaarne river\", \"significance_heading\": \"Historical Significance\", \"significance_one\": \"Molen de Adriaan has witnessed Haarlem\'s evolution over nearly 250 years. The mill played a vital role in the city\'s industrial economy, particularly during the 19th century when Haarlem was a center for various trades and manufacturing.\", \"significance_two\": \"Tragedy struck on April 23, 1932, when a devastating fire destroyed the original windmill. For decades, only the stone base remained - a silent reminder of what had been lost. However, the people of Haarlem never forgot their beloved landmark.\", \"significance_three\": \"In 1999, a dedicated foundation began raising funds to rebuild the mill. Through the tireless efforts of volunteers and generous donations from Haarlem residents and businesses, Molen de Adriaan was meticulously reconstructed using traditional methods. The restored mill was officially reopened in 2002, exactly 70 years after the fire.\", \"importance_heading\": \"Importance to Haarlem\", \"importance_one\": \"Molen de Adriaan represents more than just a historical structure - it embodies the Dutch spirit of perseverance and community. The successful reconstruction demonstrates Haarlem\'s commitment to preserving its cultural heritage for future generations.\", \"importance_two\": \"Today, the windmill serves as both a museum and an educational center. Visitors can explore five floors of exhibits about the history of windmills in the Netherlands, watch the mill\'s mechanisms in action, and enjoy spectacular 360-degree views of Haarlem from the outdoor gallery.\", \"importance_three\": \"The mill has become one of Haarlem\'s most popular attractions, drawing visitors from around the world who come to experience this quintessentially Dutch icon. Its silhouette against the Haarlem skyline remains one of the city\'s most recognizable and beloved images.\"}',NULL,NULL,3,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(31,6,'history_molen_sidebar',NULL,'{\"map_heading\": \"Find Molen de Adriaan\", \"map_address\": \"Papentorenvest 1A, 2011 AV Haarlem\", \"map_image\": \"/assets/images/history/history-molen-map.jpg\", \"map_link_text\": \"Open in OpenStreetMap ->\", \"map_link_url\": \"https://www.openstreetmap.org/search?query=Papentorenvest%201A%20Haarlem\", \"details_heading\": \"Location Details\", \"full_address_label\": \"Full Address\", \"full_address_value\": \"Papentorenvest 1A\\n2011 AV Haarlem\\nNetherlands\", \"construction_label\": \"Original Construction\", \"construction_value\": \"1779\", \"reconstruction_label\": \"Reconstruction\", \"reconstruction_value\": \"1999-2002\", \"type_label\": \"Mill Type\", \"type_value\": \"Tower Mill (Stellingmolen)\", \"height_label\": \"Height\", \"height_value\": \"Approximately 20 meters\", \"opening_label\": \"Opening Hours\", \"opening_value\": \"Mon-Sat: 13:00 - 17:00\\nSun: 12:00 - 17:00\\n(March - October)\", \"facts_heading\": \"Did You Know?\", \"fact_one\": \"The mill was destroyed by fire in 1932\", \"fact_two\": \"It took 3 years and EUR 2 million to rebuild\", \"fact_three\": \"The sails span over 26 meters across\", \"fact_four\": \"It still grinds grain on windy days\", \"fact_five\": \"5 floors of exhibits await visitors\", \"tour_heading\": \"Visit on Our Tour\", \"tour_text\": \"Experience Molen de Adriaan with our expert guides who share its remarkable story.\", \"tour_button_text\": \"Book Your Adventure\", \"tour_button_link\": \"/history/book-tour\"}',NULL,NULL,4,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(32,6,'history_molen_route_cta',NULL,'{\"heading\": \"Find It on the Route\", \"body\": \"Molen de Adriaan is stop #7 on our walking tour, located along the scenic Spaarne river.\", \"button_text\": \"Explore Route\", \"button_link\": \"/history/route-map\"}',NULL,NULL,5,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(33,3,'history_page_nav',NULL,'{\"book_tour_label\": \"Book Tour\", \"book_tour_link\": \"/history/book-tour\", \"route_map_label\": \"Route Map\", \"route_map_link\": \"/history/route-map\", \"st_bavo_label\": \"St. Bavo\'s Church\", \"st_bavo_link\": \"/history/st-bavos-church\", \"molen_label\": \"Molen de Adriaan\", \"molen_link\": \"/history/molen-de-adriaan\"}',NULL,NULL,1,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(34,6,'history_page_nav',NULL,'{\"book_tour_label\": \"Book Tour\", \"book_tour_link\": \"/history/book-tour\", \"route_map_label\": \"Route Map\", \"route_map_link\": \"/history/route-map\", \"st_bavo_label\": \"St. Bavo\'s Church\", \"st_bavo_link\": \"/history/st-bavos-church\", \"molen_label\": \"Molen de Adriaan\", \"molen_link\": \"/history/molen-de-adriaan\"}',NULL,NULL,1,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(35,4,'history_page_nav',NULL,'{\"book_tour_label\": \"Book Tour\", \"book_tour_link\": \"/history/book-tour\", \"route_map_label\": \"Route Map\", \"route_map_link\": \"/history/route-map\", \"st_bavo_label\": \"St. Bavo\'s Church\", \"st_bavo_link\": \"/history/st-bavos-church\", \"molen_label\": \"Molen de Adriaan\", \"molen_link\": \"/history/molen-de-adriaan\"}',NULL,NULL,1,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(36,5,'history_page_nav',NULL,'{\"book_tour_label\": \"Book Tour\", \"book_tour_link\": \"/history/book-tour\", \"route_map_label\": \"Route Map\", \"route_map_link\": \"/history/route-map\", \"st_bavo_label\": \"St. Bavo\'s Church\", \"st_bavo_link\": \"/history/st-bavos-church\", \"molen_label\": \"Molen de Adriaan\", \"molen_link\": \"/history/molen-de-adriaan\"}',NULL,NULL,1,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(40,7,'hero','Discover Haarlem','{\"heading\": \"Discover Haarlem\", \"hero_image\": \"/assets/images/home/home-hero.jpg\", \"hero_image_alt\": \"View over Haarlem and the Grote Kerk\"}',NULL,NULL,1,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(41,7,'feature','The Heart of Haarlem','{\"title\": \"The Heart of Haarlem\", \"article\": \"Haarlem is one of the Netherlands\' most charming and historic cities, a place where old-world beauty meets modern culture. Known for its cobblestone streets, iconic windmills, and Golden Age architecture, the city offers a warm and welcoming atmosphere for every visitor.\\n\\nWander through lively squares, explore boutique shops, discover hidden courtyards, or relax by the Spaarne River. Haarlem is also a city of creativity and taste, home to award-winning restaurants, vibrant markets, and world-class museums such as the Frans Hals Museum.\\n\\nWhether you\'re here for food, art, history, or festivals, Haarlem invites you to slow down and enjoy its unique charm. Just minutes from Amsterdam and the Dutch coastline, the city blends convenience with authenticity and stays lively all year round with parades, markets, and music.\", \"button_text\": \"Go To Events\", \"button_link\": \"#home-activities\"}',NULL,NULL,2,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(42,7,'gallery','Home Gallery','{\"item_one_label\": \"Molen de Adriaan\", \"item_one_image\": \"/assets/images/home/home-gallery-molen-de-adriaan.jpg\", \"item_one_alt\": \"Molen de Adriaan along the water\", \"item_two_label\": \"Grote Kerk\", \"item_two_image\": \"/assets/images/home/home-gallery-grote-kerk.jpg\", \"item_two_alt\": \"Grote Kerk at sunset\", \"item_three_label\": \"Old Haarlem\", \"item_three_image\": \"/assets/images/home/home-gallery-old-town.jpg\", \"item_three_alt\": \"Historic Haarlem street and canal\", \"item_four_label\": \"The Weigh House\", \"item_four_image\": \"/assets/images/home/home-gallery-the-weigh-house.jpg\", \"item_four_alt\": \"The Weigh House in Haarlem\"}',NULL,NULL,3,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(43,7,'image_left','Grote Markt, Haarlem','{\"heading\": \"Grote Markt, Haarlem\", \"body\": \"The Haarlemse Markt is one of the most iconic and long-standing markets in the Netherlands, taking place in the historic Grote Markt, right in the heart of Haarlem\'s city centre. With roots dating back centuries, the market has long been a central part of daily life in the city and continues to attract both locals and visitors alike.\\n\\nHeld every Saturday, with a smaller version on Mondays, the market offers a wide and colourful mix of stalls. Visitors can browse fresh seasonal fruits and vegetables, cheeses, bread, flowers, clothing, fabrics, and international street food.\", \"image\": \"/assets/images/home/home-grote-markt.jpg\", \"image_alt\": \"Crowded Grote Markt in Haarlem\"}',NULL,NULL,4,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(44,7,'image_right','Historic Canal Houses','{\"heading\": \"Historic Canal Houses\", \"body\": \"Haarlem\'s historic canal houses are a defining feature of the city\'s character and architectural heritage. Lining the city\'s canals, these narrow yet elegant buildings date back to the 16th and 17th centuries, when Haarlem prospered during the Dutch Golden Age.\\n\\nOriginally built for merchants, craftsmen, and traders, the canal houses reflect the city\'s economic growth. Many still display stepped or bell-shaped gables, wooden beams, and ornate brickwork while remaining carefully preserved for modern use.\", \"image_one\": \"/assets/images/home/home-canal-house-one.jpg\", \"image_one_alt\": \"Historic canal houses beside the Spaarne River\", \"image_two\": \"/assets/images/home/home-canal-house-two.jpg\", \"image_two_alt\": \"Bridge and canal houses in Haarlem\"}',NULL,NULL,5,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(45,7,'cards_grid','What you can do in Haarlem','{\"heading\": \"What you can do in Haarlem\", \"card_one_title\": \"Stories\", \"card_one_text\": \"Haarlem is a city full of character, shaped by centuries of history and the people who call it home. Explore personal stories, local legends, hidden alleys, and traditions that give the city its unique charm.\", \"card_one_image\": \"/assets/images/home/home-stories.jpg\", \"card_one_alt\": \"Audience in a theatre in Haarlem\", \"card_one_button_text\": \"Read More\", \"card_one_button_link\": \"/stories\", \"card_two_title\": \"History\", \"card_two_text\": \"Haarlem is a city rich in history and culture, shaped by centuries of art, trade, and craftsmanship. From medieval beginnings to the Dutch Golden Age, its churches, market squares, and streets tell the story.\", \"card_two_image\": \"/assets/images/history/history-grote-kerk.jpg\", \"card_two_alt\": \"The Grote Kerk in Haarlem\", \"card_two_button_text\": \"Read More\", \"card_two_button_link\": \"/history\", \"card_three_title\": \"Restaurants\", \"card_three_text\": \"Enjoy a taste of Haarlem in its vibrant restaurant scene. From elegant Michelin-starred establishments to welcoming neighbourhood cafés, the city offers something memorable for every visitor.\", \"card_three_image\": \"/assets/images/home/home-restaurants.jpg\", \"card_three_alt\": \"Restaurant street scene in Haarlem\", \"card_three_button_text\": \"Read More\", \"card_three_button_link\": \"/yummy\", \"card_four_title\": \"Dance!\", \"card_four_text\": \"Feel the bass, the lights, and the crowd. Discover the artists, find the venues, and build your own line-up for the most energetic nights of the festival.\", \"card_four_image\": \"/assets/images/home/home-dance.jpg\", \"card_four_alt\": \"Crowded dance floor with red lights\", \"card_four_button_text\": \"Read More\", \"card_four_button_link\": \"/dance\", \"card_five_title\": \"Jazz\", \"card_five_text\": \"Every summer, Haarlem comes alive with the sound of jazz. Expect great live music, sunny terraces, and an unforgettable festival atmosphere in the heart of the city.\", \"card_five_image\": \"/assets/images/home/home-jazz.jpg\", \"card_five_alt\": \"Jazz performance on stage in Haarlem\", \"card_five_button_text\": \"Read More\", \"card_five_button_link\": \"/home#home-jazz\"}',NULL,NULL,6,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(46,7,'transport','Transportation','{\"heading\": \"Transportation\", \"intro\": \"Getting around Haarlem is simple, fast, and convenient. The city\'s compact layout makes it easy to explore by foot or bike, while a reliable bus network connects every major neighbourhood. Haarlem Central Station is the main transport hub for Amsterdam, Schiphol, Zandvoort, Leiden, and other Dutch cities.\", \"list_intro\": \"Travellers can move through the city using several transport options:\", \"item_one\": \"Trains: Haarlem Central Station provides fast connections, only 15 minutes to Amsterdam and 10 minutes to Zandvoort Beach.\", \"item_two\": \"Buses: A wide network of local and regional buses makes it easy to reach attractions, events, and nearby towns.\", \"item_three\": \"Bicycles: Haarlem is a true cycling city with safe bike paths and plenty of rental options.\", \"item_four\": \"Walking: Most of the historic centre is walkable, with shops, cafes, museums, and markets all within short distance.\", \"image\": \"/assets/images/home/home-transport.jpg\", \"image_alt\": \"Buses outside Haarlem Central Station\", \"button_text\": \"View On Map\", \"button_link\": \"https://maps.google.com/?q=Haarlem+Centraal\"}',NULL,NULL,7,1,NULL,'2026-06-18 19:52:24','2026-06-18 19:52:24'),
(54,9,'welcome_banner','Ratatouille Food & Wine','{\"title\": \"Ratatouille Food & Wine\", \"button_text\": \"Book now\", \"button_link\": \"#reservation\", \"section_image\": [\"/assets/images/yummy/yummy.jpg\"]}',NULL,NULL,1,1,NULL,'2026-06-18 19:52:25','2026-06-18 19:52:25'),
(55,9,'reservation','Book your table','{\"title\": \"Book your table\", \"information\": \"A reservation fee is charged at checkout via My Program.\", \"date\": [\"Fri 24 Jul 2026\", \"Sat 25 Jul 2026\", \"Sun 26 Jul 2026\"], \"session\": [\"18:00\", \"19:30\", \"21:00\"], \"adultPrice\": 25, \"kidsPrice\": 12.5, \"button_text\": \"Add to My Program\", \"button_link\": \"\"}',NULL,NULL,20,1,NULL,'2026-06-18 19:52:25','2026-06-18 19:52:25'),
(56,10,'welcome_banner','Bistro Toujours','{\"title\": \"Bistro Toujours\", \"button_text\": \"Book now\", \"button_link\": \"#reservation\", \"section_image\": [\"/assets/images/yummy/yummy.jpg\"]}',NULL,NULL,1,1,NULL,'2026-06-18 19:52:25','2026-06-18 19:52:25'),
(57,10,'reservation','Book your table','{\"title\": \"Book your table\", \"information\": \"A reservation fee is charged at checkout via My Program.\", \"date\": [\"Fri 24 Jul 2026\", \"Sat 25 Jul 2026\", \"Sun 26 Jul 2026\"], \"session\": [\"18:00\", \"19:30\", \"21:00\"], \"adultPrice\": 22, \"kidsPrice\": 11, \"button_text\": \"Add to My Program\", \"button_link\": \"\"}',NULL,NULL,20,1,NULL,'2026-06-18 19:52:25','2026-06-18 19:52:25'),
(58,8,'restaurant_card','Ratatouille','{\"title\": \"Ratatouille\", \"introduction\": \"Ratatouille Food & Wine is one of Haarlem\'s top culinary destinations, offering an unforgettable Michelin-starred experience.\", \"rating\": \"4.0\", \"capacity\": \"52\", \"button_text\": \"View\", \"button_link\": \"/yummy/ratatouille\", \"cuisine\": [\"Sea Food\", \"French\", \"European\"], \"section_image\": [\"/assets/images/yummy/yummy.jpg\"]}',NULL,NULL,10,1,NULL,'2026-06-18 19:52:52','2026-06-18 19:52:52'),
(59,8,'restaurant_card','Bistro Toujours','{\"title\": \"Bistro Toujours\", \"introduction\": \"Bistro Toujours captures the charm of a classic French bistro while adding its own modern Haarlem identity.\", \"rating\": \"3.0\", \"capacity\": \"48\", \"button_text\": \"View\", \"button_link\": \"/yummy/bistro-toujours\", \"cuisine\": [\"Sea Food\", \"Dutch\", \"European\"], \"section_image\": [\"/assets/images/yummy/yummy.jpg\"]}',NULL,NULL,20,1,NULL,'2026-06-18 19:52:52','2026-06-18 19:52:52'),
(60,8,'restaurant_card','New Vegas','{\"title\": \"New Vegas\", \"introduction\": \"New Vegas brings a fresh and modern twist to vegetarian cuisine. With creative dishes full of color, texture, and flavor.\", \"rating\": \"3.0\", \"capacity\": \"36\", \"button_text\": \"View\", \"button_link\": \"#\", \"cuisine\": [\"Vegan\"], \"section_image\": [\"/assets/images/yummy/yummy.jpg\"]}',NULL,NULL,30,1,NULL,'2026-06-18 19:52:52','2026-06-18 19:52:52'),
(61,8,'restaurant_card','Grand Cafe Brinkman','{\"title\": \"Grand Cafe Brinkman\", \"introduction\": \"Grand Cafe Brinkman is one of Haarlem\'s most iconic gathering places, located right on the Grote Markt.\", \"rating\": \"3.0\", \"capacity\": \"100\", \"button_text\": \"View\", \"button_link\": \"#\", \"cuisine\": [\"Modern\", \"Dutch\"], \"section_image\": [\"/assets/images/yummy/yummy.jpg\"]}',NULL,NULL,40,1,NULL,'2026-06-18 19:52:52','2026-06-18 19:52:52'),
(62,8,'restaurant_card','Cafe de Roemer','{\"title\": \"Cafe de Roemer\", \"introduction\": \"Cafe de Roemer is a warm and inviting cafe-bar offering a mix of seafood and European dishes.\", \"rating\": \"4.0\", \"capacity\": \"35\", \"button_text\": \"View\", \"button_link\": \"#\", \"cuisine\": [\"Sea Food\", \"Dutch\"], \"section_image\": [\"/assets/images/yummy/yummy.jpg\"]}',NULL,NULL,50,1,NULL,'2026-06-18 19:52:52','2026-06-18 19:52:52'),
(63,8,'restaurant_card','Restaurant Fris','{\"title\": \"Restaurant Fris\", \"introduction\": \"Fris brings a fresh and modern twist to vegetarian cuisine. Creative dishes full of color, texture, and flavor.\", \"rating\": \"3.0\", \"capacity\": \"45\", \"button_text\": \"View\", \"button_link\": \"#\", \"cuisine\": [\"French\", \"Dutch\"], \"section_image\": [\"/assets/images/yummy/yummy.jpg\"]}',NULL,NULL,60,1,NULL,'2026-06-18 19:52:52','2026-06-18 19:52:52'),
(64,8,'restaurant_card','Restaurant ML','{\"title\": \"Restaurant ML\", \"introduction\": \"Restaurant ML offers a refined dining experience in the heart of Haarlem, known for its elegant atmosphere and beautifully crafted dishes.\", \"rating\": \"4.0\", \"capacity\": \"60\", \"button_text\": \"View\", \"button_link\": \"#\", \"cuisine\": [\"Sea Food\", \"Dutch\"], \"section_image\": [\"/assets/images/yummy/yummy.jpg\"]}',NULL,NULL,70,1,NULL,'2026-06-18 19:52:52','2026-06-18 19:52:52');
/*!40000 ALTER TABLE `page_section` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `page_section_image`
--

DROP TABLE IF EXISTS `page_section_image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `page_section_image` (
  `section_id` bigint(20) unsigned NOT NULL,
  `image_id` bigint(20) unsigned NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`section_id`,`image_id`),
  KEY `fk_psi_image` (`image_id`),
  CONSTRAINT `fk_psi_image` FOREIGN KEY (`image_id`) REFERENCES `image` (`image_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_psi_section` FOREIGN KEY (`section_id`) REFERENCES `page_section` (`section_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_section_image`
--

LOCK TABLES `page_section_image` WRITE;
/*!40000 ALTER TABLE `page_section_image` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `page_section_image` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment` (
  `payment_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `provider` varchar(50) DEFAULT NULL,
  `provider_payment_id` varchar(255) DEFAULT NULL,
  `stripe_session_id` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` char(3) NOT NULL DEFAULT 'EUR',
  `status` enum('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  `paid_at` datetime DEFAULT NULL,
  PRIMARY KEY (`payment_id`),
  UNIQUE KEY `uq_payment_stripe_session` (`stripe_session_id`),
  KEY `fk_payment_order` (`order_id`),
  CONSTRAINT `fk_payment_order` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment`
--

LOCK TABLES `payment` WRITE;
/*!40000 ALTER TABLE `payment` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `payment` VALUES
(1,1,'stripe',NULL,NULL,92.65,'EUR','paid','2026-06-01 11:07:12');
/*!40000 ALTER TABLE `payment` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `provider` varchar(100) NOT NULL DEFAULT 'stripe',
  `provider_payment_id` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(10) NOT NULL DEFAULT 'EUR',
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `paid_at` datetime DEFAULT NULL,
  PRIMARY KEY (`payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `pending_stripe_checkout`
--

DROP TABLE IF EXISTS `pending_stripe_checkout`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pending_stripe_checkout` (
  `stripe_session_id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `customer_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`customer_json`)),
  `items_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`items_json`)),
  `provider` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`stripe_session_id`),
  KEY `idx_pending_checkout_user` (`user_id`),
  CONSTRAINT `fk_pending_checkout_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pending_stripe_checkout`
--

LOCK TABLES `pending_stripe_checkout` WRITE;
/*!40000 ALTER TABLE `pending_stripe_checkout` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `pending_stripe_checkout` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `program_item`
--

DROP TABLE IF EXISTS `program_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `program_item` (
  `program_item_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `event_id` bigint(20) unsigned NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`program_item_id`),
  UNIQUE KEY `uq_program_user_event` (`user_id`,`event_id`),
  KEY `fk_program_event` (`event_id`),
  CONSTRAINT `fk_program_event` FOREIGN KEY (`event_id`) REFERENCES `event` (`event_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_program_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `program_item`
--

LOCK TABLES `program_item` WRITE;
/*!40000 ALTER TABLE `program_item` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `program_item` VALUES
(1,3,1,'2026-06-18 19:52:52'),
(2,3,2,'2026-06-18 19:52:52'),
(3,4,5,'2026-06-18 19:52:52');
/*!40000 ALTER TABLE `program_item` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `reservation`
--

DROP TABLE IF EXISTS `reservation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `reservation` (
  `reservation_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `restaurant_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `reservation_date` varchar(64) NOT NULL,
  `session` varchar(64) NOT NULL,
  `adult_count` int(10) unsigned NOT NULL DEFAULT 0,
  `child_count` int(10) unsigned NOT NULL DEFAULT 0,
  `special_requests` text DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`reservation_id`),
  KEY `idx_reservation_restaurant` (`restaurant_id`),
  KEY `idx_reservation_user` (`user_id`),
  KEY `idx_reservation_order` (`order_id`),
  CONSTRAINT `fk_reservation_order` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`) ON DELETE SET NULL,
  CONSTRAINT `fk_reservation_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`restaurant_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_reservation_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservation`
--

LOCK TABLES `reservation` WRITE;
/*!40000 ALTER TABLE `reservation` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `reservation` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `restaurant`
--

DROP TABLE IF EXISTS `restaurant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `restaurant` (
  `restaurant_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `capacity` int(10) unsigned NOT NULL DEFAULT 0,
  `location_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`restaurant_id`),
  UNIQUE KEY `uq_restaurant_slug` (`slug`),
  KEY `fk_restaurant_location` (`location_id`),
  CONSTRAINT `fk_restaurant_location` FOREIGN KEY (`location_id`) REFERENCES `location` (`location_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurant`
--

LOCK TABLES `restaurant` WRITE;
/*!40000 ALTER TABLE `restaurant` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `restaurant` VALUES
(1,'Ratatouille Food & Wine','ratatouille',NULL,60,NULL,'2026-06-18 19:52:26','2026-06-18 19:52:26'),
(2,'Bistro Toujours','bistro-toujours',NULL,50,NULL,'2026-06-18 19:52:26','2026-06-18 19:52:26');
/*!40000 ALTER TABLE `restaurant` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `schema_migrations`
--

DROP TABLE IF EXISTS `schema_migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `schema_migrations` (
  `filename` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  `applied_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`filename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schema_migrations`
--

LOCK TABLES `schema_migrations` WRITE;
/*!40000 ALTER TABLE `schema_migrations` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `schema_migrations` VALUES
('01_schema.sql','d48ec8525a323cfde4defcee728f0d65','2026-06-18 19:52:23'),
('02_page_section_types.sql','813befb9ea92495b9d96a3b6dc68c068','2026-06-18 19:52:24'),
('03_stories_seed_fix.sql','603fc1d8dd959cc43b39c68971fa7423','2026-06-18 19:52:24'),
('04_history_page.sql','36b30ac962458b08bfcb8400e313813c','2026-06-18 19:52:24'),
('04_page_section_restaurant_card_alias.sql','9b1bc38c72c218c8316370d14c425a19','2026-06-18 19:52:24'),
('05_history_book_tour_page.sql','9d62c3be6f45e9acf5228cdb3b84caa0','2026-06-18 19:52:24'),
('05_page_section_restaurant_card_only.sql','c6edfc8a56867f949ced0aa2c8586411','2026-06-18 19:52:24'),
('06_history_route_map_page.sql','a1c14a4a1312c0df71732cbdb00b341a','2026-06-18 19:52:24'),
('06_image_caption.sql','0b4329d19a2b927bf976933aed26fbfb','2026-06-18 19:52:24'),
('07_history_st_bavo_page.sql','c1d6bd0bb6f7326726909782a89c4753','2026-06-18 19:52:24'),
('07_page_section_welcome_banner_card.sql','e3fc4bd75a6185130338648d1b905dbb','2026-06-18 19:52:24'),
('08_drop_page_section_image_id.sql','fbf8da7a4051cc625b937cc98bc24210','2026-06-18 19:52:24'),
('08_history_molen_page.sql','0dc41dbf2f5801da9cb92f23f32879d8','2026-06-18 19:52:24'),
('09_history_cms_updates.sql','d15a77a0f8abc38b30cb0c3746c0063b','2026-06-18 19:52:24'),
('10_home_page_refresh.sql','05fe2e419b4e5e33a3713aa2eda20a4d','2026-06-18 19:52:24'),
('10_payments_table.sql','64ae57ac08930f3dcac09894b2a28107','2026-06-18 19:52:24'),
('11_history_tour_schedule_guides.sql','4ce85116af6adba6d500a129c81ad7dc','2026-06-18 19:52:25'),
('11_stories_booking_section_type.sql','5f2cdb6b4e77f34148178869defac841','2026-06-18 19:52:25'),
('12_payment_persistence.sql','804990925d967339711a179965501a11','2026-06-18 19:52:25'),
('13_history_navigation_links.sql','588b57ad7273457d6f8a16eee23b64b2','2026-06-18 19:52:25'),
('14_yummy_page.sql','08adeae1c7ec7025ce5e5be5e498a8e1','2026-06-18 19:52:25'),
('15_yummy_restaurant_pages.sql','804cebe817f1ebe3b18e742e5dce68b8','2026-06-18 19:52:25'),
('16_fix_home_history_card_image.sql','f994b3f52b8fc0e34a41c345b6bcc96b','2026-06-18 19:52:25'),
('17_dance_events.sql','e5a38de1ad2cf30dc3247276242df1a2','2026-06-18 19:52:25'),
('17_order_ticket_optional_type.sql','3529db16b0d0e014f680480cda10fd96','2026-06-18 19:52:26'),
('18_dance_programme_cleanup.sql','3feb879006e8e4189bfbb91813e0be0e','2026-06-18 19:52:26'),
('18_order_booking_entities.sql','cf0c727e54b7e8a5ff5dcbf35921411d','2026-06-18 19:52:26'),
('19_dance_artists.sql','f03c4a5ae80221a6d439504eb7c17be1','2026-06-18 19:52:26'),
('19_seed_restaurants.sql','5a5ec2428dc80a17f5fc9d5acfaa7441','2026-06-18 19:52:26'),
('20_dance_artist_details.sql','93329467020377fef75eb3c1cb98b8d5','2026-06-18 19:52:27'),
('20_order_total_price_reconcile.sql','65251b89ce6e4de6b3142b79ddcacbe7','2026-06-18 19:52:27'),
('21_dance_artist_images.sql','102960133a115055cb2c484c838281e3','2026-06-18 19:52:27'),
('21_ticket_legacy_columns_nullable.sql','599d2a8d62f23929d2299b1a37a1f3f9','2026-06-18 19:52:27'),
('22_cleanup_dead_artifacts.sql','9b6af0dd603bf55f7d360a4d8bbfa9ee','2026-06-18 19:52:27'),
('22_dance_artist_gallery_images.sql','34cad64232de96830016d5bd0c953e90','2026-06-18 19:52:27');
/*!40000 ALTER TABLE `schema_migrations` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `ticket`
--

DROP TABLE IF EXISTS `ticket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ticket` (
  `ticket_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_line_id` bigint(20) unsigned DEFAULT NULL,
  `order_ticket_id` bigint(20) unsigned NOT NULL,
  `qr_token` char(64) NOT NULL,
  `status` enum('valid','scanned','cancelled') NOT NULL DEFAULT 'valid',
  `scanned_at` datetime DEFAULT NULL,
  PRIMARY KEY (`ticket_id`),
  UNIQUE KEY `qr_token` (`qr_token`),
  KEY `fk_ticket_order_ticket` (`order_ticket_id`),
  KEY `fk_ticket_order_line` (`order_line_id`),
  CONSTRAINT `fk_ticket_order_line` FOREIGN KEY (`order_line_id`) REFERENCES `order_line` (`order_line_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ticket_order_ticket` FOREIGN KEY (`order_ticket_id`) REFERENCES `order_ticket` (`order_ticket_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket`
--

LOCK TABLES `ticket` WRITE;
/*!40000 ALTER TABLE `ticket` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `ticket` VALUES
(1,NULL,1,'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa','valid',NULL),
(2,NULL,2,'cccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc','valid',NULL);
/*!40000 ALTER TABLE `ticket` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `ticket_type`
--

DROP TABLE IF EXISTS `ticket_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ticket_type` (
  `ticket_type_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) unsigned NOT NULL,
  `name` varchar(120) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `max_quantity` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ticket_type_id`),
  KEY `fk_ticket_type_event` (`event_id`),
  CONSTRAINT `fk_ticket_type_event` FOREIGN KEY (`event_id`) REFERENCES `event` (`event_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket_type`
--

LOCK TABLES `ticket_type` WRITE;
/*!40000 ALTER TABLE `ticket_type` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `ticket_type` VALUES
(1,1,'Back2Back',75.00,1500),
(2,2,'Club',60.00,200),
(3,3,'Club',60.00,300),
(4,4,'Back2Back',110.00,2000),
(5,5,'Session Ticket',90.00,220),
(6,6,'Club',60.00,200),
(7,7,'Club',60.00,200),
(8,8,'Club',60.00,300),
(9,9,'TiestoWorld',75.00,1500),
(10,10,'Club',60.00,200),
(11,11,'Back2Back',110.00,2000),
(12,12,'Club',60.00,300),
(13,13,'Club',90.00,1500),
(14,14,'Club',60.00,200),
(15,1,'Regular',25.00,800),
(16,1,'VIP',60.00,100),
(17,2,'Standard',35.00,200),
(18,3,'Adult',15.00,250),
(19,3,'Student',10.00,80),
(20,4,'Entry',18.00,180),
(21,5,'Seat',30.00,600),
(22,20,'Regular',22.00,500);
/*!40000 ALTER TABLE `ticket_type` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role` enum('admin','customer','employee') NOT NULL DEFAULT 'customer',
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `profile_image_id` bigint(20) unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `uq_user_email` (`email`),
  UNIQUE KEY `uq_user_username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `user` VALUES
(1,'admin','admin','admin@haarlemfest.test','$2y$12$vIvVL6qdxkHgsNXQ6lbzZePy973snNNAnbRP5jegW6O40R6mXHpHG','Admin','User',NULL,NULL,1,'2026-06-18 19:52:52','2026-06-18 19:52:52'),
(2,'employee','eline','employee@haarlemfest.test','$2y$12$vIvVL6qdxkHgsNXQ6lbzZePy973snNNAnbRP5jegW6O40R6mXHpHG','Eline','Scanner',NULL,NULL,1,'2026-06-18 19:52:52','2026-06-18 19:52:52'),
(3,'customer','samj','customer1@haarlemfest.test','$2y$12$vIvVL6qdxkHgsNXQ6lbzZePy973snNNAnbRP5jegW6O40R6mXHpHG','Sam','Jansen','+31 6 11111111',4,1,'2026-06-18 19:52:52','2026-06-18 19:52:52'),
(4,'customer','noordv','customer2@haarlemfest.test','$2y$12$vIvVL6qdxkHgsNXQ6lbzZePy973snNNAnbRP5jegW6O40R6mXHpHG','Noor','de Vries','+31 6 22222222',NULL,1,'2026-06-18 19:52:52','2026-06-18 19:52:52');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
commit;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-06-18 20:08:30
