-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 02, 2020 at 11:28 PM
-- Server version: 8.0.21-0ubuntu0.20.04.3
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
-- Database: `ehms_kcmc`
--

-- --------------------------------------------------------

--
-- Table structure for table `pediatric_graph`
--

CREATE TABLE `pediatric_graph` (
  `pediatric_graph_ID` int NOT NULL,
  `heart_rate` decimal(10,0) NOT NULL,
  `respiratory_rate` decimal(10,0) NOT NULL,
  `pso2` decimal(10,0) NOT NULL,
  `temperature` decimal(10,0) NOT NULL,
  `blood_pressure_sytolic` decimal(10,0) NOT NULL,
  `blood_pressure_diasotlic` decimal(10,0) NOT NULL,
  `pulse_pressure` decimal(10,0) NOT NULL,
  `map` decimal(10,0) NOT NULL,
  `saved_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `time_min` varchar(50) NOT NULL,
  `Registration_ID` int NOT NULL,
  `Employee_ID` int NOT NULL,
  `consultation_ID` int NOT NULL
);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pediatric_graph`
--
ALTER TABLE `pediatric_graph`
  ADD PRIMARY KEY (`pediatric_graph_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pediatric_graph`
--
ALTER TABLE `pediatric_graph`
  MODIFY `pediatric_graph_ID` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
