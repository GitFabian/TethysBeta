-- SQL Dump

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `tethys_blanko_core0004`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `core_meta_dbversion`
--

CREATE TABLE IF NOT EXISTS `core_meta_dbversion` (
  `modul_uc` varchar(20) COLLATE utf8_bin NOT NULL COMMENT 'UPPERCASE!',
  `version` int(11) NOT NULL,
  PRIMARY KEY (`modul_uc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Daten für Tabelle `core_meta_dbversion`
--

INSERT INTO `core_meta_dbversion` (`modul_uc`, `version`) VALUES
('CORE', 4);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `core_settings`
--

CREATE TABLE IF NOT EXISTS `core_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(20) COLLATE utf8_bin NOT NULL,
  `modul` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  `value` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `core_users`
--

CREATE TABLE IF NOT EXISTS `core_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `nachname` varchar(100) COLLATE utf8_bin NOT NULL,
  `vorname` varchar(100) COLLATE utf8_bin NOT NULL,
  `http_auth` varchar(100) COLLATE utf8_bin NOT NULL,
  `nick` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `core_users`
--

INSERT INTO `core_users` (`id`, `active`, `nachname`, `vorname`, `http_auth`, `nick`) VALUES
(1, 1, 'User', 'Demo', 'demouser', 'Demouser');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `core_user_right`
--

CREATE TABLE IF NOT EXISTS `core_user_right` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `right` varchar(20) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `core_user_right`
--

INSERT INTO `core_user_right` (`id`, `user`, `right`) VALUES
(1, 1, 'RIGHT_ADMIN');

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `core_settings`
--
ALTER TABLE `core_settings`
  ADD CONSTRAINT `core_settings_ibfk_1` FOREIGN KEY (`user`) REFERENCES `core_users` (`id`);

--
-- Constraints der Tabelle `core_user_right`
--
ALTER TABLE `core_user_right`
  ADD CONSTRAINT `core_user_right_ibfk_1` FOREIGN KEY (`user`) REFERENCES `core_users` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
