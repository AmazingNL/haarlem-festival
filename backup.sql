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
-- Table structure for table `event`
--

DROP TABLE IF EXISTS `event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `event` (
  `event_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `location_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`event_id`),
  KEY `fk_event_location` (`location_id`),
  CONSTRAINT `fk_event_location` FOREIGN KEY (`location_id`) REFERENCES `location` (`location_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event`
--

LOCK TABLES `event` WRITE;
/*!40000 ALTER TABLE `event` DISABLE KEYS */;
set autocommit=0;
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
) ENGINE=InnoDB AUTO_INCREMENT=116 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `image`
--

LOCK TABLES `image` WRITE;
/*!40000 ALTER TABLE `image` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `image` VALUES
(1,'/uploads/events/jazz-night.jpg','Jazz Night poster',NULL,1,'2026-03-17 12:27:31'),
(2,'/uploads/events/food-tour.jpg','Food Tour image',NULL,1,'2026-03-17 12:27:31'),
(3,'/uploads/events/history-walk.jpg','History Walk',NULL,1,'2026-03-17 12:27:31'),
(4,'/uploads/profiles/sam.jpg','Sam profile photo',NULL,1,'2026-03-17 12:27:31'),
(5,'/uploads/home/hero-haarlem.jpg','Haarlem hero image',NULL,1,'2026-03-17 12:27:31'),
(6,'/uploads/home/windmill.jpg','Windmill Haarlem',NULL,1,'2026-03-17 12:27:31'),
(7,'/uploads/home/church.jpg','Church Haarlem',NULL,1,'2026-03-17 12:27:31'),
(8,'/uploads/home/grote-markt.jpg','Grote Markt',NULL,1,'2026-03-17 12:27:31'),
(9,'/uploads/home/canal-houses.jpg','Canal Houses',NULL,1,'2026-03-17 12:27:31'),
(26,'/assets/images/admin/30c3b0fda94c1a044d4ce16541a9b861.png',NULL,NULL,1,'2026-03-21 14:08:20'),
(27,'/assets/images/admin/9068e59d08ab74c24768b9d71f28a102.png',NULL,NULL,1,'2026-03-21 22:47:15'),
(28,'/assets/images/admin/f1753989180a098798cf931424891a21.png',NULL,NULL,1,'2026-03-21 22:47:27'),
(29,'/assets/images/admin/978170427b49a240b1312f1d78786acd.png',NULL,NULL,1,'2026-03-23 21:41:20'),
(30,'/assets/images/admin/2feb332fc8178e2884dc1bcc52eb2e77.png',NULL,NULL,1,'2026-03-23 21:42:22'),
(31,'/assets/images/admin/14402d8b89707a3fa1564fb496a14541.png',NULL,NULL,1,'2026-03-23 21:42:53'),
(32,'/assets/images/admin/4325ab8e30559359061a25a8365ef23c.png',NULL,NULL,1,'2026-03-23 21:43:32'),
(33,'/assets/images/admin/f3ae8ffb86c96af0d54bd1e08d32367a.png',NULL,NULL,1,'2026-03-23 21:44:11'),
(34,'/assets/images/admin/af542de4c0b038b9bdeaef8376b0167d.png',NULL,NULL,1,'2026-03-23 21:45:04'),
(35,'/assets/images/admin/7eecf020a70933ac9e0efcf23ce1438c.png',NULL,NULL,1,'2026-03-23 21:48:09'),
(42,'/assets/images/admin/be2a72fb39e2dba60a70d3c400bc2356.png',NULL,NULL,1,'2026-03-23 22:35:11'),
(43,'/assets/images/admin/8f8695b7a35f0b137c2005a1bfac2cad.png',NULL,NULL,1,'2026-03-23 22:35:40'),
(44,'/assets/images/admin/97002ff941343028b9e416c46ff52f2c.png',NULL,NULL,1,'2026-03-23 22:36:10'),
(45,'/assets/images/admin/58549e96e9ded7ad19668a7ebe898c87.png',NULL,NULL,1,'2026-03-23 22:48:19'),
(46,'/assets/images/admin/bd8a1a7e565ecb21de7471dbb14b02c5.png',NULL,NULL,1,'2026-03-23 22:48:50'),
(48,'/assets/images/admin/9eb80430147ba98da4c7ce7e9adc1a55.png',NULL,NULL,1,'2026-03-24 10:19:04'),
(49,'/assets/images/admin/8f28254d6be9a10834a65049a2feb6fd.png',NULL,NULL,1,'2026-03-24 10:19:33'),
(50,'/assets/images/admin/1c1d8c774f7024249150ee38c50af907.jpg',NULL,NULL,1,'2026-04-06 17:24:58'),
(51,'/assets/images/admin/7601503b3169b3a4a2cb6f582e2a92e7.jpg',NULL,NULL,1,'2026-04-06 17:25:57'),
(52,'/assets/images/admin/dd9bbb333adfcd48dd3dc5905d037cd8.jpg',NULL,NULL,1,'2026-04-06 17:31:20'),
(53,'/assets/images/admin/15ad06842c52aff367f86ea984cfa969.jpg',NULL,NULL,1,'2026-04-06 17:32:13'),
(54,'/assets/images/admin/16130f2be8da66039feee9db7c47fb2b.jpg',NULL,NULL,1,'2026-04-06 17:49:17'),
(55,'/assets/images/admin/2d446e56690414a1974c243b719c5e5f.jpg',NULL,NULL,1,'2026-04-06 17:49:51'),
(56,'/assets/images/admin/79c90fdc3fb3447532f9f52ed3ef28ea.jpg',NULL,NULL,1,'2026-04-06 18:14:53'),
(57,'/assets/images/admin/caa671797902e6a685b699ec639c4edd.jpg',NULL,NULL,1,'2026-04-06 18:15:46'),
(58,'/assets/images/admin/1cdc8f6626c7f6c29e1205a35dc32c38.jpg',NULL,NULL,1,'2026-04-06 18:28:02'),
(59,'/assets/images/admin/f92d7c4c96ccbb10f0228937d7830c44.jpg',NULL,NULL,1,'2026-04-06 18:30:59'),
(60,'/assets/images/admin/484cf74a5d6970390932a43612dfeeee.png',NULL,NULL,1,'2026-04-06 20:13:17'),
(61,'/assets/images/admin/696677aca3eb8cff5968c85e4c62b725.png',NULL,NULL,1,'2026-04-06 20:14:15'),
(62,'/assets/images/admin/11f55ab4f611d43e0ecdae12d23648f5.png',NULL,NULL,1,'2026-04-06 21:18:10'),
(63,'/assets/images/admin/f69af20e825055aa1fc60e8f5d4f1720.png',NULL,NULL,1,'2026-04-06 21:19:11'),
(64,'/assets/images/admin/4e534de8380c28372d0a2fe97ee621b0.png',NULL,NULL,1,'2026-04-06 21:19:33'),
(65,'/assets/images/admin/f407a03b9f9fccf755dd87ee89afff91.png',NULL,NULL,1,'2026-04-06 21:20:53'),
(66,'/assets/images/admin/af018a49a2176491445fc12cbaed8a7a.png',NULL,NULL,1,'2026-04-06 22:23:56'),
(67,'/assets/images/admin/437a1f53b6194fcc0b683bc5be359c2e.png',NULL,NULL,1,'2026-04-06 22:24:40'),
(68,'/assets/images/admin/04e617ea94e337430669cb14852775f5.png',NULL,NULL,1,'2026-04-06 22:25:11'),
(69,'/assets/images/admin/ae9b423c21d23bafa0e5f22420626c7a.png',NULL,NULL,1,'2026-04-06 22:25:59'),
(70,'/assets/images/admin/5b427a6673509f3072c2681e58ae99a0.png',NULL,NULL,1,'2026-04-06 22:26:23'),
(71,'/assets/images/admin/7b124b40cae8b2137b1c45cdac527019.png',NULL,NULL,1,'2026-04-06 22:26:46'),
(72,'/assets/images/admin/986cec73538d1a339f6cfad5a46ac692.png',NULL,NULL,1,'2026-04-06 22:27:27'),
(73,'/assets/images/admin/1e9571777c2066ac6a2b7c5133fdf641.png',NULL,NULL,1,'2026-04-06 22:27:57'),
(74,'/assets/images/admin/a7d9e700300328153c2012c248ad4375.png',NULL,NULL,1,'2026-04-06 22:28:43'),
(93,'/assets/images/stories/pexels-cottonbro-4911740.jpg','Storytelling performance on stage',NULL,1,'2026-04-21 22:39:41'),
(94,'/assets/images/stories/antonio-molinari-22FwbFrPvpU-unsplash.jpg','Audience listening to a story',NULL,1,'2026-04-21 22:39:41'),
(95,'/assets/images/stories/Foto-Mister-Anansi-leert-de-wereld-lachen.jpeg','Mister Anansi performance',NULL,1,'2026-04-21 22:39:41'),
(96,'/assets/images/stories/MisterAnansiLeendertJansen-1.jpg','Mister Anansi performer Leendert Jansen',NULL,1,'2026-04-21 22:39:41'),
(97,'/assets/images/stories/pexels-cottonbro-7319358.jpg','Performer on stage',NULL,1,'2026-04-21 22:39:41'),
(98,'/assets/images/stories/pexels-jibarofoto-2774556.jpg','Festival crowd',NULL,1,'2026-04-21 22:39:41'),
(99,'/assets/images/stories/corrie.jpeg','Corrie ten Boom',NULL,1,'2026-04-21 22:39:41'),
(100,'/assets/images/stories/tenboom.jpg','Ten Boom house',NULL,1,'2026-04-21 22:39:41'),
(101,'/assets/images/stories/drama emotion.jpg','Dramatic storytelling moment',NULL,1,'2026-04-21 22:39:41'),
(102,'/assets/images/admin/0860eeaa205f4bdae6904031ed4558e5.png',NULL,NULL,1,'2026-05-01 18:14:37'),
(103,'/assets/images/admin/2cadb7f15c463c348761e1110b75dfb5.png',NULL,NULL,1,'2026-05-01 18:15:13'),
(104,'/assets/images/admin/0467e03198959bc867979f3d8849bead.png',NULL,NULL,1,'2026-05-03 18:04:54'),
(105,'/assets/images/admin/a13e35b88e9777b6066c20c6ff603801.png',NULL,NULL,1,'2026-05-03 18:05:55'),
(106,'/assets/images/admin/17de18055da17d790107a5b5b3802d22.png',NULL,NULL,1,'2026-05-06 08:17:23'),
(107,'/assets/images/admin/04ed065d25ef7b76e6c989076cc22c5b.png',NULL,NULL,1,'2026-05-06 08:19:25'),
(108,'/assets/images/admin/1df1ef11921fccc6d4d5395b48ab0ca3.png',NULL,NULL,1,'2026-05-06 08:20:00'),
(109,'/assets/images/admin/d9f1a9b85170a85d820d382bab8ec045.png',NULL,NULL,1,'2026-05-06 08:20:14'),
(110,'/assets/images/admin/0e09a08a6666c16ba318a7f15676217d.png',NULL,NULL,1,'2026-05-06 12:34:26'),
(111,'/assets/images/admin/13f05d79dbb74d713ae688a443b9173e.png',NULL,NULL,1,'2026-05-06 12:36:12'),
(112,'/assets/images/admin/97b1ca747fa0432c79eaa88293f60a9a.png',NULL,NULL,1,'2026-05-06 12:36:25'),
(113,'/assets/images/admin/af1139fee93755d988a548027351a985.png',NULL,NULL,1,'2026-05-06 12:37:22'),
(114,'/assets/images/admin/25e40a71c091f67e384014305c7bd12e.png',NULL,NULL,1,'2026-05-06 12:37:39'),
(115,'/assets/images/admin/5492b10add5b6f51949e03a60bb6d991.png',NULL,NULL,1,'2026-05-06 12:37:53');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `location`
--

LOCK TABLES `location` WRITE;
/*!40000 ALTER TABLE `location` DISABLE KEYS */;
set autocommit=0;
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
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','paid','cancelled','expired') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `vat` int(255) DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  KEY `fk_order_user` (`user_id`),
  CONSTRAINT `fk_order_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order`
--

LOCK TABLES `order` WRITE;
/*!40000 ALTER TABLE `order` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `order` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `order_item`
--

DROP TABLE IF EXISTS `order_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_item` (
  `order_item_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `ticket_type_id` bigint(20) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  `unit_price` float NOT NULL,
  `sub_total` float NOT NULL,
  PRIMARY KEY (`order_item_id`),
  KEY `fk_order_ticket_order` (`order_id`),
  KEY `fk_order_ticket_type` (`ticket_type_id`),
  CONSTRAINT `fk_order_ticket_order` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_order_ticket_type` FOREIGN KEY (`ticket_type_id`) REFERENCES `ticket_type` (`ticket_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_item`
--

LOCK TABLES `order_item` WRITE;
/*!40000 ALTER TABLE `order_item` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `order_item` ENABLE KEYS */;
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
(1,'Home','home','published','2026-03-17 12:27:31','2026-03-17 12:27:31'),
(2,'About','about','published','2026-03-17 12:27:31','2026-03-17 12:27:31'),
(3,'Contact','contact','published','2026-03-17 12:27:31','2026-03-17 12:27:31'),
(4,'Yummy','yummy','published','2026-03-17 13:01:52','2026-03-17 13:01:52'),
(6,'Ratatouille','ratatouille','published','2026-04-15 13:32:26','2026-04-15 13:32:26'),
(9,'Stories','stories','published','2026-04-21 22:39:41','2026-04-21 22:39:41'),
(11,'History','history','published','2026-05-05 23:36:38','2026-05-05 23:36:38'),
(12,'Bistro Toujours','bistro-toujours','published','2026-05-06 10:56:58','2026-05-06 10:56:58');
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
  `section_type` enum('cta','text_block','image_text','restaurant_card','restaurants_card','welcome_banner','welcome_banner_card','gallery','haarlem_unique','haarlem_taste','reservation','hero','feature','image_left','image_right','journey','stat','timeline','transport','two_image_row','venue','cards_grid','stories_hero','what_is_stories','stories_preview','storytelling_schedule','history_hero','history_timeline','history_gallery','history_featured_locations','history_route','history_info','history_cta','history_page_nav','history_book_tour_hero','history_book_tour_booking','history_book_tour_route','history_book_tour_schedule','history_book_tour_pricing','history_book_tour_notice','history_book_tour_alert','history_route_map_hero','history_route_map_stops','history_route_map_directions','history_route_map_cta','history_st_bavo_hero','history_st_bavo_facts','history_st_bavo_article','history_st_bavo_sidebar','history_st_bavo_route_cta','history_molen_hero','history_molen_facts','history_molen_article','history_molen_sidebar','history_molen_route_cta') NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`section_id`),
  KEY `fk_section_page` (`page_id`),
  CONSTRAINT `fk_section_page` FOREIGN KEY (`page_id`) REFERENCES `page` (`page_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_section`
--

LOCK TABLES `page_section` WRITE;
/*!40000 ALTER TABLE `page_section` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `page_section` VALUES
(13,4,'welcome_banner',NULL,'{\"title\":\"Haarlem’s Culinary Scene\",\"introduction\":\"<p><span>Haarlem&rsquo;s food scene blends old world charm with bold modern flavours. From canal side bistros to Michelin-starred dining rooms, the city invites every visitor to taste its culture one plate at a time.<\\/span><\\/p>\",\"section_image\":\"\",\"section_image_alt_text\":\"\",\"section_image_caption\":\"\",\"button_text\":\"Explore Restaurants\",\"button_link\":\"\\/restaurants_cards\",\"custom_class\":\"\"}',0,1,'2026-03-19 23:22:39','2026-05-05 21:28:26'),
(14,4,'text_block',NULL,'{\"title\":\"Haarlem Food Culture\",\"sub_title\":\"A City Shaped by Flavour\",\"article\":\"<p>Haarlem&rsquo;s food culture is rooted in centuries of craftsmanship and trade. During the Dutch Golden Age, the city flourished as a center for brewing, fishing, and artisanal production, bringing spices, grains, and fresh ingredients from across Europe. These new influences blended naturally with local traditions, shaping a culinary identity that still defines Haarlem today. From historic cheese markets to long-standing breweries, the city&rsquo;s flavours carry gentle echoes of its past. Classic Dutch dishes like stamppot, warm stews, and baked treats remain part of Haarlem&rsquo;s everyday comfort, while ingredients once introduced through global trade continue to add depth and character. Haarlem&rsquo;s kitchens have always balanced tradition with curiosity, and that spirit of discovery still lives in the restaurants and caf&eacute;s throughout the city today.<\\/p>\",\"custom_class\":\"\"}',0,1,'2026-03-20 00:29:21','2026-03-23 16:54:04'),
(24,4,'gallery',NULL,'{\"title\":\"Haarlem’s food culture\",\"section_image\":[\"..\\/..\\/..\\/assets\\/images\\/admin\\/58549e96e9ded7ad19668a7ebe898c87.png\",\"..\\/..\\/..\\/assets\\/images\\/admin\\/bd8a1a7e565ecb21de7471dbb14b02c5.png\",\"..\\/..\\/..\\/assets\\/images\\/admin\\/ecd8bb27ed3e6b85c4cd9a9b1b0c768a.png\",\"..\\/..\\/..\\/assets\\/images\\/admin\\/be2a72fb39e2dba60a70d3c400bc2356.png\",\"..\\/..\\/..\\/assets\\/images\\/admin\\/8f8695b7a35f0b137c2005a1bfac2cad.png\",\"..\\/..\\/..\\/assets\\/images\\/admin\\/97002ff941343028b9e416c46ff52f2c.png\"],\"custom_class\":\"\"}',0,1,'2026-03-23 21:57:40','2026-05-03 18:57:09'),
(27,4,'haarlem_unique',NULL,'{\"title\":\"What Makes Haarlem Unique Today\",\"content\":\"<p><span>Modern Haarlem is a place where old-world charm meets creative cooking. Walk through the city and you\'ll find riverside caf&eacute;s buzzing with conversation, intimate bistros in narrow streets, and elegant restaurants tucked inside historic buildings. Local chefs mix classic Dutch flavours with French, Mediterranean, and Asian inspirations, creating dishes that feel both familiar and adventurous. With Michelin-starred restaurants, refined vegan spots, and cozy neighbourhood eateries, Haarlem celebrates food as both a craft and an experience<\\/span><\\/p>\",\"section_image\":[\"..\\/..\\/..\\/assets\\/images\\/admin\\/4e534de8380c28372d0a2fe97ee621b0.png\",\"..\\/..\\/..\\/assets\\/images\\/admin\\/f407a03b9f9fccf755dd87ee89afff91.png\"],\"custom_class\":\"\"}',0,1,'2026-04-06 18:31:32','2026-04-06 22:05:22'),
(36,4,'restaurant_card','Ratatouille','{\"title\":\"Ratatouille\",\"introduction\":\"<p>Ratatouille Food &amp; Wine is one of Haarlem\'s top culinary destinations, offering an unforgettable Michelin-starred.<\\/p>\",\"rating\":\"4.0\",\"event_id\":\"\",\"capacity\":\"52\",\"section_image\":\"\",\"cuisine\":[\"Sea Food\",\"French\",\"European\"],\"button_text\":\"View\",\"button_link\":\"\\/yummy\\/ratatouille\",\"custom_class\":\"card-excerpt\"}',10,1,'2026-04-14 09:10:13','2026-04-21 17:19:29'),
(37,4,'restaurant_card','Bistro Toujours','{\"title\": \"Bistro Toujours\", \"introduction\": \"<p>Bistro Toujours captures the charm of a classic French bistro while adding its own modern Haarlem identity.<\\/p>\", \"rating\": \"3.0\", \"event_id\": \"\", \"capacity\": \"48\", \"section_image\": [\"\\/assets\\/images\\/admin\\/7f1f1bd237a89d276e36cbaf5975dd35.png\"], \"cuisine\": [\"Sea Food\", \"Dutch\", \"European\"], \"button_text\": \"View\", \"button_link\": \"/yummy/bistro-toujours\", \"custom_class\": \"card-excerpt\"}',20,1,'2026-04-14 09:10:13','2026-05-06 10:56:58'),
(38,4,'restaurant_card','New Vegas','{\"title\":\"New Vegas\",\"introduction\":\"<p>New Vegas brings a fresh and modern twist to vegetarian cuisine. With creative dishes full of color, texture, and flavor.<\\/p>\",\"rating\":\"3.0\",\"event_id\":\"\",\"capacity\":\"36\",\"section_image\":[\"\\/assets\\/images\\/admin\\/7ab2bfaed66701b84661293875bde717.png\"],\"cuisine\":[\"Vegan\"],\"button_text\":\"View\",\"button_link\":\"#\",\"custom_class\":\"card-excerpt\"}',30,1,'2026-04-14 09:10:13','2026-04-15 12:54:29'),
(39,4,'restaurant_card','Grand Cafe Brinkman','{\"title\":\"Grand Cafe Brinkman\",\"introduction\":\"<p>Grand Cafe Brinkman is one of Haarlem\'s most iconic gathering places, located right on the Grote Markt.<\\/p>\",\"rating\":\"3.0\",\"event_id\":\"\",\"capacity\":\"100\",\"section_image\":[\"\\/assets\\/images\\/admin\\/f22b27e5d823e256d4b96192c587ed3b.png\"],\"cuisine\":[\"Modern\",\"Dutch\"],\"button_text\":\"View\",\"button_link\":\"#\",\"custom_class\":\"card-excerpt\"}',40,1,'2026-04-14 09:10:13','2026-04-15 12:55:53'),
(40,4,'restaurant_card','Cafe de Roemer','{\"title\":\"Cafe de Roemer\",\"introduction\":\"<p>Cafe de Roemer is a warm and inviting cafe-bar offering a mix of seafood and European dishes.<\\/p>\",\"rating\":\"4.0\",\"event_id\":\"\",\"capacity\":\"35\",\"section_image\":[\"\\/assets\\/images\\/admin\\/65e3c147784854aa4e5f6d8db7a39588.png\"],\"cuisine\":[\"Sea Food\",\"Dutch\"],\"button_text\":\"View\",\"button_link\":\"#\",\"custom_class\":\"card-excerpt\"}',50,1,'2026-04-14 09:10:13','2026-04-15 12:57:28'),
(41,4,'restaurant_card','Restaurant Fris','{\"title\":\"Restaurant Fris\",\"introduction\":\"<p>Fris brings a fresh and modern twist to vegetarian cuisine. Creative dishes full of color, texture, and flavor.<\\/p>\",\"rating\":\"3.0\",\"event_id\":\"\",\"capacity\":\"45\",\"section_image\":[\"\\/assets\\/images\\/admin\\/46ce9432d69a52153823de19508c2ec9.png\"],\"cuisine\":[\"French\",\"Dutch\"],\"button_text\":\"View\",\"button_link\":\"#\",\"custom_class\":\"card-excerpt\"}',60,1,'2026-04-14 09:10:13','2026-04-15 12:57:52'),
(42,4,'restaurant_card','Restaurant ML','{\"title\":\"Restaurant ML\",\"introduction\":\"<p>Restaurant ML offers a refined dining experience in the heart of Haarlem, known for its elegant atmosphere and beautifully.<\\/p>\",\"rating\":\"4.0\",\"event_id\":\"\",\"capacity\":\"60\",\"section_image\":[\"\\/assets\\/images\\/admin\\/2626845a69f005355138fa6c17fe40dc.png\"],\"cuisine\":[\"Sea Food\",\"Dutch\"],\"button_text\":\"View\",\"button_link\":\"#\",\"custom_class\":\"card-excerpt\"}',70,1,'2026-04-14 09:10:13','2026-04-15 12:58:09'),
(43,6,'welcome_banner',NULL,'{\"title\":\"Ratatouille\",\"introduction\":\"\",\"section_image\":[\"\\/assets\\/images\\/admin\\/4a3abcf6dff7e9c751084d41b050fd0b.png\"],\"button_text\":\"Book Reservation\",\"button_link\":\"\\/reservation\",\"custom_class\":\"\"}',0,1,'2026-04-16 10:21:27','2026-04-16 10:21:27'),
(44,9,'stories_hero','Stories matter','<p>Immerse yourself in captivating tales from the heart of Haarlem. Live performances, legends, and voices that stay with you long after the curtain falls.</p>',1,1,'2026-04-21 22:39:41','2026-04-21 22:39:41'),
(45,9,'what_is_stories','The Experience','<p>Stories is a unique festival strand celebrating the art of oral storytelling. Local and international performers take the stage to weave tales of myth, memory, and meaning — inviting audiences of all ages into worlds built entirely from words.</p><p>Whether you are discovering a new genre or returning to a childhood favourite, Stories offers an unforgettable evening under the Haarlem sky.</p><img class=\"wis-image\" src=\"/assets/images/stories/antonio-molinari-22FwbFrPvpU-unsplash.jpg\" alt=\"Audience at a storytelling event\">',2,1,'2026-04-21 22:39:41','2026-04-21 22:39:41'),
(46,9,'stories_preview','Take a Peek into the Stories','<div class=\"sp-mosaic\"><img src=\"/assets/images/stories/pexels-cottonbro-7319358.jpg\" alt=\"Performer on stage\"><img src=\"/assets/images/stories/Foto-Mister-Anansi-leert-de-wereld-lachen.jpeg\" alt=\"Mister Anansi\"><img src=\"/assets/images/stories/pexels-jibarofoto-2774556.jpg\" alt=\"Festival crowd\"><img src=\"/assets/images/stories/drama emotion.jpg\" alt=\"Dramatic moment\"><img src=\"/assets/images/stories/MisterAnansiLeendertJansen-1.jpg\" alt=\"Anansi portrait\"></div>',3,1,'2026-04-21 22:39:41','2026-04-21 22:39:41'),
(47,9,'storytelling_schedule','Storytelling Schedule','<div class=\"sched-day\" data-day=\"thu\"><div class=\"sched-cards\"><div class=\"sched-card\"><div class=\"sched-card-body\"><div class=\"sched-card-time\">20:00 – 21:30</div><h3 class=\"sched-card-title\">Mister Anansi</h3><div class=\"sched-card-meta\">Patronaat · Dutch &amp; English</div></div></div><div class=\"sched-card\"><div class=\"sched-card-body\"><div class=\"sched-card-time\">22:00 – 23:00</div><h3 class=\"sched-card-title\">Buurderij Haarlem</h3><div class=\"sched-card-meta\">Jopenkerk · Dutch</div></div></div></div></div><div class=\"sched-day\" data-day=\"fri\"><div class=\"sched-cards\"><div class=\"sched-card\"><div class=\"sched-card-body\"><div class=\"sched-card-time\">19:30 – 21:00</div><h3 class=\"sched-card-title\">The Sea Witch</h3><div class=\"sched-card-meta\">Teylers Museum · English</div></div></div><div class=\"sched-card\"><div class=\"sched-card-body\"><div class=\"sched-card-time\">21:30 – 23:00</div><h3 class=\"sched-card-title\">Corrie ten Boom: Her Story</h3><div class=\"sched-card-meta\">Grote Kerk · Dutch &amp; English</div></div></div></div></div><div class=\"sched-day\" data-day=\"sat\"><div class=\"sched-cards\"><div class=\"sched-card\"><div class=\"sched-card-body\"><div class=\"sched-card-time\">15:00 – 16:30</div><h3 class=\"sched-card-title\">Children\'s Tales</h3><div class=\"sched-card-meta\">Kenaupark · Dutch</div></div></div><div class=\"sched-card\"><div class=\"sched-card-body\"><div class=\"sched-card-time\">20:30 – 22:00</div><h3 class=\"sched-card-title\">Mister Anansi</h3><div class=\"sched-card-meta\">Patronaat · Dutch &amp; English</div></div></div></div></div><div class=\"sched-day\" data-day=\"sun\"><div class=\"sched-cards\"><div class=\"sched-card\"><div class=\"sched-card-body\"><div class=\"sched-card-time\">14:00 – 15:30</div><h3 class=\"sched-card-title\">Closing Stories</h3><div class=\"sched-card-meta\">Philharmonie · Dutch &amp; English</div></div></div></div></div>',4,1,'2026-04-21 22:39:41','2026-04-21 22:39:41'),
(48,6,'welcome_banner_card',NULL,'{\"title\":\"Opening Hours\",\"info\":\"<p><span>Tuesday - Sunday<\\/span><\\/p>\\r\\n<p><span>18:00 PM - 22:30 PM<\\/span><\\/p>\",\"custom_class\":\"\"}',0,1,'2026-04-22 19:46:22','2026-04-23 11:39:06'),
(49,6,'welcome_banner_card',NULL,'{\"title\":\"Cuisine\",\"info\":\"<p><span>French, Fish <br>Seafood, European<\\/span><\\/p>\",\"custom_class\":\"\"}',0,1,'2026-04-22 21:01:13','2026-04-23 11:39:52'),
(50,6,'welcome_banner_card',NULL,'{\"title\":\"Address\",\"info\":\"<p><span>Spaarne 96, 2011 CL Haarlem, Nederland<\\/span><\\/p>\",\"custom_class\":\"\"}',0,1,'2026-04-22 21:03:38','2026-04-23 11:35:48'),
(51,6,'welcome_banner_card',NULL,'{\"title\":\"Price\",\"info\":\"<p><span>Adult - 45 euro<\\/span><\\/p>\\r\\n<p><span>Kids - 22.50 euro<\\/span><\\/p>\",\"custom_class\":\"\"}',0,1,'2026-04-22 21:06:49','2026-04-23 11:34:56'),
(52,6,'text_block',NULL,'{\"title\":\"About Ratatouille\",\"sub_title\":\"about\",\"article\":\"<p>Ratatouille Food &amp; Wine is an award-winning Michelin-starred restaurant located in the historic heart of Haarlem. Known for its refined interpretation of modern French cuisine, the restaurant blends creativity, precision, and artistic presentation to deliver a dining experience that is both innovative and deeply rooted in culinary tradition. Each dish is thoughtfully composed, highlighting seasonal ingredients and bold yet balanced flavors designed to leave a lasting impression.<\\/p>\",\"custom_class\":\"\"}',0,1,'2026-04-23 11:50:37','2026-04-25 10:46:08'),
(53,6,'welcome_banner_card',NULL,'{\"title\":\"Signature Highlights\",\"info\":\"<p>Seasonal French-inspired tasting menus<\\/p>\",\"custom_class\":\"\"}',0,1,'2026-04-24 09:29:19','2026-04-25 10:43:16'),
(54,6,'welcome_banner_card',NULL,'{\"title\":\"Signature Highlights\",\"info\":\"<p>Locally sourced ingredients from Dutch farms<\\/p>\",\"custom_class\":\"\"}',0,1,'2026-04-24 09:39:48','2026-04-25 10:46:58'),
(55,6,'welcome_banner_card',NULL,'{\"title\":\"Signature Highlights\",\"info\":\"<p>Wine pairings curated by expert sommeliers<\\/p>\",\"custom_class\":\"\"}',0,1,'2026-04-24 09:40:32','2026-04-25 10:47:17'),
(58,6,'text_block',NULL,'{\"title\":\"Our Chef\",\"sub_title\":\"our chef\",\"article\":\"<p>Ratatouille Food &amp; Wine is led by Michelin-starred chef Jozua Jaring, whose vision shapes every part of our kitchen. Known for his creativity and technical skill, Chef Jaring blends classic French foundations with modern ideas, creating dishes that are both refined and surprising. His approach focuses on balance fresh seasonal ingredients, delicate flavors, and plates that tell a story.<\\/p>\",\"custom_class\":\"chef_section\"}',0,1,'2026-05-03 18:02:08','2026-05-03 18:02:08'),
(59,6,'gallery',NULL,'{\"title\": \"Our Chef\", \"section_image\": [{\"src\": \"/assets/images/admin/2cadb7f15c463c348761e1110b75dfb5.png\", \"alt\": \"Jozua Jaring\", \"caption\": \"Jozua Jaring\"}, {\"src\": \"/assets/images/admin/a13e35b88e9777b6066c20c6ff603801.png\", \"alt\": \"Chef preparing dough\", \"caption\": \"Chef in kitchen\"}], \"custom_class\": \"chef_section\"}',0,1,'2026-05-03 18:06:25','2026-05-06 12:25:21'),
(63,6,'reservation',NULL,'{\"title\":\"BOOK YOUR RESERVATION\",\"information\":\"Reservation Information\\r\\nReservation is mandatory.\\r\\nA €10 reservation fee per person will be charged when booking through the Haarlem Festival site.\\r\\nThis fee will be deducted from your final bill during your restaurant visit.\\r\\nSpecial Requests\\r\\nPlease let us know if you have any dietary restrictions, allergies, or accessibility needs (such as wheelchair access).\\u2028 You can add these requests during the reservation process so we can prepare for your visit.\",\"session\":[\"1st Session 18:00 - 19:30\",\"2nd Session 19:30 - 21:00\",\"3rd Session 21:00 - 22:30\"],\"date\":[\"Thursday July - 23rd\",\"Friday July - 24th\",\"Saturday July - 25th\",\"Sunday July - 26th\"],\"adultPrice\":\"45.00\",\"kidsPrice\":\"25\",\"button_text\":\"\",\"button_link\":\"\",\"custom_class\":\"\"}',0,1,'2026-05-04 08:27:17','2026-05-04 08:27:17'),
(64,1,'hero','Discover Haarlem','{\"heading\": \"Discover Haarlem\", \"hero_image\": \"/assets/images/home/home-hero.jpg\", \"hero_image_alt\": \"View over Haarlem and the Grote Kerk\"}',1,1,'2026-05-05 23:35:02','2026-05-05 23:35:02'),
(65,1,'feature','The Heart of Haarlem','{\"title\": \"The Heart of Haarlem\", \"article\": \"Haarlem is one of the Netherlands\' most charming and historic cities, a place where old-world beauty meets modern culture. Known for its cobblestone streets, iconic windmills, and Golden Age architecture, the city offers a warm and welcoming atmosphere for every visitor.\\n\\nWander through lively squares, explore boutique shops, discover hidden courtyards, or relax by the Spaarne River. Haarlem is also a city of creativity and taste, home to award-winning restaurants, vibrant markets, and world-class museums such as the Frans Hals Museum.\\n\\nWhether you\'re here for food, art, history, or festivals, Haarlem invites you to slow down and enjoy its unique charm. Just minutes from Amsterdam and the Dutch coastline, the city blends convenience with authenticity and stays lively all year round with parades, markets, and music.\", \"button_text\": \"Go To Events\", \"button_link\": \"#home-activities\"}',2,1,'2026-05-05 23:35:02','2026-05-05 23:35:02'),
(66,1,'gallery','Home Gallery','{\"item_one_label\": \"Molen de Adriaan\", \"item_one_image\": \"/assets/images/home/home-gallery-molen-de-adriaan.jpg\", \"item_one_alt\": \"Molen de Adriaan along the water\", \"item_two_label\": \"Grote Kerk\", \"item_two_image\": \"/assets/images/home/home-gallery-grote-kerk.jpg\", \"item_two_alt\": \"Grote Kerk at sunset\", \"item_three_label\": \"Old Haarlem\", \"item_three_image\": \"/assets/images/home/home-gallery-old-town.jpg\", \"item_three_alt\": \"Historic Haarlem street and canal\", \"item_four_label\": \"The Weigh House\", \"item_four_image\": \"/assets/images/home/home-gallery-the-weigh-house.jpg\", \"item_four_alt\": \"The Weigh House in Haarlem\"}',3,1,'2026-05-05 23:35:03','2026-05-05 23:35:03'),
(67,1,'image_left','Grote Markt, Haarlem','{\"heading\": \"Grote Markt, Haarlem\", \"body\": \"The Haarlemse Markt is one of the most iconic and long-standing markets in the Netherlands, taking place in the historic Grote Markt, right in the heart of Haarlem\'s city centre. With roots dating back centuries, the market has long been a central part of daily life in the city and continues to attract both locals and visitors alike.\\n\\nHeld every Saturday, with a smaller version on Mondays, the market offers a wide and colourful mix of stalls. Visitors can browse fresh seasonal fruits and vegetables, cheeses, bread, flowers, clothing, fabrics, and international street food.\", \"image\": \"/assets/images/home/home-grote-markt.jpg\", \"image_alt\": \"Crowded Grote Markt in Haarlem\"}',4,1,'2026-05-05 23:35:03','2026-05-05 23:35:03'),
(68,1,'image_right','Historic Canal Houses','{\"heading\": \"Historic Canal Houses\", \"body\": \"Haarlem\'s historic canal houses are a defining feature of the city\'s character and architectural heritage. Lining the city\'s canals, these narrow yet elegant buildings date back to the 16th and 17th centuries, when Haarlem prospered during the Dutch Golden Age.\\n\\nOriginally built for merchants, craftsmen, and traders, the canal houses reflect the city\'s economic growth. Many still display stepped or bell-shaped gables, wooden beams, and ornate brickwork while remaining carefully preserved for modern use.\", \"image_one\": \"/assets/images/home/home-canal-house-one.jpg\", \"image_one_alt\": \"Historic canal houses beside the Spaarne River\", \"image_two\": \"/assets/images/home/home-canal-house-two.jpg\", \"image_two_alt\": \"Bridge and canal houses in Haarlem\"}',5,1,'2026-05-05 23:35:03','2026-05-05 23:35:03'),
(69,1,'cards_grid','What you can do in Haarlem','{\"heading\": \"What you can do in Haarlem\", \"card_one_title\": \"Stories\", \"card_one_text\": \"Haarlem is a city full of character, shaped by centuries of history and the people who call it home. Explore personal stories, local legends, hidden alleys, and traditions that give the city its unique charm.\", \"card_one_image\": \"/assets/images/home/home-stories.jpg\", \"card_one_alt\": \"Audience in a theatre in Haarlem\", \"card_one_button_text\": \"Read More\", \"card_one_button_link\": \"/stories\", \"card_two_title\": \"History\", \"card_two_text\": \"Haarlem is a city rich in history and culture, shaped by centuries of art, trade, and craftsmanship. From medieval beginnings to the Dutch Golden Age, its churches, market squares, and streets tell the story.\", \"card_two_image\": \"/assets/images/home/home-history.jpg\", \"card_two_alt\": \"The Grote Kerk in Haarlem\", \"card_two_button_text\": \"Read More\", \"card_two_button_link\": \"/history\", \"card_three_title\": \"Restaurants\", \"card_three_text\": \"Enjoy a taste of Haarlem in its vibrant restaurant scene. From elegant Michelin-starred establishments to welcoming neighbourhood cafés, the city offers something memorable for every visitor.\", \"card_three_image\": \"/assets/images/home/home-restaurants.jpg\", \"card_three_alt\": \"Restaurant street scene in Haarlem\", \"card_three_button_text\": \"Read More\", \"card_three_button_link\": \"/yummy\", \"card_four_title\": \"Dance!\", \"card_four_text\": \"Feel the bass, the lights, and the crowd. Discover the artists, find the venues, and build your own line-up for the most energetic nights of the festival.\", \"card_four_image\": \"/assets/images/home/home-dance.jpg\", \"card_four_alt\": \"Crowded dance floor with red lights\", \"card_four_button_text\": \"Read More\", \"card_four_button_link\": \"/home#home-dance\", \"card_five_title\": \"Jazz\", \"card_five_text\": \"Every summer, Haarlem comes alive with the sound of jazz. Expect great live music, sunny terraces, and an unforgettable festival atmosphere in the heart of the city.\", \"card_five_image\": \"/assets/images/home/home-jazz.jpg\", \"card_five_alt\": \"Jazz performance on stage in Haarlem\", \"card_five_button_text\": \"Read More\", \"card_five_button_link\": \"/home#home-jazz\"}',6,1,'2026-05-05 23:35:03','2026-05-05 23:35:03'),
(70,1,'transport','Transportation','{\"heading\": \"Transportation\", \"intro\": \"Getting around Haarlem is simple, fast, and convenient. The city\'s compact layout makes it easy to explore by foot or bike, while a reliable bus network connects every major neighbourhood. Haarlem Central Station is the main transport hub for Amsterdam, Schiphol, Zandvoort, Leiden, and other Dutch cities.\", \"list_intro\": \"Travellers can move through the city using several transport options:\", \"item_one\": \"Trains: Haarlem Central Station provides fast connections, only 15 minutes to Amsterdam and 10 minutes to Zandvoort Beach.\", \"item_two\": \"Buses: A wide network of local and regional buses makes it easy to reach attractions, events, and nearby towns.\", \"item_three\": \"Bicycles: Haarlem is a true cycling city with safe bike paths and plenty of rental options.\", \"item_four\": \"Walking: Most of the historic centre is walkable, with shops, cafes, museums, and markets all within short distance.\", \"image\": \"/assets/images/home/home-transport.jpg\", \"image_alt\": \"Buses outside Haarlem Central Station\", \"button_text\": \"View On Map\", \"button_link\": \"https://maps.google.com/?q=Haarlem+Centraal\"}',7,1,'2026-05-05 23:35:03','2026-05-05 23:35:03'),
(71,11,'history_hero',NULL,'{\"title_line_one\": \"Discover\", \"title_line_two\": \"Haarlem\", \"intro\": \"Walk through 800 years of Dutch history in one unforgettable journey. From majestic churches to hidden courtyards, every step reveals a story.\", \"primary_button_text\": \"Book Your Adventure\", \"primary_button_link\": \"#history-cta\", \"secondary_button_text\": \"Explore Route\", \"secondary_button_link\": \"#history-route\", \"hero_image\": \"/assets/images/history/history-hero-banner.jpg\"}',1,1,'2026-05-05 23:36:38','2026-05-05 23:36:38'),
(72,11,'history_timeline',NULL,'{\"eyebrow\": \"Our Heritage\", \"heading\": \"The History of Haarlem\", \"item_one_title\": \"Century of Origins\", \"item_one_text\": \"Founded in the 10th century, Haarlem is one of the oldest cities in the Netherlands. Receiving its city rights in 1245, it became a vital center of commerce, art, and culture that would shape Dutch identity for centuries to come.\", \"item_one_text_secondary\": \"Just 20 kilometers west of Amsterdam, Haarlem has always stood as a proud rival and often surpassed the capital in cultural achievements during the Dutch Golden Age.\", \"item_one_image\": \"/assets/images/history/history-century-of-origins.jpg\", \"item_one_label\": \"City Center\", \"item_one_caption\": \"Grote Markt at Golden Hour\", \"item_two_title\": \"The Golden Age\", \"item_two_text\": \"The 17th century saw Haarlem flourish as Europe\'s artistic capital. Home to legendary painters Frans Hals, Jacob van Ruisdael, and Adriaen van Ostade, the Haarlem School of painting rivaled even Amsterdam in prestige.\", \"item_two_text_secondary\": \"The city\'s economy thrived on textile production, earning the nickname \'Linen City\'. This was also the epicenter of history\'s first speculative bubble, the legendary Tulip Mania of 1637.\", \"item_two_image\": \"/assets/images/history/history-golden-age.jpg\", \"item_two_label\": \"Dutch Masters\", \"item_two_caption\": \"Frans Hals and Contemporaries\", \"item_three_title\": \"72-73\", \"item_three_text\": \"The Siege of Haarlem during the Eighty Years\' War remains a defining moment in Dutch history. For seven months, citizens heroically resisted Spanish forces in what became a symbol of Dutch determination for independence.\", \"item_three_text_secondary\": \"Though the city ultimately fell, this resistance inspired the nation and is still commemorated today as a testament to the indomitable Dutch spirit.\", \"item_three_image\": \"/assets/images/history/history-siege-of-haarlem.jpg\", \"item_three_label\": \"Living Heritage\", \"item_three_caption\": \"Market Day Tradition\"}',2,1,'2026-05-05 23:36:38','2026-05-05 23:36:38'),
(73,11,'history_gallery',NULL,'{\"eyebrow\": \"Visual Journey\", \"heading\": \"What Awaits You\", \"card_one_label\": \"Hidden Gems\", \"card_one_title\": \"Secret Courtyards\", \"card_one_text\": \"Discover 17th-century hofjes - peaceful gardens hidden behind ancient wooden gates.\", \"card_one_image\": \"/assets/images/history/history-hidden-gems.jpg\", \"card_two_label\": \"Iconic Landmark\", \"card_two_title\": \"Molen de Adriaan\", \"card_two_text\": \"Climb the iconic windmill for panoramic views over the historic Spaarne River.\", \"card_two_image\": \"/assets/images/history/history-molen-sunset.jpg\", \"card_three_label\": \"Gothic Marvel\", \"card_three_title\": \"Sacred Architecture\", \"card_three_text\": \"Stand beneath soaring Gothic arches where Mozart once played the famous Muller organ.\", \"card_three_image\": \"/assets/images/history/history-sacred-architecture.jpg\"}',3,1,'2026-05-05 23:36:38','2026-05-05 23:36:38'),
(74,11,'history_featured_locations',NULL,'{\"eyebrow\": \"Must-See Destinations\", \"heading\": \"Featured Locations\", \"intro\": \"From medieval churches to world-class museums, each stop on our tour reveals centuries of Dutch heritage.\", \"one_label\": \"Tour Highlight\", \"one_title\": \"Grote Kerk (St. Bavo\'s Church)\", \"one_text\": \"The magnificent Gothic cathedral has dominated Haarlem\'s skyline for over 500 years. Its tower is visible across the region and the church houses the famous Muller organ once played by Mozart.\", \"one_badge\": \"Est. 1520\", \"one_image\": \"/assets/images/history/history-grote-kerk.jpg\", \"one_feature_one\": \"78m Tower Height\", \"one_feature_two\": \"Famous Muller Organ\", \"one_feature_three\": \"Gothic Architecture\", \"one_feature_four\": \"Frans Hals Tomb\", \"one_button_text\": \"Explore This Location\", \"one_button_link\": \"#history-route\", \"two_label\": \"Iconic Landmark\", \"two_title\": \"Molen de Adriaan\", \"two_text\": \"This iconic Dutch windmill stands proudly on the banks of the Spaarne River. Originally built in 1779, it was reconstructed in 2002 and now serves as a working museum.\", \"two_badge\": \"Est. 1779\", \"two_image\": \"/assets/images/history/history-molen-de-adriaan.jpg\", \"two_feature_one\": \"Built 1779\", \"two_feature_two\": \"Panoramic Views\", \"two_feature_three\": \"Working Museum\", \"two_feature_four\": \"Spaarne River\", \"two_button_text\": \"Explore This Location\", \"two_button_link\": \"#history-route\"}',4,1,'2026-05-05 23:36:38','2026-05-05 23:36:38'),
(75,11,'history_route',NULL,'{\"eyebrow\": \"Complete Walking Route\", \"heading\": \"9 Remarkable Venues\", \"intro\": \"Each stop tells a unique chapter of Haarlem\'s rich 800-year story.\", \"venue_one_title\": \"Grote Kerk\", \"venue_one_text\": \"15th-century Gothic cathedral\", \"venue_one_link\": \"#history-featured\", \"venue_two_title\": \"Grote Markt\", \"venue_two_text\": \"Historic market square since medieval times\", \"venue_three_title\": \"De Hallen\", \"venue_three_text\": \"Frans Hals Museum in 17th-century almshouses\", \"venue_four_title\": \"Proveniershof\", \"venue_four_text\": \"Beautiful hidden courtyard garden\", \"venue_five_title\": \"Jopenkerk\", \"venue_five_text\": \"Brewery in a converted 15th-century church\", \"venue_five_badge\": \"Break\", \"venue_six_title\": \"Waalse Kerk\", \"venue_six_text\": \"16th-century French Reformed church\", \"venue_seven_title\": \"Molen de Adriaan\", \"venue_seven_text\": \"Iconic windmill on the Spaarne River\", \"venue_eight_title\": \"Amsterdamse Poort\", \"venue_eight_text\": \"Last remaining medieval city gate\", \"venue_nine_title\": \"Hof van Bakenes\", \"venue_nine_text\": \"Oldest hofje in Haarlem, founded 1395\", \"button_text\": \"Explore Route\", \"button_link\": \"#history-cta\"}',5,1,'2026-05-05 23:36:38','2026-05-05 23:36:38'),
(76,11,'history_info',NULL,'{\"item_one_value\": \"2.5 Hours\", \"item_one_label\": \"Tour Duration\", \"item_two_value\": \"Max 12\", \"item_two_label\": \"Group Size\", \"item_three_value\": \"Bavo Church\", \"item_three_label\": \"Start Point\", \"item_four_value\": \"Thu - Sun\", \"item_four_label\": \"Available Days\"}',6,1,'2026-05-05 23:36:38','2026-05-05 23:36:38'),
(77,11,'history_cta',NULL,'{\"eyebrow\": \"Your Adventure Awaits\", \"title_line_one\": \"Ready to\", \"title_line_two\": \"Explore?\", \"body\": \"Book your walking tour today and discover why Haarlem has been captivating visitors for centuries. Experience history come alive through our expert-guided tours.\", \"background_image\": \"/assets/images/history/history-ready-to-explore.jpg\", \"primary_button_text\": \"Book Your Adventure\", \"primary_button_link\": \"#\", \"secondary_button_text\": \"Explore Route\", \"secondary_button_link\": \"#history-route\"}',7,1,'2026-05-05 23:36:38','2026-05-05 23:36:38'),
(78,6,'gallery',NULL,'{\"title\": \"Ratatouille Gallery\", \"section_image\": [{\"src\": \"/assets/images/admin/50b7fcf9c294ace5f04de95cf796239f.png\", \"alt\": \"Ratatouille restaurant exterior\", \"caption\": \"Ratatouille restaurant exterior\"}, {\"src\": \"/assets/images/admin/46ce9432d69a52153823de19508c2ec9.png\", \"alt\": \"Ratatouille dining room\", \"caption\": \"Ratatouille dining room\"}, {\"src\": \"/assets/images/admin/9189bbfd792f2e4a3181bd5ed87ff890.png\", \"alt\": \"Ratatouille terrace entrance\", \"caption\": \"Ratatouille terrace entrance\"}], \"custom_class\": \"ratatouille_gallery\"}',0,1,'2026-05-06 08:20:45','2026-05-06 12:25:21'),
(79,12,'welcome_banner','Bistro Toujours','{\"title\":\"Bistro Toujours\",\"introduction\":\"<p><img src=\\\"..\\/..\\/..\\/assets\\/images\\/admin\\/0e09a08a6666c16ba318a7f15676217d.png\\\" width=\\\"1536\\\" height=\\\"521\\\" alt=\\\"\\\"><\\/p>\",\"section_image\":[\"..\\/..\\/..\\/assets\\/images\\/admin\\/0e09a08a6666c16ba318a7f15676217d.png\"],\"section_image_alt_text\":\"\",\"section_image_caption\":\"\",\"button_text\":\"Book Reservation\",\"button_link\":\"\\/reservation\",\"custom_class\":\"\"}',1,1,'2026-05-06 10:56:58','2026-05-06 12:35:30'),
(80,12,'welcome_banner_card','Opening Hours','{\"title\": \"Opening Hours\", \"info\": \"<p>Tuesday - Sunday</p><p>17:30 PM - 22:00 PM</p>\", \"custom_class\": \"\"}',2,1,'2026-05-06 10:56:58','2026-05-06 10:56:58'),
(81,12,'welcome_banner_card','Cuisine','{\"title\": \"Cuisine\", \"info\": \"<p>Dutch, fish and<br>seafood, European</p>\", \"custom_class\": \"\"}',3,1,'2026-05-06 10:56:58','2026-05-06 10:56:58'),
(82,12,'welcome_banner_card','Address','{\"title\": \"Address\", \"info\": \"<p>Oude Groenmarkt 10-12, 2011 HL Haarlem, Nederland</p>\", \"custom_class\": \"\"}',4,1,'2026-05-06 10:56:58','2026-05-06 10:56:58'),
(83,12,'welcome_banner_card','Price','{\"title\":\"Price\",\"info\":\"<p>Adult - 35 euro<\\/p>\\r\\n<p>Kids - 17.50 euro<\\/p>\",\"custom_class\":\"\"}',5,1,'2026-05-06 10:56:58','2026-05-06 12:39:37'),
(84,12,'text_block','About Bistro Toujours','{\"title\": \"About Bistro Toujours\", \"sub_title\": \"about\", \"article\": \"<p>Bistro Toujours is a contemporary urban French bistro located in the heart of Haarlem, offering a dining experience that balances refined cuisine with a relaxed and approachable atmosphere. The restaurant is known for bringing classic French bistro traditions into a modern context, creating dishes that feel familiar while still offering depth, creativity, and quality.</p>\", \"custom_class\": \"\"}',6,1,'2026-05-06 10:56:58','2026-05-06 10:56:58'),
(85,12,'welcome_banner_card','Signature Highlights','{\"title\": \"Signature Highlights\", \"info\": \"<p>Stylish and accessible bistro-style dishes</p>\", \"custom_class\": \"\"}',7,1,'2026-05-06 10:56:58','2026-05-06 10:56:58'),
(86,12,'welcome_banner_card','Signature Highlights','{\"title\": \"Signature Highlights\", \"info\": \"<p>Seasonal French bistro specials</p>\", \"custom_class\": \"\"}',8,1,'2026-05-06 10:56:58','2026-05-06 10:56:58'),
(87,12,'welcome_banner_card','Signature Highlights','{\"title\": \"Signature Highlights\", \"info\": \"<p>Seafood dishes including fish and shellfish</p>\", \"custom_class\": \"\"}',9,1,'2026-05-06 10:56:58','2026-05-06 10:56:58'),
(88,12,'text_block','Our Chef','{\"title\": \"Our Chef\", \"sub_title\": \"our chef\", \"article\": \"<p>The kitchen at Bistro Toujours is led by Chef Jarno Smak, who is responsible for overseeing the restaurant\'s culinary vision and daily kitchen operations. As head chef, he plays a central role in menu development, quality control, and the overall consistency of the dishes served. His leadership ensures that the kitchen maintains high standards while staying true to the bistro concept of approachable, flavour-focused cuisine.</p>\", \"custom_class\": \"chef_section\"}',10,1,'2026-05-06 10:56:58','2026-05-06 10:56:58'),
(89,12,'gallery','Our Chef','{\"title\":\"Our Chef\",\"section_image\":[{\"src\":\"..\\/..\\/..\\/assets\\/images\\/admin\\/13f05d79dbb74d713ae688a443b9173e.png\",\"alt\":\"Fine dining plating at Bistro Toujours\",\"caption\":\"Fine dining\"},{\"src\":\"..\\/..\\/..\\/assets\\/images\\/admin\\/97b1ca747fa0432c79eaa88293f60a9a.png\",\"alt\":\"Chef in kitchen at Bistro Toujours\",\"caption\":\"Chef in kitchen\"}],\"custom_class\":\"chef_section\"}',11,1,'2026-05-06 10:56:58','2026-05-06 12:36:33'),
(90,12,'gallery','View Images','{\"title\":\"View Images\",\"section_image\":[{\"src\":\"..\\/..\\/..\\/assets\\/images\\/admin\\/af1139fee93755d988a548027351a985.png\",\"alt\":\"Bistro Toujours terrace entrance\",\"caption\":\"Bistro Toujours terrace entrance\"},{\"src\":\"..\\/..\\/..\\/assets\\/images\\/admin\\/25e40a71c091f67e384014305c7bd12e.png\",\"alt\":\"Bistro Toujours terrace seating\",\"caption\":\"Bistro Toujours terrace seating\"},{\"src\":\"..\\/..\\/..\\/assets\\/images\\/admin\\/5492b10add5b6f51949e03a60bb6d991.png\",\"alt\":\"Bistro Toujours evening terrace\",\"caption\":\"Bistro Toujours evening terrace\"}],\"custom_class\":\"bistro_gallery\"}',12,1,'2026-05-06 10:56:58','2026-05-06 12:37:56'),
(91,12,'reservation','Book Your Reservation','{\"title\": \"BOOK YOUR RESERVATION\", \"information\": \"Reservation Information\\nReservation is mandatory.\\nA €10 reservation fee per person will be charged when booking through the Haarlem Festival site.\\nThis fee will be deducted from your final bill during your restaurant visit.\\nSpecial Requests\\nPlease let us know if you have any dietary restrictions, allergies, or accessibility needs. You can add these requests during the reservation process so we can prepare for your visit.\", \"date\": [\"Thursday July - 23rd\", \"Friday July - 24th\", \"Saturday July - 25th\", \"Sunday July - 26th\"], \"session\": [\"1st Session 17:30 - 19:00\", \"2nd Session 19:00 - 20:30\", \"3rd Session 20:30 - 22:00\"], \"adultPrice\": \"35.00\", \"kidsPrice\": \"17.50\", \"button_text\": \"Add To Program\", \"button_link\": \"/yummy/bistro-toujours/book-reservation\", \"custom_class\": \"\"}',13,1,'2026-05-06 10:56:58','2026-05-06 10:56:58');
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
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  `paid_at` datetime DEFAULT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `fk_payment_order` (`order_id`),
  CONSTRAINT `fk_payment_order` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment`
