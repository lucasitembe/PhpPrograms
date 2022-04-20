-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 09, 2015 at 09:42 AM
-- Server version: 5.5.36
-- PHP Version: 5.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ehms_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_patient_registration`
--

CREATE TABLE IF NOT EXISTS `tbl_patient_registration` (
  `Registration_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Old_Registration_Number` varchar(100) DEFAULT NULL,
  `Patient_Name` varchar(200) NOT NULL,
  `Title` varchar(120) DEFAULT NULL,
  `Date_Of_Birth` date NOT NULL,
  `Gender` varchar(7) NOT NULL,
  `Region` varchar(70) NOT NULL,
  `District` varchar(40) NOT NULL,
  `Ward` varchar(50) NOT NULL,
  `Sponsor_ID` int(11) DEFAULT NULL,
  `Member_Number` varchar(100) DEFAULT NULL,
  `Member_Card_Expire_Date` date DEFAULT NULL,
  `Phone_Number` varchar(30) DEFAULT NULL,
  `Email_Address` varchar(60) DEFAULT NULL,
  `Occupation` varchar(100) DEFAULT NULL,
  `Employee_Vote_Number` varchar(150) DEFAULT NULL,
  `Emergence_Contact_Name` varchar(150) DEFAULT NULL,
  `Emergence_Contact_Number` varchar(30) DEFAULT NULL,
  `Company` varchar(150) DEFAULT NULL,
  `Employee_ID` int(11) DEFAULT NULL,
  `Registration_Date_And_Time` datetime NOT NULL,
  `Patient_Picture` varchar(15) NOT NULL DEFAULT 'default.png',
  `Status` text NOT NULL,
  PRIMARY KEY (`Registration_ID`),
  KEY `Employee_ID` (`Employee_ID`),
  KEY `Sponsor_ID` (`Sponsor_ID`),
  KEY `Region_ID` (`Region`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=50 ;

--
-- Dumping data for table `tbl_patient_registration`
--

INSERT INTO `tbl_patient_registration` (`Registration_ID`, `Old_Registration_Number`, `Patient_Name`, `Title`, `Date_Of_Birth`, `Gender`, `Region`, `District`, `Ward`, `Sponsor_ID`, `Member_Number`, `Member_Card_Expire_Date`, `Phone_Number`, `Email_Address`, `Occupation`, `Employee_Vote_Number`, `Emergence_Contact_Name`, `Emergence_Contact_Number`, `Company`, `Employee_ID`, `Registration_Date_And_Time`, `Patient_Picture`, `Status`) VALUES
(33, '0001', 'Kelvin Fussi', 'Miss', '1984-01-31', 'Male', 'Dar es salaam', 'Kinondoni', '', 4, '', '0000-00-00', '', '', 'BusinesWoman', '1234', 'Ramadhan Shabaan', '0713111222', 'Self', 17, '2014-07-18 15:52:24', 'default.png', ''),
(34, '0002', 'Namimi Nipo Hapa', 'Mr', '2014-06-04', 'Male', 'Iringa', 'Iringa Town', 'Town', 5, '87965', '2016-07-31', '0713353498', '', 'Student', '', 'Sabuni Zebedayo', '0753213476', 'Self', 17, '2014-07-18 15:54:21', 'default.png', ''),
(35, '123', 'Andrew Kajuna', 'Mr', '1985-07-17', 'Male', 'Dar es salaam', 'Kinondoni', 'Kiwe B', 6, '123rn6', '2015-12-31', '0714788944', 'kelvin@gmail.com', 'Businessman', '121332565', 'Jane John', '0753119526', 'GPITG', 17, '2014-07-20 14:11:17', 'default.png', ''),
(36, '00002', 'John Joseph', 'Mr', '2005-07-12', 'Male', 'Dar es salaam', 'Ilala', 'Ilala', 4, '201983', '2014-07-31', '', '', 'Student', '', 'Ramadhan Shabaan', '0753119526', '', 31, '2014-07-20 15:48:11', 'default.png', ''),
(37, '14431', 'Juma J Juma', 'Mr', '2006-07-11', 'Male', 'Dodoma', 'Kondoa', 'dsd', 6, '122332', '2015-07-16', '0714234561', '', 'Businessman', '121332565', 'the New One', '0753119526', 'GPITG', 17, '2014-07-24 11:47:16', 'default.png', ''),
(38, '00002', 'Aisha Kajuna', 'Mrs', '1974-08-07', 'Male', 'Dar es salaam', 'Ilala', 'Ilala B', 5, '243562437', '2015-08-31', '0714234578', 'aisha@aisha.com', 'BusinesWoman', '121332565', 'Ramadhan Shabaan', '0753119526', 'Self', 17, '2014-08-01 14:08:45', 'default.png', ''),
(39, '3241342', 'tgfthfh hjfjy', 'Mr', '2014-08-02', 'Male', 'Dar es salaam', 'Kinondoni', 'Ilala', 5, '122332', '2014-08-11', '0713353535', 'miss@miss.com', 'Businessman', '121332565', 'the New One', '0753119526', 'UDOM', 17, '2014-08-01 15:09:37', 'default.png', ''),
(40, NULL, 'Marty Salim', NULL, '2014-10-01', 'Female', 'Dar es salaam', 'Kinondoni', 'nhjgj', NULL, NULL, NULL, '0757336910', 'mwakalasyamartha@gmail.com', NULL, NULL, 'ljujhioy', '6789054', NULL, 29, '2014-10-20 16:04:40', 'default.png', 'Donor'),
(42, NULL, 'Marty Mwaky', NULL, '1997-08-16', 'Female', 'Iringa', 'Ludewa', 'Ngerengere', NULL, NULL, NULL, '757350559', 'mwakalasyamartha@yahoo.com', NULL, NULL, 'Mambo', '567890', NULL, 29, '2014-10-28 14:31:43', 'default.png', 'Donor'),
(43, NULL, 'Rehema Jumanne', NULL, '1999-04-14', 'Female', 'Dar es salaam', 'Kinondoni', 'Malamba', NULL, NULL, NULL, '0654342123', 'alex.lucas.igma@gmail.com', NULL, NULL, 'Mambo Jumanne', '09876453', NULL, 29, '2014-11-06 11:45:09', 'default.png', 'Donor'),
(44, NULL, 'Assa Mambo', NULL, '2014-10-05', 'Male', 'Dar es salaam', 'Kinondoni', 'runumba', NULL, NULL, NULL, '345445345', 'mbodolos@gmail.com', NULL, NULL, 'Mambo Jumanne', '568787698', NULL, 29, '2014-11-06 11:51:17', 'default.png', 'Donor'),
(46, NULL, 'Abdularhim Marijan', NULL, '1990-10-06', 'Male', 'Dar es salaam', 'Kinondoni', 'kigamboni', NULL, NULL, NULL, '678910', 'MarijanAbdul@gmail.com', NULL, NULL, 'Roja', '0989089', NULL, 29, '2014-11-15 10:23:55', 'default.png', 'Donor'),
(47, NULL, 'Shangwe Topha', NULL, '2014-11-07', 'Male', 'Dar es salaam', 'Ilala', 'Malamba Mawili', NULL, NULL, NULL, '4484480718', 'Shangwe@gmail.com', NULL, NULL, 'Marty Mwaky', '3680661470', NULL, 29, '2014-11-18 11:59:05', 'default.png', 'Donor'),
(48, '8080', 'Swalehe', 'Mr', '2007-03-03', 'Female', 'Dar es salaam', 'Kinondoni', 'kawe', 4, '', '0000-00-00', '00990099', 'mbodolos@gmail.com', 'BusinessWoman', '78906', 'Alex', '675777777', 'GPITG', 29, '2014-11-21 12:22:40', 'default.png', ''),
(49, NULL, 'Joseph Malaba', NULL, '1988-09-07', 'Male', 'Dar es salaam', 'Kinondoni', 'mbagala', NULL, NULL, NULL, '78901', 'malaba@gmail.com', NULL, NULL, 'Alex', '657483', NULL, 29, '2014-11-24 11:35:20', 'default.png', 'Donor');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
