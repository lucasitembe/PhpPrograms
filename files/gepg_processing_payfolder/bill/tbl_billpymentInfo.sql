-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 24, 2018 at 01:43 PM
-- Server version: 5.6.41
-- PHP Version: 5.6.38-2+ubuntu18.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gepg`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_billpymentInfo`
--

CREATE TABLE `tbl_billpymentInfo` (
  `TrxId` varchar(100) DEFAULT NULL,
  `SpCode` varchar(6) DEFAULT NULL,
  `PayRefId` varchar(100) DEFAULT NULL,
  `BillId` varchar(100) DEFAULT NULL,
  `PayCtrNum` varchar(15) DEFAULT NULL,
  `BillAmt` double DEFAULT NULL,
  `PaidAmt` double DEFAULT NULL,
  `BillPayOpt` varchar(15) DEFAULT NULL,
  `CCy` varchar(4) DEFAULT NULL,
  `TrxDtTm` datetime DEFAULT CURRENT_TIMESTAMP,
  `UsdPayChn` varchar(60) DEFAULT NULL,
  `PyrCellNum` varchar(12) DEFAULT NULL,
  `PyrName` varchar(200) DEFAULT NULL,
  `PyrEmail` varchar(100) DEFAULT NULL,
  `PspReceiptNumber` varchar(100) DEFAULT NULL,
  `PspName` varchar(100) DEFAULT NULL,
  `CtrAccNum` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
