-- MariaDB dump 10.19  Distrib 10.4.28-MariaDB, for osx10.10 (x86_64)
--
-- Host: localhost    Database: mail_platform
-- ------------------------------------------------------
-- Server version	10.4.28-MariaDB

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
-- Table structure for table `administrateurs`
--

DROP TABLE IF EXISTS `administrateurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `administrateurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `administrateurs`
--

LOCK TABLES `administrateurs` WRITE;
/*!40000 ALTER TABLE `administrateurs` DISABLE KEYS */;
/*!40000 ALTER TABLE `administrateurs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int(11) DEFAULT NULL,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  CONSTRAINT `contacts_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `emails_envoyes`
--

DROP TABLE IF EXISTS `emails_envoyes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `emails_envoyes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int(11) DEFAULT NULL,
  `destinataires` text DEFAULT NULL,
  `sujet` varchar(255) DEFAULT NULL,
  `contenu` text DEFAULT NULL,
  `date_envoi` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','deleted') DEFAULT 'active',
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emails_envoyes`
--

LOCK TABLES `emails_envoyes` WRITE;
/*!40000 ALTER TABLE `emails_envoyes` DISABLE KEYS */;
INSERT INTO `emails_envoyes` VALUES (1,5,'lunevert0@gmail.com','test ','quenny','2024-06-24 15:36:45','deleted'),(2,14,'lunevert0@gmail.com','test','fik','2024-06-24 20:58:13','active'),(3,8,'lunevert0@gmail.com','test ','mamam','2024-06-24 21:35:55','active'),(4,17,'lunevert0@gmail.com','PARTENARIAT','Ma partenaire du soir','2024-06-25 00:21:06','deleted'),(5,17,'lunevert0@gmail.com','PARTENARIAT','ma partenaire du soir hahaha','2024-06-25 00:22:17','deleted'),(6,17,'lunevert0@gmail.com','PARTENARIAT','Ma partenaire du soir hahaha','2024-06-25 00:23:24','deleted'),(7,17,'lunevert0@gmail.com','PARTENARIAT','Ma partenaire du soirrrrrrrrrrrrrrrrrrrr','2024-06-25 00:24:31','active'),(8,19,'lunevert0@gmail.com','test ','Stage à Defar sci ','2024-06-25 12:59:34','deleted');
/*!40000 ALTER TABLE `emails_envoyes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `interactions`
--

DROP TABLE IF EXISTS `interactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `interactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `administrateur_id` int(11) DEFAULT NULL,
  `date` datetime NOT NULL,
  `type` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `administrateur_id` (`administrateur_id`),
  CONSTRAINT `interactions_ibfk_1` FOREIGN KEY (`administrateur_id`) REFERENCES `administrateurs` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `interactions`
--

LOCK TABLES `interactions` WRITE;
/*!40000 ALTER TABLE `interactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `interactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email_smtp` varchar(255) DEFAULT NULL,
  `app_password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utilisateurs`
--

LOCK TABLES `utilisateurs` WRITE;
/*!40000 ALTER TABLE `utilisateurs` DISABLE KEYS */;
INSERT INTO `utilisateurs` VALUES (1,'Mareme','lunevert0@gmail.com','$2y$10$kjUklaVVKpzcP9H4mSkwveeJrVusXHzMUlBPrhOAkdoLyF/cCrpHO',NULL,'ehfwgludlhbkdzbt'),(2,'Mareme','sowmounass55@gmail.com','$2y$10$m6DeoDe5XHM0aCz7KCQI.u..dmbyyONo.P.41GpTBtSayjZ24iPGS',NULL,'ehfwgludlhbkdzbt'),(4,'Mareme','lufhjhert0@gmail.com','$2y$10$LrvUr.owwrtBm1/wqHahUeSCmOWS9G7cVC3c/GByS/GK.9kseaBtW',NULL,'ehfwgludlhbkdzbt'),(5,'Mareme','lunanoir@gmail.com','$2y$10$Ds6oa4UvWxd8Oz/9eNNwbOjr5YtuN/cEFJmfMaJleLNk4t6yftXeK',NULL,'nfrnaiqzpnqqshhc'),(6,'Mareme','lunoir@gmail.com','$2y$10$rr8FMrJiSK/ACV1f8NP5qeevXgRhzU6Y1Aw/zXzYI2mTKNggGanOG',NULL,'nfrnaiqzpnqqshhc'),(7,'Mareme','luhkkkoir@gmail.com','$2y$10$RaLBrwDWsiU02.o3Ozdd8.fOdG1x4HLsgkBGWTW3pikurfJQc8lha',NULL,'nfrnaiqzpnqqshhc'),(8,'MariQUEEN','kkkoir@gmail.com','$2y$10$JQ2uDx9VnQInqtb/BYxM3e7U1..olEc/UixvPzTN/m8QHapQKikE2',NULL,'nfrnaiqzpnqqshhc'),(9,'mareme','lunablack@gmail.com','$2y$10$T1m4trkboNpHbOdm2zTQGeaFJyMossa5eRV1lODzvtuXJnW8dzogC',NULL,'nfrnaiqzpnqqshhc'),(10,'mareme','lunab@gmail.com','$2y$10$K83.DzVtXAXLS3r28Ri0AuxfdTO5bAABNKEE2XQV88HCv6YfLVxVq',NULL,'nfrnaiqzpnqqshhc'),(11,'mareme','lunevert@gmail.com','$2y$10$f/zYhpaRicKwK329RYRvquN4XYTtRrNROxvZZvbPTwbtWXuTRLQ6a',NULL,'nfrnaiqzpnqqshhc'),(12,'Soukeyna','soukeyna@gmail.com','$2y$10$4NsgpBmt3mgq0qWLsPFnSuilqwmnSiNLPcTwioVB3y0hFJKx8.A5e',NULL,'nfrnaiqzpnqqshhc'),(13,'mam','da@gmail.com','$2y$10$8EkrYlRtNuy/R9tXcUIYZOxyFh6aQEZyZw3MutEsNFF2RbWpgMM2q',NULL,'nfrnaiqzpnqqshhc'),(14,'mam','da18@gmail.com','$2y$10$KPWh.ACIhnxcU0Bxxs2L0OGRc6XZaXCeWyc4S1vMBK5BpfQIqi1rC',NULL,'(nfrnaiqzpnqqshhc'),(15,'mamy','mamy@gmail.com','$2y$10$I87cFqelDloVqi8uJScgm.LcbVTLpU1OHyq9LzoOwEvenLCnT3MK2',NULL,'nfrnaiqzpnqqshhc'),(16,'mamy','mama@gmail.com','$2y$10$KHD7f0ex1IUdAYN2mljU.e0flrFis5t45FfbJF8Ynpl/0aiamvmA6',NULL,'nfrnaiqzpnqqshhc'),(17,'AZOUMI','DA19@gmail.com','$2y$10$QN0rY7.tzWW0OI9mMZK0TOuLhALAlWvGKkMfekQO69styiaw6FNv2',NULL,'nfrnaiqzpnqqshhc'),(19,'Mariqueen','queeny@gmail.com','$2y$10$or3TaU6aFfm8pQ6b8eaYqeUxfdmFHWljFocPB2E5ShMJuAdfkPLDO',NULL,'nfrnaiqzpnqqshhc');
/*!40000 ALTER TABLE `utilisateurs` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-06-25 17:31:51
