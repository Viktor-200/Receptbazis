-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2026. Jan 27. 10:16
-- Kiszolgáló verziója: 10.4.32-MariaDB
-- PHP verzió: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `users_db`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `felhasznalok`
--

CREATE TABLE `felhasznalok` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `felhasznalok`
--

INSERT INTO `felhasznalok` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'Viktor', '$2y$10$mU3Y7jH4fPc91Lt1Q9DP3OsBS1CIBskGp8.BQzbc2cexKfowVUqXK', '2026-01-26 18:50:21'),
(2, 'teszt1', '$2y$10$TNMFylqoh0W4NpLdw7QAN.IcTzp8pigCMmDQ9.5Ll7QTVsNOYzBhS', '2026-01-27 07:52:51'),
(3, 'teszt2', '$2y$10$vcd6CEQiJdxpdPbYOVQIH.WzjqW5N5EDiOE8b7wHKUrQ.iva/XsXm', '2026-01-27 08:14:39');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `hozzaszolasok`
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
-- A tábla adatainak kiíratása `hozzaszolasok`
--

INSERT INTO `hozzaszolasok` (`id`, `recept_id`, `felhasznalo_id`, `szoveg`, `datum`, `oldal_tipus`) VALUES
(1, 3, 2, 'nagyon finom', '2026-01-27 08:11:42', 'recept'),
(4, 3, 2, 'fincsi\r\n', '2026-01-27 08:13:54', 'recept'),
(6, 3, 3, 'tesz2', '2026-01-27 08:14:56', 'recept'),
(8, 3, 2, 'ham', '2026-01-27 08:18:37', 'recept'),
(22, 2, 2, 'rakott teszt1\r\n', '2026-01-27 09:12:47', 'recept'),
(23, 2, 2, 'tesz22', '2026-01-27 09:13:13', 'recept'),
(24, 2, 3, 'ketto', '2026-01-27 09:13:37', 'recept'),
(25, 3, 3, 'egyszeru', '2026-01-27 09:13:56', 'recept');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `receptek`
--

CREATE TABLE `receptek` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `uploaded_by` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `receptek`
--

