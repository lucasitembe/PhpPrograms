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
-- Table structure for table `tbl_department`
--

DROP TABLE IF EXISTS `tbl_department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_department` (
  `Department_ID` int NOT NULL AUTO_INCREMENT,
  `Department_Name` varchar(100) NOT NULL,
  `Department_Location` varchar(100) NOT NULL,
  `Branch_ID` int NOT NULL,
  `Department_Status` varchar(15) NOT NULL DEFAULT 'active',
  `status` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`Department_ID`),
  UNIQUE KEY `Department_Name` (`Department_Name`),
  KEY `Branch_ID` (`Branch_ID`),
  KEY `Branch_ID_2` (`Branch_ID`),
  CONSTRAINT `tbl_department_ibfk_1` FOREIGN KEY (`Branch_ID`) REFERENCES `tbl_branches` (`Branch_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_department`
--

LOCK TABLES `tbl_department` WRITE;
/*!40000 ALTER TABLE `tbl_department` DISABLE KEYS */;
INSERT INTO `tbl_department` VALUES (1,'Laboratory','Laboratory',1,'active',1),(7,'Pharmacy','Pharmacy',1,'active',1),(8,'Information Technology','Setup And Configuration',1,'active',1),(11,'Cashier','Revenue Center',1,'active',1),(17,'SUB DISPENCING STORES','Pharmacy',1,'active',1),(18,'Reception','Reception',1,'active',1),(19,'Medical','Laboratory',1,'active',1),(21,'Radiology','Radiology',1,'active',1),(22,'Doctor','Reception',1,'active',1),(23,'Clinical service','Management',1,'active',1),(24,'Store','Storage And Supply',1,'active',1),(25,'Main Laboratory','Laboratory',1,'active',1),(26,'Blood Bank','Blood Bank',1,'active',1),(27,'Rch','Rch',1,'active',1),(28,'Procedure','Procedure',1,'active',1),(29,'Theater room','Theater',1,'active',1),(30,'Surgery','Surgery',1,'active',1),(31,'PROCUREMENT AND SUPPLY (QM)','Procurement',1,'active',1),(33,'Admission','Admission',1,'active',1),(34,'Theatre','Theater',1,'active',1),(35,'Accounts Dept','Finance',1,'active',1),(36,'Dialysis','Dialysis',1,'active',1),(37,'cecap','Cecap',1,'active',1),(38,'hiv','Finance',1,'active',1),(39,'Nurse','Nurse Station',1,'active',1),(40,'Women care','Eram',1,'active',1),(41,'Optical','Optical',1,'active',1),(43,'Dental','Dental',1,'active',1),(44,'Physiotherapy','Physiotherapy',1,'active',1),(45,'Laundry','Family Planning',1,'active',1),(46,'AUDIT','Management',1,'active',1),(47,'IPD','Pharmacy',1,'active',1),(48,'OPD','Pharmacy',1,'active',1),(49,'OBGY','Surgery',1,'active',1),(50,'Mortuary','Eram',1,'active',1),(51,'CTC','HIV',1,'active',1),(52,'NEUROSURGERY','Surgery',1,'active',1),(53,'OPHTHALMOLOGY','Optical',1,'active',1),(54,'UTAWALA','Management',1,'active',1),(55,'Procurement and supply - QM','Storage And Supply',1,'not active',1),(56,'ENT','Eram',1,'active',1),(57,'Procurement (QM)','Procurement',1,'active',1),(58,'Environmental Health ','Management',1,'active',1),(59,'ORTHOPEDIC','Surgery',1,'active',1),(60,'Chief Nursing Office (CNO)','Admission',1,'active',1),(61,'OT','Surgery',1,'active',1),(63,'ANAESTHESIA','Theater',1,'active',1),(64,'Emergency ','Procedure',1,'active',1),(65,'ward 20 mwenge substore','Pharmacy',1,'not active',1),(66,'18n19 mwenge substore','Pharmacy',1,'active',1),(67,'Rch substore','Rch',1,'active',1),(68,'ward 12 substore','Pharmacy',1,'active',1),(69,'HEALTH OFFICER','Management',1,'active',1),(71,'psychiatry','Admission',1,'active',1),(72,'CSSD','Nurse Station',1,'active',1),(73,'EMERGENCE','Admission',1,'active',1),(74,'Tb and leprosy ','Admission',1,'active',1),(75,'OPD Reception','Reception',1,'active',1),(76,'INPERTIENT STORE','Storage And Supply',1,'active',1),(77,'Damu Salama','Blood Bank',1,'active',1),(79,'EVIRONMENTAL HEALTH','Management',1,'active',1),(80,'Ujenzi','Management',1,'active',1),(81,'LAB SUB STORE','Laboratory',1,'active',1),(82,'CTC Mwenge','HIV',1,'active',1),(83,'Dermatology','Procedure',1,'active',1),(84,'Infectious Diseases Department','Admission',1,'active',1),(85,'Clinics Consultation Fee','Clinic',1,'active',1),(86,'Cash Deposit','Deposit',1,'active',1),(87,'ONCOLOGY','Oncology',1,'active',1),(88,'Theater Major','Surgery',1,'active',1),(89,'UNWANTED SUB-DEPARTMENT','Eram',1,'active',1);
/*!40000 ALTER TABLE `tbl_department` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-04-08  8:19:52
