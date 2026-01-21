-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 23, 2024 at 03:15 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sehat_yuk`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(5, 'aflahadityo', '$2y$10$k3BxDyKCt12PRR3rO.sp8eTHMpH8zbcmnAY3jxqi2Y8b1.HPh88vi'),
(6, 'KEV', '$2y$10$jZFkit.DHBPSiY/R5iIYieCrGaL/sNGKiLWxbi3lm6pgtwpgAg4jC');

-- --------------------------------------------------------

--
-- Table structure for table `resep`
--

CREATE TABLE `resep` (
  `ID` int(10) NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `judul` varchar(100) DEFAULT NULL,
  `porsi` varchar(5) DEFAULT NULL,
  `kalori` varchar(10) DEFAULT NULL,
  `protein` varchar(10) DEFAULT NULL,
  `lemak` varchar(10) DEFAULT NULL,
  `karbo` varchar(10) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `bahan` text DEFAULT NULL,
  `langkah` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resep`
--

INSERT INTO `resep` (`ID`, `gambar`, `judul`, `porsi`, `kalori`, `protein`, `lemak`, `karbo`, `deskripsi`, `bahan`, `langkah`) VALUES
(0, 'uploads/', 'bandeng', '50', '50', '100', '100', '100', 'test', 'test', 'test'),
(0, 'uploads/download.jpg', 'jambu', '10', '10', '10', '10', '10', 'enak', 'bergizi', 'enak');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `resep`
--
ALTER TABLE `resep`
  ADD PRIMARY KEY (`gambar`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
