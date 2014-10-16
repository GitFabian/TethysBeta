<?php
if(!defined('USER_ADMIN')||!USER_ADMIN){echo"Keine Berechtigung!";exit;}

if ($version<1){
	dbio_query("CREATE TABLE IF NOT EXISTS `demo_features` (
  `ID` varchar(20) COLLATE utf8_bin NOT NULL,
  `value` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");
	dbio_query("INSERT INTO `demo_features` (`ID`, `value`) VALUES
('FEATURE1', '1');");
}

//=================================================================================================
dbio_query("UPDATE `core_meta_dbversion` SET `version` = '1' WHERE `modul_uc` = 'DEMO';");
//=================================================================================================
?>