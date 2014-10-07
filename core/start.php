<?php

/*
 * Includes
 */

//Serverkonfiguration:
if (file_exists('config.php')) include_once 'config.php';
	//Aufruf aus dem Modul-Rootverzeichnis:
	else include_once '../../config.php';

//Globale Funktionen:
include_once 'toolbox.php';

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
include_once CFG_HDDROOT.'/core/config.php';

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

?>