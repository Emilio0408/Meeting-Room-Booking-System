-- MySQL dump 10.13  Distrib 8.0.44, for Linux (x86_64)
--
-- Host: localhost    Database: meeting_rooms
-- ------------------------------------------------------
-- Server version    8.0.44

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
-- Table structure for table `Prenotazione`
--

CREATE DATABASE IF NOT EXISTS `meeting_rooms`;
USE `meeting_rooms`;

DROP TABLE IF EXISTS `Prenotazione`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Prenotazione` (
  `DATA` date NOT NULL,
  `FasciaOraria` time NOT NULL,
  `IDSala` int NOT NULL,
  `Utente` varchar(50) NOT NULL,
  PRIMARY KEY (`DATA`,`FasciaOraria`,`IDSala`),
  KEY `IDSala` (`IDSala`),
  KEY `Utente` (`Utente`),
  CONSTRAINT `Prenotazione_ibfk_1` FOREIGN KEY (`IDSala`) REFERENCES `SalaRiunioni` (`ID`),
  CONSTRAINT `Prenotazione_ibfk_2` FOREIGN KEY (`Utente`) REFERENCES `Utente` (`Username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Prenotazione`
--

LOCK TABLES `Prenotazione` WRITE;
/*!40000 ALTER TABLE `Prenotazione` DISABLE KEYS */;
INSERT INTO `Prenotazione` VALUES ('2025-10-30','18:00:00',2,'maioneemilio'),('2025-10-30','19:00:00',8,'maioneemilio'),('2025-10-31','11:00:00',8,'maioneemilio'),('2025-10-31','12:00:00',4,'maioneemilio');
/*!40000 ALTER TABLE `Prenotazione` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SalaRiunioni`
--

DROP TABLE IF EXISTS `SalaRiunioni`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `SalaRiunioni` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Capienza` int NOT NULL,
  `Piano` int NOT NULL,
  `Edificio` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SalaRiunioni`
--

LOCK TABLES `SalaRiunioni` WRITE;
/*!40000 ALTER TABLE `SalaRiunioni` DISABLE KEYS */;
INSERT INTO `SalaRiunioni` VALUES (1,10,1,'A'),(2,20,2,'A'),(3,15,3,'B'),(4,18,3,'C'),(8,12,3,'B');
/*!40000 ALTER TABLE `SalaRiunioni` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Utente`
--

DROP TABLE IF EXISTS `Utente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Utente` (
  `Username` varchar(50) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `Amministratore` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Utente`
--

LOCK TABLES `Utente` WRITE;
/*!40000 ALTER TABLE `Utente` DISABLE KEYS */;
INSERT INTO `Utente` VALUES ('mai092','$2y$10$yZPPC748OeJk9tcjXtZFiekIqXHeKFg5x73m2pPIc2t.0AjrMSx5u',0),('maioneemilio','$2y$10$i5Jq6tqonBozMWujxMw3s.Cx4Zpcd1s7iCgdJ9t/SL87fahs0JEHG',1);
/*!40000 ALTER TABLE `Utente` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-30 17:20:12