-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2014 at 04:41 PM
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
-- Table structure for table `tbl_patient_blood_data`
--

CREATE TABLE IF NOT EXISTS `tbl_patient_blood_data` (
  `Blood_ID` int(12) NOT NULL AUTO_INCREMENT,
  `Donor_ID` int(12) NOT NULL,
  `Blood_Group` varchar(10) NOT NULL,
  `Blood_Batch` varchar(20) NOT NULL,
  `Blood_Runner` varchar(150) NOT NULL,
  `Blood_Volume` int(45) NOT NULL,
  `Status` varchar(23) NOT NULL,
  `Date_Of_Transfusion` date NOT NULL,
  `Blood_Expire_Date` date NOT NULL,
  `Transfusion_Date_Time` datetime NOT NULL,
  `Employee_ID` int(12) NOT NULL,
  PRIMARY KEY (`Blood_ID`),
  KEY `Donor_ID` (`Donor_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `tbl_patient_blood_data`
--

INSERT INTO `tbl_patient_blood_data` (`Blood_ID`, `Donor_ID`, `Blood_Group`, `Blood_Batch`, `Blood_Runner`, `Blood_Volume`, `Status`, `Date_Of_Transfusion`, `Blood_Expire_Date`, `Transfusion_Date_Time`, `Employee_ID`) VALUES
(8, 34, 'AB-', 'Batch Three', '', 56, '', '2014-10-01', '2014-10-08', '2014-10-20 10:53:40', 0),
(9, 34, 'AB-', 'Batch Three', '', 25, '', '2014-10-02', '2014-10-05', '2014-10-20 10:54:35', 0),
(11, 33, 'O-', 'Batch Two', '', 45, '', '2014-10-01', '2014-10-20', '2014-10-23 10:40:25', 0),
(12, 35, 'O-', 'Batch Three', '', 45, '', '2014-10-01', '2014-11-01', '2014-10-28 02:28:22', 0),
(13, 42, 'O+', 'Batch One', 'Mambo', 34, '', '2014-10-01', '2014-10-29', '2014-10-28 14:40:02', 0),
(14, 34, 'A+', 'Batch One', 'Adrian', 32, '', '2014-03-04', '2014-10-08', '2014-10-28 15:00:40', 0),
(15, 33, 'B+', 'Batch Three', '', 0, '', '2014-10-05', '2014-10-20', '2014-10-29 10:42:53', 0),
(17, 33, 'A+', 'Batch Three', 'rty', 7, '', '2014-11-02', '2014-11-05', '2014-11-06 13:41:08', 0),
(18, 46, 'A+', 'Batch Three', '', 23, '', '2014-11-15', '2014-12-15', '2014-11-15 10:28:36', 0),
(19, 47, 'A+', 'Batch Three', 'Zuhery', 34, '', '2014-11-07', '2014-11-14', '2014-11-18 12:06:10', 0),
(21, 46, 'A-', 'Batch Four', '', 0, '', '2014-11-03', '2014-11-06', '2014-11-21 13:59:12', 0),
(22, 39, 'B+', 'Batch Three', 'ghgbnb', 23, '', '2014-11-09', '2014-11-26', '2014-11-24 10:11:28', 29),
(23, 49, 'AB', 'Batch One', 'Martha', 32, '', '2014-10-02', '2014-11-02', '2014-11-24 11:37:51', 29);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_patient_blood_data`
--
ALTER TABLE `tbl_patient_blood_data`
  ADD CONSTRAINT `tbl_patient_blood_data_ibfk_1` FOREIGN KEY (`Donor_ID`) REFERENCES `tbl_patient_registration` (`Registration_ID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
