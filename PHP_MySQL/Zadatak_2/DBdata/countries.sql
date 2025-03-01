-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 18, 2025 at 06:29 PM
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
-- Database: `countries`
--

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `country_name` varchar(100) NOT NULL,
  `country_code` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `country_name`, `country_code`) VALUES
(1, 'Afghanistan', 'AFG'),
(2, 'Albania', 'ALB'),
(3, 'Algeria', 'DZA'),
(4, 'Andorra', 'AND'),
(5, 'Angola', 'AGO'),
(6, 'Antigua & Barbuda', 'ATG'),
(7, 'Argentina', 'ARG'),
(8, 'Armenia', 'ARM'),
(9, 'Australia', 'AUS'),
(10, 'Austria', 'AUT'),
(11, 'Azerbaijan', 'AZE'),
(12, 'Bahamas', 'BHS'),
(13, 'Bahrain', 'BHR'),
(14, 'Bangladesh', 'BGD'),
(15, 'Barbados', 'BRB'),
(16, 'Belarus', 'BLR'),
(17, 'Belgium', 'BEL'),
(18, 'Belize', 'BLZ'),
(19, 'Benin', 'BEN'),
(20, 'Bhutan', 'BTN'),
(21, 'Bolivia', 'BOL'),
(22, 'Bosnia & Herzegovina', 'BIH'),
(23, 'Botswana', 'BWA'),
(24, 'Brazil', 'BRA'),
(25, 'Brunei', 'BRN'),
(26, 'Bulgaria', 'BGR'),
(27, 'Burkina Faso', 'BFA'),
(28, 'Burundi', 'BDI'),
(29, 'Cabo Verde', 'CPV'),
(30, 'Cambodia', 'KHM'),
(31, 'Cameroon', 'CMR'),
(32, 'Canada', 'CAN'),
(33, 'Central African Republic', 'CAF'),
(34, 'Chad', 'TCD'),
(35, 'Chile', 'CHL'),
(36, 'China', 'CHN'),
(37, 'Colombia', 'COL'),
(38, 'Comoros', 'COM'),
(39, 'Congo', 'COG'),
(40, 'Costa Rica', 'CRI'),
(41, 'Côte d\'Ivoire', 'CIV'),
(42, 'Croatia', 'HRV'),
(43, 'Cuba', 'CUB'),
(44, 'Cyprus', 'CYP'),
(45, 'Czech Republic', 'CZE'),
(46, 'Denmark', 'DNK'),
(47, 'Djibouti', 'DJI'),
(48, 'Dominica', 'DMA'),
(49, 'Dominican Republic', 'DOM'),
(50, 'DR Congo', 'COD'),
(51, 'Ecuador', 'ECU'),
(52, 'Egypt', 'EGY'),
(53, 'El Salvador', 'SLV'),
(54, 'Equatorial Guinea', 'GNQ'),
(55, 'Eritrea', 'ERI'),
(56, 'Estonia', 'EST'),
(57, 'Eswatini', 'SWZ'),
(58, 'Ethiopia', 'ETH'),
(59, 'Fiji', 'FJI'),
(60, 'Finland', 'FIN'),
(61, 'France', 'FRA'),
(62, 'Gabon', 'GAB'),
(63, 'Gambia', 'GMB'),
(64, 'Georgia', 'GEO'),
(65, 'Germany', 'DEU'),
(66, 'Ghana', 'GHA'),
(67, 'Greece', 'GRC'),
(68, 'Grenada', 'GRD'),
(69, 'Guatemala', 'GTM'),
(70, 'Guinea', 'GIN'),
(71, 'Guinea-Bissau', 'GNB'),
(72, 'Guyana', 'GUY'),
(73, 'Haiti', 'HTI'),
(74, 'Holy See', 'VAT'),
(75, 'Honduras', 'HND'),
(76, 'Hungary', 'HUN'),
(77, 'Iceland', 'ISL'),
(78, 'India', 'IND'),
(79, 'Indonesia', 'IDN'),
(80, 'Iran', 'IRN'),
(81, 'Iraq', 'IRQ'),
(82, 'Ireland', 'IRL'),
(83, 'Israel', 'ISR'),
(84, 'Italy', 'ITA'),
(85, 'Jamaica', 'JAM'),
(86, 'Japan', 'JPN'),
(87, 'Jordan', 'JOR'),
(88, 'Kazakhstan', 'KAZ'),
(89, 'Kenya', 'KEN'),
(90, 'Kiribati', 'KIR'),
(91, 'Kosovo', 'XKK'),
(92, 'Kuwait', 'KWT'),
(93, 'Kyrgyzstan', 'KGZ'),
(94, 'Laos', 'LAO'),
(95, 'Latvia', 'LVA'),
(96, 'Lebanon', 'LBN'),
(97, 'Lesotho', 'LSO'),
(98, 'Liberia', 'LBR'),
(99, 'Libya', 'LBY'),
(100, 'Liechtenstein', 'LIE'),
(101, 'Lithuania', 'LTU'),
(102, 'Luxembourg', 'LUX'),
(103, 'Madagascar', 'MDG'),
(104, 'Malawi', 'MWI'),
(105, 'Malaysia', 'MYS'),
(106, 'Maldives', 'MDV'),
(107, 'Mali', 'MLI'),
(108, 'Malta', 'MLT'),
(109, 'Marshall Islands', 'MHL'),
(110, 'Mauritania', 'MRT'),
(111, 'Mauritius', 'MUS'),
(112, 'Mexico', 'MEX'),
(113, 'Micronesia', 'FSM'),
(114, 'Moldova', 'MDA'),
(115, 'Monaco', 'MCO'),
(116, 'Mongolia', 'MNG'),
(117, 'Montenegro', 'MNE'),
(118, 'Morocco', 'MAR'),
(119, 'Mozambique', 'MOZ'),
(120, 'Myanmar', 'MMR'),
(121, 'Namibia', 'NAM'),
(122, 'Nauru', 'NRU'),
(123, 'Nepal', 'NPL'),
(124, 'Netherlands', 'NLD'),
(125, 'New Zealand', 'NZL'),
(126, 'Nicaragua', 'NIC'),
(127, 'Niger', 'NER'),
(128, 'Nigeria', 'NGA'),
(129, 'North Korea', 'PRK'),
(130, 'North Macedonia', 'MKD'),
(131, 'Norway', 'NOR'),
(132, 'Oman', 'OMN'),
(133, 'Pakistan', 'PAK'),
(134, 'Palau', 'PLW'),
(135, 'Panama', 'PAN'),
(136, 'Papua New Guinea', 'PNG'),
(137, 'Paraguay', 'PRY'),
(138, 'Peru', 'PER'),
(139, 'Philippines', 'PHL'),
(140, 'Poland', 'POL'),
(141, 'Portugal', 'PRT'),
(142, 'Qatar', 'QAT'),
(143, 'Romania', 'ROM'),
(144, 'Russia', 'RUS'),
(145, 'Rwanda', 'RWA'),
(146, 'Saint Kitts & Nevis', 'KNA'),
(147, 'Saint Lucia', 'LCA'),
(148, 'Samoa', 'WSM'),
(149, 'San Marino', 'SMR'),
(150, 'Sao Tome & Principe', 'STP'),
(151, 'Saudi Arabia', 'SAU'),
(152, 'Senegal', 'SEN'),
(153, 'Serbia', 'SRB'),
(154, 'Seychelles', 'SYC'),
(155, 'Sierra Leone', 'SLE'),
(156, 'Singapore', 'SGP'),
(157, 'Slovakia', 'SVK'),
(158, 'Slovenia', 'SVN'),
(159, 'Solomon Islands', 'SLB'),
(160, 'Somalia', 'SOM'),
(161, 'South Africa', 'ZAF'),
(162, 'South Korea', 'KOR'),
(163, 'South Sudan', 'SSD'),
(164, 'Spain', 'ESP'),
(165, 'Sri Lanka', 'LKA'),
(166, 'Saint Vincent and the Grenadines', 'VCT'),
(167, 'State of Palestine', 'PSE'),
(168, 'Sudan', 'SDN'),
(169, 'Suriname', 'SUR'),
(170, 'Sweden', 'SWE'),
(171, 'Switzerland', 'CHE'),
(172, 'Syria', 'SYR'),
(173, 'Taiwan', 'TWN'),
(174, 'Tajikistan', 'TJK'),
(175, 'Tanzania', 'TZA'),
(176, 'Thailand', 'THA'),
(177, 'Timor-Leste', 'TLS'),
(178, 'Togo', 'TGO'),
(179, 'Tonga', 'TON'),
(180, 'Trinidad & Tobago', 'TTO'),
(181, 'Tunisia', 'TUN'),
(182, 'Turkey', 'TUR'),
(183, 'Turkmenistan', 'TKM'),
(184, 'Tuvalu', 'TUV'),
(185, 'Uganda', 'UGA'),
(186, 'Ukraine', 'UKR'),
(187, 'United Arab Emirates', 'ARE'),
(188, 'United Kingdom', 'GBR'),
(189, 'United States', 'USA'),
(190, 'Uruguay', 'URY'),
(191, 'Uzbekistan', 'UZB'),
(192, 'Vanuatu', 'VUT'),
(193, 'Venezuela', 'VEN'),
(194, 'Vietnam', 'VNM'),
(195, 'Yemen', 'YEM'),
(196, 'Zambia', 'ZMB'),
(197, 'Zimbabwe', 'ZWE');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `country_code` (`country_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=198;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
