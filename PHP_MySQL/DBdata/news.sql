-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 01, 2025 at 12:47 PM
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
-- Database: `news`
--

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_archived` tinyint(1) DEFAULT 0,
  `user_id` int(11) NOT NULL,
  `status` enum('pending','approved') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `created_date`, `is_archived`, `user_id`, `status`) VALUES
(9, 'Kalendar za sezonu 2025!', 'Nova sezona svjetskog rally prvenstva donosi niz promjena koje će uzbuditi ljubitelje ovog sporta diljem svijeta. Uvođenje novih pravila, povratak legendarnih vozača i potpuno novi kalendar obećavaju uzbudljivu sezonu.\r\n\r\nNajveća novost je izbacivanje hibridnih motora, što će učiniti vozila lakšima i agilnijima. Uz to, u kalendaru se pojavljuje Saudijska Arabija kao nova destinacija, donoseći izazovne uvjete i potpuno novo iskustvo za natjecatelje.\r\n\r\nKalle Rovanperä vraća se s punim angažmanom, dok Sebastien Ogier smanjuje svoj raspored. Bit će zanimljivo vidjeti kako će se odvijati borba za naslov u ovoj dinamičnoj sezoni.\r\n\r\nZa više informacija o nadolazećim utrkama i ekskluzivnim intervjuima, pratite nas i dalje!', '2025-02-27 20:56:38', 0, 3, 'approved'),
(10, 'Novi prvak - Thierry Neuville!', 'Nakon 13 uzbudljivih utrka, Thierry Neuville okrunjen je prvakom! Od Monte Carla, do Japana zadržao je prvo mjesto u ukupnom poretku.\r\n\r\nPrijetili su i Sebastien Ogier, koji je pred kraj sezone odabrao voziti full-time, kako bi spasio Toyotine šanse za titulu vozača, te njegov timski kolega Ott Tanak. Naposlijetku, Tanak je ostao kao jedina i najveća konkurencija no greška na samom kraju Rally Japana koštala ga je titule vozača a Hyundai titule proizvođača.\r\n\r\nVeliki uspijeh proslavio je uz suvozača Martijna Wydaeghea te drugu Hyundai-evu posadu, Mikkelsen-Erikssen. Poznato je kako su Thierry i Andreas i inače dobri prijatelji pa se vidjela iskrena radost. S druge strane, Ott Tanak razočarano je sjedio u blizini slupanog i20N Rally1 Hybrida.\r\n\r\nBorba za titulu prvaka proizvođača odvijala se sve do posljednjeg brzinskog ispita. Ogier je briljirao i postavio najbrže vrijeme Powerstage-a dok je Evans odnio pobjedu.\r\n\r\nIduće sezone čeka nas još zanimljivije prvenstvo obzirom da se dvostruki svjetski prvak Kalle Rovanpera vraća, te će voziti punu sezonu. Može li Thierry Neuville obraniti titulu? Hoćemo li vidjeti novog prvaka kao Elfyna Evansa ili Adriena Fourmauxa? Hoćemo li gledati nove/stare prvake Rovanperu ili Tanaka? Saznat ćemo uskoro u Monacu!', '2025-02-27 20:58:43', 0, 3, 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `news_images`
--

CREATE TABLE `news_images` (
  `id` int(11) NOT NULL,
  `news_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news_images`
--

INSERT INTO `news_images` (`id`, `news_id`, `image_url`, `created_at`) VALUES
(3, 9, 'uploads/img_67c0d1b4d7c025.87531290.jpg', '2025-02-27 20:57:24'),
(4, 10, 'uploads/img_67c0d210c53638.89421225.jpeg', '2025-02-27 20:58:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `news_images`
--
ALTER TABLE `news_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `news_id` (`news_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `news_images`
--
ALTER TABLE `news_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `news_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users`.`users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `news_images`
--
ALTER TABLE `news_images`
  ADD CONSTRAINT `news_images_ibfk_1` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
