-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 01, 2015 at 10:20 AM
-- Server version: 5.5.36
-- PHP Version: 5.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ehms_xml`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hiv_first_visit`
--

CREATE TABLE IF NOT EXISTS `tbl_hiv_first_visit` (
  `hiv_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pr_r` int(11) NOT NULL,
  `yearslong` int(2) NOT NULL,
  `monthlong` int(2) NOT NULL,
  `daylong` int(2) NOT NULL,
  `partiner_name` varchar(30) NOT NULL,
  `mother_name` varchar(30) NOT NULL,
  `muda` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  PRIMARY KEY (`hiv_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `tbl_hiv_first_visit`
--

INSERT INTO `tbl_hiv_first_visit` (`hiv_id`, `pr_r`, `yearslong`, `monthlong`, `daylong`, `partiner_name`, `mother_name`, `muda`, `status`) VALUES
(4, 44, 0, 0, 0, 'unknown', 'unknown', '2015-04-28 09:18:37', 'active'),
(5, 266, 0, 0, 0, 'unknown', 'unknown', '2015-04-28 14:00:14', 'active'),
(6, 270, 0, 0, 0, 'unknown', 'unknown', '2015-04-29 15:37:52', 'active'),
(7, 262, 0, 0, 0, 'unknown', 'unknown', '2015-04-29 15:59:12', 'active');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
