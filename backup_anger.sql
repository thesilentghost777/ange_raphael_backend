/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.1-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: ANGER
-- ------------------------------------------------------
-- Server version	11.8.1-MariaDB-4

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
-- Table structure for table `admin_stats`
--

DROP TABLE IF EXISTS `admin_stats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_stats` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `connection_count` int(11) NOT NULL DEFAULT 0,
  `request_count` int(11) NOT NULL DEFAULT 0,
  `average_response_time` int(11) NOT NULL DEFAULT 0,
  `error_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admin_stats_date_index` (`date`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_stats`
--

LOCK TABLES `admin_stats` WRITE;
/*!40000 ALTER TABLE `admin_stats` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `admin_stats` VALUES
(1,'2025-12-07',0,3,131,0,'2025-12-07 23:41:47','2025-12-07 23:44:36');
/*!40000 ALTER TABLE `admin_stats` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `auto_ecole_paiements`
--

DROP TABLE IF EXISTS `auto_ecole_paiements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `auto_ecole_paiements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `type_paiement` enum('en_ligne','code_caisse') NOT NULL,
  `tranche` enum('x','y','complet') NOT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `statut` enum('en_attente','valide','echoue') NOT NULL DEFAULT 'en_attente',
  `methode_paiement` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `date_paiement` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `auto_ecole_paiements_transaction_id_unique` (`transaction_id`),
  KEY `auto_ecole_paiements_user_id_tranche_index` (`user_id`,`tranche`),
  CONSTRAINT `auto_ecole_paiements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `auto_ecole_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auto_ecole_paiements`
--

LOCK TABLES `auto_ecole_paiements` WRITE;
/*!40000 ALTER TABLE `auto_ecole_paiements` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `auto_ecole_paiements` VALUES
(1,1,45000.00,'code_caisse','x','CC-CC-2C7EE92C82','valide',NULL,NULL,'2025-12-07 19:02:16','2025-12-08 01:02:16','2025-12-08 01:02:16'),
(2,2,55000.00,'en_ligne','complet','TXN-20251207195007-07476EBB','valide','orange_money',NULL,'2025-12-07 19:50:07','2025-12-08 01:50:07','2025-12-08 01:50:08');
/*!40000 ALTER TABLE `auto_ecole_paiements` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `auto_ecole_users`
--

DROP TABLE IF EXISTS `auto_ecole_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `auto_ecole_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `type_permis` enum('permis_a','permis_b') NOT NULL,
  `code_parrainage` varchar(255) NOT NULL,
  `parrain_id` bigint(20) unsigned DEFAULT NULL,
  `niveau_parrainage` int(11) NOT NULL DEFAULT 0,
  `session_id` bigint(20) unsigned DEFAULT NULL,
  `centre_examen_id` bigint(20) unsigned DEFAULT NULL,
  `validated` tinyint(1) NOT NULL DEFAULT 0,
  `paiement_x_date` datetime DEFAULT NULL,
  `paiement_y_date` datetime DEFAULT NULL,
  `paiement_x_effectue` tinyint(1) NOT NULL DEFAULT 0,
  `paiement_y_effectue` tinyint(1) NOT NULL DEFAULT 0,
  `dispense_y` tinyint(1) NOT NULL DEFAULT 0,
  `code_caisse` varchar(255) DEFAULT NULL,
  `cours_verrouilles` tinyint(1) NOT NULL DEFAULT 1,
  `date_inscription` datetime NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `auto_ecole_users_email_unique` (`email`),
  UNIQUE KEY `auto_ecole_users_telephone_unique` (`telephone`),
  UNIQUE KEY `auto_ecole_users_code_parrainage_unique` (`code_parrainage`),
  UNIQUE KEY `auto_ecole_users_code_caisse_unique` (`code_caisse`),
  KEY `auto_ecole_users_session_id_foreign` (`session_id`),
  KEY `auto_ecole_users_centre_examen_id_foreign` (`centre_examen_id`),
  KEY `auto_ecole_users_code_parrainage_index` (`code_parrainage`),
  KEY `auto_ecole_users_parrain_id_index` (`parrain_id`),
  KEY `auto_ecole_users_niveau_parrainage_index` (`niveau_parrainage`),
  CONSTRAINT `auto_ecole_users_centre_examen_id_foreign` FOREIGN KEY (`centre_examen_id`) REFERENCES `centres_examen` (`id`) ON DELETE SET NULL,
  CONSTRAINT `auto_ecole_users_parrain_id_foreign` FOREIGN KEY (`parrain_id`) REFERENCES `auto_ecole_users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `auto_ecole_users_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `sessions1` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auto_ecole_users`
--

LOCK TABLES `auto_ecole_users` WRITE;
/*!40000 ALTER TABLE `auto_ecole_users` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `auto_ecole_users` VALUES
(1,'Fongang','Pulchérie','pulcheriefongang11@gmail.com','611223344','$2y$12$iUD.IJNf6sqTvWmEwcnWgeUx5AgRDaPM80uLIAqzD7qklpMZfsaQC','permis_b','ARB-08FE70',NULL,1,5,3,0,'2025-12-07 19:02:16',NULL,1,0,1,NULL,1,'2025-12-07 18:10:43',NULL,'2025-12-08 00:10:43','2025-12-08 01:02:17',NULL),
(2,'Ghost','Byden','Wilfrieddark777@gmail.com','657929578','$2y$12$eyfG2t3ksX3ngo/Q3JdSBOBi7Q8jNkCz.En7vzTryfxbLfajo5tyG','permis_b','ARB-EA734F',1,0,4,2,0,'2025-12-07 19:50:08','2025-12-07 19:50:08',1,1,0,NULL,0,'2025-12-07 18:38:57',NULL,'2025-12-08 00:38:57','2025-12-08 01:50:08',NULL);
/*!40000 ALTER TABLE `auto_ecole_users` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `centres_examen`
--

DROP TABLE IF EXISTS `centres_examen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `centres_examen` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `adresse` text NOT NULL,
  `ville` varchar(255) NOT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `centres_examen`
--

LOCK TABLES `centres_examen` WRITE;
/*!40000 ALTER TABLE `centres_examen` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `centres_examen` VALUES
(1,'Centre Ville','123 Avenue Principale','Paris','01 23 45 67 89',1,NULL,NULL),
(2,'Centre Nord','456 Rue du Nord','Lille','03 20 45 78 90',1,NULL,NULL),
(3,'Centre Sud','789 Boulevard du Sud','Marseille','04 91 23 45 67',1,NULL,NULL),
(4,'Centre Est','101 Rue de l Est','Strasbourg','03 88 76 54 32',1,NULL,NULL),
(5,'Centre Ouest','202 Boulevard de l Ouest','Nantes','02 40 12 34 56',0,NULL,NULL);
/*!40000 ALTER TABLE `centres_examen` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `chapitres`
--

DROP TABLE IF EXISTS `chapitres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `chapitres` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `module_id` bigint(20) unsigned NOT NULL,
  `nom` varchar(255) NOT NULL,
  `ordre` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chapitres_module_id_foreign` (`module_id`),
  CONSTRAINT `chapitres_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chapitres`
--

LOCK TABLES `chapitres` WRITE;
/*!40000 ALTER TABLE `chapitres` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `chapitres` VALUES
(1,1,'Règles de priorité',1,'Les différentes règles de priorité',NULL,NULL),
(2,1,'Croisements et dépassements',2,'Comment croiser et dépasser les autres véhicules',NULL,NULL),
(3,1,'Stationnement',3,'Règles de stationnement',NULL,NULL),
(4,1,'Vitesses autorisées',4,'Limitations de vitesse selon les routes',NULL,NULL),
(5,2,'Panneaux d interdiction',1,'Panneaux qui interdisent certaines actions',NULL,NULL),
(6,2,'Panneaux d obligation',2,'Panneaux qui imposent une action',NULL,NULL),
(7,2,'Panneaux de danger',3,'Panneaux qui signalent un danger',NULL,NULL),
(8,2,'Panneaux d indication',4,'Panneaux qui donnent des informations',NULL,NULL),
(9,3,'Équipements de sécurité',1,'Ceintures, airbags, etc.',NULL,NULL),
(10,3,'Distance de sécurité',2,'Comment maintenir une distance sécuritaire',NULL,NULL),
(11,3,'Conduite par intempéries',3,'Adapter sa conduite aux conditions météo',NULL,NULL),
(12,3,'Éco-conduite',4,'Conduite économique et écologique',NULL,NULL),
(13,4,'Réglage du poste de conduite',1,'Position au volant et réglages',NULL,NULL),
(14,4,'Démarrage du véhicule',2,'Procédure de démarrage',NULL,NULL),
(15,4,'Arrêt du véhicule',3,'Techniques d arrêt en sécurité',NULL,NULL),
(16,4,'Manœuvre en marche arrière',4,'Reculer en sécurité',NULL,NULL),
(17,5,'Rond-points',1,'Prise et sortie des ronds-points',NULL,NULL),
(18,5,'Feux tricolores',2,'Gestion des intersections avec feux',NULL,NULL),
(19,5,'Piétons et passages protégés',3,'Partage de la route avec les piétons',NULL,NULL),
(20,5,'Circulation dense',4,'Conduite dans les embouteillages',NULL,NULL),
(21,6,'Insertion sur autoroute',1,'Comment s insérer depuis la bretelle',NULL,NULL),
(22,6,'Circulation sur voie rapide',2,'Maintenir sa trajectoire et sa vitesse',NULL,NULL),
(23,6,'Dépassement sur autoroute',3,'Techniques de dépassement sécuritaires',NULL,NULL),
(24,6,'Sortie d autoroute',4,'Préparation et exécution de la sortie',NULL,NULL);
/*!40000 ALTER TABLE `chapitres` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `codes_caisse`
--

DROP TABLE IF EXISTS `codes_caisse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `codes_caisse` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `montant` decimal(10,2) NOT NULL,
  `tranche` enum('x','y','complet') NOT NULL,
  `utilise` tinyint(1) NOT NULL DEFAULT 0,
  `date_utilisation` datetime DEFAULT NULL,
  `date_expiration` datetime DEFAULT NULL,
  `genere_par` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codes_caisse_code_unique` (`code`),
  KEY `codes_caisse_user_id_foreign` (`user_id`),
  KEY `codes_caisse_genere_par_foreign` (`genere_par`),
  CONSTRAINT `codes_caisse_genere_par_foreign` FOREIGN KEY (`genere_par`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `codes_caisse_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `auto_ecole_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codes_caisse`
--

LOCK TABLES `codes_caisse` WRITE;
/*!40000 ALTER TABLE `codes_caisse` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `codes_caisse` VALUES
(1,'CC-2C7EE92C82',1,45000.00,'x',1,'2025-12-07 19:02:16',NULL,1,'2025-12-08 01:01:38','2025-12-08 01:02:16');
/*!40000 ALTER TABLE `codes_caisse` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `config_paiement`
--

DROP TABLE IF EXISTS `config_paiement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `config_paiement` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `montant_x` decimal(10,2) NOT NULL DEFAULT 30000.00,
  `montant_y` decimal(10,2) NOT NULL DEFAULT 30000.00,
  `montant_z` decimal(10,2) NOT NULL DEFAULT 15000.00,
  `delai_paiement_y` int(11) NOT NULL DEFAULT 60,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config_paiement`
--

LOCK TABLES `config_paiement` WRITE;
/*!40000 ALTER TABLE `config_paiement` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `config_paiement` VALUES
(1,45000.00,10000.00,0.00,30,'2025-12-07 23:43:55','2025-12-07 23:44:08');
/*!40000 ALTER TABLE `config_paiement` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `error_logs`
--

DROP TABLE IF EXISTS `error_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `error_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `error_type` varchar(255) DEFAULT NULL,
  `error_message` text NOT NULL,
  `stack_trace` longtext DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `line_number` int(11) DEFAULT NULL,
  `request_method` varchar(255) DEFAULT NULL,
  `request_url` text DEFAULT NULL,
  `request_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`request_data`)),
  `user_agent` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `http_status_code` int(11) DEFAULT NULL,
  `error_time` timestamp NOT NULL,
  `email_sent` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `error_logs_user_id_foreign` (`user_id`),
  KEY `error_logs_error_time_error_type_index` (`error_time`,`error_type`),
  CONSTRAINT `error_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `error_logs`
--

LOCK TABLES `error_logs` WRITE;
/*!40000 ALTER TABLE `error_logs` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `error_logs` VALUES
(1,'Error','Class \"App\\Models\\Paiement\" not found','#0 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Routing/ControllerDispatcher.php(46): App\\Http\\Controllers\\Api\\PaiementController->initierPaiement()\n#1 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Routing/Route.php(265): Illuminate\\Routing\\ControllerDispatcher->dispatch()\n#2 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Routing/Route.php(211): Illuminate\\Routing\\Route->runController()\n#3 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Routing/Router.php(822): Illuminate\\Routing\\Route->run()\n#4 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Routing\\Router->{closure:Illuminate\\Routing\\Router::runRouteWithinStack():821}()\n#5 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Routing/Middleware/SubstituteBindings.php(50): Illuminate\\Pipeline\\Pipeline->{closure:Illuminate\\Pipeline\\Pipeline::prepareDestination():178}()\n#6 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(219): Illuminate\\Routing\\Middleware\\SubstituteBindings->handle()\n#7 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Auth/Middleware/Authenticate.php(63): Illuminate\\Pipeline\\Pipeline->{closure:{closure:Illuminate\\Pipeline\\Pipeline::carry():194}:195}()\n#8 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(219): Illuminate\\Auth\\Middleware\\Authenticate->handle()\n#9 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->{closure:{closure:Illuminate\\Pipeline\\Pipeline::carry():194}:195}()\n#10 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Routing/Router.php(821): Illuminate\\Pipeline\\Pipeline->then()\n#11 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Routing/Router.php(800): Illuminate\\Routing\\Router->runRouteWithinStack()\n#12 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Routing/Router.php(764): Illuminate\\Routing\\Router->runRoute()\n#13 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Routing/Router.php(753): Illuminate\\Routing\\Router->dispatchToRoute()\n#14 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php(200): Illuminate\\Routing\\Router->dispatch()\n#15 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Foundation\\Http\\Kernel->{closure:Illuminate\\Foundation\\Http\\Kernel::dispatchToRouter():197}()\n#16 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php(21): Illuminate\\Pipeline\\Pipeline->{closure:Illuminate\\Pipeline\\Pipeline::prepareDestination():178}()\n#17 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/ConvertEmptyStringsToNull.php(31): Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest->handle()\n#18 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(219): Illuminate\\Foundation\\Http\\Middleware\\ConvertEmptyStringsToNull->handle()\n#19 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php(21): Illuminate\\Pipeline\\Pipeline->{closure:{closure:Illuminate\\Pipeline\\Pipeline::carry():194}:195}()\n#20 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TrimStrings.php(51): Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest->handle()\n#21 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(219): Illuminate\\Foundation\\Http\\Middleware\\TrimStrings->handle()\n#22 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Http/Middleware/ValidatePostSize.php(27): Illuminate\\Pipeline\\Pipeline->{closure:{closure:Illuminate\\Pipeline\\Pipeline::carry():194}:195}()\n#23 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(219): Illuminate\\Http\\Middleware\\ValidatePostSize->handle()\n#24 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/PreventRequestsDuringMaintenance.php(109): Illuminate\\Pipeline\\Pipeline->{closure:{closure:Illuminate\\Pipeline\\Pipeline::carry():194}:195}()\n#25 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(219): Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance->handle()\n#26 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Http/Middleware/HandleCors.php(61): Illuminate\\Pipeline\\Pipeline->{closure:{closure:Illuminate\\Pipeline\\Pipeline::carry():194}:195}()\n#27 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(219): Illuminate\\Http\\Middleware\\HandleCors->handle()\n#28 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Http/Middleware/TrustProxies.php(58): Illuminate\\Pipeline\\Pipeline->{closure:{closure:Illuminate\\Pipeline\\Pipeline::carry():194}:195}()\n#29 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(219): Illuminate\\Http\\Middleware\\TrustProxies->handle()\n#30 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/InvokeDeferredCallbacks.php(22): Illuminate\\Pipeline\\Pipeline->{closure:{closure:Illuminate\\Pipeline\\Pipeline::carry():194}:195}()\n#31 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(219): Illuminate\\Foundation\\Http\\Middleware\\InvokeDeferredCallbacks->handle()\n#32 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Http/Middleware/ValidatePathEncoding.php(26): Illuminate\\Pipeline\\Pipeline->{closure:{closure:Illuminate\\Pipeline\\Pipeline::carry():194}:195}()\n#33 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(219): Illuminate\\Http\\Middleware\\ValidatePathEncoding->handle()\n#34 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->{closure:{closure:Illuminate\\Pipeline\\Pipeline::carry():194}:195}()\n#35 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php(175): Illuminate\\Pipeline\\Pipeline->then()\n#36 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php(144): Illuminate\\Foundation\\Http\\Kernel->sendRequestThroughRouter()\n#37 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1220): Illuminate\\Foundation\\Http\\Kernel->handle()\n#38 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/public/index.php(20): Illuminate\\Foundation\\Application->handleRequest()\n#39 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php(23): require_once(\'...\')\n#40 {main}','/home/ghost/Desktop/hack_the_world/BIG_PROJECT/AUTO_ECOLE/Ange_Raphael/app/Http/Controllers/Api/PaiementController.php',34,'POST','http://192.168.1.166:8000/api/paiement/initier','{\"tranche\":\"complet\",\"methode_paiement\":\"orange_money\",\"telephone\":\"600112233\"}','okhttp/4.12.0','192.168.1.153',1,'6SwdCS1fnChAem0ywJ8jQmoNBiFhN73ZRkukhiGB',500,'2025-12-08 01:20:10',0,'2025-12-08 01:20:11','2025-12-08 01:20:11');
/*!40000 ALTER TABLE `error_logs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
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
set autocommit=0;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `filleuls`
--

DROP TABLE IF EXISTS `filleuls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `filleuls` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parrain_id` bigint(20) unsigned NOT NULL,
  `filleul_id` bigint(20) unsigned NOT NULL,
  `date_parrainage` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `filleuls_parrain_id_filleul_id_unique` (`parrain_id`,`filleul_id`),
  KEY `filleuls_filleul_id_foreign` (`filleul_id`),
  CONSTRAINT `filleuls_filleul_id_foreign` FOREIGN KEY (`filleul_id`) REFERENCES `auto_ecole_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `filleuls_parrain_id_foreign` FOREIGN KEY (`parrain_id`) REFERENCES `auto_ecole_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `filleuls`
--

LOCK TABLES `filleuls` WRITE;
/*!40000 ALTER TABLE `filleuls` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `filleuls` VALUES
(1,1,2,'2025-12-07 18:38:57','2025-12-08 00:38:57','2025-12-08 00:38:57');
/*!40000 ALTER TABLE `filleuls` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
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
set autocommit=0;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
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
set autocommit=0;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `jours_pratique`
--

DROP TABLE IF EXISTS `jours_pratique`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jours_pratique` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `jour` enum('lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche') NOT NULL,
  `heure` time NOT NULL,
  `zone` varchar(255) NOT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jours_pratique`
--

LOCK TABLES `jours_pratique` WRITE;
/*!40000 ALTER TABLE `jours_pratique` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `jours_pratique` VALUES
(1,'lundi','08:30:00','Zone A',1,NULL,NULL),
(2,'mardi','09:00:00','Zone B',1,NULL,NULL),
(3,'mercredi','14:00:00','Zone A',1,NULL,NULL),
(4,'jeudi','10:30:00','Zone C',1,NULL,NULL),
(5,'samedi','08:00:00','Zone B',0,NULL,NULL);
/*!40000 ALTER TABLE `jours_pratique` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `lecons`
--

DROP TABLE IF EXISTS `lecons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `lecons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `chapitre_id` bigint(20) unsigned NOT NULL,
  `titre` varchar(255) NOT NULL,
  `contenu_texte` text NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `ordre` int(11) NOT NULL,
  `duree_minutes` int(11) NOT NULL DEFAULT 10,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lecons_chapitre_id_foreign` (`chapitre_id`),
  CONSTRAINT `lecons_chapitre_id_foreign` FOREIGN KEY (`chapitre_id`) REFERENCES `chapitres` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lecons`
--

LOCK TABLES `lecons` WRITE;
/*!40000 ALTER TABLE `lecons` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `lecons` VALUES
(1,1,'Priorité à droite','La règle de base: priorité à droite aux intersections sans signalisation...','lecons/H8oib3u7d8WY4bOvTBy83FqtacEQyOsGUN10Fi65.png',NULL,1,15,NULL,'2025-12-07 23:52:38'),
(2,1,'Marquage au sol','Les différents marquages au sol indiquant la priorité...','lecons/A2sJKS6OdaaMFj74U2Ouc839nzWfZ1sRn0stI8WR.png',NULL,2,12,NULL,'2025-12-07 23:52:49'),
(3,1,'Feux de circulation','Comment interpréter les feux tricolores...','lecons/4NP1Y18AR7lOAHxeWUZyuNKlVkafHnKz7YsVi2Sz.png',NULL,3,10,NULL,'2025-12-07 23:53:07'),
(4,1,'Agents de la circulation','Priorité donnée par les agents...','lecons/Lwafl1ukZXzqwbB9jIJ9MRJ5RAJnJlgz8c8sgi9Y.png',NULL,4,8,NULL,'2025-12-07 23:53:17'),
(5,1,'Cas particuliers','Les exceptions aux règles générales...','lecons/GhB0UV4q3hhOrfMJBEJghyd3k0b6NB2dbDvFe3mL.png',NULL,5,18,NULL,'2025-12-07 23:53:31'),
(6,2,'Dépassement interdit','Zones où le dépassement est interdit...','depassement_interdit.jpg','video4.mp4',1,14,NULL,NULL),
(7,2,'Distance de dépassement','Distance minimale requise...','distance.jpg',NULL,2,10,NULL,NULL),
(8,2,'Dépassement à gauche','Technique standard de dépassement...','gauche.jpg','video5.mp4',3,12,NULL,NULL),
(9,2,'Croisement difficile','Comment se croiser dans les rues étroites...','etroit.jpg',NULL,4,10,NULL,NULL),
(10,2,'Dépassement cyclistes','Dépassement sécuritaire des cyclistes...','velo.jpg','video6.mp4',5,8,NULL,NULL),
(11,3,'Stationnement interdit','Zones où le stationnement est prohibé...','interdit.jpg','video7.mp4',1,12,NULL,NULL),
(12,3,'Stationnement payant','Fonctionnement des zones bleues et parcmètres...','payant.jpg',NULL,2,10,NULL,NULL),
(13,3,'Créneau','Réaliser un créneau parfait...','creneau.jpg','video8.mp4',3,20,NULL,NULL),
(14,3,'Épi et bataille','Autres techniques de stationnement...','epi.jpg',NULL,4,15,NULL,NULL),
(15,3,'Stationnement handicapé','Respect des places réservées...','handicape.jpg','video9.mp4',5,8,NULL,NULL),
(16,4,'Limites générales','Vitesses maximales par défaut...','limites.jpg','video10.mp4',1,12,NULL,NULL),
(17,4,'Zones 30 et 50','Particularités des zones limitées...','zone30.jpg',NULL,2,10,NULL,NULL),
(18,4,'Autoroute','Limitations sur autoroute...','autoroute.jpg','video11.mp4',3,8,NULL,NULL),
(19,4,'Conditions climatiques','Adaptation de la vitesse...','pluie.jpg',NULL,4,10,NULL,NULL),
(20,4,'Radars et contrôles','Fonctionnement des systèmes de contrôle...','radar.jpg','video12.mp4',5,12,NULL,NULL),
(21,5,'Interdiction d accès','Panneaux B0 et suivants...','acces.jpg','video13.mp4',1,10,NULL,NULL),
(22,5,'Interdiction de stationner','Panneaux de stationnement interdit...','stationner.jpg',NULL,2,10,NULL,NULL),
(23,5,'Limitations de gabarit','Panneaux pour poids lourds...','gabarit.jpg','video14.mp4',3,12,NULL,NULL),
(24,5,'Interdiction de tourner','Sens interdit et interdiction de tourner...','tourner.jpg',NULL,4,8,NULL,NULL),
(25,5,'Fin d interdiction','Panneaux de fin de restriction...','fin.jpg','video15.mp4',5,8,NULL,NULL),
(26,6,'Direction obligatoire','Panneaux de direction imposée...','direction.jpg','video16.mp4',1,10,NULL,NULL),
(27,6,'Vitesse minimale','Obligation de vitesse minimale...','minimale.jpg',NULL,2,8,NULL,NULL),
(28,6,'Équipements obligatoires','Chaines, pneus neige...','equipement.jpg','video17.mp4',3,12,NULL,NULL),
(29,6,'Voies réservées','Bus, vélos, covoiturage...','voie.jpg',NULL,4,10,NULL,NULL),
(30,6,'Fin d obligation','Signalisation de fin d obligation...','fin_obligation.jpg','video18.mp4',5,8,NULL,NULL),
(31,7,'Danger général','Panneau triangle rouge...','danger.jpg','video19.mp4',1,8,NULL,NULL),
(32,7,'Passage piétons','Attention aux traversées...','pieton.jpg',NULL,2,8,NULL,NULL),
(33,7,'Travaux','Zones de chantier...','travaux.jpg','video20.mp4',3,10,NULL,NULL),
(34,7,'Animaux sauvages','Traversée d animaux...','animaux.jpg',NULL,4,8,NULL,NULL),
(35,7,'Chaussée glissante','Risque de dérapage...','glissant.jpg','video21.mp4',5,8,NULL,NULL),
(36,13,'Ceinture de sécurité','Importance et réglage...','ceinture.jpg','video22.mp4',1,12,NULL,NULL),
(37,13,'Airbags','Fonctionnement et précautions...','airbag.jpg',NULL,2,10,NULL,NULL),
(38,13,'Sièges enfants','Normes et installation...','enfant.jpg','video23.mp4',3,15,NULL,NULL),
(39,13,'Extincteur','Utilisation en cas d incendie...','extincteur.jpg',NULL,4,10,NULL,NULL),
(40,13,'Trousse de secours','Contenu recommandé...','secours.jpg','video24.mp4',5,10,NULL,NULL),
(41,19,'Entrée dans un rond-point','Comment s insérer...','entree_rond.jpg','video25.mp4',1,15,NULL,NULL),
(42,19,'Choix de la voie','Quelle voie prendre selon la sortie...','voie_rond.jpg',NULL,2,12,NULL,NULL),
(43,19,'Sortie du rond-point','Procédure de sortie...','sortie_rond.jpg','video26.mp4',3,10,NULL,NULL),
(44,19,'Priorité dans le rond-point','Qui a la priorité...','priorite_rond.jpg',NULL,4,8,NULL,NULL),
(45,19,'Rond-point à plusieurs voies','Cas complexes...','multi_rond.jpg','video27.mp4',5,18,NULL,NULL),
(46,22,'Bretelle d accès','Préparation à l insertion...','bretelle.jpg','video28.mp4',1,12,NULL,NULL),
(47,22,'Observation de la circulation','Vérification des angles morts...','observation.jpg',NULL,2,10,NULL,NULL),
(48,22,'Accélération sur bretelle','Atteindre la vitesse appropriée...','acceleration.jpg','video29.mp4',3,10,NULL,NULL),
(49,22,'Insertion en sécurité','S insérer entre deux véhicules...','insertion.jpg',NULL,4,12,NULL,NULL),
(50,22,'Cas de trafic dense','Insertion difficile...','dense.jpg','video30.mp4',5,15,NULL,NULL);
/*!40000 ALTER TABLE `lecons` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2025_03_31_091517_create_notifications_table',1),
(5,'2025_05_23_074228_create_admin_stats_table',1),
(6,'2025_07_03_create_error_logs_table',1),
(7,'2025_11_29_200831_create_personal_access_tokens_table',1),
(8,'cfpam_tables',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `modules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `type` enum('theorique','pratique') NOT NULL,
  `ordre` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modules`
--

LOCK TABLES `modules` WRITE;
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `modules` VALUES
(1,'Code de la route','theorique',1,'Apprentissage des règles de circulation',NULL,NULL),
(2,'Signalisation','theorique',2,'Étude des panneaux de signalisation',NULL,NULL),
(3,'Sécurité routière','theorique',3,'Principes de sécurité et conduite défensive',NULL,NULL),
(4,'Démarrage et arrêt','pratique',4,'Manœuvres de base du véhicule',NULL,NULL),
(5,'Circulation urbaine','pratique',5,'Conduite en ville et situations complexes',NULL,NULL),
(6,'Conduite sur autoroute','pratique',6,'Techniques de conduite sur voie rapide',NULL,NULL);
/*!40000 ALTER TABLE `modules` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `personal_access_tokens` VALUES
(1,'App\\Models\\AutoEcoleUser',1,'auth_token','207714d4f571ed850f566ff15da18c0f322c96d030246d87f9ef3746adf55164','[\"*\"]','2025-12-08 01:47:56',NULL,'2025-12-08 00:10:43','2025-12-08 01:47:56'),
(2,'App\\Models\\AutoEcoleUser',2,'auth_token','b17059fb1abaee937a3089060f527d57f4eb55ded5c243392015125988ed160f','[\"*\"]','2025-12-08 11:29:08',NULL,'2025-12-08 00:38:57','2025-12-08 11:29:08');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `progression_lecons`
--

DROP TABLE IF EXISTS `progression_lecons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `progression_lecons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `lecon_id` bigint(20) unsigned NOT NULL,
  `completee` tinyint(1) NOT NULL DEFAULT 0,
  `date_completion` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `progression_lecons_user_id_lecon_id_unique` (`user_id`,`lecon_id`),
  KEY `progression_lecons_lecon_id_foreign` (`lecon_id`),
  CONSTRAINT `progression_lecons_lecon_id_foreign` FOREIGN KEY (`lecon_id`) REFERENCES `lecons` (`id`) ON DELETE CASCADE,
  CONSTRAINT `progression_lecons_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `auto_ecole_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `progression_lecons`
--

LOCK TABLES `progression_lecons` WRITE;
/*!40000 ALTER TABLE `progression_lecons` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `progression_lecons` VALUES
(1,2,1,1,'2025-12-07 20:25:55','2025-12-08 02:25:55','2025-12-08 02:25:55'),
(2,2,2,1,'2025-12-07 20:26:47','2025-12-08 02:26:47','2025-12-08 02:26:47'),
(3,2,3,1,'2025-12-07 20:58:26','2025-12-08 02:58:26','2025-12-08 02:58:26'),
(4,2,4,1,'2025-12-08 05:12:44','2025-12-08 11:12:44','2025-12-08 11:12:44'),
(5,2,5,1,'2025-12-08 05:17:09','2025-12-08 11:17:09','2025-12-08 11:17:09');
/*!40000 ALTER TABLE `progression_lecons` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `quiz`
--

DROP TABLE IF EXISTS `quiz`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `quiz` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `chapitre_id` bigint(20) unsigned NOT NULL,
  `question` text NOT NULL,
  `type` enum('qcm','vrai_faux') NOT NULL,
  `ordre` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quiz_chapitre_id_foreign` (`chapitre_id`),
  CONSTRAINT `quiz_chapitre_id_foreign` FOREIGN KEY (`chapitre_id`) REFERENCES `chapitres` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quiz`
--

LOCK TABLES `quiz` WRITE;
/*!40000 ALTER TABLE `quiz` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `quiz` VALUES
(1,1,'À une intersection sans signalisation, qui a la priorité?','qcm',1,NULL,NULL),
(2,1,'Le panneau \"Cédez le passage\" impose-t-il l arrêt?','vrai_faux',2,NULL,NULL),
(3,1,'Quel véhicule doit céder le passage dans cette situation?','qcm',3,NULL,NULL),
(4,1,'La priorité à droite s applique toujours','vrai_faux',4,NULL,NULL),
(5,1,'Que faire face à un panneau \"Stop\"?','qcm',5,NULL,NULL),
(6,2,'Le dépassement est-il autorisé juste avant un virage?','vrai_faux',1,NULL,NULL),
(7,2,'Quelle distance minimale faut-il laisser pour dépasser un cycliste?','qcm',2,NULL,NULL),
(8,2,'Peut-on dépasser par la droite sur autoroute?','vrai_faux',3,NULL,NULL),
(9,2,'Dans quel cas le dépassement est-il interdit?','qcm',4,NULL,NULL),
(10,2,'Pour dépasser un camion, faut-il plus de temps que pour dépasser une voiture?','vrai_faux',5,NULL,NULL),
(11,3,'Peut-on stationner devant une bouche d incendie?','vrai_faux',1,NULL,NULL),
(12,3,'À quelle distance minimum d un passage piéton peut-on stationner?','qcm',2,NULL,NULL),
(13,3,'Le stationnement est-il payant le dimanche?','vrai_faux',3,NULL,NULL),
(14,3,'Quel type de stationnement consomme le plus d espace?','qcm',4,NULL,NULL),
(15,3,'Doit-on laisser le frein à main en stationnement en côte?','vrai_faux',5,NULL,NULL),
(16,4,'Quelle est la vitesse maximale en agglomération?','qcm',1,NULL,NULL),
(17,4,'La vitesse sur autoroute par temps de pluie est limitée à 110 km/h','vrai_faux',2,NULL,NULL),
(18,4,'Dans une zone 30, à quelle vitesse peut-on circuler?','qcm',3,NULL,NULL),
(19,4,'Peut-on dépasser la vitesse limite pour doubler?','vrai_faux',4,NULL,NULL),
(20,4,'Quelle amende pour excès de vitesse de 20 km/h en ville?','qcm',5,NULL,NULL),
(21,5,'Que signifie un panneau rond rouge avec une barre blanche?','qcm',1,NULL,NULL),
(22,5,'Le panneau \"interdit aux véhicules de plus de 3,5t\" concerne aussi les voitures','vrai_faux',2,NULL,NULL),
(23,5,'Quel panneau indique l interdiction de stationner?','qcm',3,NULL,NULL),
(24,5,'Un panneau d interdiction est toujours rond','vrai_faux',4,NULL,NULL),
(25,5,'Que signifie un panneau avec une croix rouge?','qcm',5,NULL,NULL),
(26,6,'De quelle couleur sont les panneaux d obligation?','qcm',1,NULL,NULL),
(27,6,'Le panneau \"piste cyclable obligatoire\" concerne aussi les piétons','vrai_faux',2,NULL,NULL),
(28,6,'Que signifie un panneau rond bleu avec une flèche?','qcm',3,NULL,NULL),
(29,6,'Tous les panneaux d obligation sont ronds','vrai_faux',4,NULL,NULL),
(30,6,'Quel panneau impose le port de la ceinture?','qcm',5,NULL,NULL),
(31,7,'De quelle forme sont les panneaux de danger?','qcm',1,NULL,NULL),
(32,7,'Le panneau \"passage d animaux\" est un panneau de danger','vrai_faux',2,NULL,NULL),
(33,7,'À quelle distance commence généralement un danger?','qcm',3,NULL,NULL),
(34,7,'Un panneau de danger annonce toujours un virage','vrai_faux',4,NULL,NULL),
(35,7,'Que signifie un panneau avec un triangle à l envers?','qcm',5,NULL,NULL),
(36,8,'De quelle couleur sont généralement les panneaux d indication?','qcm',1,NULL,NULL),
(37,8,'Un panneau \"parking\" est un panneau d indication','vrai_faux',2,NULL,NULL),
(38,8,'Que signifie un panneau bleu avec un \"P\" blanc?','qcm',3,NULL,NULL),
(39,8,'Les panneaux d indication sont toujours carrés','vrai_faux',4,NULL,NULL),
(40,8,'Quel panneau indique une aire de repos?','qcm',5,NULL,NULL),
(41,9,'À partir de quel âge un enfant peut-il voyager à l avant?','qcm',1,NULL,NULL),
(42,9,'La ceinture de sécurité est obligatoire à l arrière','vrai_faux',2,NULL,NULL),
(43,9,'Quand faut-il vérifier la pression des pneus?','qcm',3,NULL,NULL),
(44,9,'Un extincteur est obligatoire dans toutes les voitures','vrai_faux',4,NULL,NULL),
(45,9,'Que contient une trousse de secours obligatoire?','qcm',5,NULL,NULL),
(46,10,'Comment calculer la distance de sécurité sur autoroute?','qcm',1,NULL,NULL),
(47,10,'La distance de sécurité doit être doublée par temps de pluie','vrai_faux',2,NULL,NULL),
(48,10,'À 90 km/h, quelle distance minimale?','qcm',3,NULL,NULL),
(49,10,'On peut évaluer la distance en comptant les secondes','vrai_faux',4,NULL,NULL),
(50,10,'Quelle distance derrière un deux-roues?','qcm',5,NULL,NULL),
(51,11,'Que faut-il faire en cas d aquaplaning?','qcm',1,NULL,NULL),
(52,11,'Les feux de brouillard avant sont autorisés par forte pluie','vrai_faux',2,NULL,NULL),
(53,11,'Comment adapter sa conduite par temps de brouillard?','qcm',3,NULL,NULL),
(54,11,'Il faut allumer les feux de route dans le brouillard','vrai_faux',4,NULL,NULL),
(55,11,'Quelle vitesse par forte pluie sur autoroute?','qcm',5,NULL,NULL),
(56,12,'Quel est le principe de l éco-conduite?','qcm',1,NULL,NULL),
(57,12,'Couper le moteur à un feu rouge économise du carburant','vrai_faux',2,NULL,NULL),
(58,12,'Quelle pression de pneus pour l éco-conduite?','qcm',3,NULL,NULL),
(59,12,'L utilisation de la climatisation n affecte pas la consommation','vrai_faux',4,NULL,NULL),
(60,12,'Quel régime moteur est le plus économique?','qcm',5,NULL,NULL),
(61,13,'À quelle distance du volant doit-on être assis?','qcm',1,NULL,NULL),
(62,13,'Le rétroviseur intérieur doit montrer l ensemble de la lunette arrière','vrai_faux',2,NULL,NULL),
(63,13,'Comment régler la hauteur du siège?','qcm',3,NULL,NULL),
(64,13,'La ceinture doit passer sur le ventre','vrai_faux',4,NULL,NULL),
(65,13,'Où placer les mains sur le volant?','qcm',5,NULL,NULL),
(66,14,'Dans quel ordre démarrer une voiture manuelle?','qcm',1,NULL,NULL),
(67,14,'Il faut toujours appuyer sur l embrayage pour démarrer','vrai_faux',2,NULL,NULL),
(68,14,'Que vérifier avant de démarrer?','qcm',3,NULL,NULL),
(69,14,'Le frein à main doit être levé pour démarrer','vrai_faux',4,NULL,NULL),
(70,14,'Que faire si le moteur ne démarre pas?','qcm',5,NULL,NULL),
(71,15,'Comment s arrêter en pente?','qcm',1,NULL,NULL),
(72,15,'Il faut toujours utiliser le frein à main à l arrêt','vrai_faux',2,NULL,NULL),
(73,15,'Où placer le levier de vitesse à l arrêt?','qcm',3,NULL,NULL),
(74,15,'On peut laisser le moteur tourner à l arrêt prolongé','vrai_faux',4,NULL,NULL),
(75,15,'Que faire avant de sortir du véhicule?','qcm',5,NULL,NULL),
(76,16,'Comment bien voir en marche arrière?','qcm',1,NULL,NULL),
(77,16,'La marche arrière est interdite sur autoroute','vrai_faux',2,NULL,NULL),
(78,16,'Quelle vitesse pour une marche arrière?','qcm',3,NULL,NULL),
(79,16,'On peut faire une marche arrière de plusieurs centaines de mètres','vrai_faux',4,NULL,NULL),
(80,16,'Que faire si on ne voit pas bien en marche arrière?','qcm',5,NULL,NULL),
(81,17,'Qui a la priorité dans un rond-point?','qcm',1,NULL,NULL),
(82,17,'On doit clignoter à gauche pour entrer dans un rond-point','vrai_faux',2,NULL,NULL),
(83,17,'Quelle voie pour sortir à la première sortie?','qcm',3,NULL,NULL),
(84,17,'Dans un rond-point à plusieurs voies, on peut changer de voie','vrai_faux',4,NULL,NULL),
(85,17,'Quand mettre le clignotant pour sortir?','qcm',5,NULL,NULL),
(86,18,'Que signifie un feu orange fixe?','qcm',1,NULL,NULL),
(87,18,'On peut passer au orange si on ne peut plus s arrêter','vrai_faux',2,NULL,NULL),
(88,18,'Que faire face à un feu rouge clignotant?','qcm',3,NULL,NULL),
(89,18,'Les feux clignotants orange signifient \"passez avec prudence\"','vrai_faux',4,NULL,NULL),
(90,18,'Peut-on tourner à droite au feu rouge?','qcm',5,NULL,NULL),
(91,19,'À quelle distance d un passage piéton faut-il s arrêter?','qcm',1,NULL,NULL),
(92,19,'On doit toujours s arrêter pour un piéton qui veut traverser','vrai_faux',2,NULL,NULL),
(93,19,'Que signifie un passage piéton surélevé?','qcm',3,NULL,NULL),
(94,19,'Les piétons ont toujours la priorité sur les passages protégés','vrai_faux',4,NULL,NULL),
(95,19,'Comment traverser un passage piéton sans feux?','qcm',5,NULL,NULL),
(96,20,'Que faire dans un embouteillage?','qcm',1,NULL,NULL),
(97,20,'Il est interdit de circuler sur la bande d arrêt d urgence','vrai_faux',2,NULL,NULL),
(98,20,'Comment éviter l accordéon dans les bouchons?','qcm',3,NULL,NULL),
(99,20,'On peut téléphoner dans les embouteillages','vrai_faux',4,NULL,NULL),
(100,20,'Quelle distance garder en circulation très ralentie?','qcm',5,NULL,NULL),
(101,21,'À quelle vitesse faut-il s insérer sur autoroute?','qcm',1,NULL,NULL),
(102,21,'On a la priorité quand on s insère sur autoroute','vrai_faux',2,NULL,NULL),
(103,21,'Comment utiliser la bande d accélération?','qcm',3,NULL,NULL),
(104,21,'Il faut s insérer au début de la bande d accélération','vrai_faux',4,NULL,NULL),
(105,21,'Que faire si on ne peut pas s insérer?','qcm',5,NULL,NULL),
(106,22,'Sur quelle voie circuler sur autoroute?','qcm',1,NULL,NULL),
(107,22,'La voie de gauche est réservée au dépassement','vrai_faux',2,NULL,NULL),
(108,22,'Quelle est la vitesse minimale sur autoroute?','qcm',3,NULL,NULL),
(109,22,'On peut stationner sur la bande d arrêt d urgence en cas de fatigue','vrai_faux',4,NULL,NULL),
(110,22,'Que faire en cas de panne sur autoroute?','qcm',5,NULL,NULL),
(111,23,'Comment dépasser sur autoroute?','qcm',1,NULL,NULL),
(112,23,'On peut dépasser par la droite sur autoroute','vrai_faux',2,NULL,NULL),
(113,23,'Quelle distance garder après un dépassement?','qcm',3,NULL,NULL),
(114,23,'Il faut clignoter avant de se rabattre','vrai_faux',4,NULL,NULL),
(115,23,'Peut-on dépasser plusieurs véhicules à la fois?','qcm',5,NULL,NULL),
(116,24,'Quand faut-il commencer à se préparer pour sortir?','qcm',1,NULL,NULL),
(117,24,'On peut freiner brusquement sur la voie de décélération','vrai_faux',2,NULL,NULL),
(118,24,'Que faire si on rate une sortie?','qcm',3,NULL,NULL),
(119,24,'La voie de décélération sert à ralentir progressivement','vrai_faux',4,NULL,NULL),
(120,24,'Peut-on traverser la bande d arrêt d urgence pour sortir?','qcm',5,NULL,NULL);
/*!40000 ALTER TABLE `quiz` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `quiz_options`
--

DROP TABLE IF EXISTS `quiz_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `quiz_options` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `quiz_id` bigint(20) unsigned NOT NULL,
  `option_texte` text NOT NULL,
  `est_correcte` tinyint(1) NOT NULL DEFAULT 0,
  `ordre` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quiz_options_quiz_id_foreign` (`quiz_id`),
  CONSTRAINT `quiz_options_quiz_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `quiz` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=381 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quiz_options`
--

LOCK TABLES `quiz_options` WRITE;
/*!40000 ALTER TABLE `quiz_options` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `quiz_options` VALUES
(1,1,'Le véhicule venant de la droite',1,1,NULL,NULL),
(2,1,'Le véhicule le plus gros',0,2,NULL,NULL),
(3,1,'Le véhicule venant de la gauche',0,3,NULL,NULL),
(4,1,'Celui qui arrive le premier',0,4,NULL,NULL),
(5,2,'Vrai',0,1,NULL,NULL),
(6,2,'Faux',1,2,NULL,NULL),
(7,3,'La voiture bleue',0,1,NULL,NULL),
(8,3,'La moto rouge',1,2,NULL,NULL),
(9,3,'Les deux doivent s arrêter',0,3,NULL,NULL),
(10,3,'Aucun des deux',0,4,NULL,NULL),
(11,4,'Vrai',0,1,NULL,NULL),
(12,4,'Faux',1,2,NULL,NULL),
(13,5,'S arrêter complètement',1,1,NULL,NULL),
(14,5,'Ralentir seulement',0,2,NULL,NULL),
(15,5,'Continuer si personne n arrive',0,3,NULL,NULL),
(16,5,'Klaxonner avant de passer',0,4,NULL,NULL),
(17,6,'Vrai',0,1,NULL,NULL),
(18,6,'Faux',1,2,NULL,NULL),
(19,7,'0,5 mètre',0,1,NULL,NULL),
(20,7,'1 mètre',1,2,NULL,NULL),
(21,7,'1,5 mètre',0,3,NULL,NULL),
(22,7,'2 mètres',0,4,NULL,NULL),
(23,8,'Vrai',0,1,NULL,NULL),
(24,8,'Faux',1,2,NULL,NULL),
(25,9,'En côte avec mauvaise visibilité',1,1,NULL,NULL),
(26,9,'Sur ligne droite en ville',0,2,NULL,NULL),
(27,9,'Sur autoroute dégagée',0,3,NULL,NULL),
(28,9,'En présence d une ligne discontinue',0,4,NULL,NULL),
(29,10,'Vrai',1,1,NULL,NULL),
(30,10,'Faux',0,2,NULL,NULL),
(31,11,'Vrai',0,1,NULL,NULL),
(32,11,'Faux',1,2,NULL,NULL),
(33,12,'3 mètres',0,1,NULL,NULL),
(34,12,'5 mètres',1,2,NULL,NULL),
(35,12,'10 mètres',0,3,NULL,NULL),
(36,12,'15 mètres',0,4,NULL,NULL),
(37,13,'Vrai',0,1,NULL,NULL),
(38,13,'Faux',1,2,NULL,NULL),
(39,14,'Stationnement en bataille',0,1,NULL,NULL),
(40,14,'Stationnement en épi',1,2,NULL,NULL),
(41,14,'Stationnement en créneau',0,3,NULL,NULL),
(42,14,'Stationnement en double file',0,4,NULL,NULL),
(43,15,'Vrai',1,1,NULL,NULL),
(44,15,'Faux',0,2,NULL,NULL),
(45,16,'30 km/h',0,1,NULL,NULL),
(46,16,'50 km/h',1,2,NULL,NULL),
(47,16,'70 km/h',0,3,NULL,NULL),
(48,16,'90 km/h',0,4,NULL,NULL),
(49,17,'Vrai',1,1,NULL,NULL),
(50,17,'Faux',0,2,NULL,NULL),
(51,18,'30 km/h maximum',1,1,NULL,NULL),
(52,18,'50 km/h maximum',0,2,NULL,NULL),
(53,18,'40 km/h maximum',0,3,NULL,NULL),
(54,18,'60 km/h maximum',0,4,NULL,NULL),
(55,19,'Vrai',0,1,NULL,NULL),
(56,19,'Faux',1,2,NULL,NULL),
(57,20,'68 euros',1,1,NULL,NULL),
(58,20,'135 euros',0,2,NULL,NULL),
(59,20,'90 euros',0,3,NULL,NULL),
(60,20,'45 euros',0,4,NULL,NULL),
(61,21,'Sens interdit',1,1,NULL,NULL),
(62,21,'Stationnement interdit',0,2,NULL,NULL),
(63,21,'Limitation de vitesse',0,3,NULL,NULL),
(64,21,'Cédez le passage',0,4,NULL,NULL),
(65,22,'Vrai',0,1,NULL,NULL),
(66,22,'Faux',1,2,NULL,NULL),
(67,23,'Panneau rond bleu avec barre rouge',1,1,NULL,NULL),
(68,23,'Panneau triangle rouge',0,2,NULL,NULL),
(69,23,'Panneau carré bleu',0,3,NULL,NULL),
(70,23,'Panneau rond blanc',0,4,NULL,NULL),
(71,24,'Vrai',1,1,NULL,NULL),
(72,24,'Faux',0,2,NULL,NULL),
(73,25,'Entrée interdite',0,1,NULL,NULL),
(74,25,'Stationnement alterné',0,2,NULL,NULL),
(75,25,'Arrêt et stationnement interdits',1,3,NULL,NULL),
(76,25,'Limitation de hauteur',0,4,NULL,NULL),
(77,26,'Rouge',0,1,NULL,NULL),
(78,26,'Bleu',1,2,NULL,NULL),
(79,26,'Vert',0,3,NULL,NULL),
(80,26,'Jaune',0,4,NULL,NULL),
(81,27,'Vrai',0,1,NULL,NULL),
(82,27,'Faux',1,2,NULL,NULL),
(83,28,'Direction obligatoire',1,1,NULL,NULL),
(84,28,'Sens interdit',0,2,NULL,NULL),
(85,28,'Priorité à droite',0,3,NULL,NULL),
(86,28,'Danger',0,4,NULL,NULL),
(87,29,'Vrai',1,1,NULL,NULL),
(88,29,'Faux',0,2,NULL,NULL),
(89,30,'Panneau avec symbole de ceinture',1,1,NULL,NULL),
(90,30,'Panneau triangle rouge',0,2,NULL,NULL),
(91,30,'Panneau carré bleu',0,3,NULL,NULL),
(92,30,'Panneau rond rouge',0,4,NULL,NULL),
(93,31,'Triangle rouge',1,1,NULL,NULL),
(94,31,'Rond bleu',0,2,NULL,NULL),
(95,31,'Carré vert',0,3,NULL,NULL),
(96,31,'Rond rouge',0,4,NULL,NULL),
(97,32,'Vrai',1,1,NULL,NULL),
(98,32,'Faux',0,2,NULL,NULL),
(99,33,'50 mètres en agglomération',0,1,NULL,NULL),
(100,33,'150 mètres hors agglomération',1,2,NULL,NULL),
(101,33,'300 mètres partout',0,3,NULL,NULL),
(102,33,'20 mètres seulement',0,4,NULL,NULL),
(103,34,'Vrai',0,1,NULL,NULL),
(104,34,'Faux',1,2,NULL,NULL),
(105,35,'Cédez le passage',1,1,NULL,NULL),
(106,35,'Stop',0,2,NULL,NULL),
(107,35,'Priorité',0,3,NULL,NULL),
(108,35,'Sens interdit',0,4,NULL,NULL),
(109,36,'Bleu',1,1,NULL,NULL),
(110,36,'Rouge',0,2,NULL,NULL),
(111,36,'Vert',0,3,NULL,NULL),
(112,36,'Jaune',0,4,NULL,NULL),
(113,37,'Vrai',1,1,NULL,NULL),
(114,37,'Faux',0,2,NULL,NULL),
(115,38,'Parking autorisé',1,1,NULL,NULL),
(116,38,'Stationnement interdit',0,2,NULL,NULL),
(117,38,'Piste cyclable',0,3,NULL,NULL),
(118,38,'Passage piéton',0,4,NULL,NULL),
(119,39,'Vrai',0,1,NULL,NULL),
(120,39,'Faux',1,2,NULL,NULL),
(121,40,'Panneau avec symbole de lit',1,1,NULL,NULL),
(122,40,'Panneau triangle rouge',0,2,NULL,NULL),
(123,40,'Panneau rond bleu',0,3,NULL,NULL),
(124,40,'Panneau carré vert',0,4,NULL,NULL),
(125,41,'10 ans',1,1,NULL,NULL),
(126,41,'12 ans',0,2,NULL,NULL),
(127,41,'14 ans',0,3,NULL,NULL),
(128,41,'16 ans',0,4,NULL,NULL),
(129,42,'Vrai',1,1,NULL,NULL),
(130,42,'Faux',0,2,NULL,NULL),
(131,43,'Tous les mois',1,1,NULL,NULL),
(132,43,'Tous les ans',0,2,NULL,NULL),
(133,43,'Tous les 6 mois',0,3,NULL,NULL),
(134,43,'Jamais, c est automatique',0,4,NULL,NULL),
(135,44,'Vrai',0,1,NULL,NULL),
(136,44,'Faux',1,2,NULL,NULL),
(137,45,'Compresses et bandages',1,1,NULL,NULL),
(138,45,'Médicaments',0,2,NULL,NULL),
(139,45,'Ciseaux chirurgicaux',0,3,NULL,NULL),
(140,45,'Stylo auto-injecteur',0,4,NULL,NULL),
(141,46,'2 secondes',1,1,NULL,NULL),
(142,46,'1 seconde',0,2,NULL,NULL),
(143,46,'50 mètres',0,3,NULL,NULL),
(144,46,'Longueur de 2 voitures',0,4,NULL,NULL),
(145,47,'Vrai',1,1,NULL,NULL),
(146,47,'Faux',0,2,NULL,NULL),
(147,48,'50 mètres',1,1,NULL,NULL),
(148,48,'30 mètres',0,2,NULL,NULL),
(149,48,'70 mètres',0,3,NULL,NULL),
(150,48,'90 mètres',0,4,NULL,NULL),
(151,49,'Vrai',1,1,NULL,NULL),
(152,49,'Faux',0,2,NULL,NULL),
(153,50,'La même que pour une voiture',1,1,NULL,NULL),
(154,50,'Moins, car ils sont plus petits',0,2,NULL,NULL),
(155,50,'Plus, car ils peuvent freiner plus court',0,3,NULL,NULL),
(156,50,'Aucune distance particulière',0,4,NULL,NULL),
(157,51,'Freiner fortement',0,1,NULL,NULL),
(158,51,'Débrayer et ne pas freiner',1,2,NULL,NULL),
(159,51,'Accélérer pour sortir de la flaque',0,3,NULL,NULL),
(160,51,'Tourner brusquement le volant',0,4,NULL,NULL),
(161,52,'Vrai',1,1,NULL,NULL),
(162,52,'Faux',0,2,NULL,NULL),
(163,53,'Allumer les feux de brouillard',1,1,NULL,NULL),
(164,53,'Rouler plus vite pour sortir du brouillard',0,2,NULL,NULL),
(165,53,'Utiliser les feux de route',0,3,NULL,NULL),
(166,53,'Klaxonner régulièrement',0,4,NULL,NULL),
(167,54,'Vrai',0,1,NULL,NULL),
(168,54,'Faux',1,2,NULL,NULL),
(169,55,'130 km/h',0,1,NULL,NULL),
(170,55,'110 km/h',1,2,NULL,NULL),
(171,55,'90 km/h',0,3,NULL,NULL),
(172,55,'70 km/h',0,4,NULL,NULL),
(173,56,'Rouler plus lentement',0,1,NULL,NULL),
(174,56,'Anticiper et être souple',1,2,NULL,NULL),
(175,56,'Toujours être en première',0,3,NULL,NULL),
(176,56,'Freiner fort à la dernière minute',0,4,NULL,NULL),
(177,57,'Vrai',1,1,NULL,NULL),
(178,57,'Faux',0,2,NULL,NULL),
(179,58,'La pression recommandée par le constructeur',1,1,NULL,NULL),
(180,58,'Sous-gonflés pour plus d adhérence',0,2,NULL,NULL),
(181,58,'Sur-gonflés pour moins de résistance',0,3,NULL,NULL),
(182,58,'Cela n a pas d importance',0,4,NULL,NULL),
(183,59,'Vrai',0,1,NULL,NULL),
(184,59,'Faux',1,2,NULL,NULL),
(185,60,'Régime le plus bas possible',1,1,NULL,NULL),
(186,60,'Régime rouge',0,2,NULL,NULL),
(187,60,'Toujours au maximum',0,3,NULL,NULL),
(188,60,'Cela n a pas d importance',0,4,NULL,NULL),
(189,61,'Bras tendus',0,1,NULL,NULL),
(190,61,'Poignets sur le volant',1,2,NULL,NULL),
(191,61,'Très près pour mieux contrôler',0,3,NULL,NULL),
(192,61,'Le plus loin possible',0,4,NULL,NULL),
(193,62,'Vrai',1,1,NULL,NULL),
(194,62,'Faux',0,2,NULL,NULL),
(195,63,'Les yeux à hauteur du pare-brise',1,1,NULL,NULL),
(196,63,'Le plus bas possible',0,2,NULL,NULL),
(197,63,'Le plus haut possible',0,3,NULL,NULL),
(198,63,'Cela n a pas d importance',0,4,NULL,NULL),
(199,64,'Vrai',0,1,NULL,NULL),
(200,64,'Faux',1,2,NULL,NULL),
(201,65,'10h10 ou 9h15',1,1,NULL,NULL),
(202,65,'6h ou 12h',0,2,NULL,NULL),
(203,65,'En bas du volant',0,3,NULL,NULL),
(204,65,'Une main seulement',0,4,NULL,NULL),
(205,66,'Embrayage, point mort, contact',1,1,NULL,NULL),
(206,66,'Contact, embrayage, première',0,2,NULL,NULL),
(207,66,'Frein, contact, embrayage',0,3,NULL,NULL),
(208,66,'Peu importe l ordre',0,4,NULL,NULL),
(209,67,'Vrai',1,1,NULL,NULL),
(210,67,'Faux',0,2,NULL,NULL),
(211,68,'Ceintures attachées',1,1,NULL,NULL),
(212,68,'Pression des pneus',0,2,NULL,NULL),
(213,68,'Niveau d huile',0,3,NULL,NULL),
(214,68,'Plaques d immatriculation',0,4,NULL,NULL),
(215,69,'Vrai',0,1,NULL,NULL),
(216,69,'Faux',1,2,NULL,NULL),
(217,70,'Appuyer sur l accélérateur',0,1,NULL,NULL),
(218,70,'Vérifier la batterie',1,2,NULL,NULL),
(219,70,'Pousser la voiture',0,3,NULL,NULL),
(220,70,'Klaxonner',0,4,NULL,NULL),
(221,71,'Frein, embrayage, point mort, frein à main',1,1,NULL,NULL),
(222,71,'Frein à main, puis frein',0,2,NULL,NULL),
(223,71,'Coup de frein sec',0,3,NULL,NULL),
(224,71,'Rétrograder jusqu à l arrêt',0,4,NULL,NULL),
(225,72,'Vrai',1,1,NULL,NULL),
(226,72,'Faux',0,2,NULL,NULL),
(227,73,'En première',0,1,NULL,NULL),
(228,73,'Au point mort',1,2,NULL,NULL),
(229,73,'En marche arrière',0,3,NULL,NULL),
(230,73,'En quatrième',0,4,NULL,NULL),
(231,74,'Vrai',0,1,NULL,NULL),
(232,74,'Faux',1,2,NULL,NULL),
(233,75,'Vérifier l angle mort',1,1,NULL,NULL),
(234,75,'Klaxonner',0,2,NULL,NULL),
(235,75,'Allumer les warnings',0,3,NULL,NULL),
(236,75,'Régler la radio',0,4,NULL,NULL),
(237,76,'Regarder dans les rétroviseurs et tourner la tête',1,1,NULL,NULL),
(238,76,'Uniquement dans le rétroviseur intérieur',0,2,NULL,NULL),
(239,76,'Regarder devant soi',0,3,NULL,NULL),
(240,76,'Faire confiance aux capteurs',0,4,NULL,NULL),
(241,77,'Vrai',1,1,NULL,NULL),
(242,77,'Faux',0,2,NULL,NULL),
(243,78,'Au ralenti',1,1,NULL,NULL),
(244,78,'À 20 km/h',0,2,NULL,NULL),
(245,78,'À 30 km/h',0,3,NULL,NULL),
(246,78,'À la vitesse normale',0,4,NULL,NULL),
(247,79,'Vrai',0,1,NULL,NULL),
(248,79,'Faux',1,2,NULL,NULL),
(249,80,'Demander à quelqu un de guider',1,1,NULL,NULL),
(250,80,'Accélérer pour finir plus vite',0,2,NULL,NULL),
(251,80,'Fermer les yeux et espérer',0,3,NULL,NULL),
(252,80,'Utiliser uniquement les rétroviseurs',0,4,NULL,NULL),
(253,81,'Les véhicules déjà engagés',1,1,NULL,NULL),
(254,81,'Celui qui arrive le premier',0,2,NULL,NULL),
(255,81,'Celui qui vient de la droite',0,3,NULL,NULL),
(256,81,'Les poids lourds',0,4,NULL,NULL),
(257,82,'Vrai',0,1,NULL,NULL),
(258,82,'Faux',1,2,NULL,NULL),
(259,83,'Voie de droite',1,1,NULL,NULL),
(260,83,'Voie de gauche',0,2,NULL,NULL),
(261,83,'N importe laquelle',0,3,NULL,NULL),
(262,83,'Voie du milieu',0,4,NULL,NULL),
(263,84,'Vrai',0,1,NULL,NULL),
(264,84,'Faux',1,2,NULL,NULL),
(265,85,'Avant la sortie précédente',1,1,NULL,NULL),
(266,85,'En entrant dans le rond-point',0,2,NULL,NULL),
(267,85,'Après être sorti',0,3,NULL,NULL),
(268,85,'Jamais, c est inutile',0,4,NULL,NULL),
(269,86,'S arrêter si possible',1,1,NULL,NULL),
(270,86,'Accélérer',0,2,NULL,NULL),
(271,86,'Continuer normalement',0,3,NULL,NULL),
(272,86,'Klaxonner',0,4,NULL,NULL),
(273,87,'Vrai',1,1,NULL,NULL),
(274,87,'Faux',0,2,NULL,NULL),
(275,88,'S arrêter comme à un stop',1,1,NULL,NULL),
(276,88,'Continuer sans s arrêter',0,2,NULL,NULL),
(277,88,'Ralentir seulement',0,3,NULL,NULL),
(278,88,'Faire un détour',0,4,NULL,NULL),
(279,89,'Vrai',1,1,NULL,NULL),
(280,89,'Faux',0,2,NULL,NULL),
(281,90,'Oui, toujours',0,1,NULL,NULL),
(282,90,'Non, jamais',0,2,NULL,NULL),
(283,90,'Seulement si un panneau l autorise',1,3,NULL,NULL),
(284,90,'Seulement la nuit',0,4,NULL,NULL),
(285,91,'Juste avant le passage',0,1,NULL,NULL),
(286,91,'Avant la ligne d arrêt',1,2,NULL,NULL),
(287,91,'Sur le passage',0,3,NULL,NULL),
(288,91,'Après le passage',0,4,NULL,NULL),
(289,92,'Vrai',1,1,NULL,NULL),
(290,92,'Faux',0,2,NULL,NULL),
(291,93,'Ralentissement obligatoire',1,1,NULL,NULL),
(292,93,'Traversée interdite',0,2,NULL,NULL),
(293,93,'Passage réservé aux enfants',0,3,NULL,NULL),
(294,93,'Zone de stationnement',0,4,NULL,NULL),
(295,94,'Vrai',1,1,NULL,NULL),
(296,94,'Faux',0,2,NULL,NULL),
(297,95,'S arrêter et laisser traverser',1,1,NULL,NULL),
(298,95,'Klaxonner pour faire traverser plus vite',0,2,NULL,NULL),
(299,95,'Contourner les piétons',0,3,NULL,NULL),
(300,95,'Accélérer pour passer avant eux',0,4,NULL,NULL),
(301,96,'Rester calme et patient',1,1,NULL,NULL),
(302,96,'Klaxonner sans cesse',0,2,NULL,NULL),
(303,96,'Faire des appels de phares',0,3,NULL,NULL),
(304,96,'Doubler par la droite',0,4,NULL,NULL),
(305,97,'Vrai',1,1,NULL,NULL),
(306,97,'Faux',0,2,NULL,NULL),
(307,98,'Garder une distance constante',1,1,NULL,NULL),
(308,98,'Coller le véhicule devant',0,2,NULL,NULL),
(309,98,'Changer de file souvent',0,3,NULL,NULL),
(310,98,'Rouler en zigzag',0,4,NULL,NULL),
(311,99,'Vrai',0,1,NULL,NULL),
(312,99,'Faux',1,2,NULL,NULL),
(313,100,'1 longueur de voiture',0,1,NULL,NULL),
(314,100,'2 secondes',1,2,NULL,NULL),
(315,100,'5 mètres',0,3,NULL,NULL),
(316,100,'Coller le véhicule devant',0,4,NULL,NULL),
(317,101,'À la vitesse de circulation',1,1,NULL,NULL),
(318,101,'À 50 km/h',0,2,NULL,NULL),
(319,101,'À 70 km/h',0,3,NULL,NULL),
(320,101,'Le plus lentement possible',0,4,NULL,NULL),
(321,102,'Vrai',0,1,NULL,NULL),
(322,102,'Faux',1,2,NULL,NULL),
(323,103,'Accélérer progressivement',1,1,NULL,NULL),
(324,103,'Ralentir à la fin',0,2,NULL,NULL),
(325,103,'S arrêter si nécessaire',0,3,NULL,NULL),
(326,103,'Klaxonner pour prévenir',0,4,NULL,NULL),
(327,104,'Vrai',0,1,NULL,NULL),
(328,104,'Faux',1,2,NULL,NULL),
(329,105,'S arrêter en bout de bande',1,1,NULL,NULL),
(330,105,'Forcer le passage',0,2,NULL,NULL),
(331,105,'Continuer sur la bande d arrêt d urgence',0,3,NULL,NULL),
(332,105,'Faire marche arrière',0,4,NULL,NULL),
(333,106,'Sur la voie de droite',1,1,NULL,NULL),
(334,106,'Sur la voie du milieu',0,2,NULL,NULL),
(335,106,'Sur la voie de gauche',0,3,NULL,NULL),
(336,106,'N importe laquelle',0,4,NULL,NULL),
(337,107,'Vrai',1,1,NULL,NULL),
(338,107,'Faux',0,2,NULL,NULL),
(339,108,'60 km/h',0,1,NULL,NULL),
(340,108,'80 km/h',1,2,NULL,NULL),
(341,108,'90 km/h',0,3,NULL,NULL),
(342,108,'100 km/h',0,4,NULL,NULL),
(343,109,'Vrai',0,1,NULL,NULL),
(344,109,'Faux',1,2,NULL,NULL),
(345,110,'S arrêter sur la bande d arrêt d urgence',1,1,NULL,NULL),
(346,110,'Rester sur la voie de droite',0,2,NULL,NULL),
(347,110,'Continuer jusqu à la sortie',0,3,NULL,NULL),
(348,110,'Appeler depuis son véhicule',0,4,NULL,NULL),
(349,111,'Par la gauche',1,1,NULL,NULL),
(350,111,'Par la droite',0,2,NULL,NULL),
(351,111,'Par n importe quelle voie',0,3,NULL,NULL),
(352,111,'En klaxonnant',0,4,NULL,NULL),
(353,112,'Vrai',0,1,NULL,NULL),
(354,112,'Faux',1,2,NULL,NULL),
(355,113,'50 mètres',0,1,NULL,NULL),
(356,113,'2 secondes dans le rétroviseur',1,2,NULL,NULL),
(357,113,'1 longueur de camion',0,3,NULL,NULL),
(358,113,'Jusqu à ce qu on ne voie plus le véhicule',0,4,NULL,NULL),
(359,114,'Vrai',1,1,NULL,NULL),
(360,114,'Faux',0,2,NULL,NULL),
(361,115,'Oui, sans limitation',1,1,NULL,NULL),
(362,115,'Non, un seul à la fois',0,2,NULL,NULL),
(363,115,'Seulement 2 maximum',0,3,NULL,NULL),
(364,115,'Seulement les voitures',0,4,NULL,NULL),
(365,116,'1 km avant',1,1,NULL,NULL),
(366,116,'Juste avant la sortie',0,2,NULL,NULL),
(367,116,'500 m avant',0,3,NULL,NULL),
(368,116,'Au dernier moment',0,4,NULL,NULL),
(369,117,'Vrai',0,1,NULL,NULL),
(370,117,'Faux',1,2,NULL,NULL),
(371,118,'Prendre la sortie suivante',1,1,NULL,NULL),
(372,118,'Faire marche arrière',0,2,NULL,NULL),
(373,118,'S arrêter sur la bande d arrêt d urgence',0,3,NULL,NULL),
(374,118,'Faire un demi-tour',0,4,NULL,NULL),
(375,119,'Vrai',1,1,NULL,NULL),
(376,119,'Faux',0,2,NULL,NULL),
(377,120,'Oui, en cas d urgence',0,1,NULL,NULL),
(378,120,'Non, jamais',1,2,NULL,NULL),
(379,120,'Oui, si personne ne regarde',0,3,NULL,NULL),
(380,120,'Seulement la nuit',0,4,NULL,NULL);
/*!40000 ALTER TABLE `quiz_options` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `resultats_quiz`
--

DROP TABLE IF EXISTS `resultats_quiz`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `resultats_quiz` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `chapitre_id` bigint(20) unsigned NOT NULL,
  `score` decimal(5,2) NOT NULL,
  `reussi` tinyint(1) NOT NULL DEFAULT 0,
  `date_tentative` datetime NOT NULL,
  `peut_retenter` tinyint(1) NOT NULL DEFAULT 1,
  `date_prochaine_tentative` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resultats_quiz_user_id_foreign` (`user_id`),
  KEY `resultats_quiz_chapitre_id_foreign` (`chapitre_id`),
  CONSTRAINT `resultats_quiz_chapitre_id_foreign` FOREIGN KEY (`chapitre_id`) REFERENCES `chapitres` (`id`) ON DELETE CASCADE,
  CONSTRAINT `resultats_quiz_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `auto_ecole_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resultats_quiz`
--

LOCK TABLES `resultats_quiz` WRITE;
/*!40000 ALTER TABLE `resultats_quiz` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `resultats_quiz` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
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
set autocommit=0;
INSERT INTO `sessions` VALUES
('bVZn9PrX8BYbufmnKLAC5NfHT7q3KFM6DrWi8t91',1,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64; rv:128.0) Gecko/20100101 Firefox/128.0','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiUEFsR1F0QzNxbnVpMWh0VGo3NUpVQW9LUzM0YmZPUzJRVnhCNnRNdCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9hdXRvLWVjb2xlL2NvZGVzLWNhaXNzZS8xIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9',1765130755);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `sessions1`
--

DROP TABLE IF EXISTS `sessions1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions1` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `date_examen` date NOT NULL,
  `statut` enum('ouvert','ferme') NOT NULL DEFAULT 'ouvert',
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions1`
--

LOCK TABLES `sessions1` WRITE;
/*!40000 ALTER TABLE `sessions1` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `sessions1` VALUES
(1,'Session Hiver 2024','2024-01-15','2024-02-28','2024-03-10','ferme','Session d examen pour l hiver 2024',NULL,NULL),
(2,'Session Printemps 2024','2024-03-01','2024-04-15','2024-05-05','ferme','Session d examen pour le printemps 2024',NULL,NULL),
(3,'Session Été 2024','2024-06-01','2024-07-15','2024-08-01','ouvert','Session d examen pour l été 2024',NULL,NULL),
(4,'Session Automne 2024','2024-09-01','2024-10-15','2024-11-10','ouvert','Session d examen pour l automne 2024',NULL,NULL),
(5,'Session Hiver 2025','2025-01-10','2025-02-20','2025-03-05','ouvert','Session d examen pour l hiver 2025',NULL,NULL);
/*!40000 ALTER TABLE `sessions1` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `user_jours_pratique`
--

DROP TABLE IF EXISTS `user_jours_pratique`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_jours_pratique` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `jour_pratique_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_jours_pratique_user_id_foreign` (`user_id`),
  KEY `user_jours_pratique_jour_pratique_id_foreign` (`jour_pratique_id`),
  CONSTRAINT `user_jours_pratique_jour_pratique_id_foreign` FOREIGN KEY (`jour_pratique_id`) REFERENCES `jours_pratique` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_jours_pratique_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `auto_ecole_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_jours_pratique`
--

LOCK TABLES `user_jours_pratique` WRITE;
/*!40000 ALTER TABLE `user_jours_pratique` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `user_jours_pratique` VALUES
(1,1,3,'2025-12-08 00:10:43','2025-12-08 00:10:43'),
(2,1,1,'2025-12-08 00:10:43','2025-12-08 00:10:43'),
(3,2,4,'2025-12-08 00:38:57','2025-12-08 00:38:57'),
(4,2,5,'2025-12-08 00:38:57','2025-12-08 00:38:57');
/*!40000 ALTER TABLE `user_jours_pratique` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `users` VALUES
(1,'dark ghost','wilfrieddark2.0@gmail.com',NULL,'$2y$12$zFH5sWsgUQ3Ws4uaZCMwduylImLeQeM3MUUlAv/NP.A4Z/zS.WqmK',NULL,'2025-12-07 23:41:47','2025-12-07 23:41:47');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
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

-- Dump completed on 2025-12-07 22:30:20
