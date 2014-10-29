<?php
$configOnly=true;
include_once '../../config_start.php';
include_once '../../core/toolbox.php';
include_once '../../core/dbio.php';
$db_exists=mysql_select_db(TETHYSDB);
if (!$db_exists){
	dbio_query("CREATE DATABASE `".TETHYSDB."` DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");
	if (!mysql_select_db(TETHYSDB)){echo"Fehler beim erstellen der Datenbank!";exit;}
}
$table_exists=(mysql_num_rows(mysql_query("SHOW TABLES LIKE 'core_meta_dbversion'"))==1);
$version=($table_exists?get_version("CORE"):0);

if ($version<1){
	dbio_query("CREATE TABLE IF NOT EXISTS `core_meta_dbversion` (
  `modul_uc` varchar(20) COLLATE utf8_bin NOT NULL COMMENT 'UPPERCASE!',
  `version` int(11) NOT NULL,
  PRIMARY KEY (`modul_uc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");
	dbio_query("INSERT INTO `core_meta_dbversion` (`modul_uc`, `version`) VALUES ('CORE', 0)");
}

if ($version<4){
	dbio_query("CREATE TABLE IF NOT EXISTS `core_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `nachname` varchar(100) COLLATE utf8_bin NOT NULL,
  `vorname` varchar(100) COLLATE utf8_bin NOT NULL,
  `http_auth` varchar(100) COLLATE utf8_bin NOT NULL,
  `nick` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;");
	dbio_query("INSERT INTO `core_users` (`id`, `active`, `nachname`, `vorname`, `http_auth`, `nick`) VALUES
(".LOGON_NONE_DEF_USER.", 1, 'User', 'Demo', 'demouser', 'Demouser');");
	dbio_query("CREATE TABLE IF NOT EXISTS `core_user_right` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `right` varchar(20) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;");
	dbio_query("ALTER TABLE `core_user_right`
  ADD CONSTRAINT `core_user_right_ibfk_1` FOREIGN KEY (`user`) REFERENCES `core_users` (`id`);");
	dbio_query("INSERT INTO `core_user_right` (`user`, `right`) VALUES
(".LOGON_NONE_DEF_USER.", 'RIGHT_ADMIN');");
	dbio_query("CREATE TABLE IF NOT EXISTS `core_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(20) COLLATE utf8_bin NOT NULL,
  `modul` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  `value` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;");
	dbio_query("ALTER TABLE `core_settings`
  ADD CONSTRAINT `core_settings_ibfk_1` FOREIGN KEY (`user`) REFERENCES `core_users` (`id`);");
}

if ($version<5){dbio_query("CREATE TABLE IF NOT EXISTS `core_logons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `cookie` varchar(26) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;");
dbio_query("ALTER TABLE `core_logons`
  ADD CONSTRAINT `core_logons_ibfk_1` FOREIGN KEY (`user`) REFERENCES `core_users` (`id`);");}

if ($version<6){dbio_query("ALTER TABLE `core_users` ADD `password` VARCHAR( 100 ) NULL AFTER `http_auth` ;");}

if ($version<7){dbio_query("ALTER TABLE `core_logons` ADD `expires` INT NOT NULL ;");}

if ($version<8){
// 	if (dbio_SELECT_SINGLE("core_users", LOGON_NONE_DEF_USER))
// 	dbio_query("INSERT INTO `core_user_right`
// 		( `user` , `right` )
// 	VALUES (  '".LOGON_NONE_DEF_USER."', 'RIGHT_USERMGMT' );");
}

#if ($version<){dbio_query("");}

$current_version=8;
//=================================================================================================
dbio_query("UPDATE `core_meta_dbversion` SET `version` = '$current_version' WHERE `modul_uc` = 'CORE';");
//=================================================================================================
include_once '../../core/start.php';
$page->init('core_update',"Update");
$new_version=get_version('CORE');
if ($new_version>$version){
	$page->say(html_div("Updated CORE: v$version &rarr; v$new_version"));
}else{
	$page->say(html_div("Up-To-Date! (Version $current_version)"));
}
if(!USER_ADMIN){page_send_exit("Keine Updates der Module!");}
foreach ($modules as $modul_id=>$modul) {
	if (strcasecmp($modul_id, "demo")==0||strcasecmp($modul_id, "tethys")==0){
		$php=ROOT_HDD_CORE."/demo/modules/$modul_id/db_update.php";
	}else{
		$php=ROOT_HDD_MODULES."/$modul_id/db_update.php";
	}
	if (file_exists($php)){
		$modulnameUC=strtoupper($modul_id);
		$version=get_version($modulnameUC);
		if (!$version) dbio_query("INSERT INTO `core_meta_dbversion` (`modul_uc`, `version`) VALUES ('$modulnameUC', 1)");
		include_once $php;
		$new_version=get_version($modulnameUC);
		if ($new_version>$version){
			$page->say(html_div("Updated $modulnameUC: v$version &rarr; v$new_version"));
		}
	}else{
		if (USER_ADMIN) echo "Keine db_update.php für Modul \"".$modul->modul_name."\"!";
	}
}
if (isset($_REQUEST['install'])){
	$page->say(html_div("Zum Testen alle <a href=\"".ROOT_HTTP_CORE."/core/admin/rights.".CFG_EXTENSION."\" target=\"_blank\">Berechtigungen</a> setzen!"));
	$page->say(html_a_button("Konfiguration", ROOT_HTTP_CORE."/core/admin/settings.".CFG_EXTENSION));
}
page_send_exit();//-----------------------------------------------------------
function get_version($modul){
	$query_version=dbio_SELECT_SINGLE("core_meta_dbversion", strtoupper($modul), "modul_uc");
	return ($query_version?$query_version['version']:0);
}
?>