--

LOCK TABLES `payment` WRITE;
/*!40000 ALTER TABLE `payment` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `payment` ENABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `program_item`
--

LOCK TABLES `program_item` WRITE;
/*!40000 ALTER TABLE `program_item` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `program_item` ENABLE KEYS */;
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
  `order_id` bigint(20) unsigned NOT NULL,
  `event_id` bigint(20) unsigned NOT NULL,
  `adult_count` int(255) DEFAULT NULL,
  `child_count` int(255) DEFAULT NULL,
  `adult_price` float DEFAULT NULL,
  `child_price` float DEFAULT NULL,
  `total_price` float NOT NULL,
  PRIMARY KEY (`restaurant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurant`
--

LOCK TABLES `restaurant` WRITE;
/*!40000 ALTER TABLE `restaurant` DISABLE KEYS */;
set autocommit=0;
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
  `version` varchar(128) DEFAULT NULL,
  `filename` varchar(255) NOT NULL,
  `checksum` char(32) NOT NULL,
  `applied_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`filename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schema_migrations`
--

LOCK TABLES `schema_migrations` WRITE;
/*!40000 ALTER TABLE `schema_migrations` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `schema_migrations` VALUES
(NULL,'01_schema.sql','6c7896fccd804b95b5e091ff01683647','2026-05-05 23:31:08'),
(NULL,'02_page_section_types.sql','4f7effe59bee0743483c3aa8ce534ba8','2026-05-05 23:36:24'),
(NULL,'03_stories_seed_fix.sql','7a9ee404eab02dc75fd1bdcae06464b2','2026-05-05 23:36:24'),
(NULL,'04_history_page.sql','91c5c0bab09ddee0290faa6f5c123c0c','2026-05-05 23:36:38'),
(NULL,'04_page_section_restaurant_card_alias.sql','24c777ddf89a81f589fab2c9a765c9f2','2026-05-05 23:36:24'),
(NULL,'05_page_section_restaurant_card_only.sql','d61f55b99aad1c3557dcd00532180020','2026-05-05 23:36:25'),
(NULL,'06_image_caption.sql','a971f2f7bf5779af78e5e2fbe79f8cab','2026-05-05 23:31:08'),
(NULL,'07_page_section_welcome_banner_card.sql','ff5ba280fc46fdcdef7df8bbc7fc8cde','2026-05-05 23:31:08'),
(NULL,'08_drop_page_section_image_id.sql','fbf8da7a4051cc625b937cc98bc24210','2026-05-05 23:31:08'),
(NULL,'10_home_page_refresh.sql','84f44e6491eec66d74dbfeef7ba14d8b','2026-05-05 23:36:25');
/*!40000 ALTER TABLE `schema_migrations` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `test_ok`
--

DROP TABLE IF EXISTS `test_ok`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `test_ok` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `test_ok`
--

LOCK TABLES `test_ok` WRITE;
/*!40000 ALTER TABLE `test_ok` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `test_ok` ENABLE KEYS */;
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
  `order_item_id` bigint(20) unsigned NOT NULL,
  `qr_token` char(64) NOT NULL,
  `status` enum('valid','scanned','cancelled') NOT NULL DEFAULT 'valid',
  `scanned_at` datetime DEFAULT NULL,
  `ticket_type_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`ticket_id`),
  UNIQUE KEY `qr_token` (`qr_token`),
  KEY `fk_ticket_order_ticket` (`order_item_id`),
  KEY `ticket_type` (`ticket_type_id`),
  CONSTRAINT `fk_ticket_order_ticket` FOREIGN KEY (`order_item_id`) REFERENCES `order_item` (`order_item_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket`
--

LOCK TABLES `ticket` WRITE;
/*!40000 ALTER TABLE `ticket` DISABLE KEYS */;
set autocommit=0;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket_type`
--

LOCK TABLES `ticket_type` WRITE;
/*!40000 ALTER TABLE `ticket_type` DISABLE KEYS */;
set autocommit=0;
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `user` VALUES
(1,'admin','admin','admin@haarlemfest.test','$2y$12$vIvVL6qdxkHgsNXQ6lbzZePy973snNNAnbRP5jegW6O40R6mXHpHG','Admin','User',NULL,NULL,1,'2026-03-17 12:27:31','2026-03-17 12:27:31'),
(2,'employee','eline','employee@haarlemfest.test','$2y$12$vIvVL6qdxkHgsNXQ6lbzZePy973snNNAnbRP5jegW6O40R6mXHpHG','Eline','Scanner',NULL,NULL,1,'2026-03-17 12:27:31','2026-03-17 12:27:31'),
(3,'customer','samj','customer1@haarlemfest.test','$2y$12$vIvVL6qdxkHgsNXQ6lbzZePy973snNNAnbRP5jegW6O40R6mXHpHG','Sam','Jansen','+31 6 11111111',4,1,'2026-03-17 12:27:31','2026-03-17 12:27:31'),
(4,'customer','noordv','customer2@haarlemfest.test','$2y$12$vIvVL6qdxkHgsNXQ6lbzZePy973snNNAnbRP5jegW6O40R6mXHpHG','Noor','de Vries','+31 6 22222222',NULL,1,'2026-03-17 12:27:31','2026-03-17 12:27:31'),
(5,'customer','123test','123test@gmail.com','$2y$12$dOxg/3Iu0IbSI9c/lLWLZeT6C1hcFGMKkY7cZsTy2.v4mIiZvVCdi','123test','123test',NULL,NULL,1,'2026-03-17 12:50:21','2026-03-17 12:50:21');
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

-- Dump completed on 2026-05-06 13:07:48
