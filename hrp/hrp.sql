-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 01, 2015 at 03:52 PM
-- Server version: 5.6.21
-- PHP Version: 5.5.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hrp`
--

-- --------------------------------------------------------

--
-- Table structure for table `application`
--

CREATE TABLE IF NOT EXISTS `application` (
`id` int(11) NOT NULL,
  `vacancy_id` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `MiddleName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Sex` varchar(1) NOT NULL,
  `MaritalStatus` int(11) NOT NULL,
  `EducationLevel` int(11) NOT NULL,
  `dob` date NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Mobile` varchar(50) NOT NULL,
  `CV` varchar(200) NOT NULL,
  `Letter` varchar(100) NOT NULL,
  `postdate` date NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0= New, 1= Called for Interview, 2= Attend Interview, 4= Accept, 5 = Reject'
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `application`
--

INSERT INTO `application` (`id`, `vacancy_id`, `FirstName`, `MiddleName`, `LastName`, `Sex`, `MaritalStatus`, `EducationLevel`, `dob`, `Email`, `Mobile`, `CV`, `Letter`, `postdate`, `status`) VALUES
(1, 2, 'Miltone', '', 'Urassa', 'M', 1, 4, '2013-05-01', 'miltoneurassa@yahoo.com', '+255 712 765538', '1368562931new.txt', '1368562931new.txt', '2013-05-14', 1),
(2, 2, 'Juma', '', 'Athuman', 'M', 1, 4, '2003-05-07', 'juma@yahoo.com', '+255 0712 765538', '1368585579old.txt', '1368585579new.txt', '2013-05-15', 3),
(3, 2, 'Anna', '', 'Athuman', 'F', 2, 1, '2013-06-04', '', '0712000020', '1371049238contact_page.txt', '1371049238contact_page.txt', '2013-06-12', 1),
(4, 2, 'Apply', '', 'Test', 'M', 1, 1, '2015-05-04', '', '', '1432370462Legalmate.txt', '1432370462Legalmate.txt', '2015-05-23', 0),
(5, 4, 'Kevin', '', 'Fussi', 'M', 1, 4, '2015-05-18', '', '', '14323814618877-ApplicationDevelopment_PlanTemplate (2).doc', '14323814618877-ApplicationDevelopment_PlanTemplate.doc', '2015-05-23', 3),
(6, 5, 'Evans', '', 'Gpitg', 'M', 1, 4, '2015-05-11', '', '', '1432402442GPITG - THE COMPANY.docx', '1432402442eHMS TIPS FOR DOCTORS.doc', '2015-05-23', 3),
(7, 6, 'Dorah', '', 'Emil Kiliba', 'F', 2, 3, '2015-05-18', '', '', '1432445981HMS Brochure.doc', '1432445981HMS Brochure.doc', '2015-05-24', 1),
(8, 7, 'Imma', '', 'Ngega', 'M', 1, 2, '2015-05-11', '', '', '14328164641-Improve Your Skills and Managing Projects.docx', '14328164641-Improve Your Skills and Managing Projects.docx', '2015-05-28', 0),
(9, 8, 'Mzeru', '', 'Gpitg', 'M', 2, 3, '1990-05-28', '', '', '14329037931-Improve Your Skills and Managing Projects.docx', '14329037931-Improve Your Skills and Managing Projects.docx', '2015-05-29', 2);

-- --------------------------------------------------------

--
-- Table structure for table `attachment`
--

