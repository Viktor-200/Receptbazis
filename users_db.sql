-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2026. Jan 27. 10:48
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
(25, 3, 3, 'egyszeru', '2026-01-27 09:13:56', 'recept'),
(26, 11, 3, 'ki a karoly', '2026-01-27 09:47:11', 'recept');

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
(3, 'Almás Sütemény', 'Hozzávalók\r\n\r\nA tésztához:\r\n\r\n    Liszt: 500 g finomliszt\r\n\r\n    Zsiradék: 250 g hideg vaj vagy margarin (ettől lesz omlós)\r\n\r\n    Cukor: 100 g porcukor\r\n\r\n    Lazítás: 1 csomag sütőpor, 1 csipet só\r\n\r\n    Kötőanyag: 2 tojássárgája, 2 evőkanál tejföl\r\n\r\nA töltelékhez:\r\n\r\n    Alma: 1,5 – 2 kg (tisztítás előtt mérve, savanykásabb fajta a legjobb)\r\n\r\n    Ízesítés: 150 g cukor (ízlés szerint), 2 teáskanál őrölt fahéj\r\n\r\n    Alap: 2-3 evőkanál zsemlemorzsa vagy darált dió (ez felszívja az alma levét)\r\n\r\nElkészítés folyamata\r\n1. A tészta összeállítása\r\n\r\nA lisztet keverd el a sütőporral, a sóval és a porcukorral. Morzsold el benne a hideg vajat, amíg homokszerű állagot nem kapsz. Add hozzá a tojássárgákat és a tejfölt, majd gyors mozdulatokkal gyúrd össze.\r\n\r\n    Tipp: Ne gyúrd túl sokáig, mert a kezed melegétől megolvad a vaj, és a tészta rágós lesz omlós helyett! Tedd a hűtőbe pihenni, amíg elkészíted az almát.\r\n\r\n2. Az alma előkészítése\r\n\r\nReszeld le az almát nagy lyukú reszelőn. Hagyd állni 10-15 percig, majd alaposan nyomkodd ki a levét. Keverd hozzá a cukrot és a fahéjat. (Sokan szeretik előre megpárolni az almát egy kevés vajon, de nyersen is tökéletes).\r\n3. Rétegezés\r\n\r\nOszd a tésztát két egyenlő részre. Lisztezett felületen nyújtsd ki az első lapot a tepsi méretére, és fektesd bele. Szórd meg egyenletesen a zsemlemorzsával, majd terítsd szét rajta a kinyomkodott almát. Nyújtsd ki a második lapot is, és óvatosan fektesd a tetejére.\r\n4. Sütés\r\n\r\nA felső lapot szurkáld meg villával több helyen, hogy a gőz távozni tudjon. Kend le a tetejét egy felvert tojással a szép színért. 180°C-ra előmelegített sütőben süsd 35-45 percig, amíg a teteje aranybarna nem lesz.', 'uploads/1769498236_almas.jpg', '', '2026-01-27 07:17:16'),
(4, 'Teriyaki tofu', '\r\nHozzávalók\r\n\r\nAlap:\r\n    36 dkg tofu\r\n    2 tk. keményítő\r\n    4 tk. szezámmag\r\n    1 fej brokkoli\r\n    25 dkg rizs\r\n    5 dl víz\r\n    3 tk. só\r\n    4 ek. olívaolaj \r\n\r\nSzósz:\r\n    1 tk. keményítő\r\n    3 gerezd fokhagyma\r\n    1 ujjnyi gyömbér\r\n    0,6 dl szójaszósz\r\n    5 tk. juharszirup\r\n    1,3 dl víz \r\n\r\nElkészítés:\r\n\r\nA receptet a tofu előkészítésével kezdjük. Öntsük le a natúr tofu levét, vágjuk kockákra, tegyük egy keverőtálba, majd forgassuk össze kettő teáskanál keményítővel. Hevítsük fel az olajat egy serpenyőben, ezután pirítsuk le rajta a felkockázott tofut. \r\n\r\nHa rizzsel szeretnénk tálalni, 250 g rizst mossunk át minimum kétszer, helyezzük egy lábasba, öntsük fel 500 ml vízzel, adjunk hozzá egy teáskanál sót, így főzzük puhára lassú lángon. Utolsó lépésként vegyük le a tűzről, és hagyjuk fedő alatt, hogy megszívja magát a maradék vízzel. \r\n\r\nA brokkolit tisztítsuk meg, szedjük rózsáira, dobjuk egy tepsibe. Locsoljuk meg kevés olívaolajjal és szórjuk meg sóval. Süssük ropogósra 190 fokon 15 perc alatt.\r\n\r\nKözben készítsük el a teriyaki szószt. Öntsük össze a vizet, a szójaszószt és a juharszirupot. Hámozzuk meg a gyömbért és a fokhagymagerezdeket, aprítsuk fel, végül keverjük a szószhoz egy teáskanál keményítővel együtt. \r\n\r\nA lepirított tofura öntsük rá az öntetet, adjuk hozzá a szezámmagot, keverjük össze. Utána hagyjuk közepes lángon rotyogni 5-6 percig, párszor keverjük át közben. \r\n\r\nNem maradt más, mint összeállítani a tálat. Kanalazzunk bele a rizsből, és halmozzuk rá a tofut és az aranybarnára pirult brokkolit.', 'uploads/1769505959_Teriyaki tofu.jpg', '', '2026-01-27 09:25:59'),
(5, 'Ananászos Csirkeragu', '\r\nHozzávalók\r\n\r\n    50 dkg csirkemellfilé\r\n    1 fej hagyma\r\n    2 gerezd reszelt fokhagyma\r\n    1 darab piros kaliforniai paprika\r\n    1 darab zöld kaliforniai paprika\r\n    olaj kevés\r\n    só és bors ízlés szerint\r\n    1 tk. fűszerpaprika\r\n    50 dkg ananászkonzerv\r\n    1 dl víz\r\n    1 ek. szójaszósz\r\n    1 tk. méz \r\n\r\nElkészítés:\r\n\r\nA csirkemellet vágjuk fel tetszés szerint, a hagymát vágjuk finomra, a paprikákat vágjuk fel tetszés szerint.\r\n\r\nEgy nagyobb serpenyőben forrósítsunk kevés olajat, majd dinszteljük meg rajta a hagymát. Adjuk hozzá a fokhagymát, forgassuk át, majd mehet rá a csirkehús. Pirítsuk fehéredésig, közben sózzuk, borsozzuk, majd szórjuk meg fűszerpaprikával.\r\n\r\nAdjuk hozzá a paprikákat és a leszűrt, majd összevágott ananászt, és öntsük fel az ananász levével és a vízzel, alaposan keverjük össze. Adjuk hozzá a szójaszószt és a mézet, majd lefedve főzzük 15-20 percig.\r\n\r\nA kész ételt rizzsel vagy más körettel tálalhatjuk is.', 'uploads/1769506147_ananászos csirkeragu.jpg', '', '2026-01-27 09:29:07'),
(6, 'Lencseleves', '\r\nHozzávalók\r\n\r\n    30 dkg lencse\r\n    2 darab közepes burgonya\r\n    1 fej hagyma\r\n    1 gerezd reszelt fokhagyma\r\n    olaj kevés\r\n    só és bors ízlés szerint\r\n    1 tk. fűszerpaprika\r\n    víz, amennyi ellepi \r\n\r\nElkészítés:\r\n\r\nA lencsét egy éjszakára áztassuk annyi hideg vízbe, amennyi ellepi, utána folyó víz alatt mossuk át.\r\n\r\nA burgonyát és a hagymát tisztítsuk meg, majd a burgonyát kockákra, a hagymát finomra vágjuk fel.\r\n\r\nEgy lábasban hevítsünk olajat, dinszteljük meg rajta a hagymát, majd adjuk hozzá a fokhagymát, és 20-30 másodperc pirítás után mehet a lábasba a lencse és a burgonya is. Fűszerezzük sóval, borssal, fűszerpaprikával, majd alaposan keverjük össze.\r\n\r\nÖntsük fel annyi vízzel, amennyi ellepi, majd fedjük le, és főzzük 25-30 percig, amíg a lencse és a krumpli megpuhul. Tálalásnál kínáljunk mellé ropogós kenyeret és tejfölt.', 'uploads/1769506236_Lencseleves.jpg', '', '2026-01-27 09:30:36'),
(7, 'Tejfölös Krumplileves', '\r\nHozzávalók \r\n\r\n    1 kg krumpli\r\n    1 fej hagyma\r\n    1 kis gumó zeller\r\n    olaj kevés\r\n    1 ek. fűszerpaprika\r\n    1 ek. erős paprikakrém\r\n    víz szükség szerint\r\n    só és bors ízlés szerint\r\n    1 darab házi ételízesítőkocka\r\n    2 darab babérlevél\r\n    30 dkg tejföl\r\n    1 ek. liszt\r\n    1 csokor petrezselyem \r\n\r\nElkészítés:\r\n\r\nA krumplit, hagymát és a zellert tisztítsuk meg, majd a hagymát vágjuk finomra, a krumplit kockázzuk fel a zellergumóval együtt.\r\n\r\nEgy magas falú edényben hevítsünk kevés olajat, majd dobjuk rá a hagymát, és dinszteljük üvegesre. Ezután adjuk hozzá a fűszerpaprikát, erős paprikakrémet, majd keverjük hozzá az előkészített krumplit és zellert. Miután átkevertük, öntsük fel annyi vízzel, amennyi ellepi, fűszerezzük sóval, borssal, házi ételízesítőkockával és babérlevéllel.\r\n\r\nA tejfölt keverjük simára a liszttel.\r\n\r\nAmikor a levesben megpuhult a krumpli, akkor keverjük hozzá a habarást, és főzzük még néhány percig.\r\n\r\nAz elkészült levest friss petrezselyemmel megszórva kínáljuk.', 'uploads/1769506311_Tejfölös-Krumplileves.jpg', '', '2026-01-27 09:31:51'),
(8, 'Sonkás-brokkolis mac and cheese', ' Hozzávalók\r\n\r\n    25 dkg makaróni\r\n    30 dkg brokkoli\r\n    30 g vaj\r\n    30 g finomliszt\r\n    4,8 dl tej\r\n    1 tk. dijoni mustár\r\n    só\r\n    bors\r\n    10 dkg cheddar sajt\r\n    10 dkg gruyére sajt\r\n\r\nElkészítés:\r\n\r\nForraljunk fel egy nagy fazék sós vizet, majd főzzük meg benne a tésztát a csomagoláson feltüntetett idő szerint, amíg al dente nem lesz.\r\n\r\n A sajtokat reszeljük le, és tegyük félre.\r\n\r\nA brokkolit szedjük rózsákra, majd egy lábas forró vízben blansírozzuk 1-2 percig, majd szűrjük le, hideg vízzel öblítsük át, és tegyük félre.\r\n\r\nA sonkát kockázzuk fel, és enyhén pirítsuk le egy serpenyőben, majd tegyük félre.\r\n\r\nEgy másik edényben, közepes lángon olvasszuk fel a vajat, adjuk hozzá a lisztet, majd folyamatosan kevergetve pirítsuk, amíg világos színű rántást nem kapunk.\r\n\r\nLassan, több részletben öntsük hozzá a tejet, közben habverővel keverjük simára. Ezután adjuk hozzá a dijoni mustárt, ízlés szerint sózzuk, borsozzuk, és főzzük tovább, amíg a szósz szépen besűrűsödik.\r\n\r\nVégül vegyük le a tűzről, keverjük bele a reszelt sajtokat, és addig keverjük, amíg teljesen fel nem olvadnak. Ekkor forgassuk bele a leszűrt tésztát, a brokkolit és a sonkakockákat, keverjük össze alaposan, majd tálaljuk még melegében.', 'uploads/1769506745_Sonkás-brokkolis mac and cheese.jpg', '', '2026-01-27 09:39:05'),
(9, 'Ropogós pulykás panini', 'Hozzávalók \r\n\r\n    4 szelet kenyér\r\n    2 ek. majonéz\r\n    16 dkg pulykamell (sült vagy főtt)\r\n    4 ek. áfonyaszósz\r\n    2 szelet provolone sajt\r\n    2 tk. kakukkfű \r\n\r\nElkészítés:\r\n\r\nA szendvicssütőt melegítsük elő.\r\n\r\nA sült vagy főtt pulykamellet vágjuk fel kisebb darabokra, a kakukkfüvet pedig alaposan öblítsük le.\r\n\r\nKét kenyérszeletet kenjünk meg majonézzel, majd helyezzük őket egy vágódeszkára.\r\n\r\nA felszeletelt pulykamell felét halmozzuk az egyik megkent kenyérszelet tetejére, szórjuk meg friss kakukkfűvel, kanalazzunk rá egy kevés áfonyalekvárt, végül fektessük rá a sajtszeletet. Borítsuk rá az üresen maradt másik szelet kenyeret, majd mehet is a szendvicssütőbe. Ezeket a lépéseket ismételjük meg a másik két szelet kenyérrel is.\r\n\r\nA szendvicseket egyesével tegyük a szendvicssütőbe, és süssük addig, amíg a kenyér ropogós és aranybarna nem lesz, a sajt pedig szépen megolvad (kb. 3-4 perc). \r\n\r\nA sütőből kivéve vágjuk félbe, és már tálalhatjuk is.', 'uploads/1769506865_Ropogós pulykás panini.jpg', '', '2026-01-27 09:41:05'),
(10, 'Carbonara', 'Hozzávalók \r\nA tésztához:\r\n\r\n    40 dkg spagetti\r\n    5 db tojássárgája\r\n    14 dkg pecorino romano\r\n    kétharmad tk. fekete bors\r\n    3 ek. olívaolaj\r\n    17,5 dkg guanciale \r\n\r\nA tálaláshoz:\r\n\r\n    2 ek. pecorino romano \r\n\r\nElkészítés:\r\n\r\nForraljunk fel egy nagy fazék sós vizet, majd tegyük bele a spagettit. A tésztát főzzük al dente-re. Szűrjük le, és tartsunk meg a főzővízből kb. 240 ml-t.\r\n\r\nKészítsük el a szószt.\r\n\r\nEgy közepes tálban keverjük össze a tojássárgákat, a reszelt pecorino sajtot és a fekete borsot. Adjunk hozzá a korábban félretett, még forró főzővízből kb. 80-120 ml-t, majd keverjük simára.\r\n\r\nEgy nagy serpenyőben melegítsük fel az olívaolajat, majd adjuk hozzá a guancialét. Süssük közepes lángon, amíg ropogós nem lesz, de vigyázzunk, hogy ne égjen meg. Ha kész, vegyük ki a serpenyőből, de a zsírt hagyjuk benne.\r\n\r\nTegyük a főtt spagettit a serpenyőbe, a maradék zsiradékra. Adjunk hozzá újabb 80-120 ml forró főzővizet, és alaposan keverjük össze, hogy a tészta átvegye az ízeket.\r\n\r\nVegyük le a serpenyőt a tűzről, és gyorsan adjuk hozzá a tojásos keveréket. Azonnal keverjük össze, hogy a tojás ne főjön meg, hanem krémes szószt alkosson.\r\n\r\nAdjuk hozzá a guancialét is, de ezzel már óvatosan forgassuk össze.\r\n\r\nTálaljuk forrón, reszelt pecorino sajttal és frissen őrölt fekete borssal megszórva.', 'uploads/1769507016_carbonara.jpg', '', '2026-01-27 09:43:36'),
(11, 'Focacciapizza', 'Tészta:\r\n    2,5 dl langyos víz\r\n    1 tk. cukor\r\n    1 csomag instant élesztő\r\n    50 dkg liszt (00-ás vagy BL55-ös)\r\n    1 tk. só\r\n    6 ek. olívaolaj\r\n    olívaolaj szükség szerint a tál és a tepsi kikenéséhez \r\n\r\nSzósz:\r\n    2 dl paradicsompassata\r\n    1 gerezd fokhagyma (reszelve)\r\n    só és bors ízlés szerint\r\n    4 levél bazsalikom\r\n    1 tk. oregánó \r\n\r\nFeltét:\r\n    15 dkg mozzarella\r\n    15 dkg cheddar sajt\r\n    10 dkg kolbász \r\n\r\nElkészítés:\r\n\r\nTészta:\r\n\r\nA langyos vízben keverjük el a cukrot és az élesztőt, majd takarjuk le, és tegyük félre 10-15 percre, míg az élesztő felfut.\r\n\r\nA liszthez forgassuk hozzá a sót, olívaolajat és a felfutott élesztőt, kézzel vagy géppel dagasszuk addig, amíg a tészta rugalmas lesz (8-12 perc).\r\n\r\nVegyünk elő egy nagy tálat, kenjük ki olajjal, majd forgassuk meg benne a tésztát. Ezután takarjuk le, és tegyük félre 1-2 órára, míg a tészta a duplájára kell.\r\n\r\nVegyünk elő egy tepsit, kenjük ki olajjal, majd a tésztát borítsuk bele, és húzzuk szét úgy, hogy beterítse a tepsit. Ezután az ujjainkkal nyomkodjuk meg a tészta tetejét, így alakítsunk ki benne mélyedéseket.\r\n\r\nSzósz:\r\n\r\nA paradicsompassatába keverjük bele a fokhagymát, sót, borsot, bazsalikomot és az oregánót.\r\n\r\nÖsszeállítás:\r\n\r\nA tészta tetejét kenjük meg olajjal, majd a paradicsomszósszal is. Ezután szórjuk rá a kétféle sajtot és a kolbászkarikákat.\r\n\r\nA pizzát toljuk 200-220 fokosra előmelegített sütőbe, és 15-20 perc alatt (lehet picit több idő is szükséges lehet) süssük szép pirosra.\r\n', 'uploads/1769507214_Focacciapizza.jpg', '', '2026-01-27 09:46:54');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT a táblához `receptek`
--
ALTER TABLE `receptek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
