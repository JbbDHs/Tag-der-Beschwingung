-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 02. Feb 2023 um 16:17
-- Server-Version: 10.4.11-MariaDB
-- PHP-Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `test`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `jahrgang`
--

CREATE TABLE `jahrgang` (
  `id` int(11) NOT NULL,
  `Jahr` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_german2_ci;

--
-- Daten für Tabelle `jahrgang`
--

INSERT INTO `jahrgang` (`id`, `Jahr`) VALUES
(1, 2022),
(2, 2021),
(3, 2019),
(4, 2018);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `schüler`
--

CREATE TABLE `schüler` (
  `id` int(11) NOT NULL,
  `Name` varchar(128) CHARACTER SET utf8 COLLATE utf8_german2_ci NOT NULL,
  `Vorname` varchar(128) CHARACTER SET utf8 COLLATE utf8_german2_ci NOT NULL,
  `Jahrgang` int(11) NOT NULL,
  `Workshop` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `workshops`
--

CREATE TABLE `workshops` (
  `id` int(11) NOT NULL,
  `Workshop` varchar(128) CHARACTER SET utf8 COLLATE utf8_german2_ci NOT NULL,
  `Obergrenze` int(11) NOT NULL,
  `Anzahl Schüler` int(11) DEFAULT 0,
  `Betreuer` varchar(128) CHARACTER SET utf8 COLLATE utf8_german2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `workshops`
--

INSERT INTO `workshops` (`id`, `Workshop`, `Obergrenze`, `Anzahl Schüler`, `Betreuer`) VALUES
(3, 'Besinnliches Kartenspielen', 0, 0, 'Simon'),
(4, 'Aufräumen im Rieth', 0, 0, 'Claudia BB'),
(5, 'Küche aufräumen', 10, 1, 'Grit');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `jahrgang`
--
ALTER TABLE `jahrgang`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `schüler`
--
ALTER TABLE `schüler`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Jahrgang` (`Jahrgang`),
  ADD KEY `Workshop` (`Workshop`);

--
-- Indizes für die Tabelle `workshops`
--
ALTER TABLE `workshops`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `jahrgang`
--
ALTER TABLE `jahrgang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `schüler`
--
ALTER TABLE `schüler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT für Tabelle `workshops`
--
ALTER TABLE `workshops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `schüler`
--
ALTER TABLE `schüler`
  ADD CONSTRAINT `schüler_ibfk_1` FOREIGN KEY (`Workshop`) REFERENCES `workshops` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `schüler_ibfk_2` FOREIGN KEY (`Jahrgang`) REFERENCES `jahrgang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