CREATE TABLE IF NOT EXISTS `attachment` (
`id` int(11) NOT NULL,
  `Employee` int(11) NOT NULL,
  `Comment` text NOT NULL,
  `Attachment` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE IF NOT EXISTS `company` (
`id` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `LandLine` varchar(100) NOT NULL,
  `Fax` varchar(100) NOT NULL,
  `Mobile` varchar(100) NOT NULL,
  `Website` varchar(100) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id`, `Name`, `Email`, `LandLine`, `Fax`, `Mobile`, `Website`) VALUES
(1, 'KAIRUKI HOSPITAL', 'info@ictsolutionsdesign.com', '', '', '', 'http://ictsolutionsdesign.com');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE IF NOT EXISTS `contact` (
`id` int(11) NOT NULL,
  `Employee` int(11) NOT NULL,
  `Street` varchar(300) NOT NULL,
  `Postal` varchar(300) NOT NULL,
  `Region` int(11) NOT NULL,
  `District` int(11) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `LandLine` varchar(100) NOT NULL,
  `Mobile` varchar(100) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`id`, `Employee`, `Street`, `Postal`, `Region`, `District`, `Email`, `LandLine`, `Mobile`) VALUES
(1, 1, 'Wazo', '123', 4, 12, 'miltoneurassa@yahoo.com', '', '0712 76 55 38'),
(2, 2, 'Mwenge', '123', 4, 13, '', '', '0712 xxxxxx'),
(3, 3, 'Mbagala', '345', 4, 14, '', '', ''),
(4, 4, 'Mikochen', '3452', 4, 13, 'test@test.com', '', '0712 xxxxxx'),
(5, 5, 'Magomen', '33454', 4, 13, 'test@yahoo.com', '', '0712000020'),
(6, 6, 'Posta', '', 0, 0, '', '', '0712 xxxxxx'),
(7, 7, 'Kigambon', '', 0, 0, '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `contracttype`
--

CREATE TABLE IF NOT EXISTS `contracttype` (
`id` int(11) NOT NULL,
  `Name` varchar(200) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contracttype`
--

INSERT INTO `contracttype` (`id`, `Name`) VALUES
(1, 'Full Time'),
(2, 'Part Time');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE IF NOT EXISTS `department` (
`id` int(11) NOT NULL,
  `Name` varchar(400) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `Name`) VALUES
(1, 'Accounting'),
(2, 'Pharmacy'),
(3, 'Theatre'),
(4, 'Laboratory'),
(5, 'Radiology'),
(6, 'Reception'),
(7, 'Ward'),
(8, 'Nursing'),
(9, 'New Department');

-- --------------------------------------------------------

--
-- Table structure for table `dependent`
--

CREATE TABLE IF NOT EXISTS `dependent` (
`id` int(11) NOT NULL,
  `Employee` int(11) NOT NULL,
  `Name` varchar(300) NOT NULL,
  `Relation` varchar(100) NOT NULL,
  `dob` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `discipline`
--

CREATE TABLE IF NOT EXISTS `discipline` (
`id` int(11) NOT NULL,
  `Employee` varchar(50) NOT NULL,
  `Violation` varchar(200) NOT NULL,
  `ViolationDate` date NOT NULL,
  `EmployeeStatement` text NOT NULL,
  `EmployerStatement` text NOT NULL,
  `Attachment` varchar(300) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `discipline`
--

INSERT INTO `discipline` (`id`, `Employee`, `Violation`, `ViolationDate`, `EmployeeStatement`, `EmployerStatement`, `Attachment`) VALUES
(1, '1', 'violation', '2015-05-11', '  mvfnldbnfdnlb;bn; .v. zn n', 'kfglglag;rb;jeeal', '1432445706eHMS Specifications.docx');

-- --------------------------------------------------------

--
-- Table structure for table `education`
--

CREATE TABLE IF NOT EXISTS `education` (
`id` int(11) NOT NULL,
  `Name` varchar(200) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `education`
--

INSERT INTO `education` (`id`, `Name`) VALUES
(1, 'Diploma'),
(2, 'Certificate'),
(3, 'Masters'),
(4, 'Bachelor');

-- --------------------------------------------------------

--
-- Table structure for table `emergencycontact`
--

CREATE TABLE IF NOT EXISTS `emergencycontact` (
`id` int(11) NOT NULL,
  `Employee` int(11) NOT NULL,
  `Name_1` varchar(200) NOT NULL,
  `Relation_1` varchar(100) NOT NULL,
  `LandLine_1` varchar(100) NOT NULL,
  `Mobile_1` varchar(100) NOT NULL,
  `Name_2` varchar(200) NOT NULL,
  `Relation_2` varchar(100) NOT NULL,
  `LandLine_2` varchar(100) NOT NULL,
  `Mobile_2` varchar(100) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `emergencycontact`
--

INSERT INTO `emergencycontact` (`id`, `Employee`, `Name_1`, `Relation_1`, `LandLine_1`, `Mobile_1`, `Name_2`, `Relation_2`, `LandLine_2`, `Mobile_2`) VALUES
(1, 1, 'Sister', 'Sister', '', '0712000020', 'Brother', 'Brother', '', '0712000000'),
(2, 54, 'Dorah Emil Kiliba', 'Mother', '255653302114', '', 'Adelard Kiliba', 'Father', '255717531539', '255717531539');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE IF NOT EXISTS `employee` (
`id` int(11) NOT NULL,
  `EmployeeId` varchar(200) NOT NULL,
  `FirstName` varchar(200) NOT NULL,
  `MiddleName` varchar(200) NOT NULL,
  `LastName` varchar(200) NOT NULL,
  `Sex` varchar(20) NOT NULL,
  `dob` date NOT NULL,
  `MaritalStatus` int(11) NOT NULL,
  `photo` varchar(300) NOT NULL,
  `Region` int(11) NOT NULL,
  `District` int(11) NOT NULL,
  `Religion` int(11) NOT NULL,
  `EducationLevel` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=58 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `EmployeeId`, `FirstName`, `MiddleName`, `LastName`, `Sex`, `dob`, `MaritalStatus`, `photo`, `Region`, `District`, `Religion`, `EducationLevel`) VALUES
(1, 'HRP/001', 'Miltone', '', 'Urassa', 'M', '1980-06-04', 1, '1432402218adek estrategies.png', 4, 13, 1, 4),
(2, 'HRP/002', 'Sophia', '', 'Kimario', 'F', '1984-06-04', 2, '', 0, 6, 1, 3),
(3, 'HRP/003', 'Juma', '', 'Athuman', 'M', '1975-03-23', 3, '', 0, 39, 2, 3),
(4, 'HRP/004', 'Anna', '', 'John', 'F', '1986-03-04', 1, '', 0, 12, 1, 1),
(5, 'HRP/005', 'Leonard', '', 'John', 'M', '1984-06-04', 1, '', 0, 61, 0, 1),
(6, 'HRP/006', 'Hellena', '', 'Jackson', 'F', '1990-04-20', 1, '', 0, 76, 0, 2),
(7, 'HRP/007', 'Jacob', '', 'Steven', 'M', '1974-03-23', 2, '', 0, 5, 0, 3),
(8, 'HRP/008', 'Steven', '', 'Leonard', 'M', '1983-03-04', 2, '', 0, 108, 0, 1),
(9, 'HRP/009', 'Pongeza', '', 'Kivaria', 'M', '1982-03-23', 2, '', 0, 32, 2, 4),
(10, 'HRP/010', 'Monica', '', 'Nzunda', 'F', '1987-03-23', 1, '', 0, 84, 1, 4),
(44, 'HRP/025', 'Pongeza', '', 'Kimario', 'M', '1967-03-29', 0, '', 0, 32, 1, 4),
(43, 'HRP/024', 'Anna', '', 'Urassa', 'F', '1977-04-08', 0, '', 0, 12, 1, 1),
(42, 'HRP/023', 'Jacob', '', 'Kimario', 'M', '1983-08-02', 0, '', 0, 5, 1, 3),
(41, 'HRP/022', 'Miltone', '', 'Jackson', 'M', '1987-05-14', 0, '', 0, 5, 0, 4),
(40, 'HRP/021', 'Jacob', '', 'Kimario', 'M', '1968-12-10', 0, '', 0, 5, 1, 3),
(39, 'HRP/020', 'Sophia', '', 'John', 'F', '1973-01-28', 0, '', 0, 6, 1, 3),
(38, 'HRP/019', 'Pongeza', '', 'Urassa', 'M', '1979-11-11', 0, '', 0, 32, 1, 4),
(37, 'HRP/018', 'Juma', '', 'Athuman', 'M', '1971-12-22', 0, '', 0, 39, 2, 3),
(36, 'HRP/017', 'Pongeza', '', 'Athuman', 'M', '1964-06-13', 0, '', 0, 32, 2, 4),
(35, 'HRP/016', 'Jacob', '', 'Steven', 'M', '1972-12-11', 0, '', 0, 5, 0, 3),
(34, 'HRP/015', 'Miltone', '', 'Kimario', 'M', '1987-05-07', 0, '', 0, 5, 1, 4),
(33, 'HRP/014', 'Juma', '', 'Steven', 'M', '1981-07-09', 0, '', 0, 39, 0, 3),
(32, 'HRP/013', 'Miltone', '', 'Steven', 'M', '1978-03-19', 0, '', 0, 5, 0, 4),
(31, 'HRP/012', 'Miltone', '', 'Nzunda', 'M', '1976-08-01', 0, '', 0, 5, 1, 4),
(30, 'HRP/011', 'Pongeza', '', 'Urassa', 'M', '1989-04-01', 0, '', 0, 32, 1, 4),
(45, 'HRP/026', 'Leonard', '', 'John', 'M', '1978-10-09', 0, '', 0, 61, 0, 1),
(46, 'HRP/027', 'Leonard', '', 'Jackson', 'M', '1964-05-09', 0, '', 0, 61, 0, 1),
(47, 'HRP/028', 'Juma', '', 'Jackson', 'M', '1967-02-07', 0, '', 0, 39, 0, 3),
(48, 'HRP/029', 'Miltone', '', 'Kimario', 'M', '1982-10-08', 0, '', 0, 5, 1, 4),
(49, 'HRP/500', 'Test', '', 'Test2', 'M', '1999-06-01', 1, '137104817145.jpg', 1, 7, 1, 1),
(50, 'HRP/600', 'Juma', '', 'Athuman', 'M', '2013-06-05', 1, '', 0, 5, 1, 1),
(51, 'HR/0095', 'Test', '', 'Lastname', 'M', '2015-05-12', 2, '', 0, 7, 0, 1),
(52, 'TEST/001', 'Test', '', 'Xx', 'M', '2015-05-05', 1, '', 0, 42, 0, 1),
(53, 'TEST/003', 'Test', '', 'Kesho', 'M', '2015-05-05', 2, '', 0, 42, 0, 1),
(54, 'ADGPITG2015', 'Adaliah', '', 'Kiliba', 'F', '2014-12-10', 1, '1432380207IMG-20150410-WA0001.jpg', 0, 13, 0, 1),
(55, 'CecyGPITG2015', 'Cecyana', '', 'Kiliba', 'M', '2015-05-18', 1, '1432393020cecy 2.jpg', 0, 13, 0, 3),
(56, '00', 'Evans', '', 'Gpitg2', 'M', '1998-05-18', 1, '', 4, 13, 0, 4),
(57, 'KH2015Male', 'Adelard', '', 'Nsaho', 'M', '2015-05-11', 2, '1432446739brandon kiliba 1.jpg', 4, 13, 0, 3);

-- --------------------------------------------------------

--
-- Stand-in structure for view `employee_view`
--
CREATE TABLE IF NOT EXISTS `employee_view` (
`id` int(11)
,`EmployeeId` varchar(200)
,`FirstName` varchar(200)
,`MiddleName` varchar(200)
,`LastName` varchar(200)
,`Sex` varchar(20)
,`dob` date
,`EducationLevel` int(11)
,`Religion` int(11)
,`MaritalStatus` int(11)
,`Department` bigint(11)
,`Position` bigint(11)
,`WorkStation` bigint(11)
,`ContractType` int(11)
,`Enddate` date
,`Amount` double
,`SalaryGrade` int(11)
,`Retere` int(0)
,`Age` int(9)
,`Street` varchar(300)
,`Postal` varchar(300)
,`Email` varchar(100)
,`Mobile` varchar(100)
,`District` int(11)
,`Region` int(11)
);
-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
`id` mediumint(8) unsigned NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrator'),
(2, 'members', 'General User'),
(3, 'account', ''),
(4, 'Head Department', 'Head Department'),
(5, 'Normal Employee', ''),
(6, 'HR Manager', 'HR Manager');

-- --------------------------------------------------------

--
-- Table structure for table `job`
--

CREATE TABLE IF NOT EXISTS `job` (
`id` int(11) NOT NULL,
  `Employee` int(11) NOT NULL,
  `Department` int(11) NOT NULL,
  `Position` int(11) NOT NULL,
  `WorkStation` int(11) NOT NULL,
  `ContractType` int(11) NOT NULL,
  `Joindate` date NOT NULL,
  `Startdate` date NOT NULL,
  `Enddate` date NOT NULL,
  `Contract` varchar(200) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `job`
--

INSERT INTO `job` (`id`, `Employee`, `Department`, `Position`, `WorkStation`, `ContractType`, `Joindate`, `Startdate`, `Enddate`, `Contract`) VALUES
(1, 1, 3, 7, 1, 1, '2000-05-01', '2000-05-01', '2019-05-01', ''),
(2, 2, 7, 4, 1, 1, '2005-03-01', '2005-03-01', '2020-03-01', ''),
(3, 3, 3, 3, 1, 1, '2008-05-01', '2008-05-01', '2038-05-01', ''),
(4, 4, 3, 5, 1, 2, '2010-07-20', '2010-07-20', '2018-07-20', ''),
(5, 5, 3, 3, 1, 1, '2005-03-01', '2005-03-01', '2019-05-01', ''),
(6, 6, 4, 5, 1, 1, '2000-05-01', '2000-05-01', '2018-07-20', ''),
(7, 7, 2, 1, 1, 1, '2008-05-01', '2008-05-01', '2023-03-30', ''),
(8, 8, 5, 3, 1, 1, '2005-03-01', '2005-03-01', '2017-06-17', ''),
(9, 9, 1, 2, 1, 1, '2000-05-01', '2000-05-01', '2014-05-01', ''),
(10, 10, 2, 3, 1, 1, '2008-05-01', '2008-05-01', '2016-06-15', ''),
(11, 44, 4, 4, 1, 1, '2005-03-01', '2005-03-01', '2017-06-17', ''),
(12, 43, 1, 3, 1, 2, '2008-05-01', '2008-05-01', '2023-03-30', ''),
(13, 42, 3, 4, 1, 1, '2005-03-01', '2005-03-01', '2019-05-01', ''),
(14, 41, 7, 4, 1, 2, '2000-05-01', '2000-05-01', '2018-07-20', ''),
(15, 40, 2, 4, 1, 2, '2008-05-01', '2008-05-01', '2023-03-30', ''),
(16, 39, 6, 3, 1, 1, '2008-05-01', '2008-05-01', '2016-06-15', ''),
(17, 38, 2, 1, 1, 1, '2008-05-01', '2008-05-01', '2038-05-01', ''),
(18, 37, 1, 1, 1, 2, '2008-05-01', '2008-05-01', '2023-03-30', ''),
(19, 36, 2, 4, 1, 2, '2008-05-01', '2008-05-01', '2023-03-30', ''),
(20, 35, 6, 3, 1, 2, '2005-03-01', '2005-03-01', '2019-05-01', ''),
(21, 34, 6, 3, 1, 2, '2008-05-01', '2008-05-01', '2016-06-15', ''),
(22, 33, 5, 1, 1, 2, '2005-03-01', '2005-03-01', '2017-06-17', ''),
(23, 32, 4, 3, 1, 1, '2000-05-01', '2000-05-01', '2018-07-20', ''),
(24, 31, 3, 2, 1, 2, '2005-03-01', '2005-03-01', '2017-06-17', ''),
(25, 30, 4, 1, 1, 2, '2005-03-01', '2005-03-01', '2019-05-01', ''),
(26, 45, 3, 1, 1, 2, '2008-05-01', '2008-05-01', '2023-03-30', ''),
(27, 46, 4, 2, 1, 2, '2008-05-01', '2008-05-01', '2023-03-30', ''),
(28, 47, 7, 1, 1, 1, '2005-03-01', '2005-03-01', '2017-06-17', ''),
(29, 48, 4, 5, 1, 1, '2000-05-01', '2000-05-01', '2014-05-01', ''),
(30, 54, 2, 1, 1, 1, '2015-05-21', '2015-05-22', '2019-05-31', ''),
(31, 56, 1, 1, 1, 1, '2015-05-18', '2015-05-11', '2015-05-28', '');

-- --------------------------------------------------------

--
-- Table structure for table `kpi_category`
--

CREATE TABLE IF NOT EXISTS `kpi_category` (
`id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kpi_category`
--

INSERT INTO `kpi_category` (`id`, `name`) VALUES
(1, 'Manager'),
(2, 'Accountant'),
(3, 'Doctor - GP'),
(4, 'Nurse'),
(5, 'Doctor - Specialist'),
(6, 'Doctor - Super Specialist'),
(7, 'Cashier');

-- --------------------------------------------------------

--
-- Table structure for table `kpi_category_indicator`
--

CREATE TABLE IF NOT EXISTS `kpi_category_indicator` (
`id` int(11) NOT NULL,
  `kpi_category` int(11) NOT NULL,
  `kpi_indicator` int(11) NOT NULL,
  `Active` int(11) NOT NULL DEFAULT '1',
  `createdon` date NOT NULL,
  `createdby` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kpi_category_indicator`
--

INSERT INTO `kpi_category_indicator` (`id`, `kpi_category`, `kpi_indicator`, `Active`, `createdon`, `createdby`) VALUES
(1, 3, 3, 1, '2013-06-18', 7),
(2, 3, 4, 1, '2013-06-18', 7),
(3, 5, 3, 1, '2013-06-18', 7),
(4, 5, 4, 1, '2013-06-18', 7),
(5, 5, 5, 1, '2013-06-18', 7),
(6, 5, 6, 1, '2013-06-18', 7),
(7, 5, 8, 1, '2013-06-18', 7),
(8, 4, 4, 0, '2013-06-18', 7),
(9, 4, 5, 0, '2013-06-18', 7),
(10, 4, 6, 1, '2013-06-18', 7),
(11, 4, 8, 1, '2013-06-18', 7),
(12, 4, 12, 0, '2013-06-18', 7),
(13, 4, 14, 0, '2013-06-18', 7),
(14, 3, 6, 1, '2013-06-18', 7),
(15, 3, 8, 1, '2013-06-18', 7),
(16, 3, 12, 1, '2013-06-18', 7),
(17, 1, 2, 1, '2013-06-19', 7),
(18, 1, 4, 1, '2013-06-19', 7),
(19, 1, 5, 1, '2013-06-19', 7),
(20, 1, 6, 1, '2013-06-19', 7),
(21, 2, 1, 1, '2013-07-03', 7),
(22, 2, 3, 1, '2013-07-03', 7),
(23, 2, 5, 1, '2013-07-03', 7),
(24, 7, 16, 1, '2015-05-29', 13),
(25, 7, 2, 1, '2015-05-29', 13),
(26, 7, 8, 1, '2015-05-29', 13),
(27, 7, 17, 1, '2015-05-29', 13);

-- --------------------------------------------------------

--
-- Table structure for table `kpi_employee`
--

CREATE TABLE IF NOT EXISTS `kpi_employee` (
`id` int(11) NOT NULL,
  `kpi_category` int(11) NOT NULL,
  `kpi_indicator` int(11) NOT NULL,
  `Employee` varchar(50) NOT NULL,
  `employee_auto` int(11) NOT NULL,
  `date_recorded` date NOT NULL,
  `recorded_by` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kpi_indicator`
--

CREATE TABLE IF NOT EXISTS `kpi_indicator` (
`id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kpi_indicator`
--

INSERT INTO `kpi_indicator` (`id`, `name`) VALUES
(1, 'Inpatient mortality rate'),
(2, 'Patient satisfaction'),
(3, 'Patient waiting times'),
(4, 'Number of patients'),
(5, 'Customer Satisfaction'),
(6, 'Registration of the Patients'),
(8, 'Quality'),
(15, 'New Key Indicator'),
(12, 'New Indicator'),
(13, 'Test'),
(14, 'TEST DATA'),
(16, 'Revenue collections'),
(17, 'Check Mzeru');

-- --------------------------------------------------------

--
-- Table structure for table `kpi_record`
--

CREATE TABLE IF NOT EXISTS `kpi_record` (
`id` int(11) NOT NULL,
  `kpi_category` int(11) NOT NULL,
  `kpi_indicator` int(11) NOT NULL,
  `kpi_value` int(11) NOT NULL,
  `Employee` varchar(100) NOT NULL,
  `employee_auto` int(11) NOT NULL,
  `date` date NOT NULL,
  `date_recorded` date NOT NULL,
  `recorded_by` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kpi_record`
--

INSERT INTO `kpi_record` (`id`, `kpi_category`, `kpi_indicator`, `kpi_value`, `Employee`, `employee_auto`, `date`, `date_recorded`, `recorded_by`) VALUES
(1, 3, 3, 2, 'HRP/005', 5, '2013-06-03', '2013-06-19', 7),
(2, 3, 4, 1, 'HRP/005', 5, '2013-06-03', '2013-06-19', 7),
(3, 3, 6, 2, 'HRP/005', 5, '2013-06-03', '2013-06-19', 7),
(4, 3, 8, 3, 'HRP/005', 5, '2013-06-03', '2013-06-19', 7),
(5, 3, 12, 3, 'HRP/005', 5, '2013-06-03', '2013-06-19', 7),
(6, 3, 3, 1, 'HRP/005', 5, '2013-06-19', '2013-06-19', 7),
(7, 3, 4, 1, 'HRP/005', 5, '2013-06-19', '2013-06-19', 7),
(8, 3, 6, 2, 'HRP/005', 5, '2013-06-19', '2013-06-19', 7),
(9, 3, 8, 2, 'HRP/005', 5, '2013-06-19', '2013-06-19', 7),
(10, 3, 12, 2, 'HRP/005', 5, '2013-06-19', '2013-06-19', 7),
(11, 3, 3, 1, 'HRP/005', 5, '2013-06-05', '2013-06-19', 7),
(12, 3, 4, 2, 'HRP/005', 5, '2013-06-05', '2013-06-19', 7),
(13, 3, 6, 2, 'HRP/005', 5, '2013-06-05', '2013-06-19', 7),
(14, 3, 8, 3, 'HRP/005', 5, '2013-06-05', '2013-06-19', 7),
(15, 3, 12, 1, 'HRP/005', 5, '2013-06-05', '2013-06-19', 7),
(16, 2, 1, 1, 'HRP/001', 1, '2015-05-23', '2015-05-23', 12),
(17, 2, 3, 2, 'HRP/001', 1, '2015-05-23', '2015-05-23', 12),
(18, 2, 5, 3, 'HRP/001', 1, '2015-05-23', '2015-05-23', 12),
(19, 2, 1, 2, 'HRP/001', 1, '2015-05-20', '2015-05-23', 12),
(20, 2, 3, 1, 'HRP/001', 1, '2015-05-20', '2015-05-23', 12),
(21, 2, 5, 3, 'HRP/001', 1, '2015-05-20', '2015-05-23', 12),
(22, 2, 1, 2, 'HRP/001', 1, '2015-05-27', '2015-05-23', 13),
(23, 2, 3, 1, 'HRP/001', 1, '2015-05-27', '2015-05-23', 13),
(24, 2, 5, 3, 'HRP/001', 1, '2015-05-27', '2015-05-23', 13),
(25, 1, 2, 1, 'ADGPITG2015', 54, '2015-05-23', '2015-05-23', 13),
(26, 1, 4, 1, 'ADGPITG2015', 54, '2015-05-23', '2015-05-23', 13),
(27, 1, 5, 1, 'ADGPITG2015', 54, '2015-05-23', '2015-05-23', 13),
(28, 1, 6, 1, 'ADGPITG2015', 54, '2015-05-23', '2015-05-23', 13),
(29, 1, 2, 1, '00', 56, '2015-05-23', '2015-05-23', 13),
(30, 1, 4, 1, '00', 56, '2015-05-23', '2015-05-23', 13),
(31, 1, 5, 3, '00', 56, '2015-05-23', '2015-05-23', 13),
(32, 1, 6, 3, '00', 56, '2015-05-23', '2015-05-23', 13),
(33, 2, 1, 1, 'HRP/001', 1, '2015-05-11', '2015-05-24', 13),
(34, 2, 3, 2, 'HRP/001', 1, '2015-05-11', '2015-05-24', 13),
(35, 2, 5, 1, 'HRP/001', 1, '2015-05-11', '2015-05-24', 13),
(36, 2, 1, 1, 'HRP/001', 1, '2015-05-28', '2015-05-28', 13),
(37, 2, 3, 1, 'HRP/001', 1, '2015-05-28', '2015-05-28', 13),
(38, 2, 5, 2, 'HRP/001', 1, '2015-05-28', '2015-05-28', 13),
(39, 7, 16, 1, 'HRP/001', 1, '2015-05-29', '2015-05-29', 13),
(40, 7, 2, 2, 'HRP/001', 1, '2015-05-29', '2015-05-29', 13),
(41, 7, 8, 1, 'HRP/001', 1, '2015-05-29', '2015-05-29', 13),
(42, 7, 17, 4, 'HRP/001', 1, '2015-05-29', '2015-05-29', 13);

-- --------------------------------------------------------

--
-- Table structure for table `kpi_record_employee`
--

CREATE TABLE IF NOT EXISTS `kpi_record_employee` (
`id` int(11) NOT NULL,
  `kpi_category` int(11) NOT NULL,
  `kpi_indicator` int(11) NOT NULL,
  `kpi_value` int(11) NOT NULL,
  `Employee` varchar(100) NOT NULL,
  `employee_auto` int(11) NOT NULL,
  `date` date NOT NULL,
  `date_recorded` date NOT NULL,
  `recorded_by` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kpi_status`
--

CREATE TABLE IF NOT EXISTS `kpi_status` (
`id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `Point` double NOT NULL,
  `Color` varchar(50) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kpi_status`
--

INSERT INTO `kpi_status` (`id`, `name`, `Point`, `Color`) VALUES
(1, 'Good', 5, '#D54C78'),
(2, 'Average', 4, '#126784'),
(3, 'Bad', 3, '#FFA500'),
(4, 'Excellent', 10, '#ffffff');

-- --------------------------------------------------------

--
-- Table structure for table `leaveroster`
--

CREATE TABLE IF NOT EXISTS `leaveroster` (
`id` int(11) NOT NULL,
  `Employee` varchar(100) NOT NULL,
  `LeaveType` int(11) NOT NULL,
  `Fromdate` date NOT NULL,
  `Todate` date NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT '0',
  `is_approved` int(11) NOT NULL DEFAULT '0',
  `Comment` text NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `leaveroster`
--

INSERT INTO `leaveroster` (`id`, `Employee`, `LeaveType`, `Fromdate`, `Todate`, `is_active`, `is_approved`, `Comment`) VALUES
(1, 'HRP/001', 1, '2013-07-01', '2013-07-28', 0, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `leavetype`
--

CREATE TABLE IF NOT EXISTS `leavetype` (
`id` int(11) NOT NULL,
  `Name` varchar(300) NOT NULL,
  `Days` varchar(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `leavetype`
--

INSERT INTO `leavetype` (`id`, `Name`, `Days`) VALUES
(1, 'Normal Leave ( Paid / Unpaid )', '28'),
(2, 'Emergency Leave (Deduct days from Normal Leave)', ''),
(3, 'Maternity', '');

-- --------------------------------------------------------

--
-- Table structure for table `leave_info`
--

CREATE TABLE IF NOT EXISTS `leave_info` (
`id` int(11) NOT NULL,
  `Employee` varchar(100) NOT NULL,
  `LeaveType` int(11) NOT NULL,
  `Fromdate` date NOT NULL,
  `Todate` date NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT '0',
  `is_approved` int(11) NOT NULL DEFAULT '0',
  `Comment` text NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `leave_info`
--

INSERT INTO `leave_info` (`id`, `Employee`, `LeaveType`, `Fromdate`, `Todate`, `is_active`, `is_approved`, `Comment`) VALUES
(1, 'HRP/010', 1, '2013-07-01', '2013-07-31', 1, 1, ''),
(2, 'HRP/005', 2, '2013-06-01', '2013-06-30', 2, 2, ''),
(3, 'HRP/005', 2, '2013-06-01', '2013-06-30', 2, 2, '');

-- --------------------------------------------------------

--
-- Stand-in structure for view `leave_view`
--
CREATE TABLE IF NOT EXISTS `leave_view` (
`id` int(11)
,`Employee` varchar(100)
,`LeaveType` int(11)
,`Fromdate` date
,`Todate` date
,`is_active` int(11)
,`is_approved` int(11)
,`days` int(7)
,`day_remain` int(8)
);
-- --------------------------------------------------------

--
-- Table structure for table `loan`
--

CREATE TABLE IF NOT EXISTS `loan` (
`id` int(11) NOT NULL,
  `Employeeid` int(11) NOT NULL,
  `Employee` varchar(200) NOT NULL,
  `Base_Amount` double NOT NULL,
  `Terms` int(11) NOT NULL,
  `Interest` double NOT NULL,
  `Loan_Amount` double NOT NULL,
  `Installment_Amount` double NOT NULL,
  `Loan_Number` varchar(100) NOT NULL,
  `is_open` int(11) NOT NULL DEFAULT '1',
  `is_approved` int(11) NOT NULL DEFAULT '0',
  `delivery` int(11) NOT NULL DEFAULT '0',
  `Month_D` int(11) NOT NULL DEFAULT '0',
  `Year_D` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `loan`
--

INSERT INTO `loan` (`id`, `Employeeid`, `Employee`, `Base_Amount`, `Terms`, `Interest`, `Loan_Amount`, `Installment_Amount`, `Loan_Number`, `is_open`, `is_approved`, `delivery`, `Month_D`, `Year_D`) VALUES
(1, 1, 'HRP/001', 200000, 5, 0, 200000, 40000, 'LN1', 1, 1, 1, 8, 2013);

-- --------------------------------------------------------

--
-- Stand-in structure for view `loanclose_view`
--
CREATE TABLE IF NOT EXISTS `loanclose_view` (
`id` int(11)
,`Employeeid` int(11)
,`Loan_Number` varchar(100)
,`Employee` varchar(200)
,`Loan_Amount` double
,`Month_D` int(11)
,`Year_D` int(11)
,`Paid` double
,`is_close` int(0)
,`TEST` bigint(12)
);
-- --------------------------------------------------------

--
-- Table structure for table `loan_payment`
--

CREATE TABLE IF NOT EXISTS `loan_payment` (
`id` int(11) NOT NULL,
  `Employeeid` int(11) NOT NULL,
  `Employee` varchar(200) NOT NULL,
  `Loan` varchar(100) NOT NULL,
  `Amount` double NOT NULL,
  `Month_D` int(11) NOT NULL,
  `Year_D` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `loan_payment`
--

INSERT INTO `loan_payment` (`id`, `Employeeid`, `Employee`, `Loan`, `Amount`, `Month_D`, `Year_D`) VALUES
(1, 1, 'HRP/001', 'LN1', 40000, 8, 2013);

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE IF NOT EXISTS `location` (
`id` int(11) NOT NULL,
  `Name` varchar(300) NOT NULL,
  `parent` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=177 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`id`, `Name`, `parent`) VALUES
(1, 'Kilimanjaro', 0),
(2, 'Kigoma', 0),
(3, 'Tanga', 0),
(4, 'Dar es Salaam', 0),
(5, 'Moshi', 1),
(6, 'Kigoma rural', 2),
(7, 'Same', 1),
(8, 'Mwanga', 1),
(9, 'Hai', 1),
(10, 'Siha', 1),
(11, 'Rombo', 1),
(12, 'Ilala', 4),
(13, 'Kinondoni', 4),
(14, 'Temeke', 4),
(15, 'Handeni', 3),
(16, 'Tabora', 0),
(17, 'Igunga', 16),
(18, 'Mwanza', 0),
(19, 'Machame', 1),
(20, 'Mara', 0),
(21, 'Tarime', 20),
(22, 'Bunda', 20),
(23, 'serengeti', 20),
(24, 'Rorya', 20),
(25, 'Butiama', 20),
(26, 'Musoma urban', 20),
(27, 'Musoma rural', 20),
(28, 'Kigoma urban', 2),
(29, 'Kibondo', 2),
(30, 'Kasulu', 2),
(31, 'Lindi', 0),
(32, 'Lindi urban', 31),
(33, 'Lindi rural', 31),
(34, 'Kilwa', 31),
(35, 'Nachingwea', 31),
(36, 'Liwale', 31),
(37, 'Ruangwa', 31),
(38, 'Katavi', 0),
(39, 'Mpanda', 38),
(40, 'Mlele', 38),
(41, 'Manyara', 0),
(42, 'Mbulu', 41),
(43, 'Babati', 41),
(44, 'Hanang', 41),
(45, 'Simanjiro', 41),
(46, 'Kiteto', 41),
(47, 'Arusha', 0),
(48, 'Arumeru', 47),
(49, 'Arusha urban', 47),
(50, 'karatu', 47),
(51, 'Monduli', 47),
(52, 'Longido', 47),
(53, 'Dodoma', 0),
(54, 'Dodoma rural - chamwino', 53),
(55, 'Bahi', 53),
(56, 'Dodoma urban', 53),
(57, 'Kondoa', 53),
(58, 'Mpwapwa', 53),
(59, 'Kongwa', 53),
(60, 'Geita', 0),
(61, 'Bukombe', 60),
(62, 'Chato', 60),
(63, 'Mbogwe', 60),
(64, 'Nyang''hwale', 60),
(65, 'Iringa', 0),
(66, 'Iringa rural', 65),
(67, 'Iringa urban', 65),
(68, 'Mufindi', 65),
(69, 'Kilolo', 65),
(70, 'Mafinga', 65),
(71, 'Makete', 65),
(72, 'Njombe', 65),
(73, 'Ludewa', 65),
(74, 'Kagera', 0),
(75, 'Bukoba rural', 74),
(76, 'Bukoba urban', 74),
(77, 'Misenyi', 74),
(78, 'Muleba', 74),
(79, 'Karagwe', 74),
(80, 'Ngara', 74),
(81, 'Biharamulo', 74),
(82, 'Kyerwa', 74),
(83, 'Mbeya', 0),
(84, 'Mbeya urban', 83),
(85, 'Mbeya rural', 83),
(86, 'Rungwe', 83),
(87, 'Kyela', 83),
(88, 'Ileje', 83),
(89, 'Mbozi', 83),
(90, 'Chunya', 83),
(91, 'Momba', 83),
(92, 'Mbarali', 83),
(93, 'Morogoro', 0),
(94, 'Kilosa', 93),
(95, 'Kilombero', 93),
(96, 'Morogoro urban', 93),
(97, 'Morogoro rural', 93),
(98, 'Mvomero', 93),
(99, 'Ulanga', 93),
(100, 'Gairo', 93),
(101, 'Mtwara', 0),
(102, 'Masasi', 101),
(103, 'Nanyumbu', 101),
(104, 'Newala', 101),
(105, 'Tandahimba', 101),
(106, 'urban - Mikindani', 101),
(107, 'Mtwara rural', 101),
(108, 'Ukerewe', 18),
(109, 'Magu', 18),
(110, 'Sengerema', 18),
(111, 'Misungwi', 18),
(112, 'Kwimba', 18),
(113, 'Nyamagana', 18),
(114, 'Ilemela', 18),
(115, 'Sinyanga', 0),
(116, 'Bariadi', 115),
(117, 'Maswa', 115),
(118, 'Meatu', 115),
(119, 'Kahama', 115),
(120, 'Kishapu', 115),
(121, 'Shinyanga urban', 115),
(122, 'Shinyanga rural', 115),
(123, 'Singida', 0),
(124, 'Iramba', 123),
(125, 'Manyoni', 123),
(126, 'Mkalama', 123),
(127, 'Ikungi', 123),
(128, 'Singida rural', 123),
(129, 'Singida urban', 123),
(130, 'Simiyu', 0),
(131, 'Busega', 130),
(132, 'Itilima', 130),
(133, 'Ruvuma', 0),
(134, 'Songea urban', 133),
(135, 'Songea rural', 133),
(136, 'Tunduru', 133),
(137, 'Mbinga', 133),
(138, 'Namtumbo', 133),
(139, 'Nyasa', 133),
(140, 'Rukwa', 0),
(141, 'Sumbawanga', 140),
(142, 'Nkasi', 140),
(143, 'Kalambo', 140),
(144, 'Pwani', 0),
(145, 'Bagamoyo', 144),
(146, 'Kibaha', 144),
(147, 'Kisarawe', 144),
(148, 'Mkuranga', 144),
(149, 'Rufiji', 144),
(150, 'Mafia', 144),
(151, 'Pemba Kusini', 0),
(152, 'Mkoani', 151),
(153, 'Chakechake', 151),
(154, 'Pemba kaskazini', 0),
(155, 'Wete', 154),
(156, 'Micheweni', 154),
(157, 'Unguja kusini', 0),
(158, 'Unguja A', 157),
(159, 'Unguja B', 157),
(160, 'Mkokoloni', 157),
(161, 'Unguja Magharibi', 0),
(162, 'Kisiwani', 161),
(163, 'Zanzibar urban', 161),
(164, 'Pangani', 3),
(165, 'Lushoto', 3),
(166, 'Korogwe', 3),
(167, 'Mkinga', 3),
(168, 'Kilindi', 3),
(169, 'Muheza', 3),
(170, 'Tanga urban', 3),
(171, 'Nzega', 16),
(172, 'Sikonge', 16),
(173, 'Kaliua', 16),
(174, 'Urambo', 16),
(175, 'Uyui', 16),
(176, 'Tabora urban', 16);

-- --------------------------------------------------------

--
-- Table structure for table `maritalstatus`
--

CREATE TABLE IF NOT EXISTS `maritalstatus` (
`id` int(11) NOT NULL,
  `Name` varchar(200) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `maritalstatus`
--

INSERT INTO `maritalstatus` (`id`, `Name`) VALUES
(1, 'Single'),
(2, 'Married'),
(3, 'Divorced');

-- --------------------------------------------------------

--
-- Table structure for table `nssf`
--

CREATE TABLE IF NOT EXISTS `nssf` (
`id` int(11) NOT NULL,
  `Employee` varchar(200) NOT NULL,
  `EmployeeNSSF` double NOT NULL,
  `EmployerNSSF` double NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `nssf`
--

INSERT INTO `nssf` (`id`, `Employee`, `EmployeeNSSF`, `EmployerNSSF`) VALUES
(1, 'HRP/001', 365900, 365900),
(2, 'HRP/002', 50900, 50900),
(3, 'HRP/003', 381900, 381900),
(4, 'HRP/004', 365200, 365200),
(5, 'HRP/005', 103800, 103800),
(6, 'HRP/006', 189200, 189200),
(7, 'HRP/007', 349800, 349800),
(8, 'HRP/008', 200500, 200500),
(9, 'HRP/009', 204700, 204700),
(10, 'HRP/010', 364400, 364400),
(11, 'HRP/025', 51300, 51300),
(12, 'HRP/024', 210100, 210100),
(13, 'HRP/023', 99600, 99600),
(14, 'HRP/022', 198800, 198800),
(15, 'HRP/021', 260000, 260000),
(16, 'HRP/020', 188300, 188300),
(17, 'HRP/019', 103800, 103800),
(18, 'HRP/018', 215900, 215900),
(19, 'HRP/017', 363200, 363200),
(20, 'HRP/016', 63400, 63400),
(21, 'HRP/015', 253000, 253000),
(22, 'HRP/014', 274600, 274600),
(23, 'HRP/013', 334900, 334900),
(24, 'HRP/012', 391200, 391200),
(25, 'HRP/011', 317700, 317700),
(26, 'HRP/026', 347000, 347000),
(27, 'HRP/027', 230600, 230600),
(28, 'HRP/028', 298100, 298100),
(29, 'HRP/029', 180200, 180200),
(30, 'HRP/500', 0, 0),
(31, 'HRP/600', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `overtime`
--

CREATE TABLE IF NOT EXISTS `overtime` (
`id` int(11) NOT NULL,
  `Employee` varchar(100) NOT NULL,
  `hours` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `overtime`
--

INSERT INTO `overtime` (`id`, `Employee`, `hours`, `date`) VALUES
(1, 'HRP/001', 2, '2013-07-02'),
(2, 'HRP/001', 30, '2015-05-26');

-- --------------------------------------------------------

--
-- Table structure for table `payee_config`
--

CREATE TABLE IF NOT EXISTS `payee_config` (
`id` int(11) NOT NULL,
  `Min_Value` double NOT NULL,
  `Max_Value` double NOT NULL,
  `Formula` text NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payee_config`
--

INSERT INTO `payee_config` (`id`, `Min_Value`, `Max_Value`, `Formula`) VALUES
(1, 1, 170000, 'X-X'),
(2, 170000, 540000, 'X-170000'),
(3, 540000, 720000, '62600+(X-540000)*0.25'),
(4, 720000, 10000000, '107600+(X-720000)*0.3'),
(5, 10000000, 20000000, 'X-X');

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE IF NOT EXISTS `payroll` (
`id` int(11) NOT NULL,
  `Employee` varchar(200) NOT NULL,
  `SalaryItem` int(11) NOT NULL,
  `Amount` double NOT NULL,
  `Month` int(11) NOT NULL,
  `Year` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=560 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payroll`
--

INSERT INTO `payroll` (`id`, `Employee`, `SalaryItem`, `Amount`, `Month`, `Year`) VALUES
(1, 'HRP/001', 2, 0, 1, 2013),
(2, 'HRP/002', 2, 0, 1, 2013),
(3, 'HRP/003', 2, 0, 1, 2013),
(4, 'HRP/004', 2, 0, 1, 2013),
(5, 'HRP/005', 2, 0, 1, 2013),
(6, 'HRP/006', 2, 0, 1, 2013),
(7, 'HRP/007', 2, 0, 1, 2013),
(8, 'HRP/008', 2, 0, 1, 2013),
(9, 'HRP/009', 2, 0, 1, 2013),
(10, 'HRP/010', 2, 0, 1, 2013),
(11, 'HRP/025', 2, 0, 1, 2013),
(12, 'HRP/024', 2, 0, 1, 2013),
(13, 'HRP/023', 2, 0, 1, 2013),
(14, 'HRP/022', 2, 0, 1, 2013),
(15, 'HRP/021', 2, 0, 1, 2013),
(16, 'HRP/020', 2, 0, 1, 2013),
(17, 'HRP/019', 2, 0, 1, 2013),
(18, 'HRP/018', 2, 0, 1, 2013),
(19, 'HRP/017', 2, 0, 1, 2013),
(20, 'HRP/016', 2, 0, 1, 2013),
(21, 'HRP/015', 2, 0, 1, 2013),
(22, 'HRP/014', 2, 0, 1, 2013),
(23, 'HRP/013', 2, 0, 1, 2013),
(24, 'HRP/012', 2, 0, 1, 2013),
(25, 'HRP/011', 2, 0, 1, 2013),
(26, 'HRP/026', 2, 0, 1, 2013),
(27, 'HRP/027', 2, 0, 1, 2013),
(28, 'HRP/028', 2, 0, 1, 2013),
(29, 'HRP/029', 2, 0, 1, 2013),
(30, 'HRP/500', 2, 0, 1, 2013),
(31, 'HRP/600', 2, 0, 1, 2013),
(32, 'HRP/001', 3, 0, 1, 2013),
(33, 'HRP/002', 3, 0, 1, 2013),
(34, 'HRP/003', 3, 0, 1, 2013),
(35, 'HRP/004', 3, 0, 1, 2013),
(36, 'HRP/005', 3, 0, 1, 2013),
(37, 'HRP/006', 3, 0, 1, 2013),
(38, 'HRP/007', 3, 0, 1, 2013),
(39, 'HRP/008', 3, 0, 1, 2013),
(40, 'HRP/009', 3, 0, 1, 2013),
(41, 'HRP/010', 3, 0, 1, 2013),
(42, 'HRP/025', 3, 0, 1, 2013),
(43, 'HRP/024', 3, 0, 1, 2013),
(44, 'HRP/023', 3, 0, 1, 2013),
(45, 'HRP/022', 3, 0, 1, 2013),
(46, 'HRP/021', 3, 0, 1, 2013),
(47, 'HRP/020', 3, 0, 1, 2013),
(48, 'HRP/019', 3, 0, 1, 2013),
(49, 'HRP/018', 3, 0, 1, 2013),
(50, 'HRP/017', 3, 0, 1, 2013),
(51, 'HRP/016', 3, 0, 1, 2013),
(52, 'HRP/015', 3, 0, 1, 2013),
(53, 'HRP/014', 3, 0, 1, 2013),
(54, 'HRP/013', 3, 0, 1, 2013),
(55, 'HRP/012', 3, 0, 1, 2013),
(56, 'HRP/011', 3, 0, 1, 2013),
(57, 'HRP/026', 3, 0, 1, 2013),
(58, 'HRP/027', 3, 0, 1, 2013),
(59, 'HRP/028', 3, 0, 1, 2013),
(60, 'HRP/029', 3, 0, 1, 2013),
(61, 'HRP/500', 3, 0, 1, 2013),
(62, 'HRP/600', 3, 0, 1, 2013),
(63, 'HRP/001', 4, 0, 1, 2013),
(64, 'HRP/002', 4, 0, 1, 2013),
(65, 'HRP/003', 4, 0, 1, 2013),
(66, 'HRP/004', 4, 0, 1, 2013),
(67, 'HRP/005', 4, 0, 1, 2013),
(68, 'HRP/006', 4, 0, 1, 2013),
(69, 'HRP/007', 4, 0, 1, 2013),
(70, 'HRP/008', 4, 0, 1, 2013),
(71, 'HRP/009', 4, 0, 1, 2013),
(72, 'HRP/010', 4, 0, 1, 2013),
(73, 'HRP/025', 4, 0, 1, 2013),
(74, 'HRP/024', 4, 0, 1, 2013),
(75, 'HRP/023', 4, 0, 1, 2013),
(76, 'HRP/022', 4, 0, 1, 2013),
(77, 'HRP/021', 4, 0, 1, 2013),
(78, 'HRP/020', 4, 0, 1, 2013),
(79, 'HRP/019', 4, 0, 1, 2013),
(80, 'HRP/018', 4, 0, 1, 2013),
(81, 'HRP/017', 4, 0, 1, 2013),
(82, 'HRP/016', 4, 0, 1, 2013),
(83, 'HRP/015', 4, 0, 1, 2013),
(84, 'HRP/014', 4, 0, 1, 2013),
(85, 'HRP/013', 4, 0, 1, 2013),
(86, 'HRP/012', 4, 0, 1, 2013),
(87, 'HRP/011', 4, 0, 1, 2013),
(88, 'HRP/026', 4, 0, 1, 2013),
(89, 'HRP/027', 4, 0, 1, 2013),
(90, 'HRP/028', 4, 0, 1, 2013),
(91, 'HRP/029', 4, 0, 1, 2013),
(92, 'HRP/500', 4, 0, 1, 2013),
(93, 'HRP/600', 4, 0, 1, 2013),
(94, 'HRP/001', 6, 0, 1, 2013),
(95, 'HRP/002', 6, 0, 1, 2013),
(96, 'HRP/003', 6, 0, 1, 2013),
(97, 'HRP/004', 6, 0, 1, 2013),
(98, 'HRP/005', 6, 0, 1, 2013),
(99, 'HRP/006', 6, 0, 1, 2013),
(100, 'HRP/007', 6, 0, 1, 2013),
(101, 'HRP/008', 6, 0, 1, 2013),
(102, 'HRP/009', 6, 0, 1, 2013),
(103, 'HRP/010', 6, 0, 1, 2013),
(104, 'HRP/025', 6, 0, 1, 2013),
(105, 'HRP/024', 6, 0, 1, 2013),
(106, 'HRP/023', 6, 0, 1, 2013),
(107, 'HRP/022', 6, 0, 1, 2013),
(108, 'HRP/021', 6, 0, 1, 2013),
(109, 'HRP/020', 6, 0, 1, 2013),
(110, 'HRP/019', 6, 0, 1, 2013),
(111, 'HRP/018', 6, 0, 1, 2013),
(112, 'HRP/017', 6, 0, 1, 2013),
(113, 'HRP/016', 6, 0, 1, 2013),
(114, 'HRP/015', 6, 0, 1, 2013),
(115, 'HRP/014', 6, 0, 1, 2013),
(116, 'HRP/013', 6, 0, 1, 2013),
(117, 'HRP/012', 6, 0, 1, 2013),
(118, 'HRP/011', 6, 0, 1, 2013),
(119, 'HRP/026', 6, 0, 1, 2013),
(120, 'HRP/027', 6, 0, 1, 2013),
(121, 'HRP/028', 6, 0, 1, 2013),
(122, 'HRP/029', 6, 0, 1, 2013),
(123, 'HRP/500', 6, 0, 1, 2013),
(124, 'HRP/600', 6, 0, 1, 2013),
(125, 'HRP/001', 7, 0, 1, 2013),
(126, 'HRP/002', 7, 0, 1, 2013),
(127, 'HRP/003', 7, 0, 1, 2013),
(128, 'HRP/004', 7, 0, 1, 2013),
(129, 'HRP/005', 7, 0, 1, 2013),
(130, 'HRP/006', 7, 0, 1, 2013),
(131, 'HRP/007', 7, 0, 1, 2013),
(132, 'HRP/008', 7, 0, 1, 2013),
(133, 'HRP/009', 7, 0, 1, 2013),
(134, 'HRP/010', 7, 0, 1, 2013),
(135, 'HRP/025', 7, 0, 1, 2013),
(136, 'HRP/024', 7, 0, 1, 2013),
(137, 'HRP/023', 7, 0, 1, 2013),
(138, 'HRP/022', 7, 0, 1, 2013),
(139, 'HRP/021', 7, 0, 1, 2013),
(140, 'HRP/020', 7, 0, 1, 2013),
(141, 'HRP/019', 7, 0, 1, 2013),
(142, 'HRP/018', 7, 0, 1, 2013),
(143, 'HRP/017', 7, 0, 1, 2013),
(144, 'HRP/016', 7, 0, 1, 2013),
(145, 'HRP/015', 7, 0, 1, 2013),
(146, 'HRP/014', 7, 0, 1, 2013),
(147, 'HRP/013', 7, 0, 1, 2013),
(148, 'HRP/012', 7, 0, 1, 2013),
(149, 'HRP/011', 7, 0, 1, 2013),
(150, 'HRP/026', 7, 0, 1, 2013),
(151, 'HRP/027', 7, 0, 1, 2013),
(152, 'HRP/028', 7, 0, 1, 2013),
(153, 'HRP/029', 7, 0, 1, 2013),
(154, 'HRP/500', 7, 0, 1, 2013),
(155, 'HRP/600', 7, 0, 1, 2013),
(156, 'HRP/001', 1, 3659000, 1, 2013),
(157, 'HRP/001', 5, 989300, 1, 2013),
(158, 'HRP/001', 9, 365900, 1, 2013),
(159, 'HRP/001', 10, 365900, 1, 2013),
(160, 'HRP/002', 1, 509000, 1, 2013),
(161, 'HRP/002', 5, 339000, 1, 2013),
(162, 'HRP/002', 9, 50900, 1, 2013),
(163, 'HRP/002', 10, 50900, 1, 2013),
(164, 'HRP/003', 1, 3819000, 1, 2013),
(165, 'HRP/003', 5, 1037300, 1, 2013),
(166, 'HRP/003', 9, 381900, 1, 2013),
(167, 'HRP/003', 10, 381900, 1, 2013),
(168, 'HRP/004', 1, 3652000, 1, 2013),
(169, 'HRP/004', 5, 987200, 1, 2013),
(170, 'HRP/004', 9, 365200, 1, 2013),
(171, 'HRP/004', 10, 365200, 1, 2013),
(172, 'HRP/005', 1, 1038000, 1, 2013),
(173, 'HRP/005', 5, 203000, 1, 2013),
(174, 'HRP/005', 9, 103800, 1, 2013),
(175, 'HRP/005', 10, 103800, 1, 2013),
(176, 'HRP/006', 1, 1892000, 1, 2013),
(177, 'HRP/006', 5, 459200, 1, 2013),
(178, 'HRP/006', 9, 189200, 1, 2013),
(179, 'HRP/006', 10, 189200, 1, 2013),
(180, 'HRP/007', 1, 3498000, 1, 2013),
(181, 'HRP/007', 5, 941000, 1, 2013),
(182, 'HRP/007', 9, 349800, 1, 2013),
(183, 'HRP/007', 10, 349800, 1, 2013),
(184, 'HRP/008', 1, 2005000, 1, 2013),
(185, 'HRP/008', 5, 493100, 1, 2013),
(186, 'HRP/008', 9, 200500, 1, 2013),
(187, 'HRP/008', 10, 200500, 1, 2013),
(188, 'HRP/009', 1, 2047000, 1, 2013),
(189, 'HRP/009', 5, 505700, 1, 2013),
(190, 'HRP/009', 9, 204700, 1, 2013),
(191, 'HRP/009', 10, 204700, 1, 2013),
(192, 'HRP/010', 1, 3644000, 1, 2013),
(193, 'HRP/010', 5, 984800, 1, 2013),
(194, 'HRP/010', 9, 364400, 1, 2013),
(195, 'HRP/010', 10, 364400, 1, 2013),
(196, 'HRP/025', 1, 513000, 1, 2013),
(197, 'HRP/025', 5, 343000, 1, 2013),
(198, 'HRP/025', 9, 51300, 1, 2013),
(199, 'HRP/025', 10, 51300, 1, 2013),
(200, 'HRP/024', 1, 2101000, 1, 2013),
(201, 'HRP/024', 5, 521900, 1, 2013),
(202, 'HRP/024', 9, 210100, 1, 2013),
(203, 'HRP/024', 10, 210100, 1, 2013),
(204, 'HRP/023', 1, 996000, 1, 2013),
(205, 'HRP/023', 5, 190400, 1, 2013),
(206, 'HRP/023', 9, 99600, 1, 2013),
(207, 'HRP/023', 10, 99600, 1, 2013),
(208, 'HRP/022', 1, 1988000, 1, 2013),
(209, 'HRP/022', 5, 488000, 1, 2013),
(210, 'HRP/022', 9, 198800, 1, 2013),
(211, 'HRP/022', 10, 198800, 1, 2013),
(212, 'HRP/021', 1, 2600000, 1, 2013),
(213, 'HRP/021', 5, 671600, 1, 2013),
(214, 'HRP/021', 9, 260000, 1, 2013),
(215, 'HRP/021', 10, 260000, 1, 2013),
(216, 'HRP/020', 1, 1883000, 1, 2013),
(217, 'HRP/020', 5, 456500, 1, 2013),
(218, 'HRP/020', 9, 188300, 1, 2013),
(219, 'HRP/020', 10, 188300, 1, 2013),
(220, 'HRP/019', 1, 1038000, 1, 2013),
(221, 'HRP/019', 5, 203000, 1, 2013),
(222, 'HRP/019', 9, 103800, 1, 2013),
(223, 'HRP/019', 10, 103800, 1, 2013),
(224, 'HRP/018', 1, 2159000, 1, 2013),
(225, 'HRP/018', 5, 539300, 1, 2013),
(226, 'HRP/018', 9, 215900, 1, 2013),
(227, 'HRP/018', 10, 215900, 1, 2013),
(228, 'HRP/017', 1, 3632000, 1, 2013),
(229, 'HRP/017', 5, 981200, 1, 2013),
(230, 'HRP/017', 9, 363200, 1, 2013),
(231, 'HRP/017', 10, 363200, 1, 2013),
(232, 'HRP/016', 1, 634000, 1, 2013),
(233, 'HRP/016', 5, 86100, 1, 2013),
(234, 'HRP/016', 9, 63400, 1, 2013),
(235, 'HRP/016', 10, 63400, 1, 2013),
(236, 'HRP/015', 1, 2530000, 1, 2013),
(237, 'HRP/015', 5, 650600, 1, 2013),
(238, 'HRP/015', 9, 253000, 1, 2013),
(239, 'HRP/015', 10, 253000, 1, 2013),
(240, 'HRP/014', 1, 2746000, 1, 2013),
(241, 'HRP/014', 5, 715400, 1, 2013),
(242, 'HRP/014', 9, 274600, 1, 2013),
(243, 'HRP/014', 10, 274600, 1, 2013),
(244, 'HRP/013', 1, 3349000, 1, 2013),
(245, 'HRP/013', 5, 896300, 1, 2013),
(246, 'HRP/013', 9, 334900, 1, 2013),
(247, 'HRP/013', 10, 334900, 1, 2013),
(248, 'HRP/012', 1, 3912000, 1, 2013),
(249, 'HRP/012', 5, 1065200, 1, 2013),
(250, 'HRP/012', 9, 391200, 1, 2013),
(251, 'HRP/012', 10, 391200, 1, 2013),
(252, 'HRP/011', 1, 3177000, 1, 2013),
(253, 'HRP/011', 5, 844700, 1, 2013),
(254, 'HRP/011', 9, 317700, 1, 2013),
(255, 'HRP/011', 10, 317700, 1, 2013),
(256, 'HRP/026', 1, 3470000, 1, 2013),
(257, 'HRP/026', 5, 932600, 1, 2013),
(258, 'HRP/026', 9, 347000, 1, 2013),
(259, 'HRP/026', 10, 347000, 1, 2013),
(260, 'HRP/027', 1, 2306000, 1, 2013),
(261, 'HRP/027', 5, 583400, 1, 2013),
(262, 'HRP/027', 9, 230600, 1, 2013),
(263, 'HRP/027', 10, 230600, 1, 2013),
(264, 'HRP/028', 1, 2981000, 1, 2013),
(265, 'HRP/028', 5, 785900, 1, 2013),
(266, 'HRP/028', 9, 298100, 1, 2013),
(267, 'HRP/028', 10, 298100, 1, 2013),
(268, 'HRP/029', 1, 1802000, 1, 2013),
(269, 'HRP/029', 5, 432200, 1, 2013),
(270, 'HRP/029', 9, 180200, 1, 2013),
(271, 'HRP/029', 10, 180200, 1, 2013),
(272, 'HRP/500', 1, 0, 1, 2013),
(273, 'HRP/500', 5, 0, 1, 2013),
(274, 'HRP/500', 9, 0, 1, 2013),
(275, 'HRP/500', 10, 0, 1, 2013),
(276, 'HRP/600', 1, 0, 1, 2013),
(277, 'HRP/600', 5, 0, 1, 2013),
(278, 'HRP/600', 9, 0, 1, 2013),
(279, 'HRP/600', 10, 0, 1, 2013),
(280, 'HRP/001', 2, 0, 8, 2013),
(281, 'HRP/002', 2, 0, 8, 2013),
(282, 'HRP/003', 2, 0, 8, 2013),
(283, 'HRP/004', 2, 0, 8, 2013),
(284, 'HRP/005', 2, 0, 8, 2013),
(285, 'HRP/006', 2, 0, 8, 2013),
(286, 'HRP/007', 2, 0, 8, 2013),
(287, 'HRP/008', 2, 0, 8, 2013),
(288, 'HRP/009', 2, 0, 8, 2013),
(289, 'HRP/010', 2, 0, 8, 2013),
(290, 'HRP/025', 2, 0, 8, 2013),
(291, 'HRP/024', 2, 0, 8, 2013),
(292, 'HRP/023', 2, 0, 8, 2013),
(293, 'HRP/022', 2, 0, 8, 2013),
(294, 'HRP/021', 2, 0, 8, 2013),
(295, 'HRP/020', 2, 0, 8, 2013),
(296, 'HRP/019', 2, 0, 8, 2013),
(297, 'HRP/018', 2, 0, 8, 2013),
(298, 'HRP/017', 2, 0, 8, 2013),
(299, 'HRP/016', 2, 0, 8, 2013),
(300, 'HRP/015', 2, 0, 8, 2013),
(301, 'HRP/014', 2, 0, 8, 2013),
(302, 'HRP/013', 2, 0, 8, 2013),
(303, 'HRP/012', 2, 0, 8, 2013),
(304, 'HRP/011', 2, 0, 8, 2013),
(305, 'HRP/026', 2, 0, 8, 2013),
(306, 'HRP/027', 2, 0, 8, 2013),
(307, 'HRP/028', 2, 0, 8, 2013),
(308, 'HRP/029', 2, 0, 8, 2013),
(309, 'HRP/500', 2, 0, 8, 2013),
(310, 'HRP/600', 2, 0, 8, 2013),
(311, 'HRP/001', 3, 0, 8, 2013),
(312, 'HRP/002', 3, 0, 8, 2013),
(313, 'HRP/003', 3, 0, 8, 2013),
(314, 'HRP/004', 3, 0, 8, 2013),
(315, 'HRP/005', 3, 0, 8, 2013),
(316, 'HRP/006', 3, 0, 8, 2013),
(317, 'HRP/007', 3, 0, 8, 2013),
(318, 'HRP/008', 3, 0, 8, 2013),
(319, 'HRP/009', 3, 0, 8, 2013),
(320, 'HRP/010', 3, 0, 8, 2013),
(321, 'HRP/025', 3, 0, 8, 2013),
(322, 'HRP/024', 3, 0, 8, 2013),
(323, 'HRP/023', 3, 0, 8, 2013),
(324, 'HRP/022', 3, 0, 8, 2013),
(325, 'HRP/021', 3, 0, 8, 2013),
(326, 'HRP/020', 3, 0, 8, 2013),
(327, 'HRP/019', 3, 0, 8, 2013),
(328, 'HRP/018', 3, 0, 8, 2013),
(329, 'HRP/017', 3, 0, 8, 2013),
(330, 'HRP/016', 3, 0, 8, 2013),
(331, 'HRP/015', 3, 0, 8, 2013),
(332, 'HRP/014', 3, 0, 8, 2013),
(333, 'HRP/013', 3, 0, 8, 2013),
(334, 'HRP/012', 3, 0, 8, 2013),
(335, 'HRP/011', 3, 0, 8, 2013),
(336, 'HRP/026', 3, 0, 8, 2013),
(337, 'HRP/027', 3, 0, 8, 2013),
(338, 'HRP/028', 3, 0, 8, 2013),
(339, 'HRP/029', 3, 0, 8, 2013),
(340, 'HRP/500', 3, 0, 8, 2013),
(341, 'HRP/600', 3, 0, 8, 2013),
(342, 'HRP/001', 4, 0, 8, 2013),
(343, 'HRP/002', 4, 0, 8, 2013),
(344, 'HRP/003', 4, 0, 8, 2013),
(345, 'HRP/004', 4, 0, 8, 2013),
(346, 'HRP/005', 4, 0, 8, 2013),
(347, 'HRP/006', 4, 0, 8, 2013),
(348, 'HRP/007', 4, 0, 8, 2013),
(349, 'HRP/008', 4, 0, 8, 2013),
(350, 'HRP/009', 4, 0, 8, 2013),
(351, 'HRP/010', 4, 0, 8, 2013),
(352, 'HRP/025', 4, 0, 8, 2013),
(353, 'HRP/024', 4, 0, 8, 2013),
(354, 'HRP/023', 4, 0, 8, 2013),
(355, 'HRP/022', 4, 0, 8, 2013),
(356, 'HRP/021', 4, 0, 8, 2013),
(357, 'HRP/020', 4, 0, 8, 2013),
(358, 'HRP/019', 4, 0, 8, 2013),
(359, 'HRP/018', 4, 0, 8, 2013),
(360, 'HRP/017', 4, 0, 8, 2013),
(361, 'HRP/016', 4, 0, 8, 2013),
(362, 'HRP/015', 4, 0, 8, 2013),
(363, 'HRP/014', 4, 0, 8, 2013),
(364, 'HRP/013', 4, 0, 8, 2013),
(365, 'HRP/012', 4, 0, 8, 2013),
(366, 'HRP/011', 4, 0, 8, 2013),
(367, 'HRP/026', 4, 0, 8, 2013),
(368, 'HRP/027', 4, 0, 8, 2013),
(369, 'HRP/028', 4, 0, 8, 2013),
(370, 'HRP/029', 4, 0, 8, 2013),
(371, 'HRP/500', 4, 0, 8, 2013),
(372, 'HRP/600', 4, 0, 8, 2013),
(373, 'HRP/001', 6, 0, 8, 2013),
(374, 'HRP/002', 6, 0, 8, 2013),
(375, 'HRP/003', 6, 0, 8, 2013),
(376, 'HRP/004', 6, 0, 8, 2013),
(377, 'HRP/005', 6, 0, 8, 2013),
(378, 'HRP/006', 6, 0, 8, 2013),
(379, 'HRP/007', 6, 0, 8, 2013),
(380, 'HRP/008', 6, 0, 8, 2013),
(381, 'HRP/009', 6, 0, 8, 2013),
(382, 'HRP/010', 6, 0, 8, 2013),
(383, 'HRP/025', 6, 0, 8, 2013),
(384, 'HRP/024', 6, 0, 8, 2013),
(385, 'HRP/023', 6, 0, 8, 2013),
(386, 'HRP/022', 6, 0, 8, 2013),
(387, 'HRP/021', 6, 0, 8, 2013),
(388, 'HRP/020', 6, 0, 8, 2013),
(389, 'HRP/019', 6, 0, 8, 2013),
(390, 'HRP/018', 6, 0, 8, 2013),
(391, 'HRP/017', 6, 0, 8, 2013),
(392, 'HRP/016', 6, 0, 8, 2013),
(393, 'HRP/015', 6, 0, 8, 2013),
(394, 'HRP/014', 6, 0, 8, 2013),
(395, 'HRP/013', 6, 0, 8, 2013),
(396, 'HRP/012', 6, 0, 8, 2013),
(397, 'HRP/011', 6, 0, 8, 2013),
(398, 'HRP/026', 6, 0, 8, 2013),
(399, 'HRP/027', 6, 0, 8, 2013),
(400, 'HRP/028', 6, 0, 8, 2013),
(401, 'HRP/029', 6, 0, 8, 2013),
(402, 'HRP/500', 6, 0, 8, 2013),
(403, 'HRP/600', 6, 0, 8, 2013),
(404, 'HRP/001', 7, 0, 8, 2013),
(405, 'HRP/002', 7, 0, 8, 2013),
(406, 'HRP/003', 7, 0, 8, 2013),
(407, 'HRP/004', 7, 0, 8, 2013),
(408, 'HRP/005', 7, 0, 8, 2013),
(409, 'HRP/006', 7, 0, 8, 2013),
(410, 'HRP/007', 7, 0, 8, 2013),
(411, 'HRP/008', 7, 0, 8, 2013),
(412, 'HRP/009', 7, 0, 8, 2013),
(413, 'HRP/010', 7, 0, 8, 2013),
(414, 'HRP/025', 7, 0, 8, 2013),
(415, 'HRP/024', 7, 0, 8, 2013),
(416, 'HRP/023', 7, 0, 8, 2013),
(417, 'HRP/022', 7, 0, 8, 2013),
(418, 'HRP/021', 7, 0, 8, 2013),
(419, 'HRP/020', 7, 0, 8, 2013),
(420, 'HRP/019', 7, 0, 8, 2013),
(421, 'HRP/018', 7, 0, 8, 2013),
(422, 'HRP/017', 7, 0, 8, 2013),
(423, 'HRP/016', 7, 0, 8, 2013),
(424, 'HRP/015', 7, 0, 8, 2013),
(425, 'HRP/014', 7, 0, 8, 2013),
(426, 'HRP/013', 7, 0, 8, 2013),
(427, 'HRP/012', 7, 0, 8, 2013),
(428, 'HRP/011', 7, 0, 8, 2013),
(429, 'HRP/026', 7, 0, 8, 2013),
(430, 'HRP/027', 7, 0, 8, 2013),
(431, 'HRP/028', 7, 0, 8, 2013),
(432, 'HRP/029', 7, 0, 8, 2013),
(433, 'HRP/500', 7, 0, 8, 2013),
(434, 'HRP/600', 7, 0, 8, 2013),
(435, 'HRP/001', 1, 3659000, 8, 2013),
(436, 'HRP/001', 5, 989300, 8, 2013),
(437, 'HRP/001', 9, 365900, 8, 2013),
(438, 'HRP/001', 10, 365900, 8, 2013),
(439, 'HRP/001', 8, 40000, 8, 2013),
(440, 'HRP/002', 1, 509000, 8, 2013),
(441, 'HRP/002', 5, 339000, 8, 2013),
(442, 'HRP/002', 9, 50900, 8, 2013),
(443, 'HRP/002', 10, 50900, 8, 2013),
(444, 'HRP/003', 1, 3819000, 8, 2013),
(445, 'HRP/003', 5, 1037300, 8, 2013),
(446, 'HRP/003', 9, 381900, 8, 2013),
(447, 'HRP/003', 10, 381900, 8, 2013),
(448, 'HRP/004', 1, 3652000, 8, 2013),
(449, 'HRP/004', 5, 987200, 8, 2013),
(450, 'HRP/004', 9, 365200, 8, 2013),
(451, 'HRP/004', 10, 365200, 8, 2013),
(452, 'HRP/005', 1, 1038000, 8, 2013),
(453, 'HRP/005', 5, 203000, 8, 2013),
(454, 'HRP/005', 9, 103800, 8, 2013),
(455, 'HRP/005', 10, 103800, 8, 2013),
(456, 'HRP/006', 1, 1892000, 8, 2013),
(457, 'HRP/006', 5, 459200, 8, 2013),
(458, 'HRP/006', 9, 189200, 8, 2013),
(459, 'HRP/006', 10, 189200, 8, 2013),
(460, 'HRP/007', 1, 3498000, 8, 2013),
(461, 'HRP/007', 5, 941000, 8, 2013),
(462, 'HRP/007', 9, 349800, 8, 2013),
(463, 'HRP/007', 10, 349800, 8, 2013),
(464, 'HRP/008', 1, 2005000, 8, 2013),
(465, 'HRP/008', 5, 493100, 8, 2013),
(466, 'HRP/008', 9, 200500, 8, 2013),
(467, 'HRP/008', 10, 200500, 8, 2013),
(468, 'HRP/009', 1, 2047000, 8, 2013),
(469, 'HRP/009', 5, 505700, 8, 2013),
(470, 'HRP/009', 9, 204700, 8, 2013),
(471, 'HRP/009', 10, 204700, 8, 2013),
(472, 'HRP/010', 1, 3644000, 8, 2013),
(473, 'HRP/010', 5, 984800, 8, 2013),
(474, 'HRP/010', 9, 364400, 8, 2013),
(475, 'HRP/010', 10, 364400, 8, 2013),
(476, 'HRP/025', 1, 513000, 8, 2013),
(477, 'HRP/025', 5, 343000, 8, 2013),
(478, 'HRP/025', 9, 51300, 8, 2013),
(479, 'HRP/025', 10, 51300, 8, 2013),
(480, 'HRP/024', 1, 2101000, 8, 2013),
(481, 'HRP/024', 5, 521900, 8, 2013),
(482, 'HRP/024', 9, 210100, 8, 2013),
(483, 'HRP/024', 10, 210100, 8, 2013),
(484, 'HRP/023', 1, 996000, 8, 2013),
(485, 'HRP/023', 5, 190400, 8, 2013),
(486, 'HRP/023', 9, 99600, 8, 2013),
(487, 'HRP/023', 10, 99600, 8, 2013),
(488, 'HRP/022', 1, 1988000, 8, 2013),
(489, 'HRP/022', 5, 488000, 8, 2013),
(490, 'HRP/022', 9, 198800, 8, 2013),
(491, 'HRP/022', 10, 198800, 8, 2013),
(492, 'HRP/021', 1, 2600000, 8, 2013),
(493, 'HRP/021', 5, 671600, 8, 2013),
(494, 'HRP/021', 9, 260000, 8, 2013),
(495, 'HRP/021', 10, 260000, 8, 2013),
(496, 'HRP/020', 1, 1883000, 8, 2013),
(497, 'HRP/020', 5, 456500, 8, 2013),
(498, 'HRP/020', 9, 188300, 8, 2013),
(499, 'HRP/020', 10, 188300, 8, 2013),
(500, 'HRP/019', 1, 1038000, 8, 2013),
(501, 'HRP/019', 5, 203000, 8, 2013),
(502, 'HRP/019', 9, 103800, 8, 2013),
(503, 'HRP/019', 10, 103800, 8, 2013),
(504, 'HRP/018', 1, 2159000, 8, 2013),
(505, 'HRP/018', 5, 539300, 8, 2013),
(506, 'HRP/018', 9, 215900, 8, 2013),
(507, 'HRP/018', 10, 215900, 8, 2013),
(508, 'HRP/017', 1, 3632000, 8, 2013),
(509, 'HRP/017', 5, 981200, 8, 2013),
(510, 'HRP/017', 9, 363200, 8, 2013),
(511, 'HRP/017', 10, 363200, 8, 2013),
(512, 'HRP/016', 1, 634000, 8, 2013),
(513, 'HRP/016', 5, 86100, 8, 2013),
(514, 'HRP/016', 9, 63400, 8, 2013),
(515, 'HRP/016', 10, 63400, 8, 2013),
(516, 'HRP/015', 1, 2530000, 8, 2013),
(517, 'HRP/015', 5, 650600, 8, 2013),
(518, 'HRP/015', 9, 253000, 8, 2013),
(519, 'HRP/015', 10, 253000, 8, 2013),
(520, 'HRP/014', 1, 2746000, 8, 2013),
(521, 'HRP/014', 5, 715400, 8, 2013),
(522, 'HRP/014', 9, 274600, 8, 2013),
(523, 'HRP/014', 10, 274600, 8, 2013),
(524, 'HRP/013', 1, 3349000, 8, 2013),
(525, 'HRP/013', 5, 896300, 8, 2013),
(526, 'HRP/013', 9, 334900, 8, 2013),
(527, 'HRP/013', 10, 334900, 8, 2013),
(528, 'HRP/012', 1, 3912000, 8, 2013),
(529, 'HRP/012', 5, 1065200, 8, 2013),
(530, 'HRP/012', 9, 391200, 8, 2013),
(531, 'HRP/012', 10, 391200, 8, 2013),
(532, 'HRP/011', 1, 3177000, 8, 2013),
(533, 'HRP/011', 5, 844700, 8, 2013),
(534, 'HRP/011', 9, 317700, 8, 2013),
(535, 'HRP/011', 10, 317700, 8, 2013),
(536, 'HRP/026', 1, 3470000, 8, 2013),
(537, 'HRP/026', 5, 932600, 8, 2013),
(538, 'HRP/026', 9, 347000, 8, 2013),
(539, 'HRP/026', 10, 347000, 8, 2013),
(540, 'HRP/027', 1, 2306000, 8, 2013),
(541, 'HRP/027', 5, 583400, 8, 2013),
(542, 'HRP/027', 9, 230600, 8, 2013),
(543, 'HRP/027', 10, 230600, 8, 2013),
(544, 'HRP/028', 1, 2981000, 8, 2013),
(545, 'HRP/028', 5, 785900, 8, 2013),
(546, 'HRP/028', 9, 298100, 8, 2013),
(547, 'HRP/028', 10, 298100, 8, 2013),
(548, 'HRP/029', 1, 1802000, 8, 2013),
(549, 'HRP/029', 5, 432200, 8, 2013),
(550, 'HRP/029', 9, 180200, 8, 2013),
(551, 'HRP/029', 10, 180200, 8, 2013),
(552, 'HRP/500', 1, 0, 8, 2013),
(553, 'HRP/500', 5, 0, 8, 2013),
(554, 'HRP/500', 9, 0, 8, 2013),
(555, 'HRP/500', 10, 0, 8, 2013),
(556, 'HRP/600', 1, 0, 8, 2013),
(557, 'HRP/600', 5, 0, 8, 2013),
(558, 'HRP/600', 9, 0, 8, 2013),
(559, 'HRP/600', 10, 0, 8, 2013);

-- --------------------------------------------------------

--
-- Table structure for table `position`
--

CREATE TABLE IF NOT EXISTS `position` (
`id` int(11) NOT NULL,
  `Name` varchar(300) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `position`
--

INSERT INTO `position` (`id`, `Name`) VALUES
(1, 'Manager'),
(2, 'Accountant'),
(3, 'Doctor - GP'),
(4, 'Nurse'),
(5, 'Doctor - Specialist'),
(6, 'Doctor - Super Specialist'),
(7, 'Cashier');

-- --------------------------------------------------------

--
-- Table structure for table `promotion`
--

CREATE TABLE IF NOT EXISTS `promotion` (
`id` int(11) NOT NULL,
  `Employee` int(11) NOT NULL,
  `Position` int(11) NOT NULL,
  `WorkStation` int(11) NOT NULL,
  `Department` int(11) NOT NULL,
  `Startdate` date NOT NULL,
  `PromotionLetter` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `qualification`
--

CREATE TABLE IF NOT EXISTS `qualification` (
`id` int(11) NOT NULL,
  `Employee` int(11) NOT NULL,
  `EducationLevel` int(11) NOT NULL,
  `College` varchar(200) NOT NULL,
  `Comment` text NOT NULL,
  `Attachment` varchar(200) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `qualification`
--

INSERT INTO `qualification` (`id`, `Employee`, `EducationLevel`, `College`, `Comment`, `Attachment`) VALUES
(1, 54, 1, 'AdeK Academy', '', '14323803418877-ApplicationDevelopment_PlanTemplate.doc'),
(2, 1, 3, 'Keller Graduate', 'c vvcv', '1432402284GPITG - THE COMPANY.docx');

-- --------------------------------------------------------

--
-- Table structure for table `religion`
--

CREATE TABLE IF NOT EXISTS `religion` (
`id` int(11) NOT NULL,
  `Name` varchar(300) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `religion`
--

INSERT INTO `religion` (`id`, `Name`) VALUES
(1, 'Lutheran'),
(2, 'Muslimu');

-- --------------------------------------------------------

--
-- Table structure for table `salary`
--

CREATE TABLE IF NOT EXISTS `salary` (
`id` int(11) NOT NULL,
  `Employee` int(11) NOT NULL,
  `SalaryGrade` int(11) NOT NULL,
  `Amount` double NOT NULL,
  `Promotion` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `salary`
--

INSERT INTO `salary` (`id`, `Employee`, `SalaryGrade`, `Amount`, `Promotion`) VALUES
(1, 1, 3, 3659000, 0),
(2, 2, 3, 509000, 0),
(3, 3, 2, 3819000, 0),
(4, 4, 3, 3652000, 0),
(5, 5, 1, 1038000, 0),
(6, 6, 2, 1892000, 0),
(7, 7, 2, 3498000, 0),
(8, 8, 2, 2005000, 0),
(9, 9, 3, 2047000, 0),
(10, 10, 2, 3644000, 0),
(11, 44, 2, 513000, 0),
(12, 43, 3, 2101000, 0),
(13, 42, 1, 996000, 0),
(14, 41, 3, 1988000, 0),
(15, 40, 1, 2600000, 0),
(16, 39, 2, 1883000, 0),
(17, 38, 2, 1038000, 0),
(18, 37, 1, 2159000, 0),
(19, 36, 2, 3632000, 0),
(20, 35, 1, 634000, 0),
(21, 34, 2, 2530000, 0),
(22, 33, 2, 2746000, 0),
(23, 32, 1, 3349000, 0),
(24, 31, 1, 3912000, 0),
(25, 30, 3, 3177000, 0),
(26, 45, 3, 3470000, 0),
(27, 46, 1, 2306000, 0),
(28, 47, 3, 2981000, 0),
(29, 48, 1, 1802000, 0);

-- --------------------------------------------------------

--
-- Table structure for table `salarycategory`
--

CREATE TABLE IF NOT EXISTS `salarycategory` (
`id` int(11) NOT NULL,
  `Name` varchar(200) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `salarycategory`
--

INSERT INTO `salarycategory` (`id`, `Name`) VALUES
(1, 'Earnings'),
(2, 'Deductions'),
(3, 'Employer Contributions');

-- --------------------------------------------------------

--
-- Table structure for table `salarygrade`
--

CREATE TABLE IF NOT EXISTS `salarygrade` (
`id` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Salary_Range` varchar(300) NOT NULL,
  `PAYEE` double NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `salarygrade`
--

INSERT INTO `salarygrade` (`id`, `Name`, `Salary_Range`, `PAYEE`) VALUES
(1, 'TSG', '0 - 170000', 0),
(2, 'TGP', '170000 - 540000', 600000),
(3, 'PTS', '540000 - 720000', 80000);

-- --------------------------------------------------------

--
-- Table structure for table `salaryitem`
--

CREATE TABLE IF NOT EXISTS `salaryitem` (
`id` int(11) NOT NULL,
  `Category` int(11) NOT NULL,
  `Name` varchar(200) NOT NULL,
  `Percent` double NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `salaryitem`
--

INSERT INTO `salaryitem` (`id`, `Category`, `Name`, `Percent`) VALUES
(1, 1, 'Basic Salary', 0),
(2, 1, 'Salary Arreas', 0),
(3, 1, 'Overtime', 5000),
(4, 1, 'Bonus', 0),
(5, 2, 'PAYEE', 10),
(6, 2, 'PAYEE Arreas', 0),
(7, 2, 'Work absence', 0),
(8, 2, 'Loan recovery', 0),
(9, 2, 'NSSF', 18),
(10, 3, 'NSSF Employer Contribution', 0);

-- --------------------------------------------------------

--
-- Table structure for table `salaryitemconfig`
--

CREATE TABLE IF NOT EXISTS `salaryitemconfig` (
`id` int(11) NOT NULL,
  `salaryitem_id` int(11) NOT NULL,
  `Employee` varchar(100) NOT NULL,
  `EmployeeContribution` double NOT NULL,
  `EmployerContribution` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `skiprepayment`
--

CREATE TABLE IF NOT EXISTS `skiprepayment` (
`id` int(11) NOT NULL,
  `Employee` varchar(100) NOT NULL,
  `Month` int(11) NOT NULL,
  `Year` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `training`
--

CREATE TABLE IF NOT EXISTS `training` (
`id` int(11) NOT NULL,
  `Employee` varchar(50) NOT NULL,
  `employee_auto` int(11) NOT NULL,
  `trainingtype` int(11) NOT NULL,
  `startdate` date NOT NULL,
  `enddate` date NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '1',
  `createdon` date NOT NULL,
  `createdby` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `training`
--

INSERT INTO `training` (`id`, `Employee`, `employee_auto`, `trainingtype`, `startdate`, `enddate`, `Status`, `createdon`, `createdby`) VALUES
(1, 'HRP/001', 1, 1, '2013-06-05', '2013-06-20', 1, '2013-06-19', 7),
(2, 'ADGPITG2015', 54, 2, '2015-05-22', '2015-06-05', 1, '2015-05-23', 13),
(3, 'ADGPITG2015', 54, 3, '2015-05-25', '2015-06-05', 1, '2015-05-23', 13),
(4, '00', 56, 2, '2015-05-23', '2015-05-26', 1, '2015-05-23', 13),
(5, 'HRP/016', 35, 3, '2015-05-21', '2015-05-28', 1, '2015-05-23', 13);

-- --------------------------------------------------------

--
-- Table structure for table `trainingstatus`
--

CREATE TABLE IF NOT EXISTS `trainingstatus` (
`id` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `trainingstatus`
--

INSERT INTO `trainingstatus` (`id`, `Name`) VALUES
(1, 'Assigned'),
(2, 'Attended');

-- --------------------------------------------------------

--
-- Table structure for table `trainingtype`
--

CREATE TABLE IF NOT EXISTS `trainingtype` (
`id` int(11) NOT NULL,
  `Name` varchar(200) NOT NULL,
  `Comment` text NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `trainingtype`
--

INSERT INTO `trainingtype` (`id`, `Name`, `Comment`) VALUES
(1, 'Training 1', 'No comentsxsxs'),
(2, 'eHMS02Recpt - Reception Works', 'Receptionists training on reception process. How to care for a patient at the reception level!'),
(3, 'eHMS02RevCnt - Revenue Center Works', 'Reception Work Receptionists training on the revenue management and processes. How to care for a patient at the cashier level!');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` mediumint(8) unsigned NOT NULL,
  `ip_address` int(10) unsigned NOT NULL,
  `username` varchar(100) NOT NULL,
  `EmployeeID` varchar(100) NOT NULL,
  `password` varchar(40) NOT NULL,
  `salt` varchar(40) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) unsigned NOT NULL,
  `last_login` int(11) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `department` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `ip_address`, `username`, `EmployeeID`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`, `department`) VALUES
(1, 2130706433, 'admin@admin.com', '00', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'admin@admin.com', NULL, NULL, NULL, 1268889823, 1433161168, 1, 'Admin', 'istrator', 'ADMIN', '0', 1),
(12, 2130706433, 'hr', 'HRP/002', 'a33699c8c74a2d98517139ee9d32da7ccd94de1a', NULL, 'hr@email.com', NULL, NULL, NULL, 1432361437, 1432379773, 1, 'Miltone', 'Urassa', 'KAIRUKI HOSPITAL', '--', 1),
(13, 2130706433, 'ade', 'HRP/016', 'fb1fe637faf5da9e182a18d1466702f476bbb137', NULL, 'ade@test.com', NULL, NULL, NULL, 1432371770, 1433161413, 1, 'ade', 'test', 'KAIRUKI HOSPITAL', '--', 1),
(14, 2130706433, 'test', 'HRP/017', '69ed2a080c9485344acc7cdfaeced4b70a06b90c', NULL, 'test@test.com', NULL, NULL, NULL, 1432378988, 1432379041, 1, 'test', 'test', 'KAIRUKI HOSPITAL', '--', 1),
(15, 2130706433, 'test2', 'HRP/024', '1e9bee1c7b4fcc0ca31c57d3cd5b121a5be3b309', NULL, 'test2@test.com', NULL, NULL, NULL, 1432379330, 1432379330, 1, 'test2', 'test2', 'KAIRUKI HOSPITAL', '--', 1),
(16, 2130706433, 'adaliah', 'ADGPITG2015', 'a4e10106da67c7da862d213f6711a0ed66f1bba3', NULL, 'adaliah@kairuki.com', NULL, NULL, NULL, 1432385253, 1432975666, 1, 'Adaliah', 'Kiliba', 'Kairuki Hospital', '--', 2),
(17, 2130706433, 'cecy', 'CecyGPITG2015', 'ec3ce393b0fd5877a03bea2bc4348d0cfefca89d', NULL, 'cecy@gpitg.com', NULL, NULL, NULL, 1432393085, 1432393097, 1, 'Cecyana', 'Kiliba', 'KAIRUKI HOSPITAL', '--', 1),
(18, 2130706433, 'kibadeni', 'HRP/018', '9742a89af3cec6c23e0d49163cf979ea01bd54da', NULL, 'kibadeni@gpitg.com', NULL, NULL, NULL, 1432898605, 1432976409, 1, 'abdallah', 'kibadeni', 'KAIRUKI HOSPITAL', '--', 1),
(19, 2130706433, 'leo', 'HRP/005', 'c40018c1a3b4ee33b59cc6fe125abc17ea1ae360', NULL, 'john@yahoo.com', NULL, NULL, NULL, 1432976611, 1432976825, 1, 'leonard', 'john', 'KAIRUKI HOSPITAL', '--', 8);

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

CREATE TABLE IF NOT EXISTS `users_groups` (
`id` mediumint(8) unsigned NOT NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_groups`
--

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
(1, 1, 1),
(18, 12, 3),
(20, 13, 6),
(22, 14, 1),
(24, 15, 6),
(26, 16, 4),
(28, 17, 1),
(30, 18, 3),
(32, 19, 6);

-- --------------------------------------------------------

--
-- Table structure for table `vacancy`
--

CREATE TABLE IF NOT EXISTS `vacancy` (
`id` int(11) NOT NULL,
  `Title` varchar(200) NOT NULL,
  `Description` text NOT NULL,
  `Attach` varchar(200) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vacancy`
--

INSERT INTO `vacancy` (`id`, `Title`, `Description`, `Attach`, `from_date`, `to_date`) VALUES
(1, 'Manager', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nam cursus. Morbi ut mi. Nullam enim leo, egestas id, condimentum at, laoreet mattis, massa. Sed eleifend nonummy diam. Praesent mauris ante, elementum et, bibendum at, posuere sit amet, nibh. Duis tincidunt lectus quis dui viverra vestibulum. Suspendisse vulputate aliquam dui', '', '2013-04-01', '2013-06-15'),
(2, 'Data entry', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nam cursus. Morbi ut mi. Nullam enim leo, egestas id, condimentum at, laoreet mattis, massa. Sed eleifend nonummy diam. Praesent mauris ante, elementum et, bibendum at, posuere sit amet, nibh. Duis tincidunt lectus quis dui viverra vestibulum. Suspendisse vulputate aliquam dui', '1368541945fi.po', '2013-05-01', '2015-06-18'),
(3, 'Chief Information Officer', 'Some Desc', '1432380541ABC Summer 2013 Flyer.docx', '2015-05-04', '2015-05-30'),
(4, 'Chief Software Engineer', '255717531539.kmcobnp', '1432381381ABC Summer 2013 Flyer.docx', '2015-05-20', '2015-05-30'),
(5, 'eHMS 2.0 Trainers', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nam cursus. Morbi ut mi. Nullam enim leo, egestas id, condimentum at, laoreet mattis, massa. Sed eleifend nonummy diam. Praesent mauris ante, elementum et, bibendum at, posuere sit amet, nibh. Duis tincidunt lectus quis dui viverra vestibulum. Suspendisse vulputate aliquam dui', '', '2015-05-20', '2015-05-30'),
(6, 'eHMS Nursing Developer for Android', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nam cursus. Morbi ut mi. Nullam enim leo, egestas id, condimentum at, laoreet mattis, massa. Sed eleifend nonummy diam. Praesent mauris ante, elementum et, bibendum at, posuere sit amet, nibh. Duis tincidunt lectus quis dui viverra vestibulum. Suspendisse vulputate aliquam dui', '1432445883HMS Brochure.doc', '2015-05-23', '2015-06-24'),
(7, 'Ngega Jobs', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nam cursus. Morbi ut mi. Nullam enim leo, egestas id, condimentum at, laoreet mattis, massa. Sed eleifend nonummy diam. Praesent mauris ante, elementum et, bibendum at, posuere sit amet, nibh. Duis tincidunt lectus quis dui viverra vestibulum. Suspendisse vulputate aliquam dui', '14328163471-Improve Your Skills and Managing Projects.docx', '2015-05-27', '2015-05-31'),
(8, 'Mzeru Jobs', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nam cursus. Morbi ut mi. Nullam enim leo, egestas id, condimentum at, laoreet mattis, massa. Sed eleifend nonummy diam. Praesent mauris ante, elementum et, bibendum at, posuere sit amet, nibh. Duis tincidunt lectus quis dui viverra vestibulum. Suspendisse vulputate aliquam dui', '14329037101-Improve Your Skills and Managing Projects.docx', '2015-05-28', '2015-07-31');

-- --------------------------------------------------------

--
-- Table structure for table `workstation`
--

CREATE TABLE IF NOT EXISTS `workstation` (
`id` int(11) NOT NULL,
  `Name` varchar(200) NOT NULL,
  `Description` varchar(500) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `workstation`
--

INSERT INTO `workstation` (`id`, `Name`, `Description`) VALUES
(1, 'KH - Mikocheni Branch', 'KH - Mikocheni Branch'),
(2, 'KH - Sinza Branch', 'KH - Sinza Branch');

-- --------------------------------------------------------

--
-- Table structure for table `year`
--

CREATE TABLE IF NOT EXISTS `year` (
`id` int(11) NOT NULL,
  `Name` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `year`
--

INSERT INTO `year` (`id`, `Name`) VALUES
(1, 2013),
(2, 2015);

-- --------------------------------------------------------

--
-- Structure for view `employee_view`
--
DROP TABLE IF EXISTS `employee_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `employee_view` AS select `e`.`id` AS `id`,`e`.`EmployeeId` AS `EmployeeId`,`e`.`FirstName` AS `FirstName`,`e`.`MiddleName` AS `MiddleName`,`e`.`LastName` AS `LastName`,`e`.`Sex` AS `Sex`,`e`.`dob` AS `dob`,`e`.`EducationLevel` AS `EducationLevel`,`e`.`Religion` AS `Religion`,`e`.`MaritalStatus` AS `MaritalStatus`,(case when ((select `promotion`.`Department` from `promotion` where ((`e`.`id` = `promotion`.`Employee`) and (`promotion`.`id` = (select max(`promotion`.`id`) from `promotion` where (`e`.`id` = `promotion`.`Employee`))))) > 0) then (select `promotion`.`Department` from `promotion` where ((`e`.`id` = `promotion`.`Employee`) and (`promotion`.`id` = (select max(`promotion`.`id`) from `promotion` where (`e`.`id` = `promotion`.`Employee`))))) else `j`.`Department` end) AS `Department`,(case when ((select `promotion`.`Position` from `promotion` where ((`e`.`id` = `promotion`.`Employee`) and (`promotion`.`id` = (select max(`promotion`.`id`) from `promotion` where (`e`.`id` = `promotion`.`Employee`))))) > 0) then (select `promotion`.`Position` from `promotion` where ((`e`.`id` = `promotion`.`Employee`) and (`promotion`.`id` = (select max(`promotion`.`id`) from `promotion` where (`e`.`id` = `promotion`.`Employee`))))) else `j`.`Position` end) AS `Position`,(case when ((select `promotion`.`WorkStation` from `promotion` where ((`e`.`id` = `promotion`.`Employee`) and (`promotion`.`id` = (select max(`promotion`.`id`) from `promotion` where (`e`.`id` = `promotion`.`Employee`))))) > 0) then (select `promotion`.`WorkStation` from `promotion` where ((`e`.`id` = `promotion`.`Employee`) and (`promotion`.`id` = (select max(`promotion`.`id`) from `promotion` where (`e`.`id` = `promotion`.`Employee`))))) else `j`.`WorkStation` end) AS `WorkStation`,`j`.`ContractType` AS `ContractType`,`j`.`Enddate` AS `Enddate`,`s`.`Amount` AS `Amount`,`s`.`SalaryGrade` AS `SalaryGrade`,(case when (floor(((to_days(now()) - to_days((select `employee`.`dob` from `employee` where (`employee`.`id` = `e`.`id`)))) / 365)) > 55) then 1 when (`j`.`Enddate` < now()) then 1 else 0 end) AS `Retere`,(case when (1 > 0) then floor(((to_days(now()) - to_days((select `employee`.`dob` from `employee` where (`employee`.`id` = `e`.`id`)))) / 365)) else 0 end) AS `Age`,`c`.`Street` AS `Street`,`c`.`Postal` AS `Postal`,`c`.`Email` AS `Email`,`c`.`Mobile` AS `Mobile`,`c`.`District` AS `District`,`c`.`Region` AS `Region` from (((`employee` `e` left join `job` `j` on((`e`.`id` = `j`.`Employee`))) left join `salary` `s` on((`e`.`id` = `s`.`Employee`))) left join `contact` `c` on((`e`.`id` = `c`.`Employee`)));

-- --------------------------------------------------------

--
-- Structure for view `leave_view`
--
DROP TABLE IF EXISTS `leave_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `leave_view` AS select `l`.`id` AS `id`,`l`.`Employee` AS `Employee`,`l`.`LeaveType` AS `LeaveType`,`l`.`Fromdate` AS `Fromdate`,`l`.`Todate` AS `Todate`,`l`.`is_active` AS `is_active`,`l`.`is_approved` AS `is_approved`,(to_days(`l`.`Todate`) - to_days(`l`.`Fromdate`)) AS `days`,(case when (((to_days(`l`.`Todate`) - to_days(`l`.`Fromdate`)) - (to_days(now()) - to_days(`l`.`Fromdate`))) > 0) then ((to_days(`l`.`Todate`) - to_days(`l`.`Fromdate`)) - (to_days(now()) - to_days(`l`.`Fromdate`))) else 0 end) AS `day_remain` from `leave_info` `l`;

-- --------------------------------------------------------

--
-- Structure for view `loanclose_view`
--
DROP TABLE IF EXISTS `loanclose_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `loanclose_view` AS select `l`.`id` AS `id`,`l`.`Employeeid` AS `Employeeid`,`l`.`Loan_Number` AS `Loan_Number`,`l`.`Employee` AS `Employee`,`l`.`Loan_Amount` AS `Loan_Amount`,`l`.`Month_D` AS `Month_D`,`l`.`Year_D` AS `Year_D`,(select sum(`pp`.`Amount`) from `loan_payment` `pp` where ((`l`.`Employee` = `pp`.`Employee`) and (`l`.`Loan_Number` = `pp`.`Loan`) and (`l`.`delivery` = 1))) AS `Paid`,(case when ((select sum(`pp`.`Amount`) from `loan_payment` `pp` where ((`l`.`Employee` = `pp`.`Employee`) and (`l`.`Loan_Number` = `pp`.`Loan`) and (`l`.`delivery` = 1))) >= `l`.`Loan_Amount`) then 1 else 0 end) AS `is_close`,(case when (1 > 0) then (`l`.`Month_D` + `l`.`Year_D`) else (`l`.`Month_D` + `l`.`Year_D`) end) AS `TEST` from `loan` `l` where (`l`.`delivery` = 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `application`
--
ALTER TABLE `application`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attachment`
--
ALTER TABLE `attachment`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contracttype`
--
ALTER TABLE `contracttype`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dependent`
--
ALTER TABLE `dependent`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discipline`
--
ALTER TABLE `discipline`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `education`
--
ALTER TABLE `education`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emergencycontact`
--
ALTER TABLE `emergencycontact`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `EmployeeId` (`EmployeeId`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job`
--
ALTER TABLE `job`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kpi_category`
--
ALTER TABLE `kpi_category`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kpi_category_indicator`
--
ALTER TABLE `kpi_category_indicator`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kpi_employee`
--
ALTER TABLE `kpi_employee`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kpi_indicator`
--
ALTER TABLE `kpi_indicator`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kpi_record`
--
ALTER TABLE `kpi_record`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kpi_record_employee`
--
ALTER TABLE `kpi_record_employee`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kpi_status`
--
ALTER TABLE `kpi_status`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leaveroster`
--
ALTER TABLE `leaveroster`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leavetype`
--
ALTER TABLE `leavetype`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leave_info`
--
ALTER TABLE `leave_info`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan`
--
ALTER TABLE `loan`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_payment`
--
ALTER TABLE `loan_payment`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `maritalstatus`
--
ALTER TABLE `maritalstatus`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nssf`
--
ALTER TABLE `nssf`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `overtime`
--
ALTER TABLE `overtime`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payee_config`
--
ALTER TABLE `payee_config`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `position`
--
ALTER TABLE `position`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `promotion`
--
ALTER TABLE `promotion`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qualification`
--
ALTER TABLE `qualification`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `religion`
--
ALTER TABLE `religion`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salary`
--
ALTER TABLE `salary`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salarycategory`
--
ALTER TABLE `salarycategory`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salarygrade`
--
ALTER TABLE `salarygrade`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salaryitem`
--
ALTER TABLE `salaryitem`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salaryitemconfig`
--
ALTER TABLE `salaryitemconfig`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `skiprepayment`
--
ALTER TABLE `skiprepayment`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `training`
--
ALTER TABLE `training`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trainingstatus`
--
ALTER TABLE `trainingstatus`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trainingtype`
--
ALTER TABLE `trainingtype`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_groups`
--
ALTER TABLE `users_groups`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vacancy`
--
ALTER TABLE `vacancy`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workstation`
--
ALTER TABLE `workstation`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `year`
--
ALTER TABLE `year`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `application`
--
ALTER TABLE `application`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `attachment`
--
ALTER TABLE `attachment`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `contracttype`
--
ALTER TABLE `contracttype`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `dependent`
--
ALTER TABLE `dependent`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `discipline`
--
ALTER TABLE `discipline`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `education`
--
ALTER TABLE `education`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `emergencycontact`
--
ALTER TABLE `emergencycontact`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=58;
--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `job`
--
ALTER TABLE `job`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `kpi_category`
--
ALTER TABLE `kpi_category`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `kpi_category_indicator`
--
ALTER TABLE `kpi_category_indicator`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `kpi_employee`
--
ALTER TABLE `kpi_employee`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `kpi_indicator`
--
ALTER TABLE `kpi_indicator`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `kpi_record`
--
ALTER TABLE `kpi_record`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=43;
--
-- AUTO_INCREMENT for table `kpi_record_employee`
--
ALTER TABLE `kpi_record_employee`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `kpi_status`
--
ALTER TABLE `kpi_status`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `leaveroster`
--
ALTER TABLE `leaveroster`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `leavetype`
--
ALTER TABLE `leavetype`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `leave_info`
--
ALTER TABLE `leave_info`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `loan`
--
ALTER TABLE `loan`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `loan_payment`
--
ALTER TABLE `loan_payment`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=177;
--
-- AUTO_INCREMENT for table `maritalstatus`
--
ALTER TABLE `maritalstatus`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `nssf`
--
ALTER TABLE `nssf`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `overtime`
--
ALTER TABLE `overtime`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `payee_config`
--
ALTER TABLE `payee_config`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=560;
--
-- AUTO_INCREMENT for table `position`
--
ALTER TABLE `position`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `promotion`
--
ALTER TABLE `promotion`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `qualification`
--
ALTER TABLE `qualification`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `religion`
--
ALTER TABLE `religion`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `salary`
--
ALTER TABLE `salary`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `salarycategory`
--
ALTER TABLE `salarycategory`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `salarygrade`
--
ALTER TABLE `salarygrade`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `salaryitem`
--
ALTER TABLE `salaryitem`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `salaryitemconfig`
--
ALTER TABLE `salaryitemconfig`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `skiprepayment`
--
ALTER TABLE `skiprepayment`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `training`
--
ALTER TABLE `training`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `trainingstatus`
--
ALTER TABLE `trainingstatus`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `trainingtype`
--
ALTER TABLE `trainingtype`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `users_groups`
--
ALTER TABLE `users_groups`
MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `vacancy`
--
ALTER TABLE `vacancy`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `workstation`
--
ALTER TABLE `workstation`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `year`
--
ALTER TABLE `year`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
