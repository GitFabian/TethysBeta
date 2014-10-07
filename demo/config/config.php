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
//include_once 'config_xxxxxxxxxx.php';
function hauptmenue($page_id){
// 	return "MENU_{$page_id}_HERE_".USER_NICK;
	include_once CFG_HDDROOT.'/core/classes/menu.php';
// 	$menu=new menu();
// 	//...
	$menu=menu_get_default($page_id);
	return $menu->toHTML();
}

?>