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
-- Table structure for table `tbl_config`
--

DROP TABLE IF EXISTS `tbl_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_config` (
  `Config_ID` int NOT NULL AUTO_INCREMENT,
  `configname` varchar(60) NOT NULL,
  `configvalue` varchar(60) NOT NULL,
  `configdesc` varchar(50) NOT NULL,
  `modified_date` datetime NOT NULL,
  `modified_by` int NOT NULL,
  `last_auto_update` date DEFAULT NULL,
  `write_auth_number` varchar(10) NOT NULL,
  PRIMARY KEY (`Config_ID`),
  UNIQUE KEY `configname` (`configname`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_config`
--

LOCK TABLES `tbl_config` WRITE;
/*!40000 ALTER TABLE `tbl_config` DISABLE KEYS */;
INSERT INTO `tbl_config` VALUES (1,'Afya_Card_Mandatory','No','(Yes or No)','0000-00-00 00:00:00',0,NULL,''),(2,'AutoLogout','0','In Seconds(Default Not Enabled)','0000-00-00 00:00:00',0,NULL,''),(3,'AutoNoShow','0','In Hours (Default Not Enabled)','0000-00-00 00:00:00',0,NULL,''),(4,'AutoSignedOff','0','In Hours (Default Not Enabled)','0000-00-00 00:00:00',0,NULL,''),(5,'Finger_Print_Mandatory','No','(Yes or No)','0000-00-00 00:00:00',0,NULL,''),(6,'GaccountingUrl','http://127.0.0.1/Final_one/gaccounting','(url format)','0000-00-00 00:00:00',0,NULL,''),(7,'HrpUrl','http://192.168.1.5/Final_one/hrp/index.php/auth/login','(url format)','0000-00-00 00:00:00',0,NULL,''),(8,'Icd_10OrIcd_9','icd_10','(icd9 or icd_10)','2018-04-09 09:24:00',0,NULL,''),(9,'IntegratedToAccounting','No','(Yes or No)','0000-00-00 00:00:00',0,NULL,''),(10,'MaximumTimeToSeePatientHistory','0','In Hrs (Defaul Disabled)','0000-00-00 00:00:00',0,NULL,''),(11,'Military','Yes','(Yes OR No)','2018-01-03 00:00:00',0,NULL,''),(12,'NhifApiConfiguration','multipleserver','(singleserver or multipleserver)','0000-00-00 00:00:00',0,NULL,''),(13,'NhifAuthorization','No','(Yes OR No)','0000-00-00 00:00:00',0,NULL,''),(14,'NhifExternalServerUrl','http://192.168.1.9/nhifprocessing/','url','0000-00-00 00:00:00',0,NULL,''),(15,'ShowCreateEpaymentBillOrMakePaymentButton','makepayment','(makepayment or epayment)','0000-00-00 00:00:00',0,NULL,''),(16,'showManulaOrOffline','manual','(offline or manual)','0000-00-00 00:00:00',0,NULL,''),(17,'Show_Afya_Card','Yes','(Yes or No)','0000-00-00 00:00:00',0,NULL,''),(18,'Show_Finger_Print','Yes','(Yes or No)','0000-00-00 00:00:00',0,NULL,'');
/*!40000 ALTER TABLE `tbl_config` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-04-08  9:28:12
