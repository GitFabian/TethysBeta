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

if ($version<3){
// 	if (dbio_SELECT_SINGLE("core_users", LOGON_NONE_DEF_USER))
// 		dbio_query("INSERT INTO `core_user_right`
// 		( `user` , `right` )
// 	VALUES (  '".LOGON_NONE_DEF_USER."', 'RIGHT_DEMOMGMT' );");
}

if ($version<4){dbio_query("CREATE TABLE IF NOT EXISTS `demo_flubtangle_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `flubtangle` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `flubtangle` (`flubtangle`),
  KEY `user` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;");
dbio_query("ALTER TABLE `demo_flubtangle_user`
  ADD CONSTRAINT `demo_flubtangle_user_ibfk_2` FOREIGN KEY (`user`) REFERENCES `core_users` (`id`),
  ADD CONSTRAINT `demo_flubtangle_user_ibfk_1` FOREIGN KEY (`flubtangle`) REFERENCES `demo_lorumipsum` (`id`);");}

#if ($version<){dbio_query("");}

//=================================================================================================
dbio_query("UPDATE `core_meta_dbversion` SET `version` = '4' WHERE `modul_uc` = 'DEMO';");
//=================================================================================================
?>