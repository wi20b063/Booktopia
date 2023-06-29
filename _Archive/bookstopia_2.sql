-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 13. Jun 2023 um 18:32
-- Server-Version: 10.4.27-MariaDB
-- PHP-Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `bookstopia`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `image_url` text NOT NULL,
  `titel` text NOT NULL,
  `autor` text NOT NULL,
  `preis` int(11) NOT NULL,
  `bewertung` int(11) DEFAULT NULL,
  `kategorie` text NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `product`
--

INSERT INTO `product` (`id`, `image_url`, `titel`, `autor`, `preis`, `bewertung`, `kategorie`, `category_id`) VALUES
(1, '../res/img/Queen Charlotte.PNG', 'Queen Charlotte', 'Julia Quinn + weitere', 13, 5, 'Roman', 1),
(2, '../res/img/West Well.PNG', 'West Well', 'Lena Kiefer', 15, 4, 'Roman', 1),
(3, '../res/img/Die 1 Methode.PNG', 'Die 1% Methode', 'James Clear', 14, 4, 'Sachbuch', 2),
(4, '../res/img/Wald Wissen.PNG', 'Wald Wissen', 'Peter Wohlleben + weitere', 30, 1, 'Sachbuch', 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `userid` int(10) UNSIGNED NOT NULL,
  `salutation` varchar(255) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `postcode` int(100) NOT NULL,
  `location` varchar(255) NOT NULL,
  `creditCard` int(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`userid`, `salutation`, `firstName`, `lastName`, `address`, `postcode`, `location`, `creditCard`, `email`, `username`, `password`, `active`, `admin`) VALUES
(18, 'Frau', 'Master', 'Master', 'Master 1', 1111, 'Master', 0, 'Master@bookstopia.at', 'Master', 'masterpw ', 1, 1),
(19, 'Frau', 'Test', 'Admin', 'Weg 1', 1010, 'Wien', 12345678, 'admin@test.at', 'testAdmin', 'fa585d89c851dd338a70dcf535aa2a92fee7836dd6aff1226583e88e0996293f16bc009c652826e0fc5c706695a03cddce372f139eff4d13959da6f1f5d3eabe', 1, 1),
(20, 'Frau', 'Alex', 'Maier', 'Test 1', 1000, 'Wien', 1, 'alex@test.at', 'testUser1', 'fa585d89c851dd338a70dcf535aa2a92fee7836dd6aff1226583e88e0996293f16bc009c652826e0fc5c706695a03cddce372f139eff4d13959da6f1f5d3eabe', 1, 0),
(29, 'Herr', 'Lukas', 'Maier', 'Weg 1', 1000, 'Wien', 1, 'lukas@test.at', 'testUser2', 'fa585d89c851dd338a70dcf535aa2a92fee7836dd6aff1226583e88e0996293f16bc009c652826e0fc5c706695a03cddce372f139eff4d13959da6f1f5d3eabe', 1, 0),
(30, 'Frau', 'Klara', 'Weiss', 'Weg 1', 1000, 'Wien', 1, 'klara@test.at', 'testUser3', 'fa585d89c851dd338a70dcf535aa2a92fee7836dd6aff1226583e88e0996293f16bc009c652826e0fc5c706695a03cddce372f139eff4d13959da6f1f5d3eabe', 1, 0),
(31, 'Divers', 'Gil', 'Maurer', 'Straße 1', 3000, 'Wien', 1, 'gil@test.at', 'testUser4', 'fa585d89c851dd338a70dcf535aa2a92fee7836dd6aff1226583e88e0996293f16bc009c652826e0fc5c706695a03cddce372f139eff4d13959da6f1f5d3eabe', 1, 0),
(32, 'Frau', 'Mia', 'Gruber', 'Test 1', 1000, 'Wien', 1, 'mia@test.at', 'testUser6', 'fa585d89c851dd338a70dcf535aa2a92fee7836dd6aff1226583e88e0996293f16bc009c652826e0fc5c706695a03cddce372f139eff4d13959da6f1f5d3eabe', 1, 0),
(33, 'Frau', 'Deni', 'Deni', 'Deni 1', 1010, 'Wien', 1234567, 'deni@deni.at', 'deni', 'a2385d91b09bd4562f61f7a378f375ea400a593f6aca1a9a1bb6c066d95203b849df13c092fadc475df26b8beaf16c07a99e10898e246e94ee016ec885f59817', 1, 0);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `userid` (`userid`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `userid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
