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
-- Table structure for table `tbl_hiv_visits`
--

CREATE TABLE IF NOT EXISTS `tbl_hiv_visits` (
  `hiv_vis_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `first_v_id` int(11) unsigned NOT NULL,
  `curr_hiv_status` varchar(30) NOT NULL,
  `did_hetake_prev_test_` varchar(30) NOT NULL,
  `did_pre_test_councel` varchar(30) NOT NULL,
  `did_post_result_councel` varchar(30) NOT NULL,
  `recommended_date_status_review` date NOT NULL,
  `arv_therapy` varchar(40) NOT NULL,
  `arv_type_medication` varchar(100) NOT NULL,
  `dateofprev_test` date NOT NULL,
  `result_ofprev_test` varchar(30) NOT NULL,
  `hiv_declaration` varchar(30) NOT NULL,
  `partiner_or_mother_tested` varchar(30) NOT NULL,
  `feeding_info` varchar(25) NOT NULL,
  `comments` varchar(254) NOT NULL,
  `muda_huu` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`hiv_vis_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `tbl_hiv_visits`
--

INSERT INTO `tbl_hiv_visits` (`hiv_vis_id`, `first_v_id`, `curr_hiv_status`, `did_hetake_prev_test_`, `did_pre_test_councel`, `did_post_result_councel`, `recommended_date_status_review`, `arv_therapy`, `arv_type_medication`, `dateofprev_test`, `result_ofprev_test`, `hiv_declaration`, `partiner_or_mother_tested`, `feeding_info`, `comments`, `muda_huu`) VALUES
(2, 4, 'Postive', 'Yes', 'Yes', 'No', '2015-04-08', 'Yes', 'ART', '2015-04-15', 'Negative', 'Yes', 'No', 'No', 'unknown', '2015-04-28 09:18:37'),
(3, 4, 'Postive', 'Yes', 'Does Not Apply', 'Does Not Apply', '2015-04-15', 'Yes', 'ARV Propholaxis', '2015-04-25', 'Negative', 'No', 'No', 'Unknown', 'unknown', '2015-04-28 13:48:38'),
(4, 5, 'Negative', 'Yes', 'No', 'Yes', '2015-04-09', 'Does Not Apply', 'ARV Propholaxis', '2015-04-15', 'Negative', 'Yes', 'No', 'No', 'unknown', '2015-04-28 14:00:14'),
(5, 5, 'Negative', 'No', 'Does Not Apply', 'Yes', '2015-04-23', 'Does Not Apply', 'ARV Propholaxis', '2015-04-23', 'Negative', 'Does Not Apply', 'No', 'Yes', 'unknown', '2015-04-29 07:24:35'),
(6, 6, 'Postive', 'Yes', 'No', 'No', '2015-04-07', 'Yes', 'ARV Propholaxis', '2015-04-06', 'Negative', 'Yes', 'No', 'Unknown', 'unknown', '2015-04-29 15:37:52'),
(7, 7, 'Postive', 'No', 'No', 'No', '2015-04-01', 'No', 'ARV Propholaxis', '2015-04-06', 'Unknown', 'No', 'Yes', 'No', 'unknown', '2015-04-29 15:59:12');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
