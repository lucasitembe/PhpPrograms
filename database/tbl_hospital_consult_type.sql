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
-- Table structure for table `tbl_hospital_consult_type`
--

DROP TABLE IF EXISTS `tbl_hospital_consult_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_hospital_consult_type` (
  `hosp_consult_ID` int NOT NULL AUTO_INCREMENT,
  `consultation_Type` varchar(50) NOT NULL,
  `allow_display_prev` tinyint(1) NOT NULL DEFAULT '0',
  `set_pre_paid` tinyint(1) NOT NULL DEFAULT '0',
  `set_duplicate_bed_assign` tinyint(1) NOT NULL DEFAULT '0',
  `set_doctors_auto_save` tinyint(1) NOT NULL DEFAULT '0',
  `enable_doct_date_chooser` tinyint(1) NOT NULL DEFAULT '0',
  `enable_pat_medic_hist` tinyint(1) NOT NULL DEFAULT '0',
  `enable_clinic_not_scroll` tinyint(1) NOT NULL DEFAULT '0',
  `enable_spec_dosage` tinyint(1) NOT NULL DEFAULT '0',
  `enb_lab_wt_no_par` tinyint(1) NOT NULL DEFAULT '0',
  `req_op_prov_dign` tinyint(1) NOT NULL DEFAULT '0',
  `req_ip_prov_dign` tinyint(1) NOT NULL DEFAULT '0',
  `req_op_final_dign` tinyint(1) NOT NULL DEFAULT '0',
  `Branch_ID` int NOT NULL,
  `date_saved` datetime NOT NULL,
  `Employee_ID` int NOT NULL,
  `en_const_per_day_count` tinyint(1) NOT NULL DEFAULT '0',
  `req_perf_by_signed_off` tinyint(1) NOT NULL DEFAULT '0',
  `en_item_status_pat_file` tinyint(1) NOT NULL DEFAULT '0',
  `en_inp_auto_bill` tinyint(1) NOT NULL DEFAULT '0',
  `mandatory_comments` int NOT NULL DEFAULT '0',
  `doctor_admits_patient` varchar(10) NOT NULL DEFAULT 'no',
  `Enable_Save_And_Transfer_Button` enum('1','0') NOT NULL DEFAULT '0',
  `require_final_diagnosis_before_select_treatment` varchar(10) NOT NULL DEFAULT 'no',
  `consulted_patient_display_max_time` int NOT NULL,
  `doctor_notice_display_max_time` int NOT NULL,
  PRIMARY KEY (`hosp_consult_ID`),
  UNIQUE KEY `consultation_Type` (`consultation_Type`),
  UNIQUE KEY `consultation_Type_2` (`consultation_Type`),
  KEY `Branch_ID` (`Branch_ID`),
  KEY `Employee_ID` (`Employee_ID`),
  CONSTRAINT `tbl_hospital_consult_type_ibfk_1` FOREIGN KEY (`Branch_ID`) REFERENCES `tbl_branches` (`Branch_ID`),
  CONSTRAINT `tbl_hospital_consult_type_ibfk_2` FOREIGN KEY (`Employee_ID`) REFERENCES `tbl_employee` (`Employee_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_hospital_consult_type`
--

LOCK TABLES `tbl_hospital_consult_type` WRITE;
/*!40000 ALTER TABLE `tbl_hospital_consult_type` DISABLE KEYS */;
INSERT INTO `tbl_hospital_consult_type` VALUES (1,'One patient to many doctor',1,0,0,1,0,0,1,0,1,1,1,0,1,'2021-01-25 08:25:01',3414,0,0,1,1,0,'yes','1','yes',0,0);
/*!40000 ALTER TABLE `tbl_hospital_consult_type` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-04-08  9:13:26
