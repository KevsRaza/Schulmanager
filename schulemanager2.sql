-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: schulmanagerdb
-- ------------------------------------------------------
-- Server version	8.0.42

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `ausbildung`
--

DROP TABLE IF EXISTS `ausbildung`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ausbildung` (
  `id_Ausbildung` int NOT NULL AUTO_INCREMENT,
  `name_Ausbildung` varchar(100) NOT NULL,
  `id_Dossier` int NOT NULL,
  PRIMARY KEY (`id_Ausbildung`),
  KEY `id_Dossier` (`id_Dossier`),
  CONSTRAINT `ausbildung_ibfk_1` FOREIGN KEY (`id_Dossier`) REFERENCES `dossier` (`id_Dossier`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ausbildung`
--

LOCK TABLES `ausbildung` WRITE;
/*!40000 ALTER TABLE `ausbildung` DISABLE KEYS */;
INSERT INTO `ausbildung` VALUES (1,'Développeur',8),(2,'Admin réseau',8),(3,'Responsable Base de données',8),(4,'Récéptionniste',7),(5,'Barman',7),(6,'Serveuse',7),(7,'Aide soignante',9),(8,'Dentiste',9),(9,'Sage femme',9),(10,'aucun',13);
/*!40000 ALTER TABLE `ausbildung` ENABLE KEYS */;
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
  PRIMARY KEY (`key`)
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
  PRIMARY KEY (`key`)
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
-- Table structure for table `dokument`
--

DROP TABLE IF EXISTS `dokument`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dokument` (
  `id_dokument` int NOT NULL AUTO_INCREMENT,
  `name_Dokument` varchar(50) NOT NULL,
  `dokumentTyp` varchar(50) NOT NULL,
  `weg_Dokument` varchar(255) NOT NULL,
  `id_Dossier` int NOT NULL,
  PRIMARY KEY (`id_dokument`),
  KEY `id_Dossier` (`id_Dossier`),
  CONSTRAINT `dokument_ibfk_1` FOREIGN KEY (`id_Dossier`) REFERENCES `dossier` (`id_Dossier`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dokument`
--

LOCK TABLES `dokument` WRITE;
/*!40000 ALTER TABLE `dokument` DISABLE KEYS */;
INSERT INTO `dokument` VALUES (1,'CV Modèle','PDF','/documents/cv.pdf',6),(2,'Visa Modèle','PDF','/documents/visa.pdf',6),(3,'CV Alpha','PDF','/documents/cvAlpha.pdf',5),(4,'Contract Alpha','PDF','/documents/contractAlpha.pdf',4),(5,'Visa Alpha','PDF','/documents/visaAlpha.pdf',5);
/*!40000 ALTER TABLE `dokument` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dossier`
--

DROP TABLE IF EXISTS `dossier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dossier` (
  `id_Dossier` int NOT NULL AUTO_INCREMENT,
  `name_Dossier` varchar(50) NOT NULL,
  PRIMARY KEY (`id_Dossier`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dossier`
--

LOCK TABLES `dossier` WRITE;
/*!40000 ALTER TABLE `dossier` DISABLE KEYS */;
INSERT INTO `dossier` VALUES (1,'A faire'),(2,'En cours'),(3,'Fini'),(4,'DocumentFirma'),(5,'DocumentSchuler'),(6,'DocumentModel'),(7,'Hotel'),(8,'Info'),(9,'Hopital'),(10,'Centre de formation'),(11,'Lycée'),(12,'Université'),(13,'Aucun'),(14,'DossierTest');
/*!40000 ALTER TABLE `dossier` ENABLE KEYS */;
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
-- Table structure for table `firma`
--

