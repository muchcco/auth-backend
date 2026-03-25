/*
SQLyog Ultimate v9.63 
MySQL - 8.0.30 : Database - jwt-mac
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `configuration_sist` */

DROP TABLE IF EXISTS `configuration_sist`;

CREATE TABLE `configuration_sist` (
  `IDCONFIGURACION` int NOT NULL AUTO_INCREMENT,
  `DESCRIPCION` varchar(250) DEFAULT NULL,
  `PARAMETRO` varchar(250) DEFAULT NULL,
  `FLAG` int DEFAULT NULL,
  `VALOR` varchar(250) DEFAULT NULL,
  `MENSAJE` varchar(250) DEFAULT NULL,
  `NUM_SOLO` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`IDCONFIGURACION`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `configuration_sist` */

LOCK TABLES `configuration_sist` WRITE;

insert  into `configuration_sist`(`IDCONFIGURACION`,`DESCRIPCION`,`PARAMETRO`,`FLAG`,`VALOR`,`MENSAJE`,`NUM_SOLO`) values (1,'ENVIO DE CORREOS','CORREO',0,'0','1 ES SI 0 ES NO',NULL),(2,'GUARDAR FILE SERVER LOCAL','FILE_PHOTO',1,'\\\\192.168.221.54\\prod-mac\\img-asesores','FILE',NULL),(3,'REVISAR LA IMAGEN EN WEB','WEB_PHOTO',1,'http://192.168.221.54/prod-mac/img-asesores','WEB',NULL);

UNLOCK TABLES;

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `failed_jobs` */

LOCK TABLES `failed_jobs` WRITE;

UNLOCK TABLES;

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

LOCK TABLES `migrations` WRITE;

insert  into `migrations`(`id`,`migration`,`batch`) values (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2024_06_11_143040_create_permission_tables',1),(6,'2024_06_11_145927_create_profiles_table',1),(7,'2024_06_11_160629_create_user_profile_table',1);

UNLOCK TABLES;

/*Table structure for table `model_has_permissions` */

DROP TABLE IF EXISTS `model_has_permissions`;

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `model_has_permissions` */

LOCK TABLES `model_has_permissions` WRITE;

insert  into `model_has_permissions`(`permission_id`,`model_type`,`model_id`) values (1,'App\\Models\\User',1),(1,'App\\Models\\User',49);

UNLOCK TABLES;

/*Table structure for table `model_has_roles` */

DROP TABLE IF EXISTS `model_has_roles`;

CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `model_has_roles` */

LOCK TABLES `model_has_roles` WRITE;

insert  into `model_has_roles`(`role_id`,`model_type`,`model_id`) values (1,'App\\Models\\User',1),(1,'App\\Models\\User',2),(2,'App\\Models\\User',3),(2,'App\\Models\\User',5),(2,'App\\Models\\User',6),(2,'App\\Models\\User',7),(1,'App\\Models\\User',8),(3,'App\\Models\\User',9),(2,'App\\Models\\User',10),(2,'App\\Models\\User',11),(2,'App\\Models\\User',12),(2,'App\\Models\\User',13),(2,'App\\Models\\User',14),(2,'App\\Models\\User',15),(2,'App\\Models\\User',17),(2,'App\\Models\\User',18),(2,'App\\Models\\User',19),(2,'App\\Models\\User',20),(2,'App\\Models\\User',21),(2,'App\\Models\\User',22),(2,'App\\Models\\User',23),(2,'App\\Models\\User',24),(6,'App\\Models\\User',25),(2,'App\\Models\\User',28),(2,'App\\Models\\User',29),(2,'App\\Models\\User',30),(5,'App\\Models\\User',31),(5,'App\\Models\\User',35),(4,'App\\Models\\User',36),(5,'App\\Models\\User',38),(5,'App\\Models\\User',39),(5,'App\\Models\\User',40),(2,'App\\Models\\User',41),(5,'App\\Models\\User',42),(6,'App\\Models\\User',43),(6,'App\\Models\\User',45),(6,'App\\Models\\User',47),(2,'App\\Models\\User',48),(5,'App\\Models\\User',49),(6,'App\\Models\\User',50),(5,'App\\Models\\User',51),(6,'App\\Models\\User',52),(5,'App\\Models\\User',53),(6,'App\\Models\\User',54),(5,'App\\Models\\User',55),(5,'App\\Models\\User',56),(7,'App\\Models\\User',58),(6,'App\\Models\\User',59),(5,'App\\Models\\User',60),(2,'App\\Models\\User',61),(5,'App\\Models\\User',62),(5,'App\\Models\\User',63),(6,'App\\Models\\User',65),(2,'App\\Models\\User',66),(6,'App\\Models\\User',67),(5,'App\\Models\\User',68),(5,'App\\Models\\User',69),(2,'App\\Models\\User',70),(4,'App\\Models\\User',71),(4,'App\\Models\\User',72),(4,'App\\Models\\User',73),(4,'App\\Models\\User',74),(2,'App\\Models\\User',75),(7,'App\\Models\\User',76),(2,'App\\Models\\User',77),(3,'App\\Models\\User',78),(3,'App\\Models\\User',79),(3,'App\\Models\\User',80),(5,'App\\Models\\User',81);

UNLOCK TABLES;

/*Table structure for table `password_reset_tokens` */

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_reset_tokens` */

LOCK TABLES `password_reset_tokens` WRITE;

UNLOCK TABLES;

/*Table structure for table `permissions` */

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `permissions` */

LOCK TABLES `permissions` WRITE;

insert  into `permissions`(`id`,`name`,`guard_name`,`created_at`,`updated_at`) values (1,'Update_basico_1','web',NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `personal_access_tokens` */

DROP TABLE IF EXISTS `personal_access_tokens`;

CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `personal_access_tokens` */

LOCK TABLES `personal_access_tokens` WRITE;

insert  into `personal_access_tokens`(`id`,`tokenable_type`,`tokenable_id`,`name`,`token`,`abilities`,`last_used_at`,`expires_at`,`created_at`,`updated_at`) values (1,'App\\Models\\User',1,'auth_token','1d3da6df7bdaa53cee1a69892ad4be43a8ee3060e82157149a1fcf2e54d4d0cb','[\"*\"]',NULL,NULL,'2024-06-11 09:20:27','2024-06-11 09:20:27'),(2,'App\\Models\\User',1,'auth_token','1ff068446a0503074ab789b40a2a279d607ba1bd816270f2a3a353a77e74503c','[\"*\"]',NULL,NULL,'2024-06-11 09:20:49','2024-06-11 09:20:49'),(3,'App\\Models\\User',1,'auth_token','a987bac24e847ecb33cb6a866deae0f0cad7a71c3ee7c22e71f888594661edbb','[\"*\"]',NULL,NULL,'2024-06-12 03:25:53','2024-06-12 03:25:53'),(4,'App\\Models\\User',1,'auth_token','42d146bbca6d5e4a6cb27a3417d54e50c27bdea0f3998c78fd881d73c24a9b27','[\"*\"]',NULL,NULL,'2024-06-12 03:25:58','2024-06-12 03:25:58'),(5,'App\\Models\\User',1,'auth_token','b92e16b843b6a5a518152ce380606aa58890622b64a4cc1436f9a0a0144c4be9','[\"*\"]',NULL,NULL,'2024-06-12 03:28:25','2024-06-12 03:28:25'),(6,'App\\Models\\User',1,'auth_token','ec9cee94d3e6c350aa6b1e90a56e2d460f1cb839c41f071a4851d768065f50e1','[\"*\"]',NULL,NULL,'2024-06-12 03:28:26','2024-06-12 03:28:26'),(7,'App\\Models\\User',1,'auth_token','0338c423d6f68510ea9f7ac78d0279f9d9627d7bb8250e6483f2ec77660f3cc8','[\"*\"]',NULL,NULL,'2024-06-12 03:28:27','2024-06-12 03:28:27'),(8,'App\\Models\\User',1,'auth_token','8104e88cb1513b1b5199418a4247f6e1bc2756023d7d374786fcf2be1b4d6e21','[\"*\"]',NULL,NULL,'2024-06-12 03:28:27','2024-06-12 03:28:27'),(9,'App\\Models\\User',1,'auth_token','550d434a0f1ceaea7ade2146b2ed95925df4f535ec55e94fbd851eb53b117a59','[\"*\"]',NULL,NULL,'2024-06-12 03:28:29','2024-06-12 03:28:29'),(10,'App\\Models\\User',1,'auth_token','48e1c915f2f33ab4e88876493c12254fa7df4076a8239d0aab704df64feafcdc','[\"*\"]',NULL,NULL,'2024-06-12 03:28:30','2024-06-12 03:28:30'),(11,'App\\Models\\User',1,'auth_token','575f39f6fbe8ca434badb023f4bead4f0bea3d4553b87f74f7c884dbd3e0f2c4','[\"*\"]',NULL,NULL,'2024-06-12 03:28:32','2024-06-12 03:28:32'),(12,'App\\Models\\User',1,'auth_token','5ced5f008266f6c825a9e075eb1a2779bdc9262bb2145e19698c7ebd54e93682','[\"*\"]',NULL,NULL,'2024-06-12 03:28:35','2024-06-12 03:28:35'),(13,'App\\Models\\User',1,'auth_token','aecf301574c2a30153eada85062d27b1ad6cf37184fc5fbae903c494269f23a8','[\"*\"]',NULL,NULL,'2024-06-12 03:29:48','2024-06-12 03:29:48'),(14,'App\\Models\\User',1,'auth_token','c9e1b5a07499f83ca74984e1200542e1b9dac298d7fdfa223d6d772b45d72d71','[\"*\"]',NULL,NULL,'2024-06-12 03:29:55','2024-06-12 03:29:55'),(15,'App\\Models\\User',1,'auth_token','30803a36d175ece4cb8b5c48bc423e20078745a4d79387258964c5c881779001','[\"*\"]',NULL,NULL,'2024-06-12 03:29:56','2024-06-12 03:29:56'),(16,'App\\Models\\User',1,'auth_token','c850c6abddc42e6dd092b4c907447e98e600f0bbfb84741b6a33ea8e5cc9abb5','[\"*\"]',NULL,NULL,'2024-06-12 03:30:23','2024-06-12 03:30:23'),(17,'App\\Models\\User',1,'auth_token','f1c8823b72707084db7b7e59ddf7fd732bac6f5d630d582b86454662f36acf8a','[\"*\"]',NULL,NULL,'2024-06-12 03:30:41','2024-06-12 03:30:41'),(18,'App\\Models\\User',1,'auth_token','97af787ab37a611b0db5a56272b66670778287d2ea1f775cd1a52a3383ad8048','[\"*\"]',NULL,NULL,'2024-06-12 03:31:30','2024-06-12 03:31:30'),(19,'App\\Models\\User',1,'auth_token','466b97a55304d69f16f9eb8c5b0752578e5a18572ff6b5cd65ccfa589b4aff39','[\"*\"]',NULL,NULL,'2024-06-12 03:42:50','2024-06-12 03:42:50'),(20,'App\\Models\\User',1,'auth_token','a2d309f547e39ba40135a1b8ca9de3d057286dd6be755179ae42096273cd5057','[\"*\"]',NULL,NULL,'2024-06-12 03:57:59','2024-06-12 03:57:59'),(21,'App\\Models\\User',1,'auth_token','10b9f9ba7f79817ea205fd5e95da7c246d7caf12600c8062e35c60ccf754713b','[\"*\"]',NULL,NULL,'2024-06-12 04:16:30','2024-06-12 04:16:30'),(22,'App\\Models\\User',1,'auth_token','6598c61336870972b09be4dad54b3907f9d98f381d289b988b6c692647c6c15d','[\"*\"]',NULL,NULL,'2024-06-12 04:34:20','2024-06-12 04:34:20'),(23,'App\\Models\\User',1,'auth_token','9482692284f27b7f111f62475ad96da1f2d61722d4ec4c474ddc525e85825bf0','[\"*\"]',NULL,NULL,'2024-06-12 04:42:50','2024-06-12 04:42:50'),(24,'App\\Models\\User',1,'auth_token','df7d2f5bbf6e86febe53f201b8e2157b916f4d0fb3b13fc379ad0182095968e2','[\"*\"]',NULL,NULL,'2024-06-12 04:50:45','2024-06-12 04:50:45'),(25,'App\\Models\\User',1,'auth_token','eb98fe316cb1a71769bcbb889693af6451309d90e008ffa1423a74bb78e679c6','[\"*\"]',NULL,NULL,'2024-06-12 04:53:00','2024-06-12 04:53:00'),(26,'App\\Models\\User',1,'auth_token','0c4c25283d9f0416fd0187b0c7d8a46514403e7a0fb0c4da80a1d129f6cd2ae6','[\"*\"]',NULL,NULL,'2024-06-12 10:07:04','2024-06-12 10:07:04'),(27,'App\\Models\\User',1,'auth_token','0415f38413f60629b2461f3da40702d4d524f683bb7be0a9131e03171c16cf30','[\"*\"]',NULL,NULL,'2024-06-12 10:07:06','2024-06-12 10:07:06'),(28,'App\\Models\\User',1,'auth_token','4055f4387a5227006a4d03d06937a27f0810b47b9888f33ba8620a9849ba7e90','[\"*\"]',NULL,NULL,'2024-06-13 05:09:15','2024-06-13 05:09:15'),(29,'App\\Models\\User',1,'auth_token','74678455aaadb0e8d61b0e2d1ca9ca019fe2820cae25d029332e5b9742d0a452','[\"*\"]',NULL,NULL,'2024-06-13 08:16:58','2024-06-13 08:16:58'),(30,'App\\Models\\User',1,'auth_token','7f42afc77669183933501faadd6fc5eb77e51c3d3fc2104467306318f3eff1d6','[\"*\"]',NULL,NULL,'2024-06-13 08:33:11','2024-06-13 08:33:11'),(31,'App\\Models\\User',1,'auth_token','68c0358c69325d4d327a39d6b745866febabd46f513cb13c33539d021648923b','[\"*\"]',NULL,NULL,'2024-06-13 08:39:23','2024-06-13 08:39:23'),(32,'App\\Models\\User',1,'auth_token','f826564ce75956b85bd4c12f87e65f855d91ec51c9af70e344d869357e28f36a','[\"*\"]',NULL,NULL,'2024-06-13 08:39:45','2024-06-13 08:39:45'),(33,'App\\Models\\User',1,'auth_token','b9d800c79c0ffb7e53aa9da1027c5d4a29fbed9ad8c8be7d40ff1690fd2ee284','[\"*\"]',NULL,NULL,'2024-06-13 08:47:31','2024-06-13 08:47:31'),(34,'App\\Models\\User',1,'auth_token','831b6407a3f907c8269eba5779eb2c8e66e73f0f59d1c4300b184f5f87f46208','[\"*\"]',NULL,NULL,'2024-06-13 08:52:19','2024-06-13 08:52:19'),(35,'App\\Models\\User',1,'auth_token','84328b58a2afd899e87f84d683c0b39c2add8a566818b396348ca04875372fc5','[\"*\"]',NULL,NULL,'2024-06-13 08:59:13','2024-06-13 08:59:13'),(36,'App\\Models\\User',1,'auth_token','e3d10b7d778049766d701a46a067283e0dded7710fb7a98c565299168ffc2e64','[\"*\"]',NULL,NULL,'2024-06-13 09:19:05','2024-06-13 09:19:05'),(37,'App\\Models\\User',1,'auth_token','c3ac8eb30e356c411a40631bbca4a9624021dc2579000c55d0750ef298bd5307','[\"*\"]',NULL,NULL,'2024-06-13 09:24:27','2024-06-13 09:24:27'),(38,'App\\Models\\User',1,'auth_token','7494b96e973147a449f4eb154d379d6d248749e8ee7a445f6b209c72b11583d1','[\"*\"]',NULL,NULL,'2024-06-13 09:24:42','2024-06-13 09:24:42'),(39,'App\\Models\\User',1,'auth_token','adc86f9c67eb9e80295e0686903d0a5cc5187b75f1c831d6528090e106b1ab54','[\"*\"]',NULL,NULL,'2024-06-13 09:26:36','2024-06-13 09:26:36'),(40,'App\\Models\\User',1,'auth_token','c47eb6e8644ed9c449f44e57af85431ab07e0fc2a6795a0e42d119f98b35af7f','[\"*\"]',NULL,NULL,'2024-06-13 09:27:02','2024-06-13 09:27:02'),(41,'App\\Models\\User',1,'auth_token','59ef3b521339c88596d67d1617e608e85289973cbf574f1feb275d2061c607af','[\"*\"]',NULL,NULL,'2024-06-13 09:36:44','2024-06-13 09:36:44'),(42,'App\\Models\\User',1,'auth_token','9e14e3a0e0e54aef8e2e0fc157a281f29f3630a3b558958386d594f1d14d565c','[\"*\"]',NULL,NULL,'2024-06-13 09:36:53','2024-06-13 09:36:53'),(43,'App\\Models\\User',1,'auth_token','d25643012e6452fa1ef7abb052a234c8e8feb9ccb52ce10030d8e976811dac65','[\"*\"]',NULL,NULL,'2024-06-13 09:39:22','2024-06-13 09:39:22'),(44,'App\\Models\\User',1,'auth_token','28ee8e03ba5ef8a972e0bd1dbd48e9fd954e6fed9dc5d8ed043f49c745a6286c','[\"*\"]',NULL,NULL,'2024-06-13 09:40:49','2024-06-13 09:40:49'),(45,'App\\Models\\User',1,'auth_token','0396170b311a1bc79bb4a8103424ce8ae31ef1ceb430e89109bbbb90a8cce6a5','[\"*\"]',NULL,NULL,'2024-06-13 09:41:13','2024-06-13 09:41:13'),(46,'App\\Models\\User',1,'auth_token','4cc7daa52822f133bd6e4f2a6725d3ecb234defbf98949b992d1875fe1a059ab','[\"*\"]',NULL,NULL,'2024-06-13 09:43:00','2024-06-13 09:43:00'),(47,'App\\Models\\User',1,'auth_token','a631299c0ae4a83ad8cab0feda78f5e8134e775e56adf500d1c3a3f6bda4e66e','[\"*\"]',NULL,NULL,'2024-06-13 09:45:12','2024-06-13 09:45:12'),(48,'App\\Models\\User',1,'auth_token','996124999255d93922b6c4fb1942736ac2f4b533086d74854cdc1d8114ffbd21','[\"*\"]',NULL,NULL,'2024-06-13 09:46:37','2024-06-13 09:46:37'),(49,'App\\Models\\User',1,'auth_token','4c4af012af1fe02aacde61b882339353221a9e3fb092ba649a39f21d6bf65ec7','[\"*\"]',NULL,NULL,'2024-06-13 10:00:04','2024-06-13 10:00:04'),(50,'App\\Models\\User',1,'auth_token','dda2830dbe209f65726ad0f903786642ebb125c351b0298688a1948f8216f5e2','[\"*\"]',NULL,NULL,'2024-06-14 01:30:16','2024-06-14 01:30:16'),(51,'App\\Models\\User',1,'auth_token','165b583caba42e31dc5818f8abd96ac10313e35cdd92ad3e9b11ea0f99297f6b','[\"*\"]',NULL,NULL,'2024-06-14 01:30:52','2024-06-14 01:30:52'),(52,'App\\Models\\User',1,'auth_token','5d47e74bdfd9d5284df595a6f20120984d6b5ec3f207c10fb99da79b5ac8c825','[\"*\"]',NULL,NULL,'2024-06-14 01:40:34','2024-06-14 01:40:34'),(53,'App\\Models\\User',1,'auth_token','2591db087467872ac695411fa181475ec66f635918058dde6d751f508256d703','[\"*\"]',NULL,NULL,'2024-06-14 01:40:58','2024-06-14 01:40:58'),(54,'App\\Models\\User',1,'auth_token','5070336aac1e1599c8bc3c461c182fcdfab982ebde7f68e139afc0d2f26e3ecb','[\"*\"]',NULL,NULL,'2024-06-14 01:44:49','2024-06-14 01:44:49'),(55,'App\\Models\\User',1,'auth_token','fc7dd3dd31132c5f93d14e62218a472ac5a70a9a8f04e450fd6aa10f5b1fe367','[\"*\"]',NULL,NULL,'2024-06-14 01:57:40','2024-06-14 01:57:40'),(56,'App\\Models\\User',1,'auth_token','b0f39910f9159cc661632ec7f1b982eac8c7c35ebe5806d28f4a13a1c96e3b24','[\"*\"]',NULL,NULL,'2024-06-14 08:16:06','2024-06-14 08:16:06'),(57,'App\\Models\\User',1,'spa','d69f93b31beca715d79593bd4fbb3b62e7314a19b0c10f16b43b4a6e7b4f16a2','[\"*\"]',NULL,NULL,'2025-09-03 15:07:24','2025-09-03 15:07:24'),(58,'App\\Models\\User',1,'spa','303612091480f16c68165afb7a306106ce68bfc81dc7d9b58b0ead92e0a0eb34','[\"*\"]',NULL,NULL,'2025-09-03 15:07:58','2025-09-03 15:07:58'),(59,'App\\Models\\User',1,'spa','a8487bfc54da1def084ad5cde0acc008e26d87c8201621b72acc9293e01483d5','[\"*\"]',NULL,NULL,'2025-09-03 15:08:15','2025-09-03 15:08:15'),(60,'App\\Models\\User',1,'spa','6fde974680026c833559b1de245c4c9a7b1248b4c111bae0415b527f86c2b85a','[\"*\"]',NULL,NULL,'2025-09-03 16:10:11','2025-09-03 16:10:11');

UNLOCK TABLES;

/*Table structure for table `profiles` */

DROP TABLE IF EXISTS `profiles`;

CREATE TABLE `profiles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `access` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `profiles` */

LOCK TABLES `profiles` WRITE;

insert  into `profiles`(`id`,`description`,`access`,`status`,`created_at`,`updated_at`) values (1,'GENERAL','1',1,'2024-06-11 09:13:44','2024-06-11 09:13:44'),(2,'REPORTES','2',1,'2024-06-11 09:30:11',NULL),(4,'ADMINISTRADOR','4',1,'2024-06-28 16:32:41',NULL),(5,'REPOSITORIO','5',1,'2024-07-03 15:30:57',NULL),(6,'REPORTES ANS','6',1,'2025-08-21 08:30:06',NULL);

UNLOCK TABLES;

/*Table structure for table `role_has_permissions` */

DROP TABLE IF EXISTS `role_has_permissions`;

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `role_has_permissions` */

LOCK TABLES `role_has_permissions` WRITE;

UNLOCK TABLES;

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `roles` */

LOCK TABLES `roles` WRITE;

insert  into `roles`(`id`,`name`,`guard_name`,`created_at`,`updated_at`) values (1,'Administrador','web','2024-06-11 09:12:36','2024-06-11 09:12:36'),(2,'Especialista TIC','web',NULL,NULL),(3,'Orientador','web',NULL,NULL),(4,'Asesor','web',NULL,NULL),(5,'Supervisor','web',NULL,NULL),(6,'Coordinador','web',NULL,NULL),(7,'Moderador','web',NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `user_profile` */

DROP TABLE IF EXISTS `user_profile`;

CREATE TABLE `user_profile` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `profile_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_profile_user_id_profile_id_unique` (`user_id`,`profile_id`),
  KEY `user_profile_profile_id_foreign` (`profile_id`),
  CONSTRAINT `user_profile_profile_id_foreign` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_profile_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=230 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `user_profile` */

LOCK TABLES `user_profile` WRITE;

insert  into `user_profile`(`id`,`user_id`,`profile_id`,`created_at`,`updated_at`) values (1,1,1,'2024-06-11 09:14:29',NULL),(2,1,2,'2024-06-11 09:30:20',NULL),(3,1,4,'2024-06-28 16:32:56',NULL),(5,1,5,NULL,NULL),(34,31,1,'2024-08-26 09:22:46','2024-08-26 09:22:46'),(39,25,4,'2024-08-28 09:00:24','2024-08-28 09:00:24'),(40,25,1,'2024-08-28 09:00:24','2024-08-28 09:00:24'),(47,5,4,'2024-09-02 01:38:42','2024-09-02 01:38:42'),(48,5,1,'2024-09-02 01:38:42','2024-09-02 01:38:42'),(49,6,4,'2024-09-02 01:38:49','2024-09-02 01:38:49'),(50,6,1,'2024-09-02 01:38:49','2024-09-02 01:38:49'),(51,7,4,'2024-09-02 01:38:57','2024-09-02 01:38:57'),(52,7,1,'2024-09-02 01:38:57','2024-09-02 01:38:57'),(55,12,4,'2024-09-02 01:39:18','2024-09-02 01:39:18'),(56,12,1,'2024-09-02 01:39:18','2024-09-02 01:39:18'),(57,13,4,'2024-09-02 01:39:26','2024-09-02 01:39:26'),(58,13,1,'2024-09-02 01:39:26','2024-09-02 01:39:26'),(59,14,4,'2024-09-02 01:39:40','2024-09-02 01:39:40'),(60,14,1,'2024-09-02 01:39:40','2024-09-02 01:39:40'),(67,19,4,'2024-09-02 01:40:18','2024-09-02 01:40:18'),(68,19,1,'2024-09-02 01:40:18','2024-09-02 01:40:18'),(69,20,4,'2024-09-02 01:40:26','2024-09-02 01:40:26'),(70,20,1,'2024-09-02 01:40:26','2024-09-02 01:40:26'),(71,21,4,'2024-09-02 01:40:33','2024-09-02 01:40:33'),(72,21,1,'2024-09-02 01:40:33','2024-09-02 01:40:33'),(73,22,4,'2024-09-02 01:40:43','2024-09-02 01:40:43'),(74,22,1,'2024-09-02 01:40:43','2024-09-02 01:40:43'),(75,23,4,'2024-09-02 01:40:54','2024-09-02 01:40:54'),(76,23,1,'2024-09-02 01:40:54','2024-09-02 01:40:54'),(77,24,4,'2024-09-02 01:41:11','2024-09-02 01:41:11'),(78,24,1,'2024-09-02 01:41:11','2024-09-02 01:41:11'),(79,28,4,'2024-09-02 01:41:21','2024-09-02 01:41:21'),(80,28,1,'2024-09-02 01:41:21','2024-09-02 01:41:21'),(87,35,2,'2024-09-02 05:59:17','2024-09-02 05:59:17'),(88,35,1,'2024-09-02 05:59:17','2024-09-02 05:59:17'),(89,35,5,'2024-09-02 05:59:17','2024-09-02 05:59:17'),(90,18,1,'2024-09-02 06:04:12','2024-09-02 06:04:12'),(91,18,4,'2024-09-02 06:04:12','2024-09-02 06:04:12'),(92,18,2,'2024-09-02 06:04:12','2024-09-02 06:04:12'),(93,18,5,'2024-09-02 06:04:12','2024-09-02 06:04:12'),(94,3,1,'2024-09-02 06:04:19','2024-09-02 06:04:19'),(95,3,4,'2024-09-02 06:04:19','2024-09-02 06:04:19'),(96,3,2,'2024-09-02 06:04:19','2024-09-02 06:04:19'),(97,3,5,'2024-09-02 06:04:19','2024-09-02 06:04:19'),(99,36,1,'2024-09-02 06:23:20','2024-09-02 06:23:20'),(102,2,1,'2024-09-03 01:43:48','2024-09-03 01:43:48'),(103,2,4,'2024-09-03 01:43:48','2024-09-03 01:43:48'),(104,2,2,'2024-09-03 01:43:48','2024-09-03 01:43:48'),(105,2,5,'2024-09-03 01:43:48','2024-09-03 01:43:48'),(106,38,1,'2024-09-03 01:44:55','2024-09-03 01:44:55'),(107,38,4,'2024-09-03 01:44:55','2024-09-03 01:44:55'),(108,39,1,'2024-09-03 02:59:03','2024-09-03 02:59:03'),(109,40,1,'2024-09-03 05:06:52','2024-09-03 05:06:52'),(110,40,2,'2024-09-03 05:06:52','2024-09-03 05:06:52'),(111,40,5,'2024-09-03 05:06:52','2024-09-03 05:06:52'),(112,41,4,'2024-09-05 02:46:21','2024-09-05 02:46:21'),(113,41,1,'2024-09-05 02:46:21','2024-09-05 02:46:21'),(114,11,4,'2024-09-05 02:46:27','2024-09-05 02:46:27'),(115,11,1,'2024-09-05 02:46:27','2024-09-05 02:46:27'),(116,42,1,'2024-09-06 02:36:33','2024-09-06 02:36:33'),(117,42,2,'2024-09-06 02:36:33','2024-09-06 02:36:33'),(118,10,1,'2024-09-10 04:15:31','2024-09-10 04:15:31'),(119,10,4,'2024-09-10 04:15:31','2024-09-10 04:15:31'),(120,10,2,'2024-09-10 04:15:31','2024-09-10 04:15:31'),(121,10,5,'2024-09-10 04:15:31','2024-09-10 04:15:31'),(122,43,1,'2024-09-11 03:40:13','2024-09-11 03:40:13'),(123,43,2,'2024-09-11 03:40:13','2024-09-11 03:40:13'),(124,43,5,'2024-09-11 03:40:13','2024-09-11 03:40:13'),(133,45,2,'2024-09-11 04:52:29','2024-09-11 04:52:29'),(134,45,1,'2024-09-11 04:52:29','2024-09-11 04:52:29'),(135,45,5,'2024-09-11 04:52:29','2024-09-11 04:52:29'),(139,47,1,'2024-09-12 01:37:56','2024-09-12 01:37:56'),(140,47,4,'2024-09-12 01:37:56','2024-09-12 01:37:56'),(141,48,1,'2024-09-20 07:25:37','2024-09-20 07:25:37'),(142,48,4,'2024-09-20 07:25:37','2024-09-20 07:25:37'),(143,49,1,'2024-09-23 08:06:40','2024-09-23 08:06:40'),(144,50,1,'2024-09-23 08:20:00','2024-09-23 08:20:00'),(145,51,1,'2024-09-25 17:13:29','2024-09-25 17:13:29'),(146,52,1,'2024-09-25 17:14:38','2024-09-25 17:14:38'),(147,53,1,'2024-09-25 17:25:59','2024-09-25 17:25:59'),(148,54,4,'2024-10-01 15:51:27','2024-10-01 15:51:27'),(149,54,1,'2024-10-01 15:51:27','2024-10-01 15:51:27'),(152,29,1,'2024-10-01 16:02:22','2024-10-01 16:02:22'),(153,29,4,'2024-10-01 16:02:22','2024-10-01 16:02:22'),(157,55,1,'2024-10-03 11:36:52','2024-10-03 11:36:52'),(158,55,2,'2024-10-03 11:36:52','2024-10-03 11:36:52'),(159,55,5,'2024-10-03 11:36:52','2024-10-03 11:36:52'),(161,58,1,'2024-10-10 12:19:12','2024-10-10 12:19:12'),(162,59,1,'2024-10-10 12:28:04','2024-10-10 12:28:04'),(163,59,2,'2024-10-10 12:28:04','2024-10-10 12:28:04'),(164,59,5,'2024-10-10 12:28:04','2024-10-10 12:28:04'),(169,60,1,'2024-10-15 08:58:55','2024-10-15 08:58:55'),(170,8,1,'2024-10-15 10:02:47','2024-10-15 10:02:47'),(171,8,2,'2024-10-15 10:02:47','2024-10-15 10:02:47'),(172,8,4,'2024-10-15 10:02:47','2024-10-15 10:02:47'),(173,8,5,'2024-10-15 10:02:47','2024-10-15 10:02:47'),(174,61,1,'2024-10-15 10:35:14','2024-10-15 10:35:14'),(175,62,1,'2024-10-15 10:35:43','2024-10-15 10:35:43'),(176,62,2,'2024-10-15 10:35:43','2024-10-15 10:35:43'),(177,56,1,'2024-10-15 15:13:28','2024-10-15 15:13:28'),(178,30,1,'2024-10-17 13:16:45','2024-10-17 13:16:45'),(179,30,4,'2024-10-17 13:16:45','2024-10-17 13:16:45'),(180,63,1,'2024-11-04 10:00:40','2024-11-04 10:00:40'),(181,63,5,'2024-11-04 10:00:40','2024-11-04 10:00:40'),(182,63,2,'2024-11-04 10:00:40','2024-11-04 10:00:40'),(183,15,1,'2024-11-04 10:01:10','2024-11-04 10:01:10'),(184,15,4,'2024-11-04 10:01:10','2024-11-04 10:01:10'),(185,15,2,'2024-11-04 10:01:10','2024-11-04 10:01:10'),(186,15,5,'2024-11-04 10:01:10','2024-11-04 10:01:10'),(187,65,1,'2024-11-08 08:56:41','2024-11-08 08:56:41'),(188,66,4,'2024-11-08 08:57:06','2024-11-08 08:57:06'),(189,66,1,'2024-11-08 08:57:06','2024-11-08 08:57:06'),(190,66,2,'2024-11-08 08:57:06','2024-11-08 08:57:06'),(194,67,1,'2024-11-13 13:39:06','2024-11-13 13:39:06'),(195,67,2,'2024-11-13 13:39:06','2024-11-13 13:39:06'),(196,68,1,'2024-12-05 09:05:09','2024-12-05 09:05:09'),(197,69,1,'2024-12-13 09:21:05','2024-12-13 09:21:05'),(198,69,2,'2024-12-13 09:21:05','2024-12-13 09:21:05'),(199,69,5,'2024-12-13 09:21:05','2024-12-13 09:21:05'),(203,70,1,'2024-12-26 20:19:39','2024-12-26 20:19:39'),(204,70,2,'2024-12-26 20:19:39','2024-12-26 20:19:39'),(205,71,2,'2024-12-26 20:27:13','2024-12-26 20:27:13'),(206,71,1,'2024-12-26 20:27:13','2024-12-26 20:27:13'),(207,72,2,'2024-12-27 08:40:07','2024-12-27 08:40:07'),(208,72,1,'2024-12-27 08:40:07','2024-12-27 08:40:07'),(209,73,1,'2024-12-27 09:08:51','2024-12-27 09:08:51'),(210,73,2,'2024-12-27 09:08:51','2024-12-27 09:08:51'),(211,74,2,'2024-12-27 09:10:37','2024-12-27 09:10:37'),(212,74,1,'2024-12-27 09:10:37','2024-12-27 09:10:37'),(216,17,1,'2024-12-31 10:36:10','2024-12-31 10:36:10'),(217,17,2,'2024-12-31 10:36:10','2024-12-31 10:36:10'),(218,17,4,'2024-12-31 10:36:10','2024-12-31 10:36:10'),(219,17,5,'2024-12-31 10:36:10','2024-12-31 10:36:10'),(220,75,4,'2025-01-02 14:24:45','2025-01-02 14:24:45'),(221,75,1,'2025-01-02 14:24:45','2025-01-02 14:24:45'),(222,76,1,'2025-01-07 10:46:47','2025-01-07 10:46:47'),(223,77,1,'2025-01-08 17:52:22','2025-01-08 17:52:22'),(224,77,4,'2025-01-08 17:52:22','2025-01-08 17:52:22'),(225,78,2,'2025-01-09 09:51:56','2025-01-09 09:51:56'),(226,79,2,'2025-01-09 09:52:16','2025-01-09 09:52:16'),(227,81,1,'2025-01-09 09:52:50','2025-01-09 09:52:50'),(228,81,2,'2025-01-09 09:52:50','2025-01-09 09:52:50'),(229,81,5,'2025-01-09 09:52:50','2025-01-09 09:52:50');

UNLOCK TABLES;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_personal` int DEFAULT NULL,
  `idcentro_mac` int DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

LOCK TABLES `users` WRITE;

insert  into `users`(`id`,`name`,`email`,`email_verified_at`,`password`,`id_personal`,`idcentro_mac`,`remember_token`,`created_at`,`updated_at`) values (1,'47286140','jmuchcco@gmail.com',NULL,'$2y$10$iqtiWH/0q6wddq5cwyACYO8XikCH4ib.5VQPAbFpbcWNKAD2cR/u2',20,20,NULL,'2024-06-11 09:12:09','2024-06-11 09:12:09'),(2,'23275167','edgarcp123@gmail.com',NULL,'$2y$10$KWGEGO4mkFxGdfvczK6QmOib4IGUYp8/vPrECczQBffVpcdNuVFBi',249,2,NULL,'2024-08-18 14:50:49','2024-08-18 14:50:49'),(3,'71838462','ECORTEZ@PCM.GOB.PE',NULL,'$2y$10$PdhuH1lQyUI5RoCiZuPs3ehbgOQDCqqhmL3cRsEhe8Rg3S/2Dr6zK',201,12,NULL,'2024-08-19 09:52:11','2024-08-19 09:52:11'),(5,'41957704','wasto@pcm.gob.pe',NULL,'$2y$10$wONv8twF6mWdRtiBF3GChe8ELstUVbCEVAPXtwglhK9v48UGhTSxO',152,9,NULL,'2024-08-19 09:53:06','2024-08-19 09:53:06'),(6,'71852986','carlosbaco96@gmail.com',NULL,'$2y$10$Ffr4i4zT2EmZz3i.Vf.aFOx3aGfk6qkCQPZuIt8JzL9N5rrDzB9wC',148,18,NULL,'2024-08-19 09:53:53','2024-08-19 09:53:53'),(7,'41850718','racero@pcm.gob.pe',NULL,'$2y$10$/elSiKF/EkDCcUotRfSace2zq/tnndRY6t5uLig1a5zQjowO8N5aS',267,8,NULL,'2024-08-19 09:54:55','2024-08-19 09:54:55'),(8,'71858503','diego.soto3003@gmail.com',NULL,'$2y$10$ikIp3VGj18yFgTeeyNx.e.azw1fvfJuUmWiV0sHq2pFJRYfIF3On6',206,11,NULL,'2024-08-19 09:56:17','2024-08-19 09:56:17'),(9,'72022599','LEAH.DAVRE20@GMAILCOM',NULL,'$2y$10$p2or7iqLJwjA.1VWFBJ9QuOdlWyKUP93gwt6cwgd3ZmQX2OqsUksS',296,12,NULL,'2024-08-19 10:00:35','2024-08-19 10:00:35'),(10,'70780270','dquezada@pcm.gob.pe',NULL,'$2y$10$.T7LIuiXP.KLaewhvdkpEuAj3BrIYdIzCP/9QFNVu2ckgr.hsNcsC',194,6,NULL,'2024-08-19 10:15:19','2024-08-19 10:15:19'),(11,'42460879','ovilla@pcm.gob.pe',NULL,'$2y$10$saZ1xFKHwT3E1sK22gHlCOPt465IvYlhT.sNcest5SFuXDXGGVijW',165,7,NULL,'2024-08-21 08:34:31','2024-08-21 08:34:31'),(12,'71571761','jflores@pcm.gob.pe',NULL,'$2y$10$CiKD5pUhe0ngW5JltiD/s.HyVqBEV/ed1NcQokZynVuTo8InvaP8O',302,16,NULL,'2024-08-21 08:41:59','2024-08-21 08:41:59'),(13,'43927410','jpfuno@pcm.gob.pe',NULL,'$2y$10$mwKH/IDi7Oh2MDreGvo.wuZdmQW64tJTuw/Bg6MW/dSHcbxfXVS0a',55,1,NULL,'2024-08-21 08:48:52','2024-08-21 08:48:52'),(14,'43899829','mdiaz.sistemas@gmail.com',NULL,'$2y$10$3XRTm8Y3N9zwbe/6QgK7augIz53zAd4k5qYTay1lDexhsVl5OeVWu',182,15,NULL,'2024-08-21 08:50:59','2024-08-21 08:50:59'),(15,'73008187','eantonneyra@gmail.com',NULL,'$2y$10$AK24HRsTj6Ww6OVsy5ip2O3/m8NEHCk7wUztf3m.KytSueks3xZMq',235,17,NULL,'2024-08-21 08:54:26','2024-08-21 08:54:26'),(17,'44049569','jespillco@pcm.gob.pe',NULL,'$2y$10$Sv3goFlnp8cZMQpEqKnfpu3pPYWTsEMywudZgbwhNXd.5hKf0ITJu',251,2,NULL,'2024-08-21 09:05:05','2024-08-21 09:05:05'),(18,'9955498','dsaavedra@pcm.gob.pe',NULL,'$2y$10$VtVOSGXijXdXAQKncLwwVeJFscW78gIx5XUkxvb4rnLsia18d1Rtq',202,12,NULL,'2024-08-21 09:26:23','2024-08-21 09:26:23'),(19,'41116740','lromero@pcm.gob.pe',NULL,'$2y$10$VCt8ICQhr1mzJHTPPrnidegPP.2h6PugS9osJD8RqEXCf1NwK/Iv6',176,13,NULL,'2024-08-21 09:27:14','2024-08-21 09:27:14'),(20,'40752495','EPARIONA@PCM.GOB.PE',NULL,'$2y$10$xAYsEDHI/xof17g9qsSXEOFtTJz9XJOmKiF02QDy.bmTa1W2FzbUu',173,13,NULL,'2024-08-21 09:27:51','2024-08-21 09:27:51'),(21,'7256891','dpuertas@pcm.gob.pe',NULL,'$2y$10$e0km/S8LnWPP1oqnCSrcLOAAk.i6xSYZVDtNN7WGZGgObn1ZU6n5S',214,14,NULL,'2024-08-21 09:28:25','2024-08-21 09:28:25'),(22,'45504419','chuamani@pcm.gob.pe',NULL,'$2y$10$SwoPdi1/PvgV6Z8kvQBC6ODoXfXWR2AMuNareRfIu9GianES3d4EK',232,8,NULL,'2024-08-21 09:29:52','2024-08-21 09:29:52'),(23,'45332693','jcalderon@pcm.gob.pe',NULL,'$2y$10$GnYBHzKDHvsCmlkzBV1VsujO6MscxpwJwA.UIfJ88.Ifl8jgFNxa.',287,11,NULL,'2024-08-21 09:30:44','2024-08-21 09:30:44'),(24,'47222090','pplaza@pcm.gob.pe',NULL,'$2y$10$i1sqPL9AMVdNwhzA1LY77uqgkb76yKzqJ5nBzfjS1iBpmXE4vLiFK',190,15,NULL,'2024-08-21 09:31:39','2024-08-21 09:31:39'),(25,'42413001','acayo@pcm.gob.pe',NULL,'$2y$10$JTwIQAMnLnbaJO6Sxzpz/.hBNIjZmIqGfwgjrxhUZbMeJ71mmofYW',162,14,NULL,'2024-08-22 04:36:08','2024-08-22 04:36:08'),(28,'46737023','cgutierrez@regioncallao.gob.pe',NULL,'$2y$10$/g38O6GUHC4bX1xg2JuPIehXE.XIzOrtcYkYvhY1OUNfu2RtadawS',361,10,NULL,'2024-08-22 06:06:29','2024-08-22 06:06:29'),(29,'44225085','alvaroq191@gmail.com',NULL,'$2y$10$7PfNaeS790K8vcNY406Y4eKc4p6ZfdJAP/ypQKYgveuIoJJ0Dsrj.',209,19,NULL,'2024-08-22 06:07:37','2024-08-22 06:07:37'),(30,'46033912','kjara@pcm.gob.pe',NULL,'$2y$10$81zyGeT69MTxxJ6G2fd3o.9GFiQneWdUb0Ua32UioBkx15hVqag7m',362,21,NULL,'2024-08-22 06:35:42','2024-08-22 06:35:42'),(31,'10244560','fgomez@pcm.gob.pe',NULL,'$2y$10$O5jceN3Z3Y9pidAUkjNbYOpV2.g458sQyS1Hn08WiSX9FJO2bk6/m',294,14,NULL,'2024-08-26 09:22:46','2024-08-26 09:22:46'),(35,'9943796','epighi@pcm.gob.pe',NULL,'$2y$10$/Sb0c6Cyi0eZCj3LO3lbNuUiwouZgE6by2.pgZ2N9kZYDVwzmx8Tq',276,12,NULL,'2024-09-02 05:59:17','2024-09-02 05:59:17'),(36,'10863828','jobregonmo@pj.gob.pe',NULL,'$2y$10$cwxc4RC.YDG8qNRf0UUXRO/n/I10lZjMdnUH3MKZpiYd8XY5.FCH6',523,12,NULL,'2024-09-02 06:23:20','2024-09-02 06:23:20'),(38,'70194555','cmamani@pcm.gob.pe',NULL,'$2y$10$y//G4eZHAUyOIWQd3C746Om.hiw3wRG.aXoQziWO9GnFl2.h7nXIW',301,16,NULL,'2024-09-03 01:44:55','2024-09-03 01:44:55'),(39,'47275663','jcomeca@pcm.gob.pe',NULL,'$2y$10$uJ8Y2giIk/QG7ytAW2EozO3/YwlmVNN0CMVx6DuqzuJZ5zukE4OJm',168,6,NULL,'2024-09-03 02:59:03','2024-09-03 02:59:03'),(40,'46019732','fcolonio@pcm.gob.pe',NULL,'$2y$10$YBFYikK4718T5OeZEbMmuOvHRgm6B.O5QOK.IRfQN9B.a1G.CeF0W',19,12,NULL,'2024-09-03 05:06:52','2024-09-03 05:06:52'),(41,'44942386','fsosa@pcm.gob.pe',NULL,'$2y$10$SpmPIh7woGcsQTpAMlEn1..okZL5k13PiyJ9sjxjZNmcTStRlfP9.',167,7,NULL,'2024-09-05 02:46:04','2024-09-05 02:46:04'),(42,'29698295','dany_ar13@hotmail.com',NULL,'$2y$10$eFf7Tt8TAu2DT7r01c6NHecb6zWiw1I5Rth6yTQtRIa30LIZCUwO2',141,1,NULL,'2024-09-06 02:36:33','2024-09-06 02:36:33'),(43,'2848432','coordinadormac@regionpiura.gob.pe',NULL,'$2y$10$9nOEpYCgKT6st2upm.MwH.4a0uribFcHpc4APSL0/Je.Ae3bSp0Re',228,17,NULL,'2024-09-11 03:40:13','2024-09-11 03:40:13'),(45,'18144542','esoto@pcm.gob.pe',NULL,'$2y$10$yuw9jLbuBT/1Cj/Aakm/Qe3H7nghN5svhH8QS84p1ELxjviFXRrCy',156,7,NULL,'2024-09-11 04:22:53','2024-09-11 04:22:53'),(47,'00793467','acastillo@pcm.gob.pe',NULL,'$2y$10$B6RfzwOT3CvAL5RyNlVyUuRvZ6L/khUrT8YACZIk6wGO5.mp9oViK',299,16,NULL,'2024-09-12 01:37:56','2024-09-12 01:37:56'),(48,'71983281','diego.cappa.dj@gmail.com',NULL,'$2y$10$TiWtwBtbIsviRk0gM6NuouKtdRaYtja7kvcIur/HkO0rfgOMI/iHK',770,20,NULL,'2024-09-20 07:25:37','2024-09-20 07:25:37'),(49,'15443040','avalle@pcm.gob.pe',NULL,'$2y$10$6uJNdGxijmlxUXxNDldad.ekUmdLysoaR4l6eDhOTmId9YvXqTaXW',769,20,NULL,'2024-09-23 08:06:40','2024-09-23 08:06:40'),(50,'41900054','cpardo@pcm.gob.pe',NULL,'$2y$10$U4tm4ntfZb6QXFQiwosW4O0c/idfYXpK6F6zhr16Ww7SNaugUKHom',767,20,NULL,'2024-09-23 08:20:00','2024-09-23 08:20:00'),(51,'10213420','PANCHANTE@PCM.GOB.PE',NULL,'$2y$10$Ej51PI.36yv5g.XyIx2OtulxcdyQNb91cuVY28e.eJbXnNKNZzwBG',172,13,NULL,'2024-09-25 17:13:29','2024-09-25 17:13:29'),(52,'80609175','kbustos@pcm.gob.pe',NULL,'$2y$10$6pZRWu.ALDeg2wD6.cUXLuphOpy99Nkoi9.vAHokQ/.UuV7HEbwZy',157,13,NULL,'2024-09-25 17:14:38','2024-09-25 17:14:38'),(53,'40044886','cchipana@pcm.gob.pe',NULL,'$2y$10$1V84ao/7U9hzk9NhtyXtpe8VOdGQ0.VALLIF/BD.e5F9Wtf53Y0LG',188,13,NULL,'2024-09-25 17:25:59','2024-09-25 17:25:59'),(54,'71046151','fsanchezr@muniventanilla.gob.pe',NULL,'$2y$10$f.RP8icV4J7R5y4C2xFAqenaeoKsytAJqfsOn4C5bSkORi9dL1ueO',177,19,NULL,'2024-10-01 15:51:27','2024-10-01 15:51:27'),(55,'43592680','sgarfias@pcm.gob.pe',NULL,'$2y$10$0gMKZI1jJWQFScWrv2izQemC7iZsGXTuCzHEygzN6L/zUd7Gg0c/W',905,7,NULL,'2024-10-03 11:36:52','2024-10-03 11:36:52'),(56,'41438092','margarita.moc81@gmail.com',NULL,'$2y$10$J1ARmRnv0Ttafu234ng5qeRimwo16T4AFFbS.J1H7/.VDdh9TbBFe',60,11,NULL,'2024-10-05 09:23:11','2024-10-05 09:23:11'),(58,'46757308','lguevara@pcm.gob.pe',NULL,'$2y$10$xNW1QZRAlbP0pmRix8iKxuZoh6Nfb68.2BH1y.Ebtzwxngxgh1Mp2',139,5,NULL,'2024-10-10 12:19:12','2024-10-10 12:19:12'),(59,'46416147','JFARFAN@PCM.GOB.PE',NULL,'$2y$10$NZ73GIdXj1qh9oKBRtpjHeEEhPv7ClBceQBFrfCkO/JqPqscAcRhS',185,11,NULL,'2024-10-10 12:28:04','2024-10-10 12:28:04'),(60,'41772591','KDELAGUILA@PCM.GOB.PE',NULL,'$2y$10$V2iptGmv0608Q6BmdfkLe.PliHeeey6drUoRONyQvfxDgJmehRCIy',221,14,NULL,'2024-10-15 08:58:19','2024-10-15 08:58:19'),(61,'47488773','emalca@pcm.gob.pe',NULL,'$2y$10$tt3Wq9I2SAnjUnV5diqoPO6DdOOp9nO6rNTD70yKrl1rI2UkJdIZ.',1001,9,NULL,'2024-10-15 10:35:14','2024-10-15 10:35:14'),(62,'44805593','lesliequito11@gmail.com',NULL,'$2y$10$2H.BgUOnio9yPpX2RmVM3u7.eIZ/hBoy8sgleZoz0Ck83gRas2pIa',1003,9,NULL,'2024-10-15 10:35:43','2024-10-15 10:35:43'),(63,'70025616','supervisormac@regionpiura.gob.pe',NULL,'$2y$10$z2Z5p3MBVlJDGIYDxELIaOPjGpMn5HkAfArUCoDGQLFj8UPwgqj2e',170,17,NULL,'2024-11-04 10:00:40','2024-11-04 10:00:40'),(65,'7977634','MSAETTONE@PCM.GOB.PE',NULL,'$2y$10$nfFJrkp2NAS6VVp66ziiQudhrWjFvSqCyrTfnMlt7xuBcMeVCtYL.',166,6,NULL,'2024-11-08 08:56:41','2024-11-08 08:56:41'),(66,'42933134','Carlosquepuy@gmail.com',NULL,'$2y$10$JQaWfvmqmAsx.5uavnMj9.QD2nSy0TgrKaLG.Y7btc0eNB441NB3i',1063,6,NULL,'2024-11-08 08:57:06','2024-11-08 08:57:06'),(67,'43332366','gescriba@pcm.gob.pe',NULL,'$2y$10$mttt4M4MYWizYla/Ze7bbupH88qxPoMcbyPUinzV9f4CA7Ty2/UJq',155,9,NULL,'2024-11-13 13:32:50','2024-11-13 13:32:50'),(68,'74962711','azulmmxiv@gmail.com',NULL,'$2y$10$zkLe/mAhr23HLADnhF8F0u4UZFvAOCxnfobBoL0MwLLks.cHoextW',282,18,NULL,'2024-12-05 09:05:09','2024-12-05 09:05:09'),(69,'44837987','CINTIBAL_AL@HOTMAIL.COM',NULL,'$2y$10$XnWtzF0Wd.oM/OA4CYMmpuUyfMOm4CWRQyqQM4Xv8Xi76FJ6ryOV6',260,2,NULL,'2024-12-13 09:21:05','2024-12-13 09:21:05'),(70,'40309107','JCANALES1979@HOTMAIL.COM',NULL,'$2y$10$AvywmCQYBZppmaX3fHo4quR8SA.J9IOjQw2wIWswp1hdiZ5vsM7YS',430,10,NULL,'2024-12-26 20:18:55','2024-12-26 20:18:55'),(71,'77274493','svillanuevaproduce@gmail.com',NULL,'$2y$10$tHqJTfc8MtVokv53ACzFTOn1P3SRqnU62V3dgU0alAdUFMe/KdpMG',797,10,NULL,'2024-12-26 20:27:13','2024-12-26 20:27:13'),(72,'44179390','eliana18_1987@hotmail.com',NULL,'$2y$10$c1ocV.MqcRdQBrPHwqIgeOmdOUDAvawcto5deIhbl3ez8rcrsESYW',1181,10,NULL,'2024-12-27 08:40:07','2024-12-27 08:40:07'),(73,'43797314','pcasassaca24@ucvvirtual.edu.pe',NULL,'$2y$10$vQl0d4CDG0z5LAxbYvICFubA2Chip4Nah/peifEjmiSpWQLS7lI86',1185,10,NULL,'2024-12-27 09:08:51','2024-12-27 09:08:51'),(74,'76668875','KEVIN.78892@GMAIL.COM',NULL,'$2y$10$vbUUUyCHJRIyVthf39BPtuz2sAs24mg2bJSqNnkn5LPDe6XYpVxgK',1186,10,NULL,'2024-12-27 09:10:37','2024-12-27 09:10:37'),(75,'42621794','renerqm@gmail.com',NULL,'$2y$10$LGViGYtbIHs0Ul1vuUsuxOI3oxiwTrbGzIksI4Lcm2btvv6xYRMvO',1292,16,NULL,'2025-01-02 14:24:45','2025-01-02 14:24:45'),(76,'45646120',NULL,NULL,'$2y$10$Y.c2E7yAfqGrESek7lxS8e3gbkrJlTpyfHROzWPiqB9aXDq77vB5y',132,5,NULL,'2025-01-07 10:46:47','2025-01-07 10:46:47'),(77,'41592870','miullertv@gmail.com',NULL,'$2y$10$pd6fieKL6Y/nbgYEWFl9guFvYWyVE5nK4XVP2tjg.gKGfUCsuNG.C',1325,14,NULL,'2025-01-08 17:51:45','2025-01-08 17:51:45'),(78,'47474508','laguilarm@pcm.gob.pe',NULL,'$2y$10$AFDPnw3dS7RXmtgMeuA.Z.Kff7vil0Wv/0wDJf4b9BqIu4xB6mUNi',159,7,NULL,'2025-01-09 09:51:56','2025-01-09 09:51:56'),(79,'72759692','wescudero@pcm.gob.pe',NULL,'$2y$10$LIixFhcaTprwIarpINgh2ut/QiHrF.A7S8zF30wDzigiQ5zDRT0k6',160,7,NULL,'2025-01-09 09:52:16','2025-01-09 09:52:16'),(80,'44116412','vlangle@pcm.gob.pe',NULL,'$2y$10$PZ8wTVO5E/FgAsCgenNCBuN9z6A7gyoPFlLv36UmhHgs/MgBzTaEu',158,7,NULL,'2025-01-09 09:52:30','2025-01-09 09:52:30'),(81,'32962765','idominguez@pcm.gob.pe',NULL,'$2y$10$0Hxvwt2bJCBtO.qC30GqDO/ggo0ee3ursOF2n8t7zKQxBvhfZJFKW',164,7,NULL,'2025-01-09 09:52:50','2025-01-09 09:52:50');

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
