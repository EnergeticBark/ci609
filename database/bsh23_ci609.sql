-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 24, 2025 at 02:43 PM
-- Server version: 8.0.43
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bsh23_ci609`
--

-- --------------------------------------------------------

--
-- Table structure for table `deathType`
--

CREATE TABLE `deathType` (
  `id` int NOT NULL,
  `name` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_bin;

--
-- Dumping data for table `deathType`
--

INSERT INTO `deathType` (`id`, `name`) VALUES
(1, 'fence'),
(2, 'fenceElectrocuted'),
(3, 'road'),
(4, 'other');

-- --------------------------------------------------------

--
-- Table structure for table `sighting`
--

CREATE TABLE `sighting` (
  `id` int NOT NULL,
  `deathType` int DEFAULT NULL,
  `time` datetime NOT NULL,
  `location` point NOT NULL,
  `accuracy` double NOT NULL,
  `image` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_bin NOT NULL,
  `notes` varchar(10000) COLLATE utf8mb4_0900_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_bin;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `deathType`
--
ALTER TABLE `deathType`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sighting`
--
ALTER TABLE `sighting`
  ADD PRIMARY KEY (`id`),
  ADD KEY `death` (`deathType`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `deathType`
--
ALTER TABLE `deathType`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sighting`
--
ALTER TABLE `sighting`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sighting`
--
ALTER TABLE `sighting`
  ADD CONSTRAINT `sighting_ibfk_1` FOREIGN KEY (`deathType`) REFERENCES `deathType` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
