<?php

$devel_zeitmessung_start=microtime(true);
$devel_performance_query_counter=0;

/*
 * Includes
 */

//Serverkonfiguration:
if (file_exists('config.php')) include_once 'config.php';
	//Aufruf aus dem Modul-Rootverzeichnis:
	else include_once '../../config.php';

//Globale Funktionen:
include_once 'toolbox.php';
include_once CFG_HDDROOT.'/core/classes/menu.php';

//Datenbank:
include_once 'dbio.php';
mysql_query ('SET NAMES utf8');

/*
 * HTML-Page
 */
include_once CFG_HDDROOT.'/core/classes/page.php';
$page=new page();
header('Content-type: text/html; charset=UTF-8');

/*
 * Login
 */
$user=null;
include_once CFG_HDDROOT.'/core/login.php';
login();

/*
 * Berechtigungen
 */
include_once CFG_HDDROOT.'/core/rights.php';
$rights=rights_init();

/*
 * Globale Konfiguration
 */
$query_cfg=dbio_SELECT("core_config","1","phpname,value");
foreach ($query_cfg as $cfg) { define($cfg['phpname'],$cfg['value']); }
$settings=get_core_settings();

/*
 * Module
 */
include_once CFG_HDDROOT.'/core/classes/module.php';
$modules=array();
module_read();

/*
 * Benutzerkonfiguration
 */

$page->add_stylesheet(CFG_HTTPROOT."/skins/".CFG_SKIN."/screen.css");

//Admin bekommt PHP-Fehler angezeigt:
if (USER_ADMIN) ini_set('display_errors', 'On');

//===============================================================================================
function get_core_settings(){
	$query_settings=dbio_SELECT("core_settings","`user` IS NULL");
	$settings=array();
	foreach ($query_settings as $setting) {
		$modul=$setting['modul'];
		if (!isset($settings[$modul])) $settings[$modul]=array();
		$key=$setting['key'];
		$settings[$modul][$key]=$setting['value'];
	}
	return $settings;
}
?>