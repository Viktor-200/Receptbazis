-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 10, 2026 at 10:16 AM
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
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_pic` varchar(255) DEFAULT 'img/alap.png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- Dumping data for table `felhasznalok`
--

INSERT INTO `felhasznalok` (`id`, `username`, `password`, `profile_pic`, `created_at`) VALUES
(1, 'receptek', '$2y$10$QyroTbpycn8VeRS87nAoLuz1w9hNf456maiuR4/32WuQDL46vWwCq', 'img/alap.png', '2025-10-12 09:14:13'),
(2, 'Viktor', '$2y$10$by.ceTdRF/7WD47bONQ.HeGyYGno1uUbszG0iqlrqetZHE0kIdFWu', 'uploads/user_1772792151_5608.png', '2025-09-11 09:14:23');

-- --------------------------------------------------------

--
-- Table structure for table `hozzaszolasok`
--

CREATE TABLE `hozzaszolasok` (
  `id` int(11) NOT NULL,
  `recept_id` int(11) NOT NULL,
  `oldal_tipus` varchar(50) DEFAULT 'recept',
  `felhasznalo_id` int(11) NOT NULL,
  `szoveg` text NOT NULL,
  `datum` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- Dumping data for table `hozzaszolasok`
--

INSERT INTO `hozzaszolasok` (`id`, `recept_id`, `oldal_tipus`, `felhasznalo_id`, `szoveg`, `datum`) VALUES
(1, 1, 'recept', 2, 'haha', '2026-03-09 08:24:52'),
(3, 1, 'recept', 1, 'hahaha kíváló!', '2026-03-09 08:36:11');

-- --------------------------------------------------------

--
-- Table structure for table `kedvencek`
--

CREATE TABLE `kedvencek` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `recept_id` int(11) NOT NULL,
  `hozzaadva` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- Dumping data for table `kedvencek`
--

INSERT INTO `kedvencek` (`id`, `user_id`, `recept_id`, `hozzaadva`) VALUES
(1, 2, 1, '2026-03-09 08:22:37'),
(2, 1, 1, '2026-03-09 08:35:58');

-- --------------------------------------------------------

--
-- Table structure for table `receptek`
--

CREATE TABLE `receptek` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `kategoria` varchar(50) DEFAULT 'Egyéb',
  `image_path` varchar(255) DEFAULT '',
  `thumbnail_path` varchar(255) DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- Dumping data for table `receptek`
--

INSERT INTO `receptek` (`id`, `user_id`, `title`, `description`, `kategoria`, `image_path`, `thumbnail_path`, `created_at`) VALUES
(1, 2, 'Csokipudding', 'https://www.youtube.com/watch?v=C9T9dSCb3B4', 'Desszert', '', 'uploads/thumb_1772792316_123.png', '2026-03-06 10:18:36'),
(2, 1, 'Frankfurti leves', 'Hozzávalók:\r\n2 ek olívaolaj\r\n1 közepes fej vöröshagyma\r\n2 gerezd fokhagyma\r\n1 teáskanál fűszerpaprika\r\n1 g köménymag\r\n1 teáskanál majoranna\r\n2 db burgonya\r\n1.5 l víz\r\n1 kis fej kelkáposzta\r\nsó ízlés szerint\r\nbors ízlés szerint\r\n250 g frankfurti virsli\r\n250 g tejföl\r\n1 ek finomliszt\r\n\r\nElkészítés:\r\n1. Az olívaolajon megpirítjuk a hagymát és a fokhagymát, majd hozzáadjuk a pirospaprikát, a római köményt és a majoránnát.\r\n2. Miután összepirítottuk őket, hozzáadjuk a feldarabolt burgonyát és kis kevergetés után felöntjük másfél liter vízzel. Az edényt lefedjük és 5 percig hagyjuk főni.\r\n3. A leves ízeinek összeérése után, belekerülnek a felszeletelt kelkáposzta levelek, valamint a só és a bors ízlés szerint. Fedő alatt ismét főzzük, de ezúttal 20 percig.\r\n4. Amíg fő a leves, egy serpenyőben megpirítjuk a karikára vágott frankfurti virsli darabokat.\r\n5. Egy külön tálba kimérjük a tejfölt, majd belekanalazzuk a lisztet és csomómentesre keverjük. Az edényből forró levet merünk a tálba, hogy a habarás is átmelegedjen, majd szűrő segítségével hozzáadjuk a leveshez.\r\n6. Miután a megpirult virsli is az edényben landolt, felforrás után tálalhatjuk.', 'Leves', 'uploads/full_1772792682_frankfurti.png', 'uploads/thumb_1772792682_frankfurti.png', '2026-03-06 10:24:42'),
(3, 1, 'Bundás Kenyér', 'Hozzávalók: 4 szelet fehér, 2 tojás, csipet só, 1 dl olaj a sütéshez.\r\n\r\nElkészítés: A tojásokat egy mélytányérban felverjük a sóval. A kenyérszeletek mindkét oldalát alaposan megmártjuk benne. Forró olajban aranybarnára sütjük mindkét felét. Papírtörlőn lecsöpögtetjük, fokhagymával vagy tejföllel tálaljuk.', 'Reggeli', 'uploads/full_1773046598_bundas.png', 'uploads/thumb_1773046598_bundas.png', '2026-03-09 08:56:38'),
(4, 1, 'Amerikai Palacsinta', 'Hozzávalók: 20 dkg liszt, 1 tojás, 3 dl tej, 5 dkg olvasztott vaj, 1 csomag sütőpor, 2 ek cukor, csipet só.\r\n\r\nElkészítés: A száraz és a nedves összetevőket külön összekeverjük, majd csomómentesre dolgozzuk az egészet. Egy teflon serpenyőt vékonyan kiolajozunk, és kis merőkanállal köröket formázunk. Amikor buborékosodik a teteje, megfordítjuk. Juharsziruppal az igazi!', 'Reggeli', 'uploads/full_1773046715_amerikaipalacsinta.png', 'uploads/thumb_1773046715_amerikaipalacsinta.png', '2026-03-09 08:58:35'),
(5, 1, 'Erdei Gombás Rántotta', 'Hozzávalók: 3 tojás, 10 dkg csiperke gomba, fél fej vöröshagyma, só, bors, petrezselyem.\r\n\r\nElkészítés: A hagymát és a szeletelt gombát kevés olajon megpirítjuk. Ráöntjük a felvert, fűszerezett tojásokat. Közepes lángon addig sütjük, amíg eléri a kívánt állagot. Friss petrezselyemmel szórjuk meg.', 'Reggeli', 'uploads/full_1773046846_erdei.png', 'uploads/thumb_1773046846_erdei.png', '2026-03-09 09:00:46'),
(6, 1, 'Sajtos Omlett', 'Hozzávalók: 3 tojás, 5 dkg reszelt cheddar vagy trappista, vaj, só.\r\n\r\nElkészítés: A tojásokat felverjük sóval. Vajon lassú tűzön sütni kezdjük, ne kevergessük! Amikor az alja már szilárd, de a teteje még kicsit remegős, rászórjuk a sajtot és félbehajtjuk. 1 percig még melegítjük, hogy a sajt elolvadjon.', 'Reggeli', 'uploads/full_1773046976_sajtosomlett.png', 'uploads/thumb_1773046976_sajtosomlett.png', '2026-03-09 09:02:56'),
(7, 1, 'Gulyásleves', 'Hozzávalók: 50 dkg marhahús, 30 dkg krumpli, 2 sárgarépa, 1 gyökér, 2 fej hagyma, fűszerpaprika, kömény.\r\n\r\nElkészítés: A hagymát apróra vágjuk, megdinszteljük, rátesszük a paprikát és a kockázott húst. Felöntjük vízzel, fűszerezzük. Amikor a hús félig puha, hozzáadjuk a zöldségeket és a krumplit. Készre főzzük.', 'Leves', 'uploads/full_1773047073_gulyas.png', 'uploads/thumb_1773047073_gulyas.png', '2026-03-09 09:04:33');

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
  ADD KEY `fk_komment_recept` (`recept_id`),
  ADD KEY `fk_komment_user` (`felhasznalo_id`);

--
-- Indexes for table `kedvencek`
--
ALTER TABLE `kedvencek`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_recept_unique` (`user_id`,`recept_id`),
  ADD KEY `recept_id` (`recept_id`);

--
-- Indexes for table `receptek`
--
ALTER TABLE `receptek`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_recept_user` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `felhasznalok`
--
ALTER TABLE `felhasznalok`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hozzaszolasok`
--
ALTER TABLE `hozzaszolasok`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kedvencek`
--
ALTER TABLE `kedvencek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `receptek`
--
ALTER TABLE `receptek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hozzaszolasok`
--
ALTER TABLE `hozzaszolasok`
  ADD CONSTRAINT `fk_komment_recept` FOREIGN KEY (`recept_id`) REFERENCES `receptek` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_komment_user` FOREIGN KEY (`felhasznalo_id`) REFERENCES `felhasznalok` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kedvencek`
--
ALTER TABLE `kedvencek`
  ADD CONSTRAINT `kedvencek_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `felhasznalok` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kedvencek_ibfk_2` FOREIGN KEY (`recept_id`) REFERENCES `receptek` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `receptek`
--
ALTER TABLE `receptek`
  ADD CONSTRAINT `fk_recept_user` FOREIGN KEY (`user_id`) REFERENCES `felhasznalok` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
