-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 12, 2021 at 11:14 AM
-- Server version: 8.0.21-0ubuntu0.20.04.4
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ehms_muganyizi`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_expemption_phro`
--

CREATE TABLE `tbl_expemption_phro` (
  `PHRO_ID` int NOT NULL,
  `Exemption_ID` int NOT NULL,
  `tathiminiyaPHRO` text NOT NULL,
  `Employee_ID` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_expemption_phro`
--
ALTER TABLE `tbl_expemption_phro`
  ADD PRIMARY KEY (`PHRO_ID`),
  ADD KEY `PHRO_ID` (`PHRO_ID`),
  ADD KEY `Exemption_ID` (`Exemption_ID`),
  ADD KEY `Employee_ID` (`Employee_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_expemption_phro`
--
ALTER TABLE `tbl_expemption_phro`
  MODIFY `PHRO_ID` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_expemption_phro`
--
ALTER TABLE `tbl_expemption_phro`
  ADD CONSTRAINT `tbl_expemption_phro_ibfk_1` FOREIGN KEY (`Exemption_ID`) REFERENCES `tbl_temporary_exemption_form` (`Exemption_ID`),
  ADD CONSTRAINT `tbl_expemption_phro_ibfk_2` FOREIGN KEY (`Employee_ID`) REFERENCES `tbl_employee` (`Employee_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
