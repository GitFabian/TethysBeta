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

if ($version){
	include_once '../../core/start.php';
	if (!USER_ADMIN) page_send_exit("Keine Berechtigung!");
	
	if(isset($_REQUEST['PART2'])){
		
		$page->say(html_header1("Framework &amp; Module"));
		$page->say(html_code($_REQUEST['PART2']));
		
	}else{
		$kommandos=trim(setting_get(null, "UPDATE_KOMMANDOS"));
		if ($kommandos){
			$kommandos=explode("\n", $kommandos);
			$screen="";
		
			foreach ($kommandos as $cmd) {
				$cmd=trim($cmd);
				$screen.="<span class=\"cmd\">&gt; $cmd</span>\n";
					
				#$output=shell_exec("cmd.exe /c chcp 850 && dir");
				$output=shell_exec($cmd);
				$output=mb_convert_encoding($output, "UTF-8", "cp850");
				$output=encode_html($output);
					
				$screen.="$output\n";
			}
		
			$page->say(html_header1("Framework &amp; Module"));
			$page->init('core_update',"Update-Teil1");
			ajax_refresh("Teil2...", "?PART2=".urlencode($screen));
		}
	}
 	
}

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

if ($version<9){dbio_query("ALTER TABLE `core_users` ADD `picture` VARCHAR( 500 ) NULL ;");}

if ($version<10){dbio_query("CREATE TABLE IF NOT EXISTS `core_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `ip` varchar(15) COLLATE utf8_bin NOT NULL,
  `keyword` varchar(50) COLLATE utf8_bin NOT NULL,
  `pars` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;");}

if ($version<11){dbio_query("ALTER TABLE `core_users` ADD `durchwahl` VARCHAR( 100 ) NULL ,
ADD `handy` VARCHAR( 100 ) NULL ,
ADD `raum` VARCHAR( 100 ) NULL ;");}

if ($version<12){dbio_query("CREATE TABLE IF NOT EXISTS `core_logs_dbedit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `user` int(11) DEFAULT NULL,
  `modul` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT 'core',
  `ip` varchar(15) COLLATE utf8_bin NOT NULL,
  `action` enum('new','edit','del') COLLATE utf8_bin NOT NULL,
  `tabelle` varchar(100) COLLATE utf8_bin NOT NULL,
  `zeile` varchar(100) COLLATE utf8_bin NOT NULL,
  `pars` text COLLATE utf8_bin,
  PRIMARY KEY (`id`),
  KEY `user` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;");
dbio_query("ALTER TABLE `core_logs_dbedit`
  ADD CONSTRAINT `core_logs_dbedit_ibfk_1` FOREIGN KEY (`user`) REFERENCES `core_users` (`id`);");}

if ($version<13){dbio_query("CREATE TABLE IF NOT EXISTS `core_rollen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;");
dbio_query("CREATE TABLE IF NOT EXISTS `core_user_rolle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `rolle` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  KEY `rolle` (`rolle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;");
dbio_query("ALTER TABLE `core_user_rolle`
  ADD CONSTRAINT `core_user_rolle_ibfk_2` FOREIGN KEY (`rolle`) REFERENCES `core_rollen` (`id`),
  ADD CONSTRAINT `core_user_rolle_ibfk_1` FOREIGN KEY (`user`) REFERENCES `core_users` (`id`);");}

#if ($version<){dbio_query("");}

$current_version=13;
//=================================================================================================
dbio_query("UPDATE `core_meta_dbversion` SET `version` = '$current_version' WHERE `modul_uc` = 'CORE';");
//=================================================================================================
include_once '../../core/start.php';
$page->init('core_update',"Update");

$page->say(html_header1("Datenbanken"));

$new_version=get_version('CORE');
if ($new_version>$version){
	$page->say(html_div("Updated CORE: v$version &rarr; v$new_version"));
}else{
	$page->say(html_div("Up-To-Date! (Version $current_version)"));
}
if(!USER_ADMIN){page_send_exit("Keine Updates der Module!");}
foreach ($modules as $modul_id=>$modul) {
	if (strcasecmp($modul_id, "demo")==0||strcasecmp($modul_id, "fun")==0||strcasecmp($modul_id, "myqueries")==0){
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
		#if (USER_ADMIN) echo "Keine db_update.php für Modul \"".$modul->modul_name."\"!";
	}
}
if (isset($_REQUEST['install'])){
	$page->say(html_div("Zum Testen alle <a href=\"".ROOT_HTTP_CORE."/core/admin/rights.".CFG_EXTENSION."\" target=\"_blank\">Berechtigungen</a> setzen!"));
	$page->say(html_a_button("Konfiguration", ROOT_HTTP_CORE."/core/admin/settings.".CFG_EXTENSION));
}

if (CFG_CSS_VERSION){
	$page->say(html_header1("CSS"));
	$page->add_html("<style>div.css_version{display:none;}</style>");
	if (file_exists(CFG_SKINDIR."/screen.css")){
		$file=fopen(CFG_SKINDIR."/screen.css", "r");
		$content=fread($file, 9999);
		fclose($file);
		preg_match("/\\n\\w*div\\.css_version\\.v(.*?)\\{/", $content, $matches);
		if (isset($matches[1])){
			$v=$matches[1];
			if (CFG_CSS_VERSION!=$v){
				setting_save(null, "CFG_CSS_VERSION", $v, false);
				$page->say("Aktualisiert. Version: ".$v);
			}else{
				$page->say("(Keine Änderung)");
			}
		}else{
			$page->say("Tag nicht gefunden!");
			setting_save(null, "CFG_CSS_VERSION", "", false);
		}
	}else{
		$page->say("CSS-Datei nicht gefunden!");
	}
}

page_send_exit();//-----------------------------------------------------------
function get_version($modul){
	$query_version=dbio_SELECT_SINGLE("core_meta_dbversion", strtoupper($modul), "modul_uc");
	return ($query_version?$query_version['version']:0);
}
?>