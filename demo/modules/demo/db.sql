--
-- Tethys-Modul "DEMO"
--

CREATE TABLE IF NOT EXISTS `core_meta_dbversion` (
  `modul_uc` varchar(20) COLLATE utf8_bin NOT NULL COMMENT 'UPPERCASE!',
  `version` int(11) NOT NULL,
  PRIMARY KEY (`modul_uc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `demo_features` (
  `ID` varchar(20) COLLATE utf8_bin NOT NULL,
  `value` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
INSERT INTO `demo_features` (`ID`, `value`) VALUES
('FEATURE1', '1');

-- DB-Version:
-- -------------------------------------------------------------------------------------------
INSERT INTO `core_meta_dbversion` (`modul_uc`, `version`) VALUES ('DEMO', 1);
-- -------------------------------------------------------------------------------------------
