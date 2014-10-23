<?php
if(!defined('USER_ADMIN')||!USER_ADMIN){echo"Keine Berechtigung!";exit;}

if ($version<2){
	dbio_query("CREATE TABLE IF NOT EXISTS `demo_lorumipsum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `flubtangle` text COLLATE utf8_bin NOT NULL,
  `abracadabra` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;");
	dbio_query("INSERT INTO `demo_lorumipsum` (`id`, `flubtangle`, `abracadabra`) VALUES
(1, 'Duh dingely zoweequabble.', 'Gobble bang quibblewheezer.'),
(2, '\"Shizzle boo yip slop.\"', 'Roo slap bläng crongle duh blung-waggle...');");
}

//=================================================================================================
dbio_query("UPDATE `core_meta_dbversion` SET `version` = '2' WHERE `modul_uc` = 'DEMO';");
//=================================================================================================
?>