INSERT INTO `receptek` (`id`, `title`, `description`, `image_path`, `uploaded_by`, `created_at`) VALUES
(2, 'Rakott Krumpli', 'Hozzávalók 3 személyre\r\n\r\n    Krumpli: 1 – 1,2 kg (lehetőleg sárga, főzni való fajta)\r\n\r\n    Tojás: 5-6 darab\r\n\r\n    Kolbász: 15-20 dkg (füstölt, lángolt kolbász a legjobb)\r\n\r\n    Tejföl: 450-500 g (egy nagy pohár)\r\n\r\n    Zsiradék: Kevés vaj vagy zsír a tepsi kikenéséhez\r\n\r\n    Fűszerek: Só, bors, ízlés szerint egy kevés pirospaprika a tejfölbe\r\n\r\nAz elkészítés lépései\r\n\r\n    Előkészítés: A krumplit héjában, sós vízben tedd fel főni. Egy másik edényben főzd keményre a tojásokat (forrástól számítva kb. 9-10 perc). Ha kész, mindkettőt hűtsd le, pucold meg, és szeleteld karikákra.\r\n\r\n    A kolbász: Karikázd fel a kolbászt is. Ha nagyon zsíros, egy serpenyőben kissé kiolvaszthatod a zsírját előre, de nyersen is a rétegek közé mehet.\r\n\r\n    Rétegezés: Egy tűzálló tálat vagy tepsit kenj ki vékonyan zsírral/vajjal.\r\n\r\n        Alulra: Egy réteg krumpli (szórd meg egy kevés sóval).\r\n\r\n        Középre: Jöhet a tojás és a kolbász vegyesen.\r\n\r\n        Locsolás: Kanazz rá a tejfölből (amit előzőleg pici sóval és borssal kikevertél).\r\n\r\n        Tetejére: Zárd le a sort egy újabb réteg krumplival.\r\n\r\n    A befejezés: A maradék tejfölt oszasd el egyenletesen a legfelső krumplirétegen. Sokan szeretik, ha jut rá egy kis reszelt sajt vagy szalonnapörc is, de az eredeti recept enélkül is kiváló.\r\n\r\n    Sütés: 180-200 fokra előmelegített sütőben süsd kb. 30-40 percig, amíg a tejföl aranybarna nem lesz a tetején.', 'uploads/1769498155_rakottkrumpli.jpg', '', '2026-01-27 07:15:55'),
(3, 'Almás Sütemény', 'Hozzávalók\r\n\r\nA tésztához:\r\n\r\n    Liszt: 500 g finomliszt\r\n\r\n    Zsiradék: 250 g hideg vaj vagy margarin (ettől lesz omlós)\r\n\r\n    Cukor: 100 g porcukor\r\n\r\n    Lazítás: 1 csomag sütőpor, 1 csipet só\r\n\r\n    Kötőanyag: 2 tojássárgája, 2 evőkanál tejföl\r\n\r\nA töltelékhez:\r\n\r\n    Alma: 1,5 – 2 kg (tisztítás előtt mérve, savanykásabb fajta a legjobb)\r\n\r\n    Ízesítés: 150 g cukor (ízlés szerint), 2 teáskanál őrölt fahéj\r\n\r\n    Alap: 2-3 evőkanál zsemlemorzsa vagy darált dió (ez felszívja az alma levét)\r\n\r\nElkészítés folyamata\r\n1. A tészta összeállítása\r\n\r\nA lisztet keverd el a sütőporral, a sóval és a porcukorral. Morzsold el benne a hideg vajat, amíg homokszerű állagot nem kapsz. Add hozzá a tojássárgákat és a tejfölt, majd gyors mozdulatokkal gyúrd össze.\r\n\r\n    Tipp: Ne gyúrd túl sokáig, mert a kezed melegétől megolvad a vaj, és a tészta rágós lesz omlós helyett! Tedd a hűtőbe pihenni, amíg elkészíted az almát.\r\n\r\n2. Az alma előkészítése\r\n\r\nReszeld le az almát nagy lyukú reszelőn. Hagyd állni 10-15 percig, majd alaposan nyomkodd ki a levét. Keverd hozzá a cukrot és a fahéjat. (Sokan szeretik előre megpárolni az almát egy kevés vajon, de nyersen is tökéletes).\r\n3. Rétegezés\r\n\r\nOszd a tésztát két egyenlő részre. Lisztezett felületen nyújtsd ki az első lapot a tepsi méretére, és fektesd bele. Szórd meg egyenletesen a zsemlemorzsával, majd terítsd szét rajta a kinyomkodott almát. Nyújtsd ki a második lapot is, és óvatosan fektesd a tetejére.\r\n4. Sütés\r\n\r\nA felső lapot szurkáld meg villával több helyen, hogy a gőz távozni tudjon. Kend le a tetejét egy felvert tojással a szép színért. 180°C-ra előmelegített sütőben süsd 35-45 percig, amíg a teteje aranybarna nem lesz.', 'uploads/1769498236_almas.jpg', '', '2026-01-27 07:17:16');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `felhasznalok`
--
ALTER TABLE `felhasznalok`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- A tábla indexei `hozzaszolasok`
--
ALTER TABLE `hozzaszolasok`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recept_id` (`recept_id`),
  ADD KEY `felhasznalo_id` (`felhasznalo_id`);

--
-- A tábla indexei `receptek`
--
ALTER TABLE `receptek`
  ADD PRIMARY KEY (`id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `felhasznalok`
--
ALTER TABLE `felhasznalok`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT a táblához `hozzaszolasok`
--
ALTER TABLE `hozzaszolasok`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT a táblához `receptek`
--
ALTER TABLE `receptek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `hozzaszolasok`
--
ALTER TABLE `hozzaszolasok`
  ADD CONSTRAINT `hozzaszolasok_ibfk_1` FOREIGN KEY (`recept_id`) REFERENCES `receptek` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hozzaszolasok_ibfk_2` FOREIGN KEY (`felhasznalo_id`) REFERENCES `felhasznalok` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
