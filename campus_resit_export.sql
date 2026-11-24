-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: campus_resit_portal
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
-- Table structure for table `account_status_histories`
--

DROP TABLE IF EXISTS `account_status_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account_status_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `changed_by` bigint(20) unsigned DEFAULT NULL,
  `previous_status` tinyint(1) DEFAULT NULL,
  `new_status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `account_status_histories_changed_by_foreign` (`changed_by`),
  KEY `account_status_histories_user_id_created_at_index` (`user_id`,`created_at`),
  CONSTRAINT `account_status_histories_changed_by_foreign` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `account_status_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account_status_histories`
--

LOCK TABLES `account_status_histories` WRITE;
/*!40000 ALTER TABLE `account_status_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `account_status_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `broadcast_messages`
--

DROP TABLE IF EXISTS `broadcast_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `broadcast_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'general',
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `emails_sent_count` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `broadcast_messages_created_by_foreign` (`created_by`),
  CONSTRAINT `broadcast_messages_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `broadcast_messages`
--

LOCK TABLES `broadcast_messages` WRITE;
/*!40000 ALTER TABLE `broadcast_messages` DISABLE KEYS */;
INSERT INTO `broadcast_messages` VALUES (1,'Welcome & Examination Registration Schedule (November 2026 Session)','Welcome candidates. The supplementary and resit examination application window is now open. All registered candidates are advised to verify their course selections and proceed with Mobile Money payments. Timetables will be uploaded continuously.','general',1,1,2,'2026-11-08 18:00:18','2026-11-08 18:00:18');
/*!40000 ALTER TABLE `broadcast_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab','i:2;',1794414162),('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab:timer','i:1794414162;',1794414162);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `courses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `course_code` varchar(255) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `credit_hours` tinyint(3) unsigned NOT NULL,
  `level` smallint(5) unsigned DEFAULT 100,
  `semester` varchar(255) DEFAULT 'All Semesters',
  `registration_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `registration_start` datetime DEFAULT NULL,
  `registration_deadline` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `courses_course_code_unique` (`course_code`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` VALUES (1,'CS101','Introduction to Computer Science',3,100,'Semester 1',150.00,'2026-11-03 22:54:57','2026-11-28 22:54:57',1,'2026-11-01 19:38:02','2026-11-08 22:54:57'),(2,'CS204','Data Structures and Algorithms',3,200,'Semester 1',180.00,'2026-11-03 22:54:57','2026-11-28 22:54:57',1,'2026-11-01 19:38:02','2026-11-08 22:54:57'),(3,'CS305','Database Management Systems',3,300,'Semester 1',200.00,'2026-11-03 22:54:57','2026-11-28 22:54:57',1,'2026-11-01 19:38:02','2026-11-08 22:54:57'),(4,'CS410','Software Engineering & Cloud Architecture',4,400,'Semester 1',250.00,'2026-11-03 22:54:57','2026-11-28 22:54:57',1,'2026-11-01 19:38:02','2026-11-08 22:54:57'),(5,'ICT112','Principles of Programming',3,100,'Semester 2',150.00,'2026-11-03 22:54:57','2026-12-03 22:54:57',1,'2026-11-08 16:13:46','2026-11-08 22:54:57'),(6,'ITC231','Database Concepts and Design',3,200,'Semester 1',180.00,'2026-11-03 22:54:57','2026-12-03 22:54:57',1,'2026-11-08 16:13:46','2026-11-08 22:54:57'),(7,'ICT232','Web Technology and Design',3,200,'Semester 1',180.00,'2026-11-03 22:54:57','2026-12-03 22:54:57',1,'2026-11-08 16:13:46','2026-11-08 22:54:57'),(8,'ICT244','Systems Analysis and Design',3,200,'Semester 2',180.00,'2026-11-03 22:54:57','2026-12-03 22:54:57',1,'2026-11-08 16:13:46','2026-11-08 22:54:57'),(9,'ICT351','Computer Networks and Security',3,300,'Semester 1',200.00,'2026-11-03 22:54:57','2026-12-03 22:54:57',1,'2026-11-08 16:13:46','2026-11-08 22:54:57'),(10,'ICT362','Object-Oriented Software Development',3,300,'Semester 2',200.00,'2026-11-03 22:54:57','2026-12-03 22:54:57',1,'2026-11-08 16:13:46','2026-11-08 22:54:57'),(11,'ICT131','Computer Hardware and Networking',3,100,'Semester 1',150.00,'2026-11-03 22:54:57','2026-12-03 22:54:57',1,'2026-11-08 22:54:44','2026-11-08 22:54:57'),(12,'ICT241','Mobile Application Development',3,200,'Semester 2',180.00,'2026-11-03 22:54:57','2026-12-03 22:54:57',1,'2026-11-08 22:54:44','2026-11-08 22:54:57'),(13,'ICT311','Software Engineering and Project Management',3,300,'Semester 1',200.00,'2026-11-03 22:54:57','2026-12-03 22:54:57',1,'2026-11-08 22:54:44','2026-11-08 22:54:57'),(14,'ICT321','Operating Systems and Computer Architecture',3,300,'Semester 1',200.00,'2026-11-03 22:54:57','2026-12-03 22:54:57',1,'2026-11-08 22:54:44','2026-11-08 22:54:57'),(15,'ICT371','E-Commerce and Digital Business',3,300,'Semester 2',180.00,'2026-11-03 22:54:57','2026-12-03 22:54:57',1,'2026-11-08 22:54:44','2026-11-08 22:54:57'),(16,'ICT381','Information Systems Management',3,300,'Semester 2',200.00,'2026-11-03 22:54:57','2026-12-03 22:54:57',1,'2026-11-08 22:54:44','2026-11-08 22:54:57'),(17,'AI201','Artificial Intelligence and Machine Learning',3,200,'Semester 2',220.00,'2026-11-03 22:54:57','2026-12-03 22:54:57',1,'2026-11-08 22:54:44','2026-11-08 22:54:57'),(18,'AI301','Deep Learning and Neural Networks',3,300,'Semester 2',220.00,'2026-11-03 22:54:57','2026-12-03 22:54:57',1,'2026-11-08 22:54:44','2026-11-08 22:54:57'),(19,'ICT271','Cyber Security Fundamentals',3,200,'Semester 2',200.00,'2026-11-03 22:54:57','2026-12-03 22:54:57',1,'2026-11-08 22:54:44','2026-11-08 22:54:57'),(20,'ICT281','Cloud Computing and Infrastructure',3,200,'Semester 2',200.00,'2026-11-03 22:54:57','2026-12-03 22:54:57',1,'2026-11-08 22:54:44','2026-11-08 22:54:57'),(21,'ICT361','Internet of Things (IoT) and Embedded Systems',3,300,'Semester 1',200.00,'2026-11-03 22:54:57','2026-12-03 22:54:57',1,'2026-11-08 22:54:44','2026-11-08 22:54:57'),(22,'BUS101','Principles of Business and Entrepreneurship',3,100,'Semester 1',150.00,'2026-11-03 22:54:57','2026-12-03 22:54:57',1,'2026-11-08 22:54:44','2026-11-08 22:54:57'),(23,'BUS211','Marketing and Digital Communication',3,200,'Semester 1',150.00,'2026-11-03 22:54:57','2026-12-03 22:54:57',1,'2026-11-08 22:54:44','2026-11-08 22:54:57'),(24,'BUS221','Financial Literacy and Accounting Basics',3,200,'Semester 1',150.00,'2026-11-03 22:54:57','2026-12-03 22:54:57',1,'2026-11-08 22:54:44','2026-11-08 22:54:57'),(25,'ENT101','Innovation and Technology Entrepreneurship',3,100,'Semester 2',150.00,'2026-11-03 22:54:57','2026-12-03 22:54:57',1,'2026-11-08 22:54:44','2026-11-08 22:54:57'),(26,'BUS231','Project Planning and Management',3,200,'Semester 2',150.00,'2026-11-03 22:54:57','2026-12-03 22:54:57',1,'2026-11-08 22:54:44','2026-11-08 22:54:57'),(27,'SOC101','Introduction to Sociology and Social Studies',2,100,'Semester 1',120.00,'2026-11-03 22:54:57','2026-12-03 22:54:57',1,'2026-11-08 22:54:44','2026-11-08 22:54:57'),(28,'SOC201','Community Development and Civic Education',2,200,'Semester 1',120.00,'2026-11-03 22:54:57','2026-12-03 22:54:57',1,'2026-11-08 22:54:44','2026-11-08 22:54:57'),(29,'EDU101','Educational Psychology and Learning Theories',3,100,'Semester 2',130.00,'2026-11-03 22:54:57','2026-12-03 22:54:57',1,'2026-11-08 22:54:44','2026-11-08 22:54:57'),(30,'EDU211','Curriculum Design and Assessment',3,200,'Semester 2',130.00,'2026-11-03 22:54:57','2026-12-03 22:54:57',1,'2026-11-08 22:54:44','2026-11-08 22:54:57'),(31,'MAT101','Applied Mathematics for Technology',3,100,'Semester 1',150.00,'2026-11-03 22:54:57','2026-12-03 22:54:57',1,'2026-11-08 22:54:44','2026-11-08 22:54:57'),(32,'COM101','Technical Communication and Report Writing',2,100,'Semester 1',120.00,'2026-11-03 22:54:57','2026-12-03 22:54:57',1,'2026-11-08 22:54:44','2026-11-08 22:54:57');
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `exams`
--

DROP TABLE IF EXISTS `exams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exams` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `academic_year` varchar(255) NOT NULL,
  `semester` varchar(255) NOT NULL,
  `exam_type` enum('resit','regular','supplementary_quiz') NOT NULL DEFAULT 'resit',
  `duration_minutes` smallint(5) unsigned NOT NULL DEFAULT 120,
  `total_marks` smallint(5) unsigned NOT NULL DEFAULT 100,
  `status` enum('scheduled','in_progress','completed','cancelled') NOT NULL DEFAULT 'scheduled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exams_course_id_foreign` (`course_id`),
  CONSTRAINT `exams_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exams`
--

LOCK TABLES `exams` WRITE;
/*!40000 ALTER TABLE `exams` DISABLE KEYS */;
/*!40000 ALTER TABLE `exams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000001_create_cache_table',1),(2,'0001_01_01_000002_create_jobs_table',1),(3,'2026_10_30_152425_create_users_table',1),(4,'2026_10_31_160000_create_courses_table',1),(5,'2026_10_31_163156_create_registrations_table',1),(6,'2026_11_01_000001_create_payments_table',1),(7,'2026_11_01_000002_create_exams_table',1),(8,'2026_11_01_000003_create_timetables_table',1),(9,'2026_11_01_000004_create_results_table',1),(10,'2026_11_03_120000_create_staff_invitations_table',2),(11,'2026_11_03_140000_create_supplementary_cycles_table',3),(12,'2026_11_05_000001_create_resit_requests_table',4),(13,'2026_11_05_200000_create_supplementary_applications_table',4),(14,'2026_11_05_210000_create_resit_form_submissions_table',4),(15,'2026_11_08_170000_add_is_open_to_supplementary_cycles_table',5),(16,'2026_11_08_180000_make_level_and_semester_nullable_in_courses_table',6),(17,'2026_11_08_181000_create_broadcast_messages_table',6),(18,'2026_11_08_190000_add_rejection_reason_to_resit_requests_table',7),(19,'2026_11_09_120000_add_account_status_tracking',8),(20,'2026_11_10_120000_add_pending_email_change_fields_to_users_table',8),(21,'2026_11_10_130000_generalize_email_verification_pin_fields',8),(22,'2026_11_10_140000_add_email_verified_at_to_users_table',8),(23,'2026_11_10_150000_create_password_reset_tokens_table',8),(24,'2026_11_10_160000_add_attempts_to_password_reset_tokens_table',8);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `registration_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `transaction_reference` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) NOT NULL DEFAULT 'momo',
  `phone_number` varchar(255) NOT NULL,
  `network` varchar(255) DEFAULT NULL,
  `status` enum('pending','successful','failed') NOT NULL DEFAULT 'pending',
  `gateway_reference` varchar(255) DEFAULT NULL,
  `gateway_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gateway_response`)),
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_transaction_reference_unique` (`transaction_reference`),
  KEY `payments_registration_id_foreign` (`registration_id`),
  KEY `payments_user_id_status_index` (`user_id`,`status`),
  CONSTRAINT `payments_registration_id_foreign` FOREIGN KEY (`registration_id`) REFERENCES `registrations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,1,5,'RESIT-MOMO-20261108-D9WQ6TPY',130.00,'momo','0544893582','MTN','successful','RESIT-MOMO-20261108-D9WQ6TPY','{\"id\":6744657528,\"domain\":\"test\",\"status\":\"success\",\"reference\":\"RESIT-MOMO-20261108-D9WQ6TPY\",\"receipt_number\":\"10101\",\"amount\":13000,\"message\":null,\"gateway_response\":\"Approved\",\"paid_at\":\"2026-11-08T23:15:12.000Z\",\"created_at\":\"2026-11-08T23:15:01.000Z\",\"channel\":\"mobile_money\",\"currency\":\"GHS\",\"ip_address\":\"154.163.188.183\",\"metadata\":{\"registration_id\":\"1\",\"course_code\":\"EDU211\",\"student_id\":\"5\",\"phone_number\":\"0544893582\",\"network\":\"MTN\",\"referrer\":\"http:\\/\\/127.0.0.1:8000\\/\"},\"log\":{\"start_time\":1794179707,\"time_spent\":4,\"attempts\":1,\"errors\":0,\"success\":false,\"mobile\":false,\"input\":[],\"history\":[{\"type\":\"action\",\"message\":\"Attempted to pay with mobile money\",\"time\":4}]},\"fees\":254,\"fees_split\":null,\"authorization\":{\"authorization_code\":\"AUTH_snxx0x1icy\",\"bin\":\"055XXX\",\"last4\":\"X987\",\"exp_month\":\"12\",\"exp_year\":\"9999\",\"channel\":\"mobile_money\",\"card_type\":\"\",\"bank\":\"MTN\",\"country_code\":\"GH\",\"brand\":\"Mtn\",\"reusable\":false,\"signature\":null,\"account_name\":null,\"mobile_money_number\":\"0551234987\",\"receiver_bank_account_number\":null,\"receiver_bank\":null},\"customer\":{\"id\":415607853,\"first_name\":null,\"last_name\":null,\"email\":\"kelvinhonorajunior@gmail.com\",\"customer_code\":\"CUS_swmwy44u4tc1t1c\",\"phone\":null,\"metadata\":null,\"risk_action\":\"default\",\"international_format_phone\":null},\"plan\":null,\"split\":[],\"order_id\":null,\"paidAt\":\"2026-11-08T23:15:12.000Z\",\"createdAt\":\"2026-11-08T23:15:01.000Z\",\"requested_amount\":13000,\"pos_transaction_data\":null,\"source\":null,\"fees_breakdown\":null,\"connect\":null,\"transaction_date\":\"2026-11-08T23:15:01.000Z\",\"plan_object\":[],\"subaccount\":[]}','2026-11-08 23:15:20','2026-11-08 23:14:59','2026-11-08 23:15:20'),(2,2,5,'RESIT-MOMO-20261108-SMRNUI6D',200.00,'momo','0544893582','MTN','successful','RESIT-MOMO-20261108-SMRNUI6D','{\"id\":6744661780,\"domain\":\"test\",\"status\":\"success\",\"reference\":\"RESIT-MOMO-20261108-SMRNUI6D\",\"receipt_number\":\"10101\",\"amount\":20000,\"message\":null,\"gateway_response\":\"Approved\",\"paid_at\":\"2026-11-08T23:20:50.000Z\",\"created_at\":\"2026-11-08T23:20:46.000Z\",\"channel\":\"mobile_money\",\"currency\":\"GHS\",\"ip_address\":\"154.163.188.183\",\"metadata\":{\"registration_id\":\"2\",\"course_code\":\"CS305\",\"student_id\":\"5\",\"phone_number\":\"0544893582\",\"network\":\"MTN\",\"referrer\":\"http:\\/\\/127.0.0.1:8000\\/\"},\"log\":{\"start_time\":1794180048,\"time_spent\":2,\"attempts\":1,\"errors\":0,\"success\":false,\"mobile\":false,\"input\":[],\"history\":[{\"type\":\"action\",\"message\":\"Attempted to pay with mobile money\",\"time\":2}]},\"fees\":390,\"fees_split\":null,\"authorization\":{\"authorization_code\":\"AUTH_yuqjjk07l1\",\"bin\":\"055XXX\",\"last4\":\"X987\",\"exp_month\":\"12\",\"exp_year\":\"9999\",\"channel\":\"mobile_money\",\"card_type\":\"\",\"bank\":\"MTN\",\"country_code\":\"GH\",\"brand\":\"Mtn\",\"reusable\":false,\"signature\":null,\"account_name\":null,\"mobile_money_number\":\"0551234987\",\"receiver_bank_account_number\":null,\"receiver_bank\":null},\"customer\":{\"id\":415607853,\"first_name\":null,\"last_name\":null,\"email\":\"kelvinhonorajunior@gmail.com\",\"customer_code\":\"CUS_swmwy44u4tc1t1c\",\"phone\":null,\"metadata\":null,\"risk_action\":\"default\",\"international_format_phone\":null},\"plan\":null,\"split\":[],\"order_id\":null,\"paidAt\":\"2026-11-08T23:20:50.000Z\",\"createdAt\":\"2026-11-08T23:20:46.000Z\",\"requested_amount\":20000,\"pos_transaction_data\":null,\"source\":null,\"fees_breakdown\":null,\"connect\":null,\"transaction_date\":\"2026-11-08T23:20:46.000Z\",\"plan_object\":[],\"subaccount\":[]}','2026-11-08 23:20:57','2026-11-08 23:20:45','2026-11-08 23:20:57'),(3,3,5,'RESIT-MOMO-20261109-FTGPRFDU',150.00,'momo','0544893582','MTN','pending','RESIT-MOMO-20261109-FTGPRFDU','{\"authorization_url\":\"https:\\/\\/checkout.paystack.com\\/vd2qrsspzirw2tz\",\"access_code\":\"vd2qrsspzirw2tz\",\"reference\":\"RESIT-MOMO-20261109-FTGPRFDU\"}',NULL,'2026-11-09 15:12:51','2026-11-09 15:12:52'),(4,3,5,'RESIT-MOMO-20261109-UCPYMAQG',150.00,'momo','0544893582','MTN','successful','RESIT-MOMO-20261109-UCPYMAQG','{\"id\":6746792216,\"domain\":\"test\",\"status\":\"success\",\"reference\":\"RESIT-MOMO-20261109-UCPYMAQG\",\"receipt_number\":\"10101\",\"amount\":15000,\"message\":null,\"gateway_response\":\"Approved\",\"paid_at\":\"2026-11-09T15:14:07.000Z\",\"created_at\":\"2026-11-09T15:13:20.000Z\",\"channel\":\"mobile_money\",\"currency\":\"GHS\",\"ip_address\":\"102.176.74.44\",\"metadata\":{\"registration_id\":\"3\",\"course_code\":\"ICT131\",\"student_id\":\"5\",\"phone_number\":\"0544893582\",\"network\":\"MTN\",\"referrer\":\"http:\\/\\/127.0.0.1:8000\\/\"},\"log\":{\"start_time\":1794237204,\"time_spent\":43,\"attempts\":1,\"errors\":0,\"success\":false,\"mobile\":false,\"input\":[],\"history\":[{\"type\":\"action\",\"message\":\"Attempted to pay with mobile money\",\"time\":43}]},\"fees\":293,\"fees_split\":null,\"authorization\":{\"authorization_code\":\"AUTH_qplqhldt2b\",\"bin\":\"055XXX\",\"last4\":\"X987\",\"exp_month\":\"12\",\"exp_year\":\"9999\",\"channel\":\"mobile_money\",\"card_type\":\"\",\"bank\":\"MTN\",\"country_code\":\"GH\",\"brand\":\"Mtn\",\"reusable\":false,\"signature\":null,\"account_name\":null,\"mobile_money_number\":\"0551234987\",\"receiver_bank_account_number\":null,\"receiver_bank\":null},\"customer\":{\"id\":415607853,\"first_name\":null,\"last_name\":null,\"email\":\"kelvinhonorajunior@gmail.com\",\"customer_code\":\"CUS_swmwy44u4tc1t1c\",\"phone\":null,\"metadata\":null,\"risk_action\":\"default\",\"international_format_phone\":null},\"plan\":null,\"split\":[],\"order_id\":null,\"paidAt\":\"2026-11-09T15:14:07.000Z\",\"createdAt\":\"2026-11-09T15:13:20.000Z\",\"requested_amount\":15000,\"pos_transaction_data\":null,\"source\":null,\"fees_breakdown\":null,\"connect\":null,\"transaction_date\":\"2026-11-09T15:13:20.000Z\",\"plan_object\":[],\"subaccount\":[]}','2026-11-09 15:14:17','2026-11-09 15:13:20','2026-11-09 15:14:17');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registrations`
--

DROP TABLE IF EXISTS `registrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `registrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `course_id` bigint(20) unsigned NOT NULL,
  `semester` varchar(255) NOT NULL,
  `academic_year` varchar(255) NOT NULL,
  `status` enum('pending_payment','paid','approved','rejected') NOT NULL DEFAULT 'pending_payment',
  `approval_remarks` text DEFAULT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `registrations_user_id_course_id_semester_academic_year_unique` (`user_id`,`course_id`,`semester`,`academic_year`),
  KEY `registrations_course_id_foreign` (`course_id`),
  KEY `registrations_approved_by_foreign` (`approved_by`),
  CONSTRAINT `registrations_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `registrations_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `registrations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registrations`
--

LOCK TABLES `registrations` WRITE;
/*!40000 ALTER TABLE `registrations` DISABLE KEYS */;
INSERT INTO `registrations` VALUES (1,5,30,'Semester 2','2026/2027','approved',NULL,NULL,'2026-11-08 23:28:38','2026-11-08 23:12:42','2026-11-08 23:28:38'),(2,5,3,'Semester 1','2026/2027','approved',NULL,2,'2026-11-08 23:22:08','2026-11-08 23:19:43','2026-11-08 23:22:08'),(3,5,11,'Semester 1','2026/2027','approved',NULL,NULL,'2026-11-09 15:14:17','2026-11-09 15:12:36','2026-11-09 15:14:17');
/*!40000 ALTER TABLE `registrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resit_form_submissions`
--

DROP TABLE IF EXISTS `resit_form_submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resit_form_submissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `exam_type` enum('resit','supplementary') NOT NULL,
  `name` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `index_number` varchar(255) NOT NULL,
  `level` varchar(255) NOT NULL,
  `programme` varchar(255) NOT NULL,
  `number_of_papers` int(11) NOT NULL,
  `course_details` text NOT NULL,
  `year_of_entry` varchar(255) NOT NULL,
  `year_of_completion` varchar(255) NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `medical_report_path` varchar(255) DEFAULT NULL,
  `letter_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resit_form_submissions_user_id_foreign` (`user_id`),
  CONSTRAINT `resit_form_submissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resit_form_submissions`
--

LOCK TABLES `resit_form_submissions` WRITE;
/*!40000 ALTER TABLE `resit_form_submissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `resit_form_submissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resit_requests`
--

DROP TABLE IF EXISTS `resit_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resit_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `index_number` varchar(255) NOT NULL,
  `level` varchar(255) NOT NULL,
  `programme` varchar(255) NOT NULL,
  `paper_count` int(10) unsigned NOT NULL,
  `course_entries` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`course_entries`)),
  `year_entry` year(4) NOT NULL,
  `year_completion` year(4) NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `exam_type` enum('resit','supplementary') NOT NULL,
  `medical_report_path` varchar(255) DEFAULT NULL,
  `letter_path` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'approved',
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resit_requests_user_id_foreign` (`user_id`),
  CONSTRAINT `resit_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resit_requests`
--

LOCK TABLES `resit_requests` WRITE;
/*!40000 ALTER TABLE `resit_requests` DISABLE KEYS */;
INSERT INTO `resit_requests` VALUES (1,5,'Kelvyn Honora','Department of ICT Education','5240101305','200','B.Sc. Information Technology Education',1,'[\"EDU211 - Curriculum Design and Assessment\"]',2024,2028,'0544893582','resit',NULL,NULL,'approved',NULL,'2026-11-08 23:12:42','2026-11-08 23:12:42'),(2,5,'Kelvyn Honora','Department of ICT Education','5240101305','200','B.Sc. Information Technology Education',1,'[\"CS305 - Database Management Systems\"]',2024,2028,'0544893582','resit',NULL,NULL,'approved',NULL,'2026-11-08 23:19:43','2026-11-08 23:19:43'),(3,5,'Kelvyn Honora','Department of ICT Education','5240101305','200','B.Sc. Information Technology Education',1,'[\"ICT131 - Computer Hardware and Networking\"]',2024,2028,'0544893582','resit',NULL,NULL,'approved',NULL,'2026-11-09 15:12:36','2026-11-09 15:12:36');
/*!40000 ALTER TABLE `resit_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `results`
--

DROP TABLE IF EXISTS `results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `results` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `registration_id` bigint(20) unsigned NOT NULL,
  `exam_id` bigint(20) unsigned DEFAULT NULL,
  `score` decimal(5,2) DEFAULT NULL,
  `grade` varchar(5) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `entered_by` bigint(20) unsigned DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `results_registration_id_exam_id_unique` (`registration_id`,`exam_id`),
  KEY `results_exam_id_foreign` (`exam_id`),
  KEY `results_entered_by_foreign` (`entered_by`),
  CONSTRAINT `results_entered_by_foreign` FOREIGN KEY (`entered_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `results_exam_id_foreign` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `results_registration_id_foreign` FOREIGN KEY (`registration_id`) REFERENCES `registrations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `results`
--

LOCK TABLES `results` WRITE;
/*!40000 ALTER TABLE `results` DISABLE KEYS */;
/*!40000 ALTER TABLE `results` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff_invitations`
--

DROP TABLE IF EXISTS `staff_invitations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff_invitations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `role` enum('admin','chief_examiner') NOT NULL,
  `token_hash` char(64) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `used_at` timestamp NULL DEFAULT NULL,
  `revoked_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `accepted_user_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `staff_invitations_token_hash_unique` (`token_hash`),
  KEY `staff_invitations_created_by_foreign` (`created_by`),
  KEY `staff_invitations_accepted_user_id_foreign` (`accepted_user_id`),
  KEY `staff_invitations_email_role_index` (`email`,`role`),
  CONSTRAINT `staff_invitations_accepted_user_id_foreign` FOREIGN KEY (`accepted_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `staff_invitations_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff_invitations`
--

LOCK TABLES `staff_invitations` WRITE;
/*!40000 ALTER TABLE `staff_invitations` DISABLE KEYS */;
/*!40000 ALTER TABLE `staff_invitations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supplementary_applications`
--

DROP TABLE IF EXISTS `supplementary_applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supplementary_applications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending_approval',
  `medical_report` varchar(255) DEFAULT NULL,
  `letter` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `supplementary_applications_user_id_foreign` (`user_id`),
  CONSTRAINT `supplementary_applications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supplementary_applications`
--

LOCK TABLES `supplementary_applications` WRITE;
/*!40000 ALTER TABLE `supplementary_applications` DISABLE KEYS */;
/*!40000 ALTER TABLE `supplementary_applications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supplementary_cycles`
--

DROP TABLE IF EXISTS `supplementary_cycles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supplementary_cycles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `registration_starts_at` datetime NOT NULL,
  `registration_ends_at` datetime NOT NULL,
  `student_access_ends_at` datetime NOT NULL,
  `is_current` tinyint(1) NOT NULL DEFAULT 1,
  `is_open` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `supplementary_cycles_is_current_index` (`is_current`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supplementary_cycles`
--

LOCK TABLES `supplementary_cycles` WRITE;
/*!40000 ALTER TABLE `supplementary_cycles` DISABLE KEYS */;
INSERT INTO `supplementary_cycles` VALUES (1,'Nov 2026 Supplementary','2026-11-06 22:54:57','2026-12-08 22:54:57','2027-01-07 22:54:57',1,1,'2026-11-05 13:47:23','2026-11-09 15:11:13');
/*!40000 ALTER TABLE `supplementary_cycles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timetables`
--

DROP TABLE IF EXISTS `timetables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `timetables` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `exam_id` bigint(20) unsigned DEFAULT NULL,
  `course_id` bigint(20) unsigned NOT NULL,
  `academic_year` varchar(255) NOT NULL,
  `semester` varchar(255) NOT NULL,
  `exam_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `venue` varchar(255) NOT NULL,
  `seating_capacity` smallint(5) unsigned NOT NULL DEFAULT 50,
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `published_by` bigint(20) unsigned DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `timetables_exam_id_foreign` (`exam_id`),
  KEY `timetables_course_id_foreign` (`course_id`),
  KEY `timetables_published_by_foreign` (`published_by`),
  KEY `timetables_venue_exam_date_index` (`venue`,`exam_date`),
  CONSTRAINT `timetables_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `timetables_exam_id_foreign` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `timetables_published_by_foreign` FOREIGN KEY (`published_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timetables`
--

LOCK TABLES `timetables` WRITE;
/*!40000 ALTER TABLE `timetables` DISABLE KEYS */;
INSERT INTO `timetables` VALUES (1,NULL,5,'2026/2027','Supplementary & Resit Session','2026-11-20','09:00:00','12:00:00','ICT Lab 1 (Main Campus)',60,'published',1,'2026-11-08 22:54:57','2026-11-08 22:54:57','2026-11-08 22:54:57'),(2,NULL,6,'2026/2027','Supplementary & Resit Session','2026-11-22','09:00:00','12:00:00','Great Hall Room A',120,'published',1,'2026-11-08 22:54:57','2026-11-08 22:54:57','2026-11-08 22:54:57'),(3,NULL,7,'2026/2027','Supplementary & Resit Session','2026-11-24','13:00:00','16:00:00','ICT Lab 2 (Main Campus)',50,'published',1,'2026-11-08 22:54:57','2026-11-08 22:54:57','2026-11-08 22:54:57');
/*!40000 ALTER TABLE `timetables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','chief_examiner','admin') NOT NULL DEFAULT 'student',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `student_id_number` varchar(255) DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `pending_email` varchar(255) DEFAULT NULL,
  `email_verification_code_hash` char(64) DEFAULT NULL,
  `email_verification_expires_at` timestamp NULL DEFAULT NULL,
  `email_verification_attempts` tinyint(3) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_student_id_number_unique` (`student_id_number`),
  UNIQUE KEY `users_pending_email_unique` (`pending_email`),
  UNIQUE KEY `users_email_verification_code_hash_unique` (`email_verification_code_hash`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'System Administrator (Academic Affairs)','admin@campus.edu',NULL,'$2y$12$HSjYE7Rx3lDKmgVSpmhHUO2TMsZCMAJkzP85UuC2UbqO6nwRNhsba','admin',1,NULL,'+233240000001',NULL,'2026-11-01 19:38:01','2026-11-08 22:54:57',NULL,NULL,NULL,0),(2,'Prof. Kwame Mensah (Chief Examination Officer)','examiner@campus.edu',NULL,'$2y$12$/APG0wGO6q/uUt2AzsNpEuAVP9tXWBUxKQpKOy17GBFIbu.vTjrt.','chief_examiner',1,NULL,'+233240000002',NULL,'2026-11-01 19:38:02','2026-11-08 22:54:56',NULL,NULL,NULL,0),(3,'Kelvyn Kofi Doe','student1@campus.edu',NULL,'$2y$12$BJ2YsXS5GCaLhdXspw7PQef.7ZpMz3lLcXKnT0jLMUtwQDp8hgfOy','student',1,'2026000001','+233241234567',NULL,'2026-11-01 19:38:02','2026-11-08 22:54:57',NULL,NULL,NULL,0),(4,'Akosua Serwaa','student2@campus.edu',NULL,'$2y$12$DD9jrTZD074wmxGhSpURCeWS8WtomPRL4HukDikO6Nu757KgzmD1.','student',1,'2026000002','+233249876543',NULL,'2026-11-01 19:38:02','2026-11-08 22:54:57',NULL,NULL,NULL,0),(5,'Kelvyn Honora','kelvinhonorajunior@gmail.com',NULL,'$2y$12$SBNg04/eYG7voKR2md0cfeUzTdbil4qlTEouxgyjoFy5cz0aWyNpO','student',1,'5240101305','0544893582',NULL,'2026-11-03 15:10:15','2026-11-03 15:10:15',NULL,NULL,NULL,0);
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

-- Dump completed on 2026-11-22  9:56:41