DROP TABLE IF EXISTS `firma`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `firma` (
  `id_Firma` int NOT NULL AUTO_INCREMENT,
  `name_Firma` varchar(100) NOT NULL,
  `manager_Firma` varchar(100) NOT NULL,
  `logo_Firma` varchar(255) NOT NULL,
  `id_Dossier` int NOT NULL,
  PRIMARY KEY (`id_Firma`),
  KEY `dokument_ibfk_1_idx` (`id_Dossier`),
  KEY `id_Dossier` (`id_Dossier`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `firma`
--

LOCK TABLES `firma` WRITE;
/*!40000 ALTER TABLE `firma` DISABLE KEYS */;
INSERT INTO `firma` VALUES (1,'Tech Solutions GmbH','Mme. Weber','/logos/tech-solutions.png',8),(2,'Hetzner','Mr Hetzner','/logos/hetzner.png',8),(3,'Ionos','Mlle Ionos','/logos/Ionos.png',8),(4,'Hopital','Mr. Artzt','/logos/Hopital.png',9),(5,'Aucun','Aucun','/logos/Aucun.png',13);
/*!40000 ALTER TABLE `firma` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_09_07_112819_create_dossiers_table',2),(5,'2025_09_07_112914_create_schules_table',2),(6,'2025_09_07_112940_create_firmas_table',2),(7,'2025_09_07_115055_adjust_schuler_table',2);
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
-- Table structure for table `schule`
--

DROP TABLE IF EXISTS `schule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `schule` (
  `id_Schule` int NOT NULL AUTO_INCREMENT,
  `name_Schule` varchar(100) NOT NULL,
  `land_Schule` varchar(50) NOT NULL,
  `name_Schulleiter` varchar(100) NOT NULL,
  `id_Dossier` int NOT NULL,
  PRIMARY KEY (`id_Schule`),
  KEY `id_Dossier` (`id_Dossier`),
  CONSTRAINT `schule_ibfk_1` FOREIGN KEY (`id_Dossier`) REFERENCES `dossier` (`id_Dossier`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schule`
--

LOCK TABLES `schule` WRITE;
/*!40000 ALTER TABLE `schule` DISABLE KEYS */;
INSERT INTO `schule` VALUES (1,'Lycée Technique','Allemagne','M. Schmidt',11),(2,'AST Schule','Madagascar','Mme. Schteffek',10),(3,'Infocentre','Madagascar','Mr. Princi',12),(5,'Test','TestLand','Mr Test',14);
/*!40000 ALTER TABLE `schule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schuler`
--

DROP TABLE IF EXISTS `schuler`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `schuler` (
  `id_Schuler` int NOT NULL AUTO_INCREMENT,
  `vorname` varchar(50) DEFAULT NULL,
  `familiename` varchar(50) NOT NULL,
  `geburtsdatum_Schuler` date NOT NULL,
  `land_Shuler` varchar(50) NOT NULL,
  `deutschniveau_Schuler` enum('A1','A2','B1','B2','C1','C2') DEFAULT NULL,
  `bildungsniveau_Schuler` enum('Primaire','Secondaire','Universitaire','Professionnel') DEFAULT NULL,
  `datum_Anfang_Ausbildung` date NOT NULL,
  `datum_Ende_Ausbildung` date NOT NULL,
  `id_Firma` int NOT NULL,
  `id_Schule` int NOT NULL,
  `id_Ausbildung` int NOT NULL,
  `id_Dossier` int NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`id_Schuler`),
  UNIQUE KEY `email` (`email`),
  KEY `id_Firma` (`id_Firma`),
  KEY `id_Schule` (`id_Schule`),
  KEY `id_Ausbildung` (`id_Ausbildung`),
  KEY `id_Dossier` (`id_Dossier`),
  CONSTRAINT `schuler_ibfk_1` FOREIGN KEY (`id_Firma`) REFERENCES `firma` (`id_Firma`),
  CONSTRAINT `schuler_ibfk_2` FOREIGN KEY (`id_Schule`) REFERENCES `schule` (`id_Schule`),
  CONSTRAINT `schuler_ibfk_3` FOREIGN KEY (`id_Ausbildung`) REFERENCES `ausbildung` (`id_Ausbildung`),
  CONSTRAINT `schuler_ibfk_5` FOREIGN KEY (`id_Dossier`) REFERENCES `dossier` (`id_Dossier`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schuler`
--

LOCK TABLES `schuler` WRITE;
/*!40000 ALTER TABLE `schuler` DISABLE KEYS */;
INSERT INTO `schuler` VALUES (1,'Alpha','Rab','2001-11-09','Madagascar','A2','Universitaire','2025-05-05','2028-05-05',5,3,10,1,'atsiorinirina@gmail.com'),(2,'Johnny','Stann','2000-05-05','Perou','B2','Secondaire','2024-06-06','2027-06-06',4,1,7,2,'johnnystann@gmail.com'),(3,'Nina','Williams','2003-09-09','Afrique','B1','Universitaire','2022-04-04','2025-04-04',1,1,5,3,'ninawilliams@gmail.com');
/*!40000 ALTER TABLE `schuler` ENABLE KEYS */;
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
INSERT INTO `sessions` VALUES ('2rNMRR6wFoQZZBfvkpcTV92BFzU0izL3MBvE24Ao',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUkdtT21hRUxVaHJtemtnT0lTNmlVV200bkJVcnp1QTY3eDRCRzVWRiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozNToiaHR0cDovL3NodWxlbWFuYWdlcjIudGVzdC9kYXNoYm9hcmQiO319',1758135098),('akmKiOnNhNFfKXuaJLght8k7ennvec6nPoNFCYxK',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVzN4SzFSdU5zOVROaVZIUXh5dXQ4M25qalBDT3hET1JwWHE4Z1RneiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozNToiaHR0cDovL3NodWxlbWFuYWdlcjIudGVzdC9kYXNoYm9hcmQiO319',1758141262),('HpYnLuaqp0DvufcVr85Nyf2Zz3BtQ84OYxXyseO1',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoidzBoSVhXZ0hNT2JhazY1eUcyTlJnaVB0dXFkQ1pTY2p6MHV3WlBOQiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozNToiaHR0cDovL3NodWxlbWFuYWdlcjIudGVzdC9kYXNoYm9hcmQiO319',1758142144),('K0CnSUWvPL7bb1jib2ruSM9i2GwviobB2vN0rYmp',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.22.1 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoidWJYa1U2OVBTVHJJa2lPMk1XSmdMdjlJRlZoNkdjazRkaW41QmF0YiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9zaHVsZW1hbmFnZXIyLnRlc3QvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1758138651),('LsArptzkWLJOgaPUhuM4OtbXT38xngQzXguLeAK5',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.22.1 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNE81UVlRQkZBRmpSRGczVEs2UlBpZkpUeE9Tc0lDT0xGdTBYcjQxTyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9zaHVsZW1hbmFnZXIyLnRlc3QvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1758136059),('u84SjrmA2CagIflbqGjq0anV9y0OJzJC1dE6v6UH',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.22.1 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoic0g2Mk1XcVA4UTFYcXJmdG1neU9oQ1p5MDc3TGg1WmRBaE1KU1BpYyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly9zaHVsZW1hbmFnZXIyLnRlc3QvP2hlcmQ9cHJldmlldyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1758136059),('Yi9ssUoVshfvAzSvc3VuJI82new0DC941EZ0OM6g',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.22.1 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiclQ2U3JEdW1yZGRWVEtjWUgxT3RtRFYxVlhkakROcVFDSWF0bks4MCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly9zaHVsZW1hbmFnZXIyLnRlc3QvP2hlcmQ9cHJldmlldyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1758138651);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
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
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Alpha','alpha@gmail.com',NULL,'$2y$12$ydjwtbgML.0oaFh8.PM3w.KPZdtx09Jp6KWfbB4LgkilPTOw7E7Q6',NULL,'2025-06-17 05:20:04','2025-06-17 05:20:04'),(2,'Johnny','johnny@gmail.com',NULL,'$2y$12$4xE3gVXm3bGvnPo6nUPXU.RONgFBZnE1b.5kdVJLHpIk0VN./QFv.',NULL,'2025-06-17 05:20:04','2025-06-17 05:20:04'),(3,'test','test@gmail.com',NULL,'test',NULL,NULL,NULL),(4,'Stann','testtest@gmail.com',NULL,'$2y$12$0dIniipAmXZTjjpHmbvPMOTOJhYYg3UHuCD6zmlD4RMslcDS2CDp6',NULL,'2025-07-30 10:27:35','2025-07-30 10:27:35'),(5,'JS','js@gmail.com',NULL,'$2y$12$PhGCicoN8.MX1bFwSwlVWOXP2U3y2tKT411pD.BG1SrNnpmhfsDl6',NULL,'2025-09-07 02:51:30','2025-09-07 02:51:30');
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

-- Dump completed on 2025-09-18  0:05:58
