-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 12, 2019 at 11:18 AM
-- Server version: 5.6.35-1+deb.sury.org~xenial+0.1
-- PHP Version: 5.6.39-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hrp`
--

-- --------------------------------------------------------

--
-- Table structure for table `users_from_ehms`
--

CREATE TABLE `users_from_ehms` (
  `id` int(11) NOT NULL,
  `ehms_user_id` int(11) NOT NULL,
  `hrp_user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_from_ehms`
--

INSERT INTO `users_from_ehms` (`id`, `ehms_user_id`, `hrp_user_id`) VALUES
(1, 2712, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users_from_ehms`
--
ALTER TABLE `users_from_ehms`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users_from_ehms`
--
ALTER TABLE `users_from_ehms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
