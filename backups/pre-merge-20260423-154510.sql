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
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
(101,'/assets/images/stories/drama emotion.jpg','Dramatic storytelling moment',NULL,1,'2026-04-21 22:39:41');
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
(9,'Stories','stories','published','2026-04-21 22:39:41','2026-04-21 22:39:41');
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
  `section_type` enum('cta','text_block','image_text','restaurant_card','welcome_banner','welcome_banner_card','gallery','haarlem_unique','stories_hero','what_is_stories','stories_preview','storytelling_schedule') NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`section_id`),
  KEY `fk_section_page` (`page_id`),
  CONSTRAINT `fk_section_page` FOREIGN KEY (`page_id`) REFERENCES `page` (`page_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_section`
--

LOCK TABLES `page_section` WRITE;
/*!40000 ALTER TABLE `page_section` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `page_section` VALUES
(13,4,'welcome_banner',NULL,'{\"title\":\"HaarlemÔÇÖs Culinary Scene\",\"introduction\":\"<p><span>Haarlem&rsquo;s food scene blends old world charm with bold modern flavours. From canal side bistros to Michelin-starred dining rooms, the city invites every visitor to taste its culture one plate at a time.<\\/span><\\/p>\",\"section_image\":[\"\\/assets\\/images\\/admin\\/d001d5761fc8c25784c317da8037ac46.jpg\"],\"button_text\":\"Explore Restaurants\",\"button_link\":\"restaurants_card\",\"custom_class\":\"\"}',0,1,'2026-03-19 23:22:39','2026-03-21 21:18:54'),
(14,4,'text_block',NULL,'{\"title\":\"Haarlem Food Culture\",\"sub_title\":\"A City Shaped by Flavour\",\"article\":\"<p>Haarlem&rsquo;s food culture is rooted in centuries of craftsmanship and trade. During the Dutch Golden Age, the city flourished as a center for brewing, fishing, and artisanal production, bringing spices, grains, and fresh ingredients from across Europe. These new influences blended naturally with local traditions, shaping a culinary identity that still defines Haarlem today. From historic cheese markets to long-standing breweries, the city&rsquo;s flavours carry gentle echoes of its past. Classic Dutch dishes like stamppot, warm stews, and baked treats remain part of Haarlem&rsquo;s everyday comfort, while ingredients once introduced through global trade continue to add depth and character. Haarlem&rsquo;s kitchens have always balanced tradition with curiosity, and that spirit of discovery still lives in the restaurants and caf&eacute;s throughout the city today.<\\/p>\",\"custom_class\":\"\"}',0,1,'2026-03-20 00:29:21','2026-03-23 16:54:04'),
(24,4,'gallery',NULL,'{\"title\":\"Haarlem taste\",\"section_image\":[{\"src\":\"..\\/..\\/..\\/assets\\/images\\/admin\\/58549e96e9ded7ad19668a7ebe898c87.png\",\"alt\":\"Dutch Apple Pie\",\"caption\":\"Dutch Apple Pie\"},{\"src\":\"..\\/..\\/..\\/assets\\/images\\/admin\\/bd8a1a7e565ecb21de7471dbb14b02c5.png\",\"alt\":\"raw herring eaten with onions and pickles\",\"caption\":\"Raw herring eaten with onions and pickles\"},{\"src\":\"..\\/..\\/..\\/assets\\/images\\/admin\\/ecd8bb27ed3e6b85c4cd9a9b1b0c768a.png\",\"alt\":\"Boterkoek (Butter Cake)\",\"caption\":\"Boterkoek (Butter Cake)\"},{\"src\":\"..\\/..\\/..\\/assets\\/images\\/admin\\/be2a72fb39e2dba60a70d3c400bc2356.png\",\"alt\":\"Poffertjes\",\"caption\":\"Poffertjes\"},{\"src\":\"..\\/..\\/..\\/assets\\/images\\/admin\\/8f8695b7a35f0b137c2005a1bfac2cad.png\",\"alt\":\"Tompouce\",\"caption\":\"Tompouce\"},{\"src\":\"..\\/..\\/..\\/assets\\/images\\/admin\\/97002ff941343028b9e416c46ff52f2c.png\",\"alt\":\"A fresh warm Stroopwafels\",\"caption\":\"A fresh warm Stroopwafels\"}],\"custom_class\":\"\"}',0,1,'2026-03-23 21:57:40','2026-03-23 22:49:58'),
(27,4,'haarlem_unique',NULL,'{\"title\":\"What Makes Haarlem Unique Today\",\"content\":\"<p><span>Modern Haarlem is a place where old-world charm meets creative cooking. Walk through the city and you\'ll find riverside caf&eacute;s buzzing with conversation, intimate bistros in narrow streets, and elegant restaurants tucked inside historic buildings. Local chefs mix classic Dutch flavours with French, Mediterranean, and Asian inspirations, creating dishes that feel both familiar and adventurous. With Michelin-starred restaurants, refined vegan spots, and cozy neighbourhood eateries, Haarlem celebrates food as both a craft and an experience<\\/span><\\/p>\",\"section_image\":[\"..\\/..\\/..\\/assets\\/images\\/admin\\/4e534de8380c28372d0a2fe97ee621b0.png\",\"..\\/..\\/..\\/assets\\/images\\/admin\\/f407a03b9f9fccf755dd87ee89afff91.png\"],\"custom_class\":\"\"}',0,1,'2026-04-06 18:31:32','2026-04-06 22:05:22'),
(28,4,'gallery',NULL,'{\"title\":\"haarlem taste\",\"section_image\":[\"..\\/..\\/..\\/assets\\/images\\/admin\\/a7d9e700300328153c2012c248ad4375.png\",\"..\\/..\\/..\\/assets\\/images\\/admin\\/437a1f53b6194fcc0b683bc5be359c2e.png\",\"..\\/..\\/..\\/assets\\/images\\/admin\\/04e617ea94e337430669cb14852775f5.png\",\"..\\/..\\/..\\/assets\\/images\\/admin\\/ae9b423c21d23bafa0e5f22420626c7a.png\",\"..\\/..\\/..\\/assets\\/images\\/admin\\/5b427a6673509f3072c2681e58ae99a0.png\",\"..\\/..\\/..\\/assets\\/images\\/admin\\/7b124b40cae8b2137b1c45cdac527019.png\",\"..\\/..\\/..\\/assets\\/images\\/admin\\/986cec73538d1a339f6cfad5a46ac692.png\",\"..\\/..\\/..\\/assets\\/images\\/admin\\/1e9571777c2066ac6a2b7c5133fdf641.png\"],\"custom_class\":\"\"}',0,1,'2026-04-06 22:29:08','2026-04-06 22:29:08'),
(36,4,'restaurant_card','Ratatouille','{\"title\":\"Ratatouille\",\"introduction\":\"<p>Ratatouille Food &amp; Wine is one of Haarlem\'s top culinary destinations, offering an unforgettable Michelin-starred.<\\/p>\",\"rating\":\"4.0\",\"event_id\":\"\",\"capacity\":\"52\",\"section_image\":\"\",\"cuisine\":[\"Sea Food\",\"French\",\"European\"],\"button_text\":\"View\",\"button_link\":\"\\/yummy\\/ratatouille\",\"custom_class\":\"card-excerpt\"}',10,1,'2026-04-14 09:10:13','2026-04-21 17:19:29'),
(37,4,'restaurant_card','Bistro Toujours','{\"title\":\"Bistro Toujours\",\"introduction\":\"<p>Bistro Toujours captures the charm of a classic French bistro while adding its own modern Haarlem identity.<\\/p>\",\"rating\":\"3.0\",\"event_id\":\"\",\"capacity\":\"48\",\"section_image\":[\"\\/assets\\/images\\/admin\\/7f1f1bd237a89d276e36cbaf5975dd35.png\"],\"cuisine\":[\"Sea Food\",\"Dutch\",\"European\"],\"button_text\":\"View\",\"button_link\":\"#\",\"custom_class\":\"card-excerpt\"}',20,1,'2026-04-14 09:10:13','2026-04-15 12:54:08'),
(38,4,'restaurant_card','New Vegas','{\"title\":\"New Vegas\",\"introduction\":\"<p>New Vegas brings a fresh and modern twist to vegetarian cuisine. With creative dishes full of color, texture, and flavor.<\\/p>\",\"rating\":\"3.0\",\"event_id\":\"\",\"capacity\":\"36\",\"section_image\":[\"\\/assets\\/images\\/admin\\/7ab2bfaed66701b84661293875bde717.png\"],\"cuisine\":[\"Vegan\"],\"button_text\":\"View\",\"button_link\":\"#\",\"custom_class\":\"card-excerpt\"}',30,1,'2026-04-14 09:10:13','2026-04-15 12:54:29'),
(39,4,'restaurant_card','Grand Cafe Brinkman','{\"title\":\"Grand Cafe Brinkman\",\"introduction\":\"<p>Grand Cafe Brinkman is one of Haarlem\'s most iconic gathering places, located right on the Grote Markt.<\\/p>\",\"rating\":\"3.0\",\"event_id\":\"\",\"capacity\":\"100\",\"section_image\":[\"\\/assets\\/images\\/admin\\/f22b27e5d823e256d4b96192c587ed3b.png\"],\"cuisine\":[\"Modern\",\"Dutch\"],\"button_text\":\"View\",\"button_link\":\"#\",\"custom_class\":\"card-excerpt\"}',40,1,'2026-04-14 09:10:13','2026-04-15 12:55:53'),
(40,4,'restaurant_card','Cafe de Roemer','{\"title\":\"Cafe de Roemer\",\"introduction\":\"<p>Cafe de Roemer is a warm and inviting cafe-bar offering a mix of seafood and European dishes.<\\/p>\",\"rating\":\"4.0\",\"event_id\":\"\",\"capacity\":\"35\",\"section_image\":[\"\\/assets\\/images\\/admin\\/65e3c147784854aa4e5f6d8db7a39588.png\"],\"cuisine\":[\"Sea Food\",\"Dutch\"],\"button_text\":\"View\",\"button_link\":\"#\",\"custom_class\":\"card-excerpt\"}',50,1,'2026-04-14 09:10:13','2026-04-15 12:57:28'),
(41,4,'restaurant_card','Restaurant Fris','{\"title\":\"Restaurant Fris\",\"introduction\":\"<p>Fris brings a fresh and modern twist to vegetarian cuisine. Creative dishes full of color, texture, and flavor.<\\/p>\",\"rating\":\"3.0\",\"event_id\":\"\",\"capacity\":\"45\",\"section_image\":[\"\\/assets\\/images\\/admin\\/46ce9432d69a52153823de19508c2ec9.png\"],\"cuisine\":[\"French\",\"Dutch\"],\"button_text\":\"View\",\"button_link\":\"#\",\"custom_class\":\"card-excerpt\"}',60,1,'2026-04-14 09:10:13','2026-04-15 12:57:52'),
(42,4,'restaurant_card','Restaurant ML','{\"title\":\"Restaurant ML\",\"introduction\":\"<p>Restaurant ML offers a refined dining experience in the heart of Haarlem, known for its elegant atmosphere and beautifully.<\\/p>\",\"rating\":\"4.0\",\"event_id\":\"\",\"capacity\":\"60\",\"section_image\":[\"\\/assets\\/images\\/admin\\/2626845a69f005355138fa6c17fe40dc.png\"],\"cuisine\":[\"Sea Food\",\"Dutch\"],\"button_text\":\"View\",\"button_link\":\"#\",\"custom_class\":\"card-excerpt\"}',70,1,'2026-04-14 09:10:13','2026-04-15 12:58:09'),
(43,6,'welcome_banner',NULL,'{\"title\":\"Ratatouille\",\"introduction\":\"\",\"section_image\":[\"\\/assets\\/images\\/admin\\/4a3abcf6dff7e9c751084d41b050fd0b.png\"],\"button_text\":\"Book Reservation\",\"button_link\":\"\\/reservation\",\"custom_class\":\"\"}',0,1,'2026-04-16 10:21:27','2026-04-16 10:21:27'),
(44,9,'stories_hero','Stories matter','<p>Immerse yourself in captivating tales from the heart of Haarlem. Live performances, legends, and voices that stay with you long after the curtain falls.</p>',1,1,'2026-04-21 22:39:41','2026-04-21 22:39:41'),
(45,9,'what_is_stories','The Experience','<p>Stories is a unique festival strand celebrating the art of oral storytelling. Local and international performers take the stage to weave tales of myth, memory, and meaning ÔÇö inviting audiences of all ages into worlds built entirely from words.</p><p>Whether you are discovering a new genre or returning to a childhood favourite, Stories offers an unforgettable evening under the Haarlem sky.</p><img class=\"wis-image\" src=\"/assets/images/stories/antonio-molinari-22FwbFrPvpU-unsplash.jpg\" alt=\"Audience at a storytelling event\">',2,1,'2026-04-21 22:39:41','2026-04-21 22:39:41'),
(46,9,'stories_preview','Take a Peek into the Stories','<div class=\"sp-mosaic\"><img src=\"/assets/images/stories/pexels-cottonbro-7319358.jpg\" alt=\"Performer on stage\"><img src=\"/assets/images/stories/Foto-Mister-Anansi-leert-de-wereld-lachen.jpeg\" alt=\"Mister Anansi\"><img src=\"/assets/images/stories/pexels-jibarofoto-2774556.jpg\" alt=\"Festival crowd\"><img src=\"/assets/images/stories/drama emotion.jpg\" alt=\"Dramatic moment\"><img src=\"/assets/images/stories/MisterAnansiLeendertJansen-1.jpg\" alt=\"Anansi portrait\"></div>',3,1,'2026-04-21 22:39:41','2026-04-21 22:39:41'),
(47,9,'storytelling_schedule','Storytelling Schedule','<div class=\"sched-day\" data-day=\"thu\"><div class=\"sched-cards\"><div class=\"sched-card\"><div class=\"sched-card-body\"><div class=\"sched-card-time\">20:00 ÔÇô 21:30</div><h3 class=\"sched-card-title\">Mister Anansi</h3><div class=\"sched-card-meta\">Patronaat ┬À Dutch &amp; English</div></div></div><div class=\"sched-card\"><div class=\"sched-card-body\"><div class=\"sched-card-time\">22:00 ÔÇô 23:00</div><h3 class=\"sched-card-title\">Buurderij Haarlem</h3><div class=\"sched-card-meta\">Jopenkerk ┬À Dutch</div></div></div></div></div><div class=\"sched-day\" data-day=\"fri\"><div class=\"sched-cards\"><div class=\"sched-card\"><div class=\"sched-card-body\"><div class=\"sched-card-time\">19:30 ÔÇô 21:00</div><h3 class=\"sched-card-title\">The Sea Witch</h3><div class=\"sched-card-meta\">Teylers Museum ┬À English</div></div></div><div class=\"sched-card\"><div class=\"sched-card-body\"><div class=\"sched-card-time\">21:30 ÔÇô 23:00</div><h3 class=\"sched-card-title\">Corrie ten Boom: Her Story</h3><div class=\"sched-card-meta\">Grote Kerk ┬À Dutch &amp; English</div></div></div></div></div><div class=\"sched-day\" data-day=\"sat\"><div class=\"sched-cards\"><div class=\"sched-card\"><div class=\"sched-card-body\"><div class=\"sched-card-time\">15:00 ÔÇô 16:30</div><h3 class=\"sched-card-title\">Children\'s Tales</h3><div class=\"sched-card-meta\">Kenaupark ┬À Dutch</div></div></div><div class=\"sched-card\"><div class=\"sched-card-body\"><div class=\"sched-card-time\">20:30 ÔÇô 22:00</div><h3 class=\"sched-card-title\">Mister Anansi</h3><div class=\"sched-card-meta\">Patronaat ┬À Dutch &amp; English</div></div></div></div></div><div class=\"sched-day\" data-day=\"sun\"><div class=\"sched-cards\"><div class=\"sched-card\"><div class=\"sched-card-body\"><div class=\"sched-card-time\">14:00 ÔÇô 15:30</div><h3 class=\"sched-card-title\">Closing Stories</h3><div class=\"sched-card-meta\">Philharmonie ┬À Dutch &amp; English</div></div></div></div></div>',4,1,'2026-04-21 22:39:41','2026-04-21 22:39:41'),
(48,6,'welcome_banner_card',NULL,'{\"title\":\"Opening Hours\",\"info\":\"<p><span>Tuesday - Sunday<\\/span><\\/p>\\r\\n<p><span>18:00 PM - 22:30 PM<\\/span><\\/p>\",\"custom_class\":\"\"}',0,1,'2026-04-22 19:46:22','2026-04-23 11:39:06'),
(49,6,'welcome_banner_card',NULL,'{\"title\":\"Cuisine\",\"info\":\"<p><span>French, Fish <br>Seafood, European<\\/span><\\/p>\",\"custom_class\":\"\"}',0,1,'2026-04-22 21:01:13','2026-04-23 11:39:52'),
(50,6,'welcome_banner_card',NULL,'{\"title\":\"Address\",\"info\":\"<p><span>Spaarne 96, 2011 CL Haarlem, Nederland<\\/span><\\/p>\",\"custom_class\":\"\"}',0,1,'2026-04-22 21:03:38','2026-04-23 11:35:48'),
(51,6,'welcome_banner_card',NULL,'{\"title\":\"Price\",\"info\":\"<p><span>Adult - 45 euro<\\/span><\\/p>\\r\\n<p><span>Kids - 22.50 euro<\\/span><\\/p>\",\"custom_class\":\"\"}',0,1,'2026-04-22 21:06:49','2026-04-23 11:34:56'),
(52,6,'text_block',NULL,'{\"title\":\"About Ratatouille\",\"sub_title\":\"about\",\"article\":\"<p><span>Ratatouille Food &amp; Wine is an award-winning Michelin-starred restaurant located in the historic heart of Haarlem. Known for its refined interpretation of modern French cuisine, the restaurant blends creativity, precision, and artistic presentation to deliver a dining experience that is both innovative and deeply rooted in culinary tradition. Each dish is thoughtfully composed, highlighting seasonal ingredients and bold yet balanced flavors designed to leave a lasting impression.<\\/span><\\/p>\",\"custom_class\":\"\"}',0,1,'2026-04-23 11:50:37','2026-04-23 11:50:37');
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
  `version` varchar(128) NOT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schema_migrations`
--

LOCK TABLES `schema_migrations` WRITE;
/*!40000 ALTER TABLE `schema_migrations` DISABLE KEYS */;
set autocommit=0;
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

-- Dump completed on 2026-04-23 13:45:11
