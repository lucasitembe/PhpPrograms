-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2015 at 07:26 PM
-- Server version: 5.6.25-log
-- PHP Version: 5.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gpitg_wsdlapi`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bank_transaction_cache`
--

CREATE TABLE IF NOT EXISTS `tbl_bank_transaction_cache` (
  `Transaction_ID` int(11) NOT NULL,
  `P_Name` varchar(200) NOT NULL,
  `Registration_ID` int(11) NOT NULL,
  `Payment_Code` varchar(100) DEFAULT NULL,
  `Amount_Required` int(11) NOT NULL,
  `Employee_ID` int(11) DEFAULT NULL,
  `Transaction_Date_Time` datetime NOT NULL,
  `Transaction_Date` date NOT NULL,
  `Transaction_Status` varchar(20) NOT NULL DEFAULT 'pending',
  `Process_Status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_bank_transaction_cache`
--

INSERT INTO `tbl_bank_transaction_cache` (`Transaction_ID`, `P_Name`, `Registration_ID`, `Payment_Code`, `Amount_Required`, `Employee_ID`, `Transaction_Date_Time`, `Transaction_Date`, `Transaction_Status`, `Process_Status`) VALUES
(86, 'MWANAHAMISI HAMADI', 63263, '010000103', 8000, 34, '2015-11-18 16:04:32', '2015-11-18', 'pending', 'pending'),
(87, 'INNOCENSIA CLEMENT', 63184, '010000113', 1000, 34, '2015-11-18 16:18:40', '2015-11-18', 'pending', 'pending'),
(88, 'Charles Joseph tura', 63256, '010000183', 28000, 34, '2015-11-18 17:39:28', '2015-11-18', 'pending', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_patient_registration`
--

CREATE TABLE IF NOT EXISTS `tbl_patient_registration` (
  `Registration_ID` int(11) NOT NULL,
  `Patient_Name` varchar(200) NOT NULL,
  `Date_Of_Birth` date NOT NULL,
  `Gender` varchar(7) NOT NULL,
  `Phone_Number` varchar(30) DEFAULT NULL,
  `Registration_Date_And_Time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payments`
--

CREATE TABLE IF NOT EXISTS `tbl_payments` (
  `Payment_ID` int(11) NOT NULL,
  `Patient_Name` varchar(120) CHARACTER SET utf8 DEFAULT NULL,
  `Registration_ID` int(11) NOT NULL,
  `Amount_Paid` int(11) NOT NULL,
  `Payment_Code` varchar(20) CHARACTER SET utf8 NOT NULL,
  `Payment_Receipt` varchar(20) CHARACTER SET utf8 NOT NULL,
  `Transaction_Ref` varchar(150) CHARACTER SET utf8 NOT NULL,
  `Transaction_Date` datetime NOT NULL,
  `Process_Status` varchar(20) CHARACTER SET utf8 NOT NULL DEFAULT 'Completed',
  `Status_Code` int(11) NOT NULL DEFAULT '200' COMMENT '200=pending, 300=Details Received'
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_payments`
--

INSERT INTO `tbl_payments` (`Payment_ID`, `Patient_Name`, `Registration_ID`, `Amount_Paid`, `Payment_Code`, `Payment_Receipt`, `Transaction_Ref`, `Transaction_Date`, `Process_Status`, `Status_Code`) VALUES
(19, 'MWANAHAMISI HAMADI', 63263, 20000, '010000103', '1653451094', 'FT15001447922408', '2015-11-19 11:40:11', 'Completed', 200);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_process_logs`
--

CREATE TABLE IF NOT EXISTS `tbl_process_logs` (
  `Log_ID` int(11) NOT NULL,
  `Patient_Name` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `Registration_ID` int(11) NOT NULL,
  `Amount_Paid` int(11) NOT NULL,
  `Invoice_Number` varchar(150) CHARACTER SET utf8 DEFAULT NULL,
  `Log_Status` varchar(100) CHARACTER SET utf8 NOT NULL,
  `transfer_Ref` varchar(150) CHARACTER SET utf8 DEFAULT NULL,
  `transfer_Date` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_bank_transaction_cache`
--
ALTER TABLE `tbl_bank_transaction_cache`
  ADD PRIMARY KEY (`Transaction_ID`),
  ADD KEY `Registration_ID` (`Registration_ID`),
  ADD KEY `Employee_ID` (`Employee_ID`);

--
-- Indexes for table `tbl_patient_registration`
--
ALTER TABLE `tbl_patient_registration`
  ADD PRIMARY KEY (`Registration_ID`);

--
-- Indexes for table `tbl_payments`
--
ALTER TABLE `tbl_payments`
  ADD PRIMARY KEY (`Payment_ID`),
  ADD KEY `Payment_Code` (`Payment_Code`);

--
-- Indexes for table `tbl_process_logs`
--
ALTER TABLE `tbl_process_logs`
  ADD PRIMARY KEY (`Log_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_bank_transaction_cache`
--
ALTER TABLE `tbl_bank_transaction_cache`
  MODIFY `Transaction_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=89;
--
-- AUTO_INCREMENT for table `tbl_payments`
--
ALTER TABLE `tbl_payments`
  MODIFY `Payment_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `tbl_process_logs`
--
ALTER TABLE `tbl_process_logs`
  MODIFY `Log_ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=63;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
