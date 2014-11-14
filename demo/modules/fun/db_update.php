<?php
if(!defined('USER_ADMIN')||!USER_ADMIN){echo"Keine Berechtigung!";exit;}

if ($version<1){dbio_query("CREATE TABLE IF NOT EXISTS `fun_logs_spw` (
  `id` varchar(200) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");}

if ($version<2){dbio_query("DROP TABLE fun_logs_spw");
dbio_query("CREATE TABLE IF NOT EXISTS `fun_logs_spw` (
  `id` varchar(200) COLLATE utf8_bin NOT NULL,
  `nr` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `nr` (`nr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;");}

if ($version<3){dbio_query("ALTER TABLE `fun_logs_spw` ADD `q` ENUM( 'like', 'dislike', 'unknown' ) NOT NULL DEFAULT 'unknown';");}

#if ($version<){dbio_query("");}

//=================================================================================================
dbio_query("UPDATE `core_meta_dbversion` SET `version` = '3' WHERE `modul_uc` = 'FUN';");
//=================================================================================================
?>