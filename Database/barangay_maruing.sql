-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: barangay_maruing
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `about_content`
--

DROP TABLE IF EXISTS `about_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `about_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image_path` varchar(255) DEFAULT NULL,
  `history` longtext DEFAULT NULL,
  `vision` text DEFAULT NULL,
  `mission` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `about_content`
--

LOCK TABLES `about_content` WRITE;
/*!40000 ALTER TABLE `about_content` DISABLE KEYS */;
INSERT INTO `about_content` VALUES (1,'uploads/about/about_1792713252_6adaa22432d7d.png','Wahahha','Hello','HIi','2026-10-22 23:48:44','2026-10-22 23:54:12');
/*!40000 ALTER TABLE `about_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `announcements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `what` varchar(255) DEFAULT NULL,
  `when_date` varchar(255) DEFAULT NULL,
  `where_place` varchar(255) DEFAULT NULL,
  `who` varchar(255) DEFAULT NULL,
  `why` text DEFAULT NULL,
  `content` text NOT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `status` enum('published','draft','archived') DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcements`
--

LOCK TABLES `announcements` WRITE;
/*!40000 ALTER TABLE `announcements` DISABLE KEYS */;
INSERT INTO `announcements` VALUES (4,'dsgs','gf','dsfs','xhf','gnf','bcvb','','uploads/announcements/1792493789_6ad748dd5a5cb.jpg','published','2026-10-20 10:56:29','2026-10-20 10:56:29');
/*!40000 ALTER TABLE `announcements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `barangay_information`
--

DROP TABLE IF EXISTS `barangay_information`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `barangay_information` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `barangay_name` varchar(100) NOT NULL,
  `municipality` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barangay_information`
--

LOCK TABLES `barangay_information` WRITE;
/*!40000 ALTER TABLE `barangay_information` DISABLE KEYS */;
INSERT INTO `barangay_information` VALUES (1,'Maruing','Lapuyan','Zamboanga del Sur','Welcome to the official information system of Barangay Maruing.','Barangay Hall, Maruing, Lapuyan, Zamboanga del Sur','09123456789','info@barangaymaruing.com','2026-08-28 12:44:13','2026-08-28 12:44:13');
/*!40000 ALTER TABLE `barangay_information` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feedback_chats`
--

DROP TABLE IF EXISTS `feedback_chats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feedback_chats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `thread_id` varchar(100) NOT NULL,
  `sender_name` varchar(255) NOT NULL,
  `sender_type` enum('user','admin') NOT NULL DEFAULT 'user',
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feedback_chats`
--

LOCK TABLES `feedback_chats` WRITE;
/*!40000 ALTER TABLE `feedback_chats` DISABLE KEYS */;
INSERT INTO `feedback_chats` VALUES (1,'thread_6ac8c44e03d10','Dash','user','sdfs',1,'2026-10-09 10:50:13'),(2,'thread_6ac8c6f8c038f','Daisy','user','[Service: Barangay Clearance]\n\nGsdds',1,'2026-10-09 10:51:12'),(3,'thread_6ac8c7e329720','sdas','user','[Service: Certificate of Indigency]\n\nfdfsd',1,'2026-10-09 11:10:29'),(4,'thread_6ac8c7e329720','Admin','admin','fasfas\\',0,'2026-10-09 11:40:43'),(5,'thread_6ac8d3674ea7e','Dale','user','[Service: Business Clearance]\n\nMamaligya ko ',1,'2026-10-09 11:50:00'),(6,'thread_6ac8d3674ea7e','Admin','admin','Ay pagbuot',1,'2026-10-09 11:50:49');
/*!40000 ALTER TABLE `feedback_chats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery_folders`
--

DROP TABLE IF EXISTS `gallery_folders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gallery_folders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_folders`
--

LOCK TABLES `gallery_folders` WRITE;
/*!40000 ALTER TABLE `gallery_folders` DISABLE KEYS */;
INSERT INTO `gallery_folders` VALUES (1,'Meetings','Barangay assemblies, council sessions, and committee hearings','2026-10-13 01:40:16','2026-10-13 01:40:16'),(2,'Sports Fest','Inter-purok basketball, volleyball, and community games','2026-10-13 01:40:16','2026-10-13 01:40:16'),(3,'Community Services','Medical missions, relief goods distribution, and clean-up drives','2026-10-13 01:40:16','2026-10-13 01:40:16'),(4,'Hello','ahsfhas','2026-10-13 01:53:11','2026-10-13 01:53:11'),(5,'Tour','ssd','2026-10-20 02:28:20','2026-10-20 02:28:20');
/*!40000 ALTER TABLE `gallery_folders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery_media`
--

DROP TABLE IF EXISTS `gallery_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gallery_media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `folder_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `folder_id` (`folder_id`),
  CONSTRAINT `fk_gallery_folder` FOREIGN KEY (`folder_id`) REFERENCES `gallery_folders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_media`
--

LOCK TABLES `gallery_media` WRITE;
/*!40000 ALTER TABLE `gallery_media` DISABLE KEYS */;
INSERT INTO `gallery_media` VALUES (1,1,'uploads/gallery/1791856320_6acd8ec08bec5_images.jpg','1791856320_6acd8ec08bec5_images.jpg','images',59609,'2026-10-13 01:52:00'),(2,2,'uploads/gallery/1791856332_6acd8eccae131_Oishi_Veggie_Flutes__Six-Panel_Crunch_Campaign.png','1791856332_6acd8eccae131_Oishi_Veggie_Flutes__Six-Panel_Crunch_Campaign.png','Shiii',2546893,'2026-10-13 01:52:12'),(3,3,'uploads/gallery/1791856346_6acd8edac4285_make_this_4k_20261006101241.jpg','1791856346_6acd8edac4285_make_this_4k_20261006101241.jpg','gdsgs',400516,'2026-10-13 01:52:26'),(5,5,'uploads/gallery/1792463370_6ad6d20aad646_e5a7399d-4c60-4b2a-af2f-9c8b66e56192.jpg','1792463370_6ad6d20aad646_e5a7399d-4c60-4b2a-af2f-9c8b66e56192.jpg','Tour',155353,'2026-10-20 02:29:30');
/*!40000 ALTER TABLE `gallery_media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `officials`
--

DROP TABLE IF EXISTS `officials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `officials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `official_key` varchar(50) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `officials`
--

LOCK TABLES `officials` WRITE;
/*!40000 ALTER TABLE `officials` DISABLE KEYS */;
INSERT INTO `officials` VALUES (1,'captain','Flutes','BARANGAY CAPTAIN','uploads/officials/official_captain_1792033619.jpg','To our residents, partners, and every member of our community, thank you for your trust, cooperation, and unwavering support. At Barangay Maruing, we remain committed to delivering honest and transparent public service that promotes safety, unity, and progress for all. Together, let us continue building a barangay where every family feels heard, respected, and supported.','active','2026-10-15 02:55:24','2026-10-15 03:15:47'),(2,'secretary','dgsd','BARANGAY Secretary','uploads/officials/official_secretary_1792034185.png',NULL,'active','2026-10-15 03:16:25','2026-10-15 03:16:25'),(3,'treasurer','Name','BARANGAY Treasurer','https://via.placeholder.com/150/e2e8f0/64748b?text=Photo',NULL,'active','2026-10-15 03:21:29','2026-10-15 03:21:29'),(4,'kagawad_1','Name','BARANGAY KAGAWAD','https://via.placeholder.com/150/e2e8f0/64748b?text=Photo',NULL,'active','2026-10-15 03:21:29','2026-10-15 03:21:29'),(5,'kagawad_2','Name','BARANGAY KAGAWAD','https://via.placeholder.com/150/e2e8f0/64748b?text=Photo',NULL,'active','2026-10-15 03:21:29','2026-10-15 03:21:29'),(6,'kagawad_3','Name','BARANGAY KAGAWAD','https://via.placeholder.com/150/e2e8f0/64748b?text=Photo',NULL,'active','2026-10-15 03:21:29','2026-10-15 03:21:29'),(7,'kagawad_4','Name','BARANGAY KAGAWAD','https://via.placeholder.com/150/e2e8f0/64748b?text=Photo',NULL,'active','2026-10-15 03:21:29','2026-10-15 03:21:29'),(8,'kagawad_5','Name','BARANGAY KAGAWAD','https://via.placeholder.com/150/e2e8f0/64748b?text=Photo',NULL,'active','2026-10-15 03:21:29','2026-10-15 03:21:29'),(9,'kagawad_6','Name','BARANGAY KAGAWAD','https://via.placeholder.com/150/e2e8f0/64748b?text=Photo',NULL,'active','2026-10-15 03:21:29','2026-10-15 03:21:29'),(10,'kagawad_7','Name','BARANGAY KAGAWAD','https://via.placeholder.com/150/e2e8f0/64748b?text=Photo',NULL,'active','2026-10-15 03:21:29','2026-10-15 03:21:29');
/*!40000 ALTER TABLE `officials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(100) DEFAULT 'bi-file-earmark-text',
  `requirements` text DEFAULT NULL,
  `fee` varchar(100) DEFAULT 'Free',
  `processing_time` varchar(100) DEFAULT '15-30 Minutes',
  `button_text` varchar(100) DEFAULT 'Request Document',
  `button_url` varchar(255) DEFAULT 'contact.php',
  `display_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (1,'Barangay Clearance','Request a Barangay Clearance for employment, banking, or other legal purposes. Essential for verifying your residency and good standing.','bi-file-earmark-text','Valid Government ID, 2x2 Photo, Cedula / Community Tax Certificate','Free','15-30 Minutes','Request Document','contact.php',1,'active','2026-10-21 03:31:00','2026-10-21 03:40:06'),(2,'Certificate of Indigency','Obtain a Certificate of Indigency to avail of government assistance, scholarships, and medical support programs.','bi-house-heart','Valid Government ID, Proof of Low Income / Endorsement','Free','15-30 Minutes','Request Document','contact.php',2,'active','2026-10-21 03:31:00','2026-10-21 03:40:06'),(3,'Business Clearance','Required for all new and renewing businesses operating within the barangay jurisdiction prior to Mayor\'s permit application.','bi-briefcase','DTI / SEC Registration, Contract of Lease or Title, Previous Barangay Clearance (if renewal)','Free','15-30 Minutes','Request Document','contact.php',3,'active','2026-10-21 03:31:00','2026-10-21 03:40:06'),(4,'Certificate of Residency','Proof of your residency within the barangay. Often required for school enrollments, ID applications, and other local transactions.','bi-person-badge','Valid Government ID, Proof of Billing or Landlord Certification','Free','15-30 Minutes','Request Document','contact.php',4,'active','2026-10-21 03:31:00','2026-10-21 03:40:06'),(6,'bd','sgs','bi-cash-coin','sdgs','Free','1-2 Business day','Request Document','contact.php',5,'active','2026-10-21 03:52:05','2026-10-21 03:52:05');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sk_officials`
--

DROP TABLE IF EXISTS `sk_officials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sk_officials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `official_key` varchar(50) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sk_officials`
--

LOCK TABLES `sk_officials` WRITE;
/*!40000 ALTER TABLE `sk_officials` DISABLE KEYS */;
INSERT INTO `sk_officials` VALUES (1,'chairperson','Hon. Ryan M. Tubos','SK CHAIRPERSON','uploads/sk_officials/sk_chairperson_1792463520.jpg','Tabang','active','2026-10-15 04:07:23','2026-10-20 02:32:00'),(2,'secretary','Hon. Rosemer G. Banagan','SK SECRETARY','public/image/SK Officials/Rosmer G. Banagan.jpeg',NULL,'active','2026-10-15 04:07:23','2026-10-15 04:07:23'),(3,'treasurer','Hon. Judith G. Tambagel','SK TREASURER','public/image/SK Officials/Judith G. Tambagel.jpeg',NULL,'active','2026-10-15 04:07:23','2026-10-15 04:07:23'),(4,'kagawad_1','Hon. Renzarr L. Roxas','SK KAGAWAD 1','public/image/SK Officials/Renzarr L. Roxas.jpeg',NULL,'active','2026-10-15 04:07:23','2026-10-15 04:07:23'),(5,'kagawad_2','Hon. Benie L. Ligao','SK KAGAWAD 2','public/image/SK Officials/Benie L. Ligao.jpeg',NULL,'active','2026-10-15 04:07:23','2026-10-15 04:07:23'),(6,'kagawad_3','Hon. Jhon Mark Charton D. Singue','SK KAGAWAD 3','public/image/SK Officials/Jhon Mark Charton D. Singue.jpeg',NULL,'active','2026-10-15 04:07:23','2026-10-15 04:07:23'),(7,'kagawad_4','Hon. Mark A. Alpay','SK KAGAWAD 4','public/image/SK Officials/Mark A. Alpay.jpeg',NULL,'active','2026-10-15 04:07:23','2026-10-15 04:07:23'),(8,'kagawad_5','Hon. Marlon B. Amplao','SK KAGAWAD 5','public/image/SK Officials/Marlon B. Amplao.jpeg',NULL,'active','2026-10-15 04:07:23','2026-10-15 04:07:23'),(9,'kagawad_6','Hon. Verdelyn G. Halilong','SK KAGAWAD 6','public/image/SK Officials/Verdelyn G. Halilong.jpeg',NULL,'active','2026-10-15 04:07:23','2026-10-15 04:07:23'),(10,'kagawad_7','Hon. Mc Felco T Bugao','SK KAGAWAD 7','public/image/SK Officials/Mc Felco T. Bugao.jpeg',NULL,'active','2026-10-15 04:07:23','2026-10-15 04:07:23');
/*!40000 ALTER TABLE `sk_officials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sm_legend_spots`
--

DROP TABLE IF EXISTS `sm_legend_spots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sm_legend_spots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `house_number` varchar(50) DEFAULT NULL,
  `husband_name` varchar(150) DEFAULT NULL,
  `spouse_name` varchar(150) DEFAULT NULL,
  `children` text DEFAULT NULL,
  `legend_id` int(11) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `details` text DEFAULT NULL,
  `marker_width` int(11) DEFAULT 40,
  `marker_height` int(11) DEFAULT 40,
  `top_position` decimal(8,4) DEFAULT NULL,
  `left_position` decimal(8,4) DEFAULT NULL,
  `marker_image` varchar(255) DEFAULT NULL,
  `house_image` varchar(255) DEFAULT NULL,
  `pos_x` decimal(8,4) DEFAULT NULL,
  `pos_y` decimal(8,4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `legend_id` (`legend_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sm_legend_spots`
--

LOCK TABLES `sm_legend_spots` WRITE;
/*!40000 ALTER TABLE `sm_legend_spots` DISABLE KEYS */;
/*!40000 ALTER TABLE `sm_legend_spots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sm_legends`
--

DROP TABLE IF EXISTS `sm_legends`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sm_legends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `icon_path` varchar(255) DEFAULT NULL,
  `color_hex` varchar(7) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sm_legends`
--

LOCK TABLES `sm_legends` WRITE;
/*!40000 ALTER TABLE `sm_legends` DISABLE KEYS */;
INSERT INTO `sm_legends` VALUES (1,'Household',NULL,'#00FF00',1),(2,'School',NULL,'#0000FF',1),(3,'Sari-Sari Store',NULL,'#FFA500',1),(4,'Chapel',NULL,'#800080',1);
/*!40000 ALTER TABLE `sm_legends` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sm_purok_1_spots`
--

DROP TABLE IF EXISTS `sm_purok_1_spots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sm_purok_1_spots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `house_number` varchar(50) DEFAULT NULL,
  `husband_name` varchar(150) DEFAULT NULL,
  `spouse_name` varchar(150) DEFAULT NULL,
  `children` text DEFAULT NULL,
  `legend_id` int(11) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `details` text DEFAULT NULL,
  `marker_width` int(11) DEFAULT 40,
  `marker_height` int(11) DEFAULT 40,
  `top_position` decimal(8,4) DEFAULT NULL,
  `left_position` decimal(8,4) DEFAULT NULL,
  `marker_image` varchar(255) DEFAULT NULL,
  `house_image` varchar(255) DEFAULT NULL,
  `pos_x` decimal(8,4) DEFAULT NULL,
  `pos_y` decimal(8,4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `legend_id` (`legend_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sm_purok_1_spots`
--

LOCK TABLES `sm_purok_1_spots` WRITE;
/*!40000 ALTER TABLE `sm_purok_1_spots` DISABLE KEYS */;
INSERT INTO `sm_purok_1_spots` VALUES (1,'565','asd','gds',NULL,NULL,'',NULL,40,40,41.1600,52.5000,'uploads/markers/1791422572_Icon2.png','uploads/houses/1791422572_house_ChatGPTImageSep27202611_53_21AM.png',NULL,NULL,'2026-10-08 01:22:52'),(2,'46754','Dash','Dahsesa',NULL,NULL,'',NULL,40,40,59.4300,33.4800,'uploads/markers/1791424545_Icon2.png','uploads/houses/1791424545_house_images.jpg',NULL,NULL,'2026-10-08 01:55:45'),(4,'24535','5235','dfdf','[\"Duday\"]',NULL,'',NULL,40,40,24.4300,74.9200,'uploads/markers/1792491347_Icon2.png','uploads/houses/1792491347_house_BarangayTabonForestVillage.png',NULL,NULL,'2026-10-20 10:15:47');
/*!40000 ALTER TABLE `sm_purok_1_spots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sm_purok_2_spots`
--

DROP TABLE IF EXISTS `sm_purok_2_spots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sm_purok_2_spots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `house_number` varchar(50) DEFAULT NULL,
  `husband_name` varchar(150) DEFAULT NULL,
  `spouse_name` varchar(150) DEFAULT NULL,
  `children` text DEFAULT NULL,
  `legend_id` int(11) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `details` text DEFAULT NULL,
  `marker_width` int(11) DEFAULT 40,
  `marker_height` int(11) DEFAULT 40,
  `top_position` decimal(8,4) DEFAULT NULL,
  `left_position` decimal(8,4) DEFAULT NULL,
  `marker_image` varchar(255) DEFAULT NULL,
  `house_image` varchar(255) DEFAULT NULL,
  `pos_x` decimal(8,4) DEFAULT NULL,
  `pos_y` decimal(8,4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `legend_id` (`legend_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sm_purok_2_spots`
--

LOCK TABLES `sm_purok_2_spots` WRITE;
/*!40000 ALTER TABLE `sm_purok_2_spots` DISABLE KEYS */;
INSERT INTO `sm_purok_2_spots` VALUES (1,'31','Helloo','Malupiton',NULL,NULL,'',NULL,40,40,36.3200,53.1400,'uploads/markers/1791422755_Icon2.png','uploads/houses/1791422755_house_TabonBarangayhall.jpg',NULL,NULL,'2026-10-08 01:25:55');
/*!40000 ALTER TABLE `sm_purok_2_spots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sm_purok_3_spots`
--

DROP TABLE IF EXISTS `sm_purok_3_spots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sm_purok_3_spots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `house_number` varchar(50) DEFAULT NULL,
  `husband_name` varchar(150) DEFAULT NULL,
  `spouse_name` varchar(150) DEFAULT NULL,
  `children` text DEFAULT NULL,
  `legend_id` int(11) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `details` text DEFAULT NULL,
  `marker_width` int(11) DEFAULT 40,
  `marker_height` int(11) DEFAULT 40,
  `top_position` decimal(8,4) DEFAULT NULL,
  `left_position` decimal(8,4) DEFAULT NULL,
  `marker_image` varchar(255) DEFAULT NULL,
  `house_image` varchar(255) DEFAULT NULL,
  `pos_x` decimal(8,4) DEFAULT NULL,
  `pos_y` decimal(8,4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `legend_id` (`legend_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sm_purok_3_spots`
--

LOCK TABLES `sm_purok_3_spots` WRITE;
/*!40000 ALTER TABLE `sm_purok_3_spots` DISABLE KEYS */;
INSERT INTO `sm_purok_3_spots` VALUES (1,'234','nfd','as',NULL,NULL,'',NULL,40,40,50.0000,50.0000,'uploads/markers/1791423250_images.jpg','uploads/houses/1791423250_house_images.jpg',NULL,NULL,'2026-10-08 01:34:10');
/*!40000 ALTER TABLE `sm_purok_3_spots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sm_purok_4_spots`
--

DROP TABLE IF EXISTS `sm_purok_4_spots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sm_purok_4_spots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `house_number` varchar(50) DEFAULT NULL,
  `husband_name` varchar(150) DEFAULT NULL,
  `spouse_name` varchar(150) DEFAULT NULL,
  `children` text DEFAULT NULL,
  `legend_id` int(11) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `details` text DEFAULT NULL,
  `marker_width` int(11) DEFAULT 40,
  `marker_height` int(11) DEFAULT 40,
  `top_position` decimal(8,4) DEFAULT NULL,
  `left_position` decimal(8,4) DEFAULT NULL,
  `marker_image` varchar(255) DEFAULT NULL,
  `house_image` varchar(255) DEFAULT NULL,
  `pos_x` decimal(8,4) DEFAULT NULL,
  `pos_y` decimal(8,4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `legend_id` (`legend_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sm_purok_4_spots`
--

LOCK TABLES `sm_purok_4_spots` WRITE;
/*!40000 ALTER TABLE `sm_purok_4_spots` DISABLE KEYS */;
INSERT INTO `sm_purok_4_spots` VALUES (1,'213','dfh','zvzxc',NULL,NULL,'',NULL,40,40,50.0000,50.0000,'uploads/markers/1791423267_images.jpg','uploads/houses/1791423267_house_images.jpg',NULL,NULL,'2026-10-08 01:34:27');
/*!40000 ALTER TABLE `sm_purok_4_spots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sm_purok_5_spots`
--

DROP TABLE IF EXISTS `sm_purok_5_spots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sm_purok_5_spots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `house_number` varchar(50) DEFAULT NULL,
  `husband_name` varchar(150) DEFAULT NULL,
  `spouse_name` varchar(150) DEFAULT NULL,
  `children` text DEFAULT NULL,
  `legend_id` int(11) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `details` text DEFAULT NULL,
  `marker_width` int(11) DEFAULT 40,
  `marker_height` int(11) DEFAULT 40,
  `top_position` decimal(8,4) DEFAULT NULL,
  `left_position` decimal(8,4) DEFAULT NULL,
  `marker_image` varchar(255) DEFAULT NULL,
  `house_image` varchar(255) DEFAULT NULL,
  `pos_x` decimal(8,4) DEFAULT NULL,
  `pos_y` decimal(8,4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `legend_id` (`legend_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sm_purok_5_spots`
--

LOCK TABLES `sm_purok_5_spots` WRITE;
/*!40000 ALTER TABLE `sm_purok_5_spots` DISABLE KEYS */;
INSERT INTO `sm_purok_5_spots` VALUES (1,'214','dfb',',kgfgh',NULL,NULL,'',NULL,40,40,50.0000,50.0000,'uploads/markers/1791423287_images.jpg','uploads/houses/1791423287_house_images.jpg',NULL,NULL,'2026-10-08 01:34:47');
/*!40000 ALTER TABLE `sm_purok_5_spots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sm_purok_6_spots`
--

DROP TABLE IF EXISTS `sm_purok_6_spots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sm_purok_6_spots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `house_number` varchar(50) DEFAULT NULL,
  `husband_name` varchar(150) DEFAULT NULL,
  `spouse_name` varchar(150) DEFAULT NULL,
  `children` text DEFAULT NULL,
  `legend_id` int(11) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `details` text DEFAULT NULL,
  `marker_width` int(11) DEFAULT 40,
  `marker_height` int(11) DEFAULT 40,
  `top_position` decimal(8,4) DEFAULT NULL,
  `left_position` decimal(8,4) DEFAULT NULL,
  `marker_image` varchar(255) DEFAULT NULL,
  `house_image` varchar(255) DEFAULT NULL,
  `pos_x` decimal(8,4) DEFAULT NULL,
  `pos_y` decimal(8,4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `legend_id` (`legend_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sm_purok_6_spots`
--

LOCK TABLES `sm_purok_6_spots` WRITE;
/*!40000 ALTER TABLE `sm_purok_6_spots` DISABLE KEYS */;
INSERT INTO `sm_purok_6_spots` VALUES (1,'5','dfhdf','zvdsd',NULL,NULL,'',NULL,40,40,50.0000,50.0000,'uploads/markers/1791423302_images.jpg','uploads/houses/1791423302_house_images.jpg',NULL,NULL,'2026-10-08 01:35:02');
/*!40000 ALTER TABLE `sm_purok_6_spots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sm_purok_7_spots`
--

DROP TABLE IF EXISTS `sm_purok_7_spots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sm_purok_7_spots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `house_number` varchar(50) DEFAULT NULL,
  `husband_name` varchar(150) DEFAULT NULL,
  `spouse_name` varchar(150) DEFAULT NULL,
  `children` text DEFAULT NULL,
  `legend_id` int(11) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `details` text DEFAULT NULL,
  `marker_width` int(11) DEFAULT 40,
  `marker_height` int(11) DEFAULT 40,
  `top_position` decimal(8,4) DEFAULT NULL,
  `left_position` decimal(8,4) DEFAULT NULL,
  `marker_image` varchar(255) DEFAULT NULL,
  `house_image` varchar(255) DEFAULT NULL,
  `pos_x` decimal(8,4) DEFAULT NULL,
  `pos_y` decimal(8,4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `legend_id` (`legend_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sm_purok_7_spots`
--

LOCK TABLES `sm_purok_7_spots` WRITE;
/*!40000 ALTER TABLE `sm_purok_7_spots` DISABLE KEYS */;
INSERT INTO `sm_purok_7_spots` VALUES (1,'4t4','sdgsd','asfas',NULL,NULL,'',NULL,40,40,50.0000,50.0000,'uploads/markers/1791423357_images.jpg','uploads/houses/1791423357_house_images.jpg',NULL,NULL,'2026-10-08 01:35:57');
/*!40000 ALTER TABLE `sm_purok_7_spots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sm_purok_8_spots`
--

DROP TABLE IF EXISTS `sm_purok_8_spots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sm_purok_8_spots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `house_number` varchar(50) DEFAULT NULL,
  `husband_name` varchar(150) DEFAULT NULL,
  `spouse_name` varchar(150) DEFAULT NULL,
  `children` text DEFAULT NULL,
  `legend_id` int(11) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `details` text DEFAULT NULL,
  `marker_width` int(11) DEFAULT 40,
  `marker_height` int(11) DEFAULT 40,
  `top_position` decimal(8,4) DEFAULT NULL,
  `left_position` decimal(8,4) DEFAULT NULL,
  `marker_image` varchar(255) DEFAULT NULL,
  `house_image` varchar(255) DEFAULT NULL,
  `pos_x` decimal(8,4) DEFAULT NULL,
  `pos_y` decimal(8,4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `legend_id` (`legend_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sm_purok_8_spots`
--

LOCK TABLES `sm_purok_8_spots` WRITE;
/*!40000 ALTER TABLE `sm_purok_8_spots` DISABLE KEYS */;
INSERT INTO `sm_purok_8_spots` VALUES (1,'542','dgsd','dfsdf',NULL,NULL,'',NULL,40,40,50.0000,50.0000,'uploads/markers/1791423371_images.jpg','uploads/houses/1791423371_house_images.jpg',NULL,NULL,'2026-10-08 01:36:11');
/*!40000 ALTER TABLE `sm_purok_8_spots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sm_purok_9_spots`
--

DROP TABLE IF EXISTS `sm_purok_9_spots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sm_purok_9_spots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `house_number` varchar(50) DEFAULT NULL,
  `husband_name` varchar(150) DEFAULT NULL,
  `spouse_name` varchar(150) DEFAULT NULL,
  `children` text DEFAULT NULL,
  `legend_id` int(11) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `details` text DEFAULT NULL,
  `marker_width` int(11) DEFAULT 40,
  `marker_height` int(11) DEFAULT 40,
  `top_position` decimal(8,4) DEFAULT NULL,
  `left_position` decimal(8,4) DEFAULT NULL,
  `marker_image` varchar(255) DEFAULT NULL,
  `house_image` varchar(255) DEFAULT NULL,
  `pos_x` decimal(8,4) DEFAULT NULL,
  `pos_y` decimal(8,4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `legend_id` (`legend_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sm_purok_9_spots`
--

LOCK TABLES `sm_purok_9_spots` WRITE;
/*!40000 ALTER TABLE `sm_purok_9_spots` DISABLE KEYS */;
INSERT INTO `sm_purok_9_spots` VALUES (1,'45','sdgs','dada',NULL,NULL,'',NULL,40,40,42.1200,47.0700,'uploads/markers/1791423389_images.jpg','uploads/houses/1791423389_house_images.jpg',NULL,NULL,'2026-10-08 01:36:29');
/*!40000 ALTER TABLE `sm_purok_9_spots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'System Admin','admin@barangaymaruing.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','admin','active','2026-08-28 12:44:13','2026-08-28 12:44:13');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-23  7:55:01
