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
-- Table structure for table `tbl_system_configuration`
--

DROP TABLE IF EXISTS `tbl_system_configuration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_system_configuration` (
  `Configuration_ID` int NOT NULL AUTO_INCREMENT,
  `Branch_ID` int DEFAULT NULL,
  `Centralized_Collection` varchar(20) DEFAULT NULL,
  `Departmental_Collection` varchar(20) DEFAULT NULL,
  `Reception_Picking_Items` varchar(10) NOT NULL DEFAULT 'no',
  `Show_Pharmaceutical_Before_Payments` varchar(10) NOT NULL DEFAULT 'yes',
  `Display_Send_To_Cashier_Button` varchar(10) NOT NULL DEFAULT 'no',
  `Enable_Add_More_Medication` varchar(10) NOT NULL DEFAULT 'no',
  `Enable_Inpatient_To_Check_Again` varchar(10) NOT NULL DEFAULT 'no',
  `Allow_Direct_Cash_Outpatient` varchar(10) NOT NULL DEFAULT 'no',
  `Change_Medication_Location` varchar(10) NOT NULL DEFAULT 'no',
  `Filtered_Pharmacy_Patient_List` varchar(10) NOT NULL DEFAULT 'yes',
  `Allow_Cashier_To_Approve_Pharmaceutical` varchar(10) NOT NULL DEFAULT 'no',
  `Pharmacy_Patient_List_Displays_Only_Current_Checked_In` varchar(10) NOT NULL DEFAULT 'no',
  `Require_Patient_Phone_Number` varchar(5) NOT NULL DEFAULT 'no',
  `DHIS_Source_Report` varchar(30) NOT NULL DEFAULT 'Final Diagnosis',
  `Transfer_Patient_Module_Status` varchar(15) NOT NULL DEFAULT 'Disabled',
  `Enable_Splash_Index` varchar(5) NOT NULL DEFAULT 'no',
  `Default_Patient_Direction` varchar(50) NOT NULL DEFAULT 'none',
  `Mobile_Payment` varchar(20) DEFAULT 'no',
  `Inpatient_Prepaid` varchar(20) NOT NULL DEFAULT 'yes',
  `price_precision` varchar(20) NOT NULL DEFAULT 'no',
  `Imbalance_Discharge` varchar(20) NOT NULL DEFAULT 'no',
  `Departmental_Stock_Movement` varchar(10) NOT NULL DEFAULT 'yes',
  `Hospital_Name` varchar(70) DEFAULT NULL,
  `Box_Address` varchar(50) DEFAULT NULL,
  `Telephone` varchar(50) DEFAULT NULL,
  `Cell_Phone` varchar(50) DEFAULT NULL,
  `Fax` varchar(50) DEFAULT NULL,
  `Tin` varchar(50) DEFAULT NULL,
  `hospital_id` varchar(20) DEFAULT NULL,
  `offline_ecr_number` varchar(50) DEFAULT NULL,
  `Direct_departmental_payments` varchar(10) NOT NULL DEFAULT 'no',
  `Approval_Levels` int NOT NULL DEFAULT '0',
  `currency_id` int DEFAULT NULL,
  `Editable_Quantity_Received` varchar(10) NOT NULL DEFAULT 'no',
  `Store_Order_Add_Items_By_Pop_Up` varchar(4) NOT NULL DEFAULT 'no',
  `enable_receive_by_package` varchar(10) NOT NULL DEFAULT 'no',
  `enable_zeroing_price` varchar(10) NOT NULL DEFAULT 'no',
  `Expire_Password_Days` int NOT NULL,
  `Allow_login_failure_Count` varchar(10) NOT NULL DEFAULT 'no',
  `minimum_password_length` int NOT NULL,
  `alphanumeric_password` varchar(10) NOT NULL DEFAULT 'no',
  `Change_password_first_login` varchar(10) NOT NULL DEFAULT 'no',
  `All_Items_Payments` varchar(10) NOT NULL DEFAULT 'no',
  `Reception_Must_Fill_Exemption_Missing_Information` varchar(5) NOT NULL DEFAULT 'no',
  `Allow_Pharmaceutical_Dispensing_Above_Actual_Balance` varchar(5) NOT NULL DEFAULT 'yes',
  `Allow_Aditional_Instructions_On_Pharmacy_Menu` varchar(5) NOT NULL DEFAULT 'no',
  `Allow_Pharmacy_To_Dispense_Multiple_Patients` varchar(5) NOT NULL DEFAULT 'no',
  `Pharmacy_Additional_Instruction` varchar(50) NOT NULL DEFAULT '50',
  `Dispense_Credit_Patients_after_24_hrs` varchar(10) NOT NULL DEFAULT 'no',
  `Allow_Direct_Departmental_Payments_Auto_Billing` varchar(5) NOT NULL DEFAULT 'no',
  `Display_Cash_Bill_Button_On_Inpatient_Departmental_Payments` varchar(5) NOT NULL DEFAULT 'no',
  `opd_patients_days` int NOT NULL,
  `doctor_admits_patient` varchar(10) NOT NULL DEFAULT 'no',
  `Include_Exemption_Sponsors_In_Normal_Registration` varchar(5) NOT NULL DEFAULT 'no',
  `Registration_Mode` varchar(70) NOT NULL DEFAULT 'Receiving Patient Names Together',
  `nhif_base_url` text,
  `private_key` text,
  `public_key` text,
  `facility_code` text,
  `nhif_username` text,
  `stamp` varchar(200) DEFAULT 'stamp.png',
  `Use_managament_approval_bill` varchar(5) NOT NULL DEFAULT 'no',
  `allow_sms_to_patient` varchar(50) NOT NULL DEFAULT 'no',
  PRIMARY KEY (`Configuration_ID`),
  KEY `Branch_ID` (`Branch_ID`),
  KEY `currency_id` (`currency_id`),
  CONSTRAINT `tbl_system_configuration_ibfk_1` FOREIGN KEY (`currency_id`) REFERENCES `tbl_currency` (`currency_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_system_configuration`
--

LOCK TABLES `tbl_system_configuration` WRITE;
/*!40000 ALTER TABLE `tbl_system_configuration` DISABLE KEYS */;
INSERT INTO `tbl_system_configuration` VALUES (1,1,'yes','yes','no','yes','yes','yes','no','yes','yes','no','yes','yes','yes','Final Diagnosis','Enabled','yes','Direct To Clinic','yes','yes','no','no','yes','Lugalo Referral Hospital','65300','','','','101-042-219','',NULL,'yes',1,1,'no','no','no','yes',0,'no',0,'no','no','yes','no','no','yes','yes','50','no','no','yes',0,'no','yes','Receiving Patient Names Together','https://verification.nhif.or.tz/apiserver/api/v1/','Z3hW1HaB2JKJpDY6GbE0mvWGo4/kVK8o7QaJFy78M9A=','NHIF','01128','lugalorh','stamp.png','no','no');
/*!40000 ALTER TABLE `tbl_system_configuration` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-04-08  9:08:06
