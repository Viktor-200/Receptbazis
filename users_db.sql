-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 12, 2026 at 12:09 PM
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
-- Database: `users_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `felhasznalok`
--

CREATE TABLE `felhasznalok` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_pic` varchar(255) DEFAULT 'img/alap.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Dumping data for table `felhasznalok`
--

INSERT INTO `felhasznalok` (`id`, `username`, `password`, `created_at`, `profile_pic`) VALUES
(1, 'Viktor', '$2y$10$mU3Y7jH4fPc91Lt1Q9DP3OsBS1CIBskGp8.BQzbc2cexKfowVUqXK', '2026-01-26 18:50:21', 'uploads/user_1770894467_1285.png');

-- --------------------------------------------------------

--
-- Table structure for table `hozzaszolasok`
--

CREATE TABLE `hozzaszolasok` (
  `id` int(11) NOT NULL,
  `recept_id` int(11) DEFAULT NULL,
  `felhasznalo_id` int(10) UNSIGNED NOT NULL,
  `szoveg` mediumtext NOT NULL,
  `datum` timestamp NOT NULL DEFAULT current_timestamp(),
  `oldal_tipus` varchar(20) DEFAULT 'recept'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Dumping data for table `hozzaszolasok`
--

INSERT INTO `hozzaszolasok` (`id`, `recept_id`, `felhasznalo_id`, `szoveg`, `datum`, `oldal_tipus`) VALUES
(37, 2, 1, 'haha', '2026-02-12 10:34:32', 'recept');

-- --------------------------------------------------------

--
-- Table structure for table `receptek`
--

CREATE TABLE `receptek` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `uploaded_by` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- Dumping data for table `receptek`
--

INSERT INTO `receptek` (`id`, `user_id`, `title`, `description`, `image_path`, `uploaded_by`, `created_at`) VALUES
(2, 0, 'Rakott Krumpli', 'Hozzávalók 3 személyre\r\n\r\n    Krumpli: 1 – 1,2 kg (lehetőleg sárga, főzni való fajta)\r\n\r\n    Tojás: 5-6 darab\r\n\r\n    Kolbász: 15-20 dkg (füstölt, lángolt kolbász a legjobb)\r\n\r\n    Tejföl: 450-500 g (egy nagy pohár)\r\n\r\n    Zsiradék: Kevés vaj vagy zsír a tepsi kikenéséhez\r\n\r\n    Fűszerek: Só, bors, ízlés szerint egy kevés pirospaprika a tejfölbe\r\n\r\nAz elkészítés lépései\r\n\r\n    Előkészítés: A krumplit héjában, sós vízben tedd fel főni. Egy másik edényben főzd keményre a tojásokat (forrástól számítva kb. 9-10 perc). Ha kész, mindkettőt hűtsd le, pucold meg, és szeleteld karikákra.\r\n\r\n    A kolbász: Karikázd fel a kolbászt is. Ha nagyon zsíros, egy serpenyőben kissé kiolvaszthatod a zsírját előre, de nyersen is a rétegek közé mehet.\r\n\r\n    Rétegezés: Egy tűzálló tálat vagy tepsit kenj ki vékonyan zsírral/vajjal.\r\n\r\n        Alulra: Egy réteg krumpli (szórd meg egy kevés sóval).\r\n\r\n        Középre: Jöhet a tojás és a kolbász vegyesen.\r\n\r\n        Locsolás: Kanazz rá a tejfölből (amit előzőleg pici sóval és borssal kikevertél).\r\n\r\n        Tetejére: Zárd le a sort egy újabb réteg krumplival.\r\n\r\n    A befejezés: A maradék tejfölt oszasd el egyenletesen a legfelső krumplirétegen. Sokan szeretik, ha jut rá egy kis reszelt sajt vagy szalonnapörc is, de az eredeti recept enélkül is kiváló.\r\n\r\n    Sütés: 180-200 fokra előmelegített sütőben süsd kb. 30-40 percig, amíg a tejföl aranybarna nem lesz a tetején.', 'uploads/1769498155_rakottkrumpli.jpg', '', '2026-01-27 07:15:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `felhasznalok`
--
ALTER TABLE `felhasznalok`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `hozzaszolasok`
--
ALTER TABLE `hozzaszolasok`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recept_id` (`recept_id`),
  ADD KEY `felhasznalo_id` (`felhasznalo_id`);

--
-- Indexes for table `receptek`
--
ALTER TABLE `receptek`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `felhasznalok`
--
ALTER TABLE `felhasznalok`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hozzaszolasok`
--
ALTER TABLE `hozzaszolasok`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `receptek`
--
ALTER TABLE `receptek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hozzaszolasok`
--
ALTER TABLE `hozzaszolasok`
  ADD CONSTRAINT `hozzaszolasok_ibfk_1` FOREIGN KEY (`recept_id`) REFERENCES `receptek` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hozzaszolasok_ibfk_2` FOREIGN KEY (`felhasznalo_id`) REFERENCES `felhasznalok` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
