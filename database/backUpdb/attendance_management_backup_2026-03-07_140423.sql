-- MySQL dump 10.13  Distrib 8.0.39, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: attendance_management
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `academic_years`
--

DROP TABLE IF EXISTS `academic_years`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `academic_years` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `academic_years`
--

LOCK TABLES `academic_years` WRITE;
/*!40000 ALTER TABLE `academic_years` DISABLE KEYS */;
INSERT INTO `academic_years` VALUES (1,'2024-2025','2024-01-01','2024-12-31','2026-02-28 09:43:38','2026-02-28 09:43:38'),(2,'2025-2026','2025-01-01','2025-12-31','2026-02-28 09:43:38','2026-02-28 09:43:38'),(3,'2026-2027','2026-01-01','2026-12-31','2026-02-28 09:43:38','2026-02-28 09:43:38');
/*!40000 ALTER TABLE `academic_years` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attendance_records`
--

DROP TABLE IF EXISTS `attendance_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attendance_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `class_session_id` bigint unsigned NOT NULL,
  `student_id` bigint unsigned NOT NULL,
  `recorded_by` bigint unsigned NOT NULL,
  `status` enum('present','absent','permission') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'present',
  `comment` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attendance_records_class_session_id_student_id_unique` (`class_session_id`,`student_id`),
  KEY `attendance_records_student_id_foreign` (`student_id`),
  KEY `attendance_records_recorded_by_foreign` (`recorded_by`),
  CONSTRAINT `attendance_records_class_session_id_foreign` FOREIGN KEY (`class_session_id`) REFERENCES `class_sessions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attendance_records_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `attendance_records_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendance_records`
--

LOCK TABLES `attendance_records` WRITE;
/*!40000 ALTER TABLE `attendance_records` DISABLE KEYS */;
INSERT INTO `attendance_records` VALUES (1,1,1,3,'present',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(2,1,2,3,'present',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(3,2,1,4,'absent','Sick leave','2026-02-28 09:43:38','2026-02-28 09:43:38'),(4,2,3,4,'present',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38');
/*!40000 ALTER TABLE `attendance_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blacklists`
--

DROP TABLE IF EXISTS `blacklists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blacklists` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `term_id` bigint unsigned NOT NULL,
  `absence_count` int unsigned NOT NULL DEFAULT '0',
  `flagged_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blacklists_student_id_term_id_unique` (`student_id`,`term_id`),
  KEY `blacklists_term_id_foreign` (`term_id`),
  CONSTRAINT `blacklists_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blacklists_term_id_foreign` FOREIGN KEY (`term_id`) REFERENCES `terms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blacklists`
--

LOCK TABLES `blacklists` WRITE;
/*!40000 ALTER TABLE `blacklists` DISABLE KEYS */;
INSERT INTO `blacklists` VALUES (1,1,1,5,'2026-02-28 16:43:38','2026-02-28 09:43:38','2026-02-28 09:43:38');
/*!40000 ALTER TABLE `blacklists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
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
-- Table structure for table `class_sessions`
--

DROP TABLE IF EXISTS `class_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `class_sessions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `class_id` bigint unsigned NOT NULL,
  `term_id` bigint unsigned NOT NULL,
  `teacher_id` bigint unsigned NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `day_of_week` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `status` enum('scheduled','not_started','ongoing','completed','canceled','postponed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scheduled',
  `created_on` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `class_sessions_class_id_foreign` (`class_id`),
  KEY `class_sessions_term_id_foreign` (`term_id`),
  KEY `class_sessions_teacher_id_foreign` (`teacher_id`),
  KEY `class_sessions_subject_id_foreign` (`subject_id`),
  CONSTRAINT `class_sessions_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `class_sessions_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `class_sessions_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `class_sessions_term_id_foreign` FOREIGN KEY (`term_id`) REFERENCES `terms` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class_sessions`
--

LOCK TABLES `class_sessions` WRITE;
/*!40000 ALTER TABLE `class_sessions` DISABLE KEYS */;
INSERT INTO `class_sessions` VALUES (1,1,1,1,1,'Monday','08:00:00','09:00:00','scheduled',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(2,2,1,2,2,'Monday','09:00:00','10:00:00','scheduled',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(3,3,1,1,3,'Monday','10:00:00','11:00:00','scheduled',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(4,1,1,1,1,'Tuesday','08:00:00','09:00:00','scheduled',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(5,2,1,2,2,'Tuesday','09:00:00','10:00:00','scheduled',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(6,3,1,1,3,'Tuesday','10:00:00','11:00:00','scheduled',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(7,1,1,1,1,'Wednesday','08:00:00','09:00:00','scheduled',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(8,2,1,2,2,'Wednesday','09:00:00','10:00:00','scheduled',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(9,3,1,1,3,'Wednesday','10:00:00','11:00:00','scheduled',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(10,1,1,1,1,'Thursday','08:00:00','09:00:00','scheduled',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(11,2,1,2,2,'Thursday','09:00:00','10:00:00','scheduled',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(12,3,1,1,3,'Thursday','10:00:00','11:00:00','scheduled',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(13,1,1,1,1,'Friday','08:00:00','09:00:00','scheduled',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(14,2,1,2,2,'Friday','09:00:00','10:00:00','scheduled',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(15,3,1,1,3,'Friday','10:00:00','11:00:00','scheduled',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(16,1,3,2,6,'Monday','01:00:00','02:00:00','ongoing',NULL,'2026-02-28 23:54:47','2026-03-01 00:28:24');
/*!40000 ALTER TABLE `class_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `class_teacher`
--

DROP TABLE IF EXISTS `class_teacher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `class_teacher` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `class_id` bigint unsigned NOT NULL,
  `teacher_id` bigint unsigned NOT NULL,
  `assigned_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `class_teacher_class_id_teacher_id_unique` (`class_id`,`teacher_id`),
  KEY `class_teacher_teacher_id_foreign` (`teacher_id`),
  CONSTRAINT `class_teacher_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `class_teacher_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class_teacher`
--

LOCK TABLES `class_teacher` WRITE;
/*!40000 ALTER TABLE `class_teacher` DISABLE KEYS */;
INSERT INTO `class_teacher` VALUES (1,1,1,NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(2,2,2,NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(3,3,1,NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(4,4,2,NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(5,5,1,NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38');
/*!40000 ALTER TABLE `class_teacher` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classes`
--

DROP TABLE IF EXISTS `classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `classes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `grade_level_id` bigint unsigned NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `room_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `classes_grade_level_id_foreign` (`grade_level_id`),
  CONSTRAINT `classes_grade_level_id_foreign` FOREIGN KEY (`grade_level_id`) REFERENCES `grade_levels` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classes`
--

LOCK TABLES `classes` WRITE;
/*!40000 ALTER TABLE `classes` DISABLE KEYS */;
INSERT INTO `classes` VALUES (1,'Class 1-A',1,'2025-01-01','2025-12-31','101','2026-02-28 09:43:38','2026-02-28 09:43:38'),(2,'Class 1-B',1,'2025-01-01','2025-12-31','102','2026-02-28 09:43:38','2026-02-28 09:43:38'),(3,'Class 2-A',2,'2025-01-01','2025-12-31','201','2026-02-28 09:43:38','2026-02-28 09:43:38'),(4,'Class 2-B',2,'2025-01-01','2025-12-31','202','2026-02-28 09:43:38','2026-02-28 09:43:38'),(5,'Class 3-A',3,'2025-01-01','2025-12-31','301','2026-02-28 09:43:38','2026-02-28 09:43:38');
/*!40000 ALTER TABLE `classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `enrollments`
--

DROP TABLE IF EXISTS `enrollments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `enrollments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `class_id` bigint unsigned NOT NULL,
  `student_id` bigint unsigned NOT NULL,
  `grade_level_id` bigint unsigned NOT NULL,
  `enrolled_on` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `enrollments_class_id_student_id_unique` (`class_id`,`student_id`),
  KEY `enrollments_student_id_foreign` (`student_id`),
  KEY `enrollments_grade_level_id_foreign` (`grade_level_id`),
  CONSTRAINT `enrollments_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `enrollments_grade_level_id_foreign` FOREIGN KEY (`grade_level_id`) REFERENCES `grade_levels` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `enrollments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enrollments`
--

LOCK TABLES `enrollments` WRITE;
/*!40000 ALTER TABLE `enrollments` DISABLE KEYS */;
INSERT INTO `enrollments` VALUES (1,1,1,1,'2025-01-15','2026-02-28 09:43:38','2026-02-28 09:43:38'),(2,1,2,1,'2025-01-15','2026-02-28 09:43:38','2026-02-28 09:43:38'),(3,2,3,1,'2025-01-15','2026-02-28 09:43:38','2026-02-28 09:43:38'),(4,3,4,2,'2025-01-15','2026-02-28 09:43:38','2026-02-28 09:43:38');
/*!40000 ALTER TABLE `enrollments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
-- Table structure for table `grade_level_subjects`
--

DROP TABLE IF EXISTS `grade_level_subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grade_level_subjects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `grade_level_id` bigint unsigned NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `grade_level_subjects_grade_level_id_subject_id_unique` (`grade_level_id`,`subject_id`),
  KEY `grade_level_subjects_subject_id_foreign` (`subject_id`),
  CONSTRAINT `grade_level_subjects_grade_level_id_foreign` FOREIGN KEY (`grade_level_id`) REFERENCES `grade_levels` (`id`) ON DELETE CASCADE,
  CONSTRAINT `grade_level_subjects_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grade_level_subjects`
--

LOCK TABLES `grade_level_subjects` WRITE;
/*!40000 ALTER TABLE `grade_level_subjects` DISABLE KEYS */;
INSERT INTO `grade_level_subjects` VALUES (1,1,1,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(2,1,2,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(3,1,6,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(4,1,7,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(5,2,1,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(6,2,2,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(7,2,3,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(8,3,1,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(9,3,2,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(10,3,3,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(11,3,4,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(12,3,8,'2026-02-28 09:43:38','2026-02-28 09:43:38');
/*!40000 ALTER TABLE `grade_level_subjects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grade_levels`
--

DROP TABLE IF EXISTS `grade_levels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grade_levels` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_no` int unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `grade_levels_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grade_levels`
--

LOCK TABLES `grade_levels` WRITE;
/*!40000 ALTER TABLE `grade_levels` DISABLE KEYS */;
INSERT INTO `grade_levels` VALUES (1,'GL-01','Grade 1',1,1,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(2,'GL-02','Grade 2',2,1,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(3,'GL-03','Grade 3',3,1,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(4,'GL-04','Grade 4',4,1,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(5,'GL-05','Grade 5',5,1,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(6,'GL-06','Grade 6',6,1,'2026-02-28 09:43:38','2026-02-28 09:43:38');
/*!40000 ALTER TABLE `grade_levels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_01_24_183708_create_roles_table',1),(5,'2026_01_24_183715_create_permissions_table',1),(6,'2026_01_24_183728_create_user_role_table',1),(7,'2026_01_24_183734_create_role_permission_table',1),(8,'2026_01_24_190342_create_personal_access_tokens_table',1),(9,'2026_01_27_160000_create_grade_levels_table',1),(10,'2026_01_27_160010_create_subjects_table',1),(11,'2026_01_27_161929_create_academic_years_table',1),(12,'2026_01_27_161938_create_classes_table',1),(13,'2026_01_27_161938_create_terms_table',1),(14,'2026_01_27_161947_create_students_table',1),(15,'2026_01_27_161947_create_teachers_table',1),(16,'2026_01_27_161957_create_blacklists_table',1),(17,'2026_01_27_163317_create_class_teacher_table',1),(18,'2026_01_27_163434_create_enrollments_table',1),(19,'2026_01_27_163750_create_class_sessions_table',1),(20,'2026_01_27_164056_create_attendance_records_table',1),(21,'2026_01_27_165020_create_user_profiles_table',1),(22,'2026_02_04_180251_create_report_exports_table',1),(23,'2026_02_05_174816_create_grade_level_subject',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'Manage Users','users.manage','active',NULL,'2026-02-28 09:43:37','2026-02-28 09:43:37'),(2,'Manage Roles','roles.manage','active',NULL,'2026-02-28 09:43:37','2026-02-28 09:43:37'),(3,'Manage Permissions','permissions.manage','active',NULL,'2026-02-28 09:43:37','2026-02-28 09:43:37'),(4,'Manage Teachers','teachers.manage','active',NULL,'2026-02-28 09:43:37','2026-02-28 09:43:37'),(5,'Manage Students','students.manage','active',NULL,'2026-02-28 09:43:37','2026-02-28 09:43:37'),(6,'Manage Attendance','attendance.manage','active',NULL,'2026-02-28 09:43:37','2026-02-28 09:43:37'),(7,'View Reports','reports.view','active',NULL,'2026-02-28 09:43:37','2026-02-28 09:43:37'),(8,'Export Reports','reports.export','active',NULL,'2026-02-28 09:43:37','2026-02-28 09:43:37');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (1,'App\\Models\\User',4,'api-token','2d3f01c3a3dd00434aa6260315e2ea9365d21739921f31b71c59228788ba1da3','[\"*\"]',NULL,NULL,'2026-02-28 10:01:36','2026-02-28 10:01:36'),(2,'App\\Models\\User',2,'api-token','fcda1a628e824021ff8d4e4d28d449077d31e8feb4b0b7258acd435fc2b3241d','[\"*\"]','2026-03-06 09:27:58',NULL,'2026-03-06 09:07:36','2026-03-06 09:27:58'),(3,'App\\Models\\User',1,'api-token','ed1a3b10414ee8bf55d013316d4214443eeaf6ff8c1b99d401bc86c66db0c30c','[\"*\"]','2026-03-06 09:38:06',NULL,'2026-03-06 09:28:32','2026-03-06 09:38:06'),(4,'App\\Models\\User',6,'api-token','f72a0938efa92cbf53ad9e59371e36e3bb0d24429f2e5f799dfd00d8633f0a49','[\"*\"]','2026-03-06 23:48:11',NULL,'2026-03-06 09:38:53','2026-03-06 23:48:11'),(5,'App\\Models\\User',1,'api-token','fdc83075c0507e30f812f50ce918425f84081483630274b936c934f22023df7c','[\"*\"]','2026-03-07 00:00:30',NULL,'2026-03-06 23:50:36','2026-03-07 00:00:30');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `report_exports`
--

DROP TABLE IF EXISTS `report_exports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `report_exports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `class_id` bigint unsigned NOT NULL,
  `report_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('completed','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size_kb` int unsigned NOT NULL DEFAULT '0',
  `filters` json DEFAULT NULL,
  `exported_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `report_exports_user_id_foreign` (`user_id`),
  KEY `report_exports_class_id_foreign` (`class_id`),
  CONSTRAINT `report_exports_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `report_exports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `report_exports`
--

LOCK TABLES `report_exports` WRITE;
/*!40000 ALTER TABLE `report_exports` DISABLE KEYS */;
INSERT INTO `report_exports` VALUES (1,3,1,'attendance','pdf','completed','exports/class-1-report-2025-01-15.pdf',256,'\"{\\\"term\\\":1,\\\"class\\\":1}\"','2025-01-15 10:30:00','2026-02-28 09:43:38','2026-02-28 09:43:38'),(2,4,2,'attendance','xlsx','completed','exports/class-2-report-2025-01-15.xlsx',512,'\"{\\\"term\\\":1,\\\"class\\\":2}\"','2025-01-15 11:45:00','2026-02-28 09:43:39','2026-02-28 09:43:39');
/*!40000 ALTER TABLE `report_exports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint unsigned NOT NULL,
  `permission_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_permissions_role_id_permission_id_unique` (`role_id`,`permission_id`),
  KEY `role_permissions_permission_id_foreign` (`permission_id`),
  CONSTRAINT `role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permissions`
--

LOCK TABLES `role_permissions` WRITE;
/*!40000 ALTER TABLE `role_permissions` DISABLE KEYS */;
INSERT INTO `role_permissions` VALUES (1,1,1,'2026-02-28 09:43:37','2026-02-28 09:43:37'),(2,1,2,'2026-02-28 09:43:37','2026-02-28 09:43:37'),(3,1,3,'2026-02-28 09:43:37','2026-02-28 09:43:37'),(4,1,4,'2026-02-28 09:43:37','2026-02-28 09:43:37'),(5,1,5,'2026-02-28 09:43:37','2026-02-28 09:43:37'),(6,1,6,'2026-02-28 09:43:37','2026-02-28 09:43:37'),(7,1,7,'2026-02-28 09:43:37','2026-02-28 09:43:37'),(8,1,8,'2026-02-28 09:43:37','2026-02-28 09:43:37'),(9,2,1,'2026-02-28 09:43:37','2026-02-28 09:43:37'),(10,2,4,'2026-02-28 09:43:37','2026-02-28 09:43:37'),(11,2,5,'2026-02-28 09:43:37','2026-02-28 09:43:37'),(12,3,6,'2026-02-28 09:43:37','2026-02-28 09:43:37'),(13,3,7,'2026-02-28 09:43:37','2026-02-28 09:43:37'),(14,4,7,'2026-02-28 09:43:37','2026-02-28 09:43:37');
/*!40000 ALTER TABLE `role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Super Admin','super_admin','active','Full system access','2026-02-28 09:43:37','2026-02-28 09:43:37'),(2,'Admin','admin','active','System administrator','2026-02-28 09:43:37','2026-02-28 09:43:37'),(3,'Teacher','teacher','active','Teacher role','2026-02-28 09:43:37','2026-02-28 09:43:37'),(4,'Student','student','active','Student role','2026-02-28 09:43:37','2026-02-28 09:43:37');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `students` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `student_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `students_student_code_unique` (`student_code`),
  KEY `students_user_id_foreign` (`user_id`),
  CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` VALUES (1,5,'STU-001','active','2026-02-28 09:43:38','2026-02-28 09:43:38'),(2,6,'STU-002','active','2026-02-28 09:43:38','2026-02-28 09:43:38'),(3,7,'STU-003','active','2026-02-28 09:43:38','2026-02-28 09:43:38'),(4,8,'STU-004','active','2026-02-28 09:43:38','2026-02-28 09:43:38'),(5,10,'STU-00010','active','2026-03-06 09:24:20','2026-03-06 09:24:20');
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subjects`
--

DROP TABLE IF EXISTS `subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subjects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subjects_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subjects`
--

LOCK TABLES `subjects` WRITE;
/*!40000 ALTER TABLE `subjects` DISABLE KEYS */;
INSERT INTO `subjects` VALUES (1,'Mathematics','MATH','2026-02-28 09:43:38','2026-02-28 09:43:38'),(2,'English','ENG','2026-02-28 09:43:38','2026-02-28 09:43:38'),(3,'Physics','PHY','2026-02-28 09:43:38','2026-02-28 09:43:38'),(4,'Chemistry','CHEM','2026-02-28 09:43:38','2026-02-28 09:43:38'),(5,'Biology','BIO','2026-02-28 09:43:38','2026-02-28 09:43:38'),(6,'History','HIS','2026-02-28 09:43:38','2026-02-28 09:43:38'),(7,'Geography','GEO','2026-02-28 09:43:38','2026-02-28 09:43:38'),(8,'Computer Science','CS','2026-02-28 09:43:38','2026-02-28 09:43:38');
/*!40000 ALTER TABLE `subjects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teachers`
--

DROP TABLE IF EXISTS `teachers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teachers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `teacher_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `teachers_teacher_code_unique` (`teacher_code`),
  KEY `teachers_user_id_foreign` (`user_id`),
  CONSTRAINT `teachers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teachers`
--

LOCK TABLES `teachers` WRITE;
/*!40000 ALTER TABLE `teachers` DISABLE KEYS */;
INSERT INTO `teachers` VALUES (1,3,'TEACH-001','inactive','2026-02-28 09:43:38','2026-03-06 23:57:11'),(2,4,'TEACH-002','active','2026-02-28 09:43:38','2026-02-28 09:43:38');
/*!40000 ALTER TABLE `teachers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `terms`
--

DROP TABLE IF EXISTS `terms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `terms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `academic_year_id` bigint unsigned NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `terms_academic_year_id_foreign` (`academic_year_id`),
  CONSTRAINT `terms_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `terms`
--

LOCK TABLES `terms` WRITE;
/*!40000 ALTER TABLE `terms` DISABLE KEYS */;
INSERT INTO `terms` VALUES (1,'Term 1',1,'2025-01-01','2025-04-30','2026-02-28 09:43:38','2026-02-28 09:43:38'),(2,'Term 2',1,'2025-05-01','2025-08-31','2026-02-28 09:43:38','2026-02-28 09:43:38'),(3,'Term 3',1,'2025-09-01','2025-12-31','2026-02-28 09:43:38','2026-02-28 09:43:38');
/*!40000 ALTER TABLE `terms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_profiles`
--

DROP TABLE IF EXISTS `user_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_profiles` (
  `user_id` bigint unsigned NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `user_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_profiles`
--

LOCK TABLES `user_profiles` WRITE;
/*!40000 ALTER TABLE `user_profiles` DISABLE KEYS */;
INSERT INTO `user_profiles` VALUES (1,'Super','Admin','+1234567890','Male','1990-01-01','123 Admin Street',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(2,'Admin','User','+1234567891','Male','1991-01-01','456 Main Street',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(3,'John','Teacher','+1234567892','Male','1988-01-01','789 Teacher Lane',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(4,'Jane','Teacher','+1234567893','Female','1989-01-01','321 School Road',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(5,'Alice','Student','+1234567894','Female','2010-01-01','654 Student Ave',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(6,'Bob','Student','+1234567895','Male','2010-06-01','987 Youth Blvd',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(7,'Charlie','Student','+1234567896','Male','2010-09-01','135 College Dr',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(8,'Diana','Student','+1234567897','Female','2011-01-01','246 Education St',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38');
/*!40000 ALTER TABLE `user_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_role`
--

DROP TABLE IF EXISTS `user_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_role` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_role_user_id_role_id_unique` (`user_id`,`role_id`),
  KEY `user_role_role_id_foreign` (`role_id`),
  CONSTRAINT `user_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_role_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_role`
--

LOCK TABLES `user_role` WRITE;
/*!40000 ALTER TABLE `user_role` DISABLE KEYS */;
INSERT INTO `user_role` VALUES (1,1,1,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(2,2,2,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(3,3,3,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(4,4,3,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(5,5,4,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(6,6,4,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(7,7,4,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(8,8,4,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(9,9,4,NULL,NULL),(10,10,4,NULL,NULL);
/*!40000 ALTER TABLE `user_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Super Admin','superadmin@example.com',NULL,'$2y$12$H65C0k8K4xqz5L04uiJwleueZcMrVpVdTfdCaXwK8Ph8yo4cro1V2','active',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(2,'Admin','admin@example.com',NULL,'$2y$12$vT4xI76cVhiuyK.MZMrsDejfDNj7qWdwxQaYv7UVXiBGr62SOuWmu','active',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(3,'John Teacher','john.teacher@example.com',NULL,'$2y$12$8J4ciDiOJbNWe/3WUjM/MuzylzLTNMDYBHI/WlXQUcez3lOtk8PWm','active',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(4,'Phanna Teacher','phanna.teacher@example.com',NULL,'$2y$12$TNpvnumd7Myu1WTZhOvbT.Qq92EFCEXq.cUL/CNt6M7sZxEjMQE7m','active',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(5,'SakHak Student','sakhak.student@example.com',NULL,'$2y$12$H1DbOsP3SLgfvpf97ofHZ.hs.8yUS/b.jGrAIfE5sczgSMBLrKX5y','active',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(6,'Yut Student','yut.student@example.com',NULL,'$2y$12$Z4G18dhNhLqudfrFHQpvX.BWIluxNh/KPYfr4q9bSFEZcGYOIgzYS','active',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(7,'Vivhet Student','vivhet.student@example.com',NULL,'$2y$12$D0bMwu3VSB1QYz0UgAmX2OXVptUWWSPnrRxpSe0Ck3kNQBwQIai12','active',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(8,'Namhong Student','namhong.student@example.com',NULL,'$2y$12$Q1jsjAa1D/G7PH0c2WZpyu11l6mib4j12PT3ILreUxHPeW.NQ98fe','active',NULL,'2026-02-28 09:43:38','2026-02-28 09:43:38'),(9,'Phannaa','phannaa@example.com',NULL,'$2y$12$V08CmO/DPjx.fbnKFFqF7eJYTmj4HFGOeHXODWloeCWPQOyr8ql3e','active',NULL,'2026-03-06 09:21:46','2026-03-06 09:21:46'),(10,'Phannaaa','phannaaa@example.com',NULL,'$2y$12$/dSdTnYMhjqwCa28BD/3eOF7B6Oe/PEvgbELCq6zPnttXFS9PnUYe','active',NULL,'2026-03-06 09:24:20','2026-03-06 09:24:20');
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

-- Dump completed on 2026-03-07 14:04:24
