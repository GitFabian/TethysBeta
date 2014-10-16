<?php

$devel_zeitmessung_start=microtime(true);
$devel_performance_query_counter=0;

/*
 * Includes
 */

//Globale Funktionen:
include_once 'toolbox.php';
include_once ROOT_HDD_CORE.'/core/classes/menu.php';

//Datenbank:
include_once 'dbio.php';
mysql_query ('SET NAMES utf8');

/*
 * HTML-Page
 */
include_once ROOT_HDD_CORE.'/core/classes/page.php';
$page=new page();
header('Content-type: text/html; charset=UTF-8');
$global_id_counter=0;

/*
 * Login
 */
$user=null;
include_once ROOT_HDD_CORE.'/core/login.php';
login();

/*
 * Berechtigungen
 */
include_once ROOT_HDD_CORE.'/core/rights.php';
$rights=rights_init();

/*
 * Globale Konfiguration
 */
include_once ROOT_HDD_CORE.'/core/settings.php';
$settings=array();
$user_settings=array();
init_settings();

/*
 * Module
 */
include_once ROOT_HDD_CORE.'/core/classes/module.php';
$modules=array();
module_read();

/*
 * Benutzerkonfiguration
 */
if (strcasecmp(CFG_SKIN,"demo")==0){
	$page->add_stylesheet(ROOT_HTTP_CORE."/demo/skins/".CFG_SKIN."/screen.css");
}else{
	$page->add_stylesheet(ROOT_HTTP_SKINS."/".CFG_SKIN."/screen.css");
}

//Admin bekommt PHP-Fehler angezeigt:
if (USER_ADMIN) ini_set('display_errors', 'On');

//===============================================================================================
?>