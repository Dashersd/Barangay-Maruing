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

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `about_content` (
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
INSERT IGNORE INTO `about_content` VALUES (1,'uploads/about/about_1792713252_6adaa22432d7d.png','Wahahha','Hello','HIi','2026-10-22 23:48:44','2026-10-22 23:54:12');
/*!40000 ALTER TABLE `about_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `activity_logs`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
INSERT IGNORE INTO `activity_logs` VALUES (1,NULL,'Logs Cleared','Admin reset the system activity logs','::1','2026-10-26 06:53:59'),(2,NULL,'Admin Profile Updated','Admin details updated: Dash (helmandacuma@gmail.com)','::1','2026-10-26 07:04:33'),(3,NULL,'Security Update','Admin account password was changed for: helmandacuma@gmail.com','::1','2026-10-26 08:09:58'),(4,1,'Admin Login','Admin logged in: helmandacuma5@gmail.com','::1','2026-10-26 08:25:17'),(5,1,'Admin Login','Admin logged in: helmandacuma5@gmail.com','::1','2026-10-26 08:27:51'),(6,1,'Admin Login','Admin logged in: helmandacuma5@gmail.com','::1','2026-10-26 10:29:49'),(7,NULL,'Security Update','Admin account password was changed for: helmandacuma5@gmail.com','::1','2026-10-26 10:30:54'),(8,1,'Admin Login','Admin logged in: helmandacuma5@gmail.com','::1','2026-10-26 10:31:04'),(9,NULL,'Admin Profile Updated','Admin details updated: admin (admin@gmail.com)','::1','2026-10-26 10:32:45'),(10,1,'Admin Login','Admin logged in: admin@gmail.com','::1','2026-10-26 10:32:54'),(11,NULL,'Admin Profile Updated','Admin details updated: admin (admin@gmail.com) (password changed)','::1','2026-10-26 10:48:14'),(12,1,'Admin Login','Admin logged in: admin@gmail.com','::1','2026-10-26 10:48:22'),(13,2,'Admin Login','Admin logged in: helmandacuma@gmail.com','::1','2026-10-27 00:29:42'),(14,1,'Admin Login','Admin logged in: admin@gmail.com','::1','2026-10-27 02:48:49'),(15,2,'Admin Login','Admin logged in: helmandacuma@gmail.com','::1','2026-10-27 06:04:12'),(16,1,'Admin Login','Admin logged in: admin@gmail.com','::1','2026-10-27 15:30:32'),(17,2,'Admin Login','Admin logged in: helmandacuma@gmail.com','::1','2026-10-28 12:05:05'),(18,NULL,'Hero Slides Uploaded','4 background image(s) uploaded for hero section (5-second interval).','::1','2026-10-28 12:56:24'),(19,NULL,'Hero Slide Deleted','Deleted hero background ID 1','::1','2026-10-28 13:04:24'),(20,NULL,'Hero Slide Deleted','Deleted hero background ID 2','::1','2026-10-28 13:04:28'),(21,NULL,'Hero Slide Deleted','Deleted hero background ID 3','::1','2026-10-28 13:04:31'),(22,NULL,'Hero Slide Deleted','Deleted hero background ID 4','::1','2026-10-28 13:04:35'),(23,1,'Admin Login','Admin logged in: admin@gmail.com','::1','2026-10-30 02:31:31'),(24,1,'Admin Login','Admin logged in: admin@gmail.com','::1','2026-11-01 03:58:30');
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `announcements`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `announcements` (
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
INSERT IGNORE INTO `announcements` VALUES (4,'dsgs','gf','dsfs','xhf','gnf','bcvb','','uploads/announcements/1792493789_6ad748dd5a5cb.jpg','published','2026-10-20 10:56:29','2026-10-20 10:56:29');
/*!40000 ALTER TABLE `announcements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `barangay_information`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `barangay_information` (
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
  `slogan` varchar(255) DEFAULT 'Serbisyong Tapat, Para sa Lahat',
  `office_hours` varchar(150) DEFAULT 'Monday - Friday, 8:00 AM - 5:00 PM',
  `facebook_url` varchar(255) DEFAULT 'https://www.facebook.com/',
  `police_hotline` varchar(50) DEFAULT '0998-598-7241',
  `fire_hotline` varchar(50) DEFAULT '0917-123-4567',
  `health_hotline` varchar(50) DEFAULT '0920-987-6543',
  `tanod_hotline` varchar(50) DEFAULT '0931-234-5678',
  `logo_path` varchar(255) DEFAULT '../public/image/Maruing Logo 2.png',
  `hero_slideshow_speed` int(11) DEFAULT 5,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barangay_information`
--

LOCK TABLES `barangay_information` WRITE;
/*!40000 ALTER TABLE `barangay_information` DISABLE KEYS */;
INSERT IGNORE INTO `barangay_information` VALUES (1,'Maruing','Lapuyan','Zamboanga del Sur','Welcome to the official information system of Barangay Maruing.','Barangay Hall, Maruing, Lapuyan, Zamboanga del Sur','09123456789','info@barangaymaruing.com','2026-08-28 12:44:13','2026-08-28 12:44:13','Serbisyong Tapat, Para sa Lahat','Monday - Friday, 8:00 AM - 5:00 PM','https://www.facebook.com/','0998-598-7241','0917-123-4567','0920-987-6543','0931-234-5678','../public/image/Maruing Logo 2.png',5);
/*!40000 ALTER TABLE `barangay_information` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_verifications`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `email_verifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL,
  `pin_code` varchar(10) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `service_type` varchar(150) DEFAULT 'General Inquiry',
  `message` text NOT NULL,
  `expires_at` datetime NOT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `expires_at` (`expires_at`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_verifications`
--

LOCK TABLES `email_verifications` WRITE;
/*!40000 ALTER TABLE `email_verifications` DISABLE KEYS */;
INSERT IGNORE INTO `email_verifications` VALUES (2,'helmandacuma5@gmail.com','848839','Dash','Barangay Clearance','Test inquiry','2026-11-01 06:01:23',2,'2026-11-01 04:56:23'),(3,'helmandacuma5@gmail.com','232277','Dash','Barangay Clearance','sdfsdfs','2026-11-01 06:10:55',1,'2026-11-01 05:05:55'),(4,'helmandacuma5@gmail.com','572901','Dash','Barangay Clearance','sdgsdg','2026-11-01 07:09:15',1,'2026-11-01 06:04:15'),(5,'helmandacuma12@gmail.com','189005','shdghsdg','Barangay Clearance','dsfsd','2026-11-01 07:11:39',2,'2026-11-01 06:06:39'),(6,'helmandacuma12@gmail.com','980458','shdghsdg','Barangay Clearance','dsfsd','2026-11-01 07:22:12',1,'2026-11-01 06:17:12');
/*!40000 ALTER TABLE `email_verifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feedback_chats`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `feedback_chats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `thread_id` varchar(100) NOT NULL,
  `sender_name` varchar(255) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `sender_type` enum('user','admin') NOT NULL DEFAULT 'user',
  `message` text NOT NULL,
  `file_attachment` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feedback_chats`
--

LOCK TABLES `feedback_chats` WRITE;
/*!40000 ALTER TABLE `feedback_chats` DISABLE KEYS */;
INSERT IGNORE INTO `feedback_chats` VALUES (1,'thread_6ac8c44e03d10','Dash',NULL,'user','sdfs',NULL,NULL,1,'2026-10-09 10:50:13'),(2,'thread_6ac8c6f8c038f','Daisy',NULL,'user','[Service: Barangay Clearance]\n\nGsdds',NULL,NULL,1,'2026-10-09 10:51:12'),(3,'thread_6ac8c7e329720','sdas',NULL,'user','[Service: Certificate of Indigency]\n\nfdfsd',NULL,NULL,1,'2026-10-09 11:10:29'),(4,'thread_6ac8c7e329720','Admin',NULL,'admin','fasfas\\',NULL,NULL,0,'2026-10-09 11:40:43'),(5,'thread_6ac8d3674ea7e','Dale',NULL,'user','[Service: Business Clearance]\n\nMamaligya ko ',NULL,NULL,1,'2026-10-09 11:50:00'),(6,'thread_6ac8d3674ea7e','Admin',NULL,'admin','Ay pagbuot',NULL,NULL,1,'2026-10-09 11:50:49'),(7,'thread_6adabf3662d7a','asfas',NULL,'user','[Service: Business Clearance]\n\nfdsfasfa',NULL,NULL,1,'2026-10-23 01:58:19'),(8,'thread_6adabf3662d7a','Admin',NULL,'admin','Please bring 1 valid ID and visit the Barangay Hall during office hours (8AM - 5PM).',NULL,NULL,1,'2026-10-23 01:58:33'),(9,'thread_6adde9fec0b7b','Dale',NULL,'user','[Service: Certificate of Indigency]\n\nHello',NULL,NULL,1,'2026-10-25 11:47:35'),(10,'thread_6adabf3662d7a','Admin',NULL,'admin','Good day! How may we assist you today?',NULL,NULL,0,'2026-10-25 11:47:46'),(11,'thread_6ae010d1ef43c','Dash','mugiw200@gmail.com','user','[Service: Barangay Clearance]\n\nNgayo ko Clearance',NULL,NULL,1,'2026-10-27 02:49:43'),(12,'thread_6ae010d1ef43c','Admin',NULL,'admin','Sent an attachment: Activity.docx','uploads/chat_files/1793071352_6ae018f84365e.docx','Activity.docx',1,'2026-10-27 03:22:32'),(13,'thread_6ae010d1ef43c','Dash','mugiw200@gmail.com','user','sdas',NULL,NULL,1,'2026-10-27 04:01:55'),(14,'thread_6ae010d1ef43c','Dash','mugiw200@gmail.com','user','dasdas',NULL,NULL,1,'2026-10-27 04:02:02'),(15,'thread_6ae03efe1d5ad','sada','dash@gmail.com','user','[Service: Certificate of Indigency]\n\nsdad',NULL,NULL,1,'2026-10-27 06:05:04'),(16,'thread_6ae6b8c377a10','Dash','helmandacuma5@gmail.com','user','[Service: Barangay Clearance]\n\nsdgsdgsgwe',NULL,NULL,1,'2026-11-01 03:58:15'),(17,'thread_6ae6b8c377a10','Dash','helmandacuma5@gmail.com','user','[Service: Certificate of Indigency]\n\nsdsd',NULL,NULL,1,'2026-11-01 04:12:31'),(18,'inquiry_1793506351846_x52voo','ffaa','helmandacuma5@gmail.com','user','[Service: Certificate of Indigency]\n\nsdgsdgsd',NULL,NULL,1,'2026-11-01 04:13:42'),(20,'inquiry_1793506422234_g8s79x','Dash','helmandacuma5@gmail.com','user','[Service: General Inquiry / Feedback]\n\nHSFHHF',NULL,NULL,1,'2026-11-01 04:54:06'),(21,'inquiry_1793509690_8069d7','Dash','helmandacuma5@gmail.com','user','[Service: Barangay Clearance]\n\nsdfsdfs',NULL,NULL,1,'2026-11-01 05:08:10'),(22,'inquiry_1793513085_b8a383','Dash','helmandacuma5@gmail.com','user','[Service: Barangay Clearance]\n\nsdgsdg',NULL,NULL,1,'2026-11-01 06:04:45'),(23,'inquiry_1793513858_3f0a0e','shdghsdg','helmandacuma12@gmail.com','user','[Service: Barangay Clearance]\n\ndsfsd',NULL,NULL,1,'2026-11-01 06:17:38');
/*!40000 ALTER TABLE `feedback_chats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery_folders`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `gallery_folders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_folders`
--

LOCK TABLES `gallery_folders` WRITE;
/*!40000 ALTER TABLE `gallery_folders` DISABLE KEYS */;
INSERT IGNORE INTO `gallery_folders` VALUES (1,'Meetings','Barangay assemblies, council sessions, and committee hearings','2026-10-13 01:40:16','2026-10-13 01:40:16'),(2,'Sports Fest','Inter-purok basketball, volleyball, and community games','2026-10-13 01:40:16','2026-10-13 01:40:16'),(3,'Community Services','Medical missions, relief goods distribution, and clean-up drives','2026-10-13 01:40:16','2026-10-13 01:40:16'),(4,'Hello','ahsfhas','2026-10-13 01:53:11','2026-10-13 01:53:11'),(5,'Tour','ssd','2026-10-20 02:28:20','2026-10-20 02:28:20'),(6,'Dale','','2026-10-25 11:43:02','2026-10-25 11:43:02');
/*!40000 ALTER TABLE `gallery_folders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery_media`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `gallery_media` (
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
INSERT IGNORE INTO `gallery_media` VALUES (1,1,'uploads/gallery/1791856320_6acd8ec08bec5_images.jpg','1791856320_6acd8ec08bec5_images.jpg','images',59609,'2026-10-13 01:52:00'),(2,2,'uploads/gallery/1791856332_6acd8eccae131_Oishi_Veggie_Flutes__Six-Panel_Crunch_Campaign.png','1791856332_6acd8eccae131_Oishi_Veggie_Flutes__Six-Panel_Crunch_Campaign.png','Shiii',2546893,'2026-10-13 01:52:12'),(3,3,'uploads/gallery/1791856346_6acd8edac4285_make_this_4k_20261006101241.jpg','1791856346_6acd8edac4285_make_this_4k_20261006101241.jpg','gdsgs',400516,'2026-10-13 01:52:26'),(5,5,'uploads/gallery/1792463370_6ad6d20aad646_e5a7399d-4c60-4b2a-af2f-9c8b66e56192.jpg','1792463370_6ad6d20aad646_e5a7399d-4c60-4b2a-af2f-9c8b66e56192.jpg','Tour',155353,'2026-10-20 02:29:30');
/*!40000 ALTER TABLE `gallery_media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hero_backgrounds`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `hero_backgrounds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image_path` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hero_backgrounds`
--

LOCK TABLES `hero_backgrounds` WRITE;
/*!40000 ALTER TABLE `hero_backgrounds` DISABLE KEYS */;
/*!40000 ALTER TABLE `hero_backgrounds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `officials`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `officials` (
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
INSERT IGNORE INTO `officials` VALUES (1,'captain','Flutes','BARANGAY CAPTAIN','uploads/officials/official_captain_1792033619.jpg','To our residents, partners, and every member of our community, thank you for your trust, cooperation, and unwavering support. At Barangay Maruing, we remain committed to delivering honest and transparent public service that promotes safety, unity, and progress for all. Together, let us continue building a barangay where every family feels heard, respected, and supported.','active','2026-10-15 02:55:24','2026-10-15 03:15:47'),(2,'secretary','dgsd','BARANGAY Secretary','uploads/officials/official_secretary_1792034185.png',NULL,'active','2026-10-15 03:16:25','2026-10-15 03:16:25'),(3,'treasurer','Name','BARANGAY Treasurer','https://via.placeholder.com/150/e2e8f0/64748b?text=Photo',NULL,'active','2026-10-15 03:21:29','2026-10-15 03:21:29'),(4,'kagawad_1','Name','BARANGAY KAGAWAD','https://via.placeholder.com/150/e2e8f0/64748b?text=Photo',NULL,'active','2026-10-15 03:21:29','2026-10-15 03:21:29'),(5,'kagawad_2','Name','BARANGAY KAGAWAD','https://via.placeholder.com/150/e2e8f0/64748b?text=Photo',NULL,'active','2026-10-15 03:21:29','2026-10-15 03:21:29'),(6,'kagawad_3','Name','BARANGAY KAGAWAD','https://via.placeholder.com/150/e2e8f0/64748b?text=Photo',NULL,'active','2026-10-15 03:21:29','2026-10-15 03:21:29'),(7,'kagawad_4','Name','BARANGAY KAGAWAD','https://via.placeholder.com/150/e2e8f0/64748b?text=Photo',NULL,'active','2026-10-15 03:21:29','2026-10-15 03:21:29'),(8,'kagawad_5','Name','BARANGAY KAGAWAD','https://via.placeholder.com/150/e2e8f0/64748b?text=Photo',NULL,'active','2026-10-15 03:21:29','2026-10-15 03:21:29'),(9,'kagawad_6','Name','BARANGAY KAGAWAD','https://via.placeholder.com/150/e2e8f0/64748b?text=Photo',NULL,'active','2026-10-15 03:21:29','2026-10-15 03:21:29'),(10,'kagawad_7','Name','BARANGAY KAGAWAD','https://via.placeholder.com/150/e2e8f0/64748b?text=Photo',NULL,'active','2026-10-15 03:21:29','2026-10-15 03:21:29');
/*!40000 ALTER TABLE `officials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(100) DEFAULT 'bi-file-earmark-text',
  `requirements` text DEFAULT NULL,
  `document_file` varchar(255) DEFAULT NULL,
  `fee` varchar(100) DEFAULT 'Free',
  `processing_time` varchar(100) DEFAULT 'Same Day',
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
INSERT IGNORE INTO `services` VALUES (1,'Barangay Clearance','Request a Barangay Clearance for employment, banking, or other legal purposes. Essential for verifying your residency and good standing.','bi-file-earmark-text','Valid Government ID, 2x2 Photo, Cedula / Community Tax Certificate','uploads/documents/1793070589_6ae015fdec509.docx','Free','15-30 Minutes','Request Document','contact.php',1,'active','2026-10-21 03:31:00','2026-10-27 03:09:49'),(2,'Certificate of Indigency','Obtain a Certificate of Indigency to avail of government assistance, scholarships, and medical support programs.','bi-house-heart','Valid Government ID, Proof of Low Income / Endorsement',NULL,'Free','15-30 Minutes','Request Document','contact.php',2,'active','2026-10-21 03:31:00','2026-10-21 03:40:06'),(3,'Business Clearance','Required for all new and renewing businesses operating within the barangay jurisdiction prior to Mayor\'s permit application.','bi-briefcase','DTI / SEC Registration, Contract of Lease or Title, Previous Barangay Clearance (if renewal)',NULL,'Free','15-30 Minutes','Request Document','contact.php',3,'active','2026-10-21 03:31:00','2026-10-21 03:40:06'),(4,'Certificate of Residency','Proof of your residency within the barangay. Often required for school enrollments, ID applications, and other local transactions.','bi-person-badge','Valid Government ID, Proof of Billing or Landlord Certification',NULL,'Free','15-30 Minutes','Request Document','contact.php',4,'active','2026-10-21 03:31:00','2026-10-21 03:40:06'),(6,'bd','sgs','bi-cash-coin','sdgs',NULL,'Free','1-2 Business day','Request Document','contact.php',5,'active','2026-10-21 03:52:05','2026-10-21 03:52:05');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sk_officials`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `sk_officials` (
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
INSERT IGNORE INTO `sk_officials` VALUES (1,'chairperson','Hon. Ryan M. Tubos','SK CHAIRPERSON','uploads/sk_officials/sk_chairperson_1792463520.jpg','Tabang','active','2026-10-15 04:07:23','2026-10-20 02:32:00'),(2,'secretary','Hon. Rosemer G. Banagan','SK SECRETARY','public/image/SK Officials/Rosmer G. Banagan.jpeg',NULL,'active','2026-10-15 04:07:23','2026-10-15 04:07:23'),(3,'treasurer','Hon. Judith G. Tambagel','SK TREASURER','public/image/SK Officials/Judith G. Tambagel.jpeg',NULL,'active','2026-10-15 04:07:23','2026-10-15 04:07:23'),(4,'kagawad_1','Hon. Renzarr L. Roxas','SK KAGAWAD 1','public/image/SK Officials/Renzarr L. Roxas.jpeg',NULL,'active','2026-10-15 04:07:23','2026-10-15 04:07:23'),(5,'kagawad_2','Hon. Benie L. Ligao','SK KAGAWAD 2','public/image/SK Officials/Benie L. Ligao.jpeg',NULL,'active','2026-10-15 04:07:23','2026-10-15 04:07:23'),(6,'kagawad_3','Hon. Jhon Mark Charton D. Singue','SK KAGAWAD 3','public/image/SK Officials/Jhon Mark Charton D. Singue.jpeg',NULL,'active','2026-10-15 04:07:23','2026-10-15 04:07:23'),(7,'kagawad_4','Hon. Mark A. Alpay','SK KAGAWAD 4','public/image/SK Officials/Mark A. Alpay.jpeg',NULL,'active','2026-10-15 04:07:23','2026-10-15 04:07:23'),(8,'kagawad_5','Hon. Marlon B. Amplao','SK KAGAWAD 5','public/image/SK Officials/Marlon B. Amplao.jpeg',NULL,'active','2026-10-15 04:07:23','2026-10-15 04:07:23'),(9,'kagawad_6','Hon. Verdelyn G. Halilong','SK KAGAWAD 6','public/image/SK Officials/Verdelyn G. Halilong.jpeg',NULL,'active','2026-10-15 04:07:23','2026-10-15 04:07:23'),(10,'kagawad_7','Hon. Mc Felco T Bugao','SK KAGAWAD 7','public/image/SK Officials/Mc Felco T. Bugao.jpeg',NULL,'active','2026-10-15 04:07:23','2026-10-15 04:07:23');
/*!40000 ALTER TABLE `sk_officials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sm_legend_spots`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `sm_legend_spots` (
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sm_legend_spots`
--

LOCK TABLES `sm_legend_spots` WRITE;
/*!40000 ALTER TABLE `sm_legend_spots` DISABLE KEYS */;
INSERT IGNORE INTO `sm_legend_spots` VALUES (2,NULL,NULL,NULL,NULL,7,'711',NULL,40,40,30.3500,33.0700,'bi-shop',NULL,NULL,NULL,'2026-10-23 07:04:58'),(3,NULL,NULL,NULL,NULL,8,'Dale',NULL,40,40,38.5500,64.0900,'bi-shop','uploads/landmarks/1792928711_landmark_LapuyanLogo.png',NULL,NULL,'2026-10-25 11:45:11');
/*!40000 ALTER TABLE `sm_legend_spots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sm_legends`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `sm_legends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `icon_path` varchar(255) DEFAULT NULL,
  `color_hex` varchar(7) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `display_order` int(11) DEFAULT 0,
  `top_position` decimal(8,4) DEFAULT 50.0000,
  `left_position` decimal(8,4) DEFAULT 50.0000,
  `marker_width` int(11) DEFAULT 40,
  `marker_height` int(11) DEFAULT 40,
  `image_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sm_legends`
--

LOCK TABLES `sm_legends` WRITE;
/*!40000 ALTER TABLE `sm_legends` DISABLE KEYS */;
INSERT IGNORE INTO `sm_legends` VALUES (1,'Barangay Hall','bi-building-fill','#6366f1',1,1,48.0000,42.0000,40,40,'public/image/Hall.jpg'),(2,'School','bi-book-half','#10b981',1,2,38.0000,45.0000,40,40,NULL),(3,'Court','bi-dribbble','#f59e0b',1,3,45.0000,48.0000,40,40,NULL),(4,'Health Center','bi-heart-pulse-fill','#e11d48',1,4,52.0000,40.0000,40,40,NULL),(5,'Other Landmarks','bi-geo-fill','#94a3b8',1,5,60.0000,55.0000,40,40,'uploads/landmarks/1793061002_landmark_e5a7399d-4c60-4b2a-af2f-9c8b66e56192.jpg'),(7,'711','bi-shop','#6366f1',1,6,30.3500,33.0700,40,40,NULL),(8,'Dale','bi-shop','#94a3b8',1,7,38.5500,64.0900,40,40,'uploads/landmarks/1792928711_landmark_LapuyanLogo.png');
/*!40000 ALTER TABLE `sm_legends` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sm_purok_1_spots`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `sm_purok_1_spots` (
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sm_purok_1_spots`
--

LOCK TABLES `sm_purok_1_spots` WRITE;
/*!40000 ALTER TABLE `sm_purok_1_spots` DISABLE KEYS */;
INSERT IGNORE INTO `sm_purok_1_spots` VALUES (1,'565','asd','gds',NULL,NULL,'',NULL,40,40,41.1600,52.5000,'uploads/markers/1791422572_Icon2.png','uploads/houses/1791422572_house_ChatGPTImageSep27202611_53_21AM.png',NULL,NULL,'2026-10-08 01:22:52'),(2,'46754','Dash','Dahsesa',NULL,NULL,'',NULL,40,40,59.4300,33.4800,'uploads/markers/1791424545_Icon2.png','uploads/houses/1791424545_house_images.jpg',NULL,NULL,'2026-10-08 01:55:45'),(4,'24535','5235','dfdf','[\"Duday\",\"afasfas\",\"dsfsfs\"]',NULL,'',NULL,40,40,24.4300,74.9200,'uploads/markers/1792491347_Icon2.png','uploads/houses/1792491347_house_BarangayTabonForestVillage.png',NULL,NULL,'2026-10-20 10:15:47'),(5,'4634','4634','2342','[\"fasaf\",\"sdfasdf\",\"dfgdfg\"]',NULL,'',NULL,40,40,64.3100,71.6000,'uploads/markers/1792725923_Icon2.png','uploads/houses/1792725923_house_e5a7399d-4c60-4b2a-af2f-9c8b66e56192.jpg',NULL,NULL,'2026-10-23 03:25:23');
/*!40000 ALTER TABLE `sm_purok_1_spots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sm_purok_2_spots`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `sm_purok_2_spots` (
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sm_purok_2_spots`
--

LOCK TABLES `sm_purok_2_spots` WRITE;
/*!40000 ALTER TABLE `sm_purok_2_spots` DISABLE KEYS */;
INSERT IGNORE INTO `sm_purok_2_spots` VALUES (1,'31','Helloo','Malupiton',NULL,NULL,'',NULL,40,40,36.3200,53.1400,'uploads/markers/1791422755_Icon2.png','uploads/houses/1791422755_house_TabonBarangayhall.jpg',NULL,NULL,'2026-10-08 01:25:55'),(2,'dfhdf','dfndfg','vsds','[\"dgsdgds\",\"sdfsgr\"]',NULL,'',NULL,40,40,20.6600,68.8500,'uploads/markers/1792726101_images.jpg','uploads/houses/1792726101_house_e5a7399d-4c60-4b2a-af2f-9c8b66e56192.jpg',NULL,NULL,'2026-10-23 03:28:21');
/*!40000 ALTER TABLE `sm_purok_2_spots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sm_purok_3_spots`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `sm_purok_3_spots` (
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
INSERT IGNORE INTO `sm_purok_3_spots` VALUES (1,'234','nfd','as',NULL,NULL,'',NULL,40,40,50.0000,50.0000,'uploads/markers/1791423250_images.jpg','uploads/houses/1791423250_house_images.jpg',NULL,NULL,'2026-10-08 01:34:10');
/*!40000 ALTER TABLE `sm_purok_3_spots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sm_purok_4_spots`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `sm_purok_4_spots` (
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
INSERT IGNORE INTO `sm_purok_4_spots` VALUES (1,'213','dfh','zvzxc',NULL,NULL,'',NULL,40,40,50.0000,50.0000,'uploads/markers/1791423267_images.jpg','uploads/houses/1791423267_house_images.jpg',NULL,NULL,'2026-10-08 01:34:27');
/*!40000 ALTER TABLE `sm_purok_4_spots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sm_purok_5_spots`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `sm_purok_5_spots` (
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
INSERT IGNORE INTO `sm_purok_5_spots` VALUES (1,'214','dfb',',kgfgh',NULL,NULL,'',NULL,40,40,50.0000,50.0000,'uploads/markers/1791423287_images.jpg','uploads/houses/1791423287_house_images.jpg',NULL,NULL,'2026-10-08 01:34:47');
/*!40000 ALTER TABLE `sm_purok_5_spots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sm_purok_6_spots`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `sm_purok_6_spots` (
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
INSERT IGNORE INTO `sm_purok_6_spots` VALUES (1,'5','dfhdf','zvdsd',NULL,NULL,'',NULL,40,40,50.0000,50.0000,'uploads/markers/1791423302_images.jpg','uploads/houses/1791423302_house_images.jpg',NULL,NULL,'2026-10-08 01:35:02');
/*!40000 ALTER TABLE `sm_purok_6_spots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sm_purok_7_spots`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `sm_purok_7_spots` (
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
INSERT IGNORE INTO `sm_purok_7_spots` VALUES (1,'4t4','sdgsd','asfas',NULL,NULL,'',NULL,40,40,50.0000,50.0000,'uploads/markers/1791423357_images.jpg','uploads/houses/1791423357_house_images.jpg',NULL,NULL,'2026-10-08 01:35:57');
/*!40000 ALTER TABLE `sm_purok_7_spots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sm_purok_8_spots`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `sm_purok_8_spots` (
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
INSERT IGNORE INTO `sm_purok_8_spots` VALUES (1,'542','dgsd','dfsdf',NULL,NULL,'',NULL,40,40,50.0000,50.0000,'uploads/markers/1791423371_images.jpg','uploads/houses/1791423371_house_images.jpg',NULL,NULL,'2026-10-08 01:36:11');
/*!40000 ALTER TABLE `sm_purok_8_spots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sm_purok_9_spots`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `sm_purok_9_spots` (
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
INSERT IGNORE INTO `sm_purok_9_spots` VALUES (1,'45','sdgs','dada',NULL,NULL,'',NULL,40,40,42.1200,47.0700,'uploads/markers/1791423389_images.jpg','uploads/houses/1791423389_house_images.jpg',NULL,NULL,'2026-10-08 01:36:29');
/*!40000 ALTER TABLE `sm_purok_9_spots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `users` (
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT IGNORE INTO `users` VALUES (1,'admin','admin@gmail.com','$2y$10$floIxUYslYDoCvlT7i.kA.PiSZpZvfUjrktbCLbWc4qswefW4iSOe','admin','active','2026-08-28 12:44:13','2026-10-26 10:48:14'),(2,'Dash','helmandacuma@gmail.com','$2y$10$mGpJBGQ7UG46iVKkoW2/V.9QCSbU1MrJrAJB.h6PUlBsXQndlheve','admin','active','2026-10-26 08:18:33','2026-10-26 08:23:04');
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

-- Dump completed on 2026-11-02 12:12:04
