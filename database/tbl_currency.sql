-- MySQL dump 10.13  Distrib 8.0.28, for Linux (x86_64)
--
-- Host: localhost    Database: ehms3_database
-- ------------------------------------------------------
-- Server version	8.0.28-0ubuntu0.20.04.3

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
-- Table structure for table `tbl_currency`
--

DROP TABLE IF EXISTS `tbl_currency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_currency` (
  `currency_id` int NOT NULL AUTO_INCREMENT,
  `currency_name` varchar(100) NOT NULL,
  `currency_code` varchar(100) NOT NULL,
  `currency_symbol` varchar(100) NOT NULL,
  `employee_id` int DEFAULT NULL,
  `date_modified` datetime NOT NULL,
  `default_` varchar(20) DEFAULT 'no',
  PRIMARY KEY (`currency_id`),
  UNIQUE KEY `currency_name` (`currency_name`),
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_currency`
--

LOCK TABLES `tbl_currency` WRITE;
/*!40000 ALTER TABLE `tbl_currency` DISABLE KEYS */;
INSERT INTO `tbl_currency` VALUES (1,'Tanzania shilling','TZS','',32,'2015-12-19 17:33:30','no'),(3,'United States dollar','USD','',32,'2015-12-19 17:34:41','no'),(6,'Kenya shilling','KES','',32,'2015-12-19 17:36:58','no'),(8,'Uganda shilling','UGX','',32,'2015-12-19 17:38:00','no'),(9,'Burundi franc','BIF','',32,'2015-12-19 17:39:18','no'),(10,'Rwanda franc','RWAF','',32,'2015-12-19 17:40:17','no');
/*!40000 ALTER TABLE `tbl_currency` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-04-08  9:20:49
