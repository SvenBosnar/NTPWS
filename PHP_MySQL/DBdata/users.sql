-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 01, 2025 at 12:48 PM
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
-- Database: `users`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `country` varchar(2) NOT NULL,
  `birth_date` date NOT NULL,
  `password` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `role` enum('user','editor','admin') DEFAULT 'user',
  `is_activated` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `country`, `birth_date`, `password`, `username`, `role`, `is_activated`) VALUES
(3, 'SVEN', 'BOSNAR', 'sven.bosnar@gmail.com', 'Cr', '1998-05-25', '$2y$10$R6c3F1ipIoPi0fKOALUXJOOTijgncTqrBEP5JRlVn/QzJ4bcHTja6', 'sbosnar', 'admin', 1),
(4, 'Sven2', 'Bosnar2', 'bosnar.sven@gmail.com', 'Ba', '1989-05-25', '$2y$10$zOGg6mQjUyiEziP9SdsH9.RdHCtMf2cKH1CagrT5v9B.Ug.DHlYKG', 'sbosnar2', 'editor', 1),
(5, 'sven3', 'bosnar3', 'sven.izmisljeni@gmail.com', 'Cr', '1999-05-25', '$2y$10$O9KgQvKiZ.1dv0KkTH8Owet7h.iBcWA5b.KwDaw6mcMZjITKKrZ0S', 'sbosnar3', 'user', 1),
(6, 'Sven', 'Test', 'sven.test@gmail.com', 'Va', '1998-05-30', '$2y$10$K0QuOczkLapwFrUSLmV5Ce27AW4em0FqGisUGK.Rx/XH/aJx/GAFm', 'stest', 'user', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
