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
-- Table structure for table `tbl_blood_checked`
--

CREATE TABLE IF NOT EXISTS `tbl_blood_checked` (
  `Blood_Checked_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Blood_Group` varchar(10) NOT NULL,
  `Blood_Batch` varchar(20) NOT NULL,
  `Blood_ID` int(100) NOT NULL,
  `BloodRecorded` int(11) NOT NULL,
  `Blood_Status` varchar(50) NOT NULL,
  `Employee_ID` int(12) NOT NULL,
  `Patient_Given` varchar(150) NOT NULL,
  `Reason` varchar(500) NOT NULL,
  `Date_Taken` date NOT NULL,
  `Registered_Date_And_Time` datetime NOT NULL,
  PRIMARY KEY (`Blood_Checked_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

--
-- Dumping data for table `tbl_blood_checked`
--

INSERT INTO `tbl_blood_checked` (`Blood_Checked_ID`, `Blood_Group`, `Blood_Batch`, `Blood_ID`, `BloodRecorded`, `Blood_Status`, `Employee_ID`, `Patient_Given`, `Reason`, `Date_Taken`, `Registered_Date_And_Time`) VALUES
(5, 'O+', 'Batch Two', 0, 23, 'USED', 0, 'Kelvin Fussi', 'Accident', '2014-10-02', '2014-10-24 11:43:01'),
(6, 'O+', 'Batch Two', 0, 67, 'USED', 0, 'Namimi Nipo Hapa', 'Transfusion', '2014-10-02', '2014-10-24 11:50:42'),
(7, 'B+', 'Batch Three', 0, 67, 'USED', 0, 'Andrew Kajuna', 'Transfusion', '2014-10-02', '2014-10-24 11:52:49'),
(8, 'A+', 'Batch Two', 0, 89, 'USED', 0, 'John Joseph', 't57r8jkfhij', '2014-01-01', '2014-10-24 11:55:28'),
(9, 'B+', 'Batch Three', 0, 9, 'USED', 0, 'John Joseph', 'efyhtrgfjh', '2014-10-02', '2014-10-27 10:01:23'),
(10, 'B+', 'Batch Three', 0, 45, 'USED', 0, 'John Joseph', 'diarrhoea', '2014-10-03', '2014-10-27 10:16:03'),
(11, 'O-', 'Batch Three', 0, 8, 'USED', 0, 'Namimi Nipo Hapa', '', '2014-10-20', '2014-10-28 09:43:45'),
(12, 'AB-', 'Batch Two', 0, 16, 'DISPOSED', 0, '', '', '2014-10-06', '2014-10-28 15:23:48'),
(13, 'O+', 'Batch Two', 0, 12, 'DISPOSED', 0, '', 'Expired', '2014-07-02', '2014-11-06 12:23:31'),
(14, 'A+', 'Batch Three', 0, 12, 'USED', 0, 'Aisha Kajuna', 'Accident', '2014-11-16', '2014-11-15 10:38:38'),
(16, 'O-', 'Batch Two', 0, 8, 'USED', 0, 'tgfthfh hjfjy', 'Pregnancy', '2014-11-09', '2014-11-17 11:58:04'),
(17, 'A+', 'Batch Three', 0, 13, 'DISPOSED', 0, '', 'Expired', '2014-11-01', '2014-11-18 12:40:47'),
(18, 'B+', 'Batch Three', 0, 13, 'DISPOSED', 0, '', '', '2014-11-03', '2014-11-18 13:12:26'),
(19, 'B-', 'Batch Three', 0, 10, 'DISPOSED', 0, '', '', '2014-11-16', '2014-11-18 13:14:40'),
(20, 'A+', 'Batch Three', 0, 9, 'DISPOSED', 0, '', '', '2014-11-30', '2014-11-18 13:23:20'),
(21, 'A+', 'Batch Three', 0, 2, 'USED', 0, 'John Joseph', 'Accident', '2014-11-10', '2014-11-18 14:31:56'),
(22, 'A+', 'Batch Three', 0, 3, 'USED', 0, 'John Joseph', 'dgfhfg', '2014-11-02', '2014-11-19 10:17:16'),
(23, 'A+', 'Batch Three', 0, 4, 'DISPOSED', 0, '', 'Accident', '2014-10-02', '2014-11-19 11:17:59'),
(26, 'AB-', 'Batch Two', 9, 4, 'DISPOSED', 0, '', 'ftdgf', '2014-11-02', '2014-11-22 12:42:50'),
(27, 'B+', 'Batch Two', 16, 6, 'USED', 29, 'John Joseph', 'trouble', '2014-11-17', '2014-11-24 09:47:07'),
(28, 'AB-', 'Batch Three', 7, 5, 'USED', 29, 'Swalehe', 'uyutre', '2014-10-02', '2014-11-24 09:48:09'),
(29, 'AB-', 'Batch Three', 10, 21, 'DISPOSED', 29, '', 'kugftuivy', '2014-08-06', '2014-11-24 09:52:13');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
