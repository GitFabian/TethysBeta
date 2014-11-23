<?php
if(!defined('USER_ADMIN')||!USER_ADMIN){echo"Keine Berechtigung!";exit;}

if ($version<1){dbio_query("CREATE TABLE IF NOT EXISTS `myqueries_queries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(500) COLLATE utf8_bin NOT NULL,
  `beschreibung` text COLLATE utf8_bin NOT NULL,
  `query` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;");}

if ($version<2){dbio_query("CREATE TABLE IF NOT EXISTS `myqueries_connections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server` varchar(100) COLLATE utf8_bin NOT NULL,
  `username` varchar(100) COLLATE utf8_bin NOT NULL,
  `password` varchar(100) COLLATE utf8_bin NOT NULL,
  `dbase` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;");
dbio_query("ALTER TABLE `myqueries_queries` ADD `connection` INT NULL AFTER `id` ;");
dbio_query("ALTER TABLE `myqueries_queries` ADD INDEX ( `connection` ) ;");
dbio_query("ALTER TABLE `myqueries_queries` ADD FOREIGN KEY ( `connection` ) REFERENCES `myqueries_connections` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT ;");
dbio_query("CREATE TABLE IF NOT EXISTS `myqueries_user_query` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `query` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  KEY `query` (`query`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;");
dbio_query("ALTER TABLE `myqueries_user_query`
  ADD CONSTRAINT `myqueries_user_con_ibfk_2` FOREIGN KEY (`query`) REFERENCES `myqueries_queries` (`id`),
  ADD CONSTRAINT `myqueries_user_con_ibfk_1` FOREIGN KEY (`user`) REFERENCES `core_users` (`id`);");}

if ($version<3){dbio_query("ALTER TABLE `myqueries_connections` ADD `name` VARCHAR( 200 ) NOT NULL AFTER `id` ;");}

if ($version<4){dbio_query("CREATE TABLE IF NOT EXISTS `myqueries_admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `con` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  KEY `con` (`con`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;");
dbio_query("ALTER TABLE `myqueries_admins`
  ADD CONSTRAINT `myqueries_admins_ibfk_2` FOREIGN KEY (`con`) REFERENCES `myqueries_connections` (`id`),
  ADD CONSTRAINT `myqueries_admins_ibfk_1` FOREIGN KEY (`user`) REFERENCES `core_users` (`id`);");}

if ($version<5){dbio_query("ALTER TABLE `myqueries_queries` ADD `owner` INT NULL ,ADD INDEX ( `owner` ) ;");
dbio_query("ALTER TABLE `myqueries_queries` ADD FOREIGN KEY ( `owner` ) REFERENCES `core_users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT ;");}

#if ($version<){dbio_query("");}

//=================================================================================================
dbio_query("UPDATE `core_meta_dbversion` SET `version` = '5' WHERE `modul_uc` = 'MYQUERIES';");
//=================================================================================================
?>