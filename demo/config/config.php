<?php

/*
 * Server-Konfiguration
 * http://217.91.49.199/tethyswiki/index.php/Konfigurationsdatei#Server-Konfiguration
 * 
 */
define('CFG_HDDROOT', 'C:\\...\\Tethys');
define('CFG_HTTPROOT', '/tethys');
define('CFG_EXTENSION', 'dev');

/*
 * Datenbank
 */
mysql_connect('localhost','username','password');
mysql_select_db('tethys');

/*
 * Hauptmenü
 */
#include_once 'config_xxxxxxxxxx.php';
function hauptmenue($highlight){
	return "MENU_${highlight}_HERE";
	//TODO:...
	include_once CFG_HDDROOT.'/core/classes/menu.php';
	//TODO:...
}

?>