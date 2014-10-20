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
if (!mysql_select_db(TETHYSDB)||mysql_num_rows(mysql_query("SHOW TABLES LIKE 'core_meta_dbversion'"))!=1){
	echo "Datenbank nicht gefunden! [<a href=\"demo/database/update.php\">Datenbank initialisieren</a>]";exit;}
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

//Admin bekommt PHP-Fehler angezeigt:
if (USER_ADMIN) ini_set('display_errors', 'On');

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
$css_hdd=ROOT_HDD_SKINS."/".CFG_SKIN;
$css_http=ROOT_HTTP_SKINS."/".CFG_SKIN;
if (strcasecmp(CFG_SKIN,"demo")==0){
	$css_hdd=ROOT_HDD_CORE."/demo/skins/".CFG_SKIN;
	$css_http=ROOT_HTTP_CORE."/demo/skins/".CFG_SKIN;
}
$page->add_stylesheet($css_http."/screen.css");
foreach ($modules as $mod_id => $module) {
	if (file_exists($css_hdd."/mod_$mod_id.css")){
		$page->add_stylesheet($css_http."/mod_$mod_id.css");
	}
}

//===============================================================================================
?>