<?php

/*
 * Server-Konfiguration
 * http://217.91.49.199/tethyswiki/index.php/Konfigurationsdatei#Server-Konfiguration
 * 
 */
#define('CFG_HDDROOT', 'C:\\...\\Tethys');
#define('CFG_HTTPROOT', '/tethys');
define('CFG_EXTENSION', 'php');

define('ROOT_HDD_CORE', 'C:\\...\\MyProject\\tethys');
define('ROOT_HDD_MODULES', 'C:\\...\\MyProject\\shared\\modules');
define('ROOT_HDD_SKINS', 'C:\\...\\MyProject\\shared\\skins');
define('ROOT_HDD_DATA', 'C:\\...\\MyProject\\data');

define('ROOT_HTTP_CORE', '/tethys');
define('ROOT_HTTP_MODULES', '/tethys/modules');
define('ROOT_HTTP_SKINS', '/tethys/skins');
define('ROOT_HTTP_DATA', '/tethys/data');

/*
 * Datenbank
 */
mysql_connect('localhost','username','password');
mysql_select_db('tethys');

/*
 * Hauptmenü
 */
//include_once 'C:\\...\\MyProject\\shared\\config_xxxxxxxxxx.php';
function hauptmenue($page_id){
// 	return "MENU_{$page_id}_HERE_".USER_NICK;
// 	$menu=new menu(null,null,$page_id);
// 	//...
	$menu=menu_get_default($page_id);
	return $menu->toHTML();
}

/*
 * Default Settings
 */
function get_default_setting($key){
	#if ($key=='CFG_SKIN') return "demo";
	return null;
}

/*
 * Start
 */
include_once ROOT_HDD_CORE.'\\core\\start.php';

?>