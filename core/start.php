<?php

$devel_zeitmessung_start=microtime(true);
$devel_performance_query_counter=0;

/*
 * Abwärtskompatibilität
 */
//2014-10
if (!defined('CFG_LOGON_TYPE')) define('CFG_LOGON_TYPE','none');
if (!defined('LOGON_NONE_DEF_USER')) define('LOGON_NONE_DEF_USER','1');
//2014-11
if (!function_exists('setting_override')){function setting_override($modul,$key){return null;}}
if (!defined('CFG_LOGON_COOKIE')) define('CFG_LOGON_COOKIE','0');

/*
 * Includes
 */

//Globale Funktionen:
include_once 'toolbox.php';
include_once ROOT_HDD_CORE.'/core/classes/menu.php';

//Datenbank:
if (!mysql_select_db(TETHYSDB)||mysql_num_rows(mysql_query("SHOW TABLES LIKE 'core_meta_dbversion'"))!=1){
	echo "Datenbank nicht gefunden! [<a href=\"demo/database/update.".CFG_EXTENSION."\">Datenbank initialisieren</a>]";exit;}
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
$page->add_stylesheet(CFG_SKINPATH."/screen.css");
if (file_exists(CFG_SKINDIR."/print.css")){
	$page->add_stylesheet(CFG_SKINPATH."/print.css",(isset($_REQUEST["printview"])?null:"print"));
}
foreach ($modules as $mod_id => $module) {
	if (file_exists(CFG_SKINDIR."/mod_$mod_id.css")){
		$page->add_stylesheet(CFG_SKINPATH."/mod_$mod_id.css");
	}
}
if (isset($page->libraries[ROOT_HTTP_CORE."/core/html/alertify.js-shim-0.3.8/alertify.min.js"])){
	if (file_exists(CFG_SKINDIR."/alertify.css")) $page->add_stylesheet(CFG_SKINPATH."/alertify.css");
}

/*
 * Hauptmenü
 */
include_once setting_get(null,'CFG_HAUPTMENUE');

/*
 * Kompatibilität
 */
if(setting_get(null, "FIREFOX_EXCLUSIVE")){
	include_once ROOT_HDD_CORE.'/core/classes/userAgent.php';
	if(!userAgent::is_firefox()){
		if (isset($_COOKIE['tethys_browseroverride'])){
			$page->message_error( setting_get(null, "FIREFOX_EXCLUSIV_MSG") );
			$css="/browser/".$_COOKIE['tethys_browseroverride']."/browser.css";
			if(file_exists(CFG_SKINDIR.$css)){
				$page->add_stylesheet(CFG_SKINPATH.$css);}
		}else{
			if (isset($_REQUEST['browseroverride'])){
				$vendor=(userAgent::get_vendor()?:"Unbekannt");
				setcookie("tethys_browseroverride",$vendor,null,"/");
				$page->message_error(setting_get(null, "FIREFOX_EXCLUSIV_MSG"));
				$css="/browser/$vendor/browser.css";
				if(file_exists(CFG_SKINDIR.$css)){
					$page->add_stylesheet(CFG_SKINPATH.$css);}
			}else{
				$page->init("FIREFOX_EXCLUSIVE", "Falscher Browser");
				page_send_exit(setting_get(null, "FIREFOX_EXCLUSIV_MSG")
						."<p></p><p></p><i>Seite trotzdem anzeigen (kann Fehler enthalten): <a href=\"?browseroverride\">Start</a></i>"
					);
			}
		}
	}
}

/*
 * Nochmal Login
 */
if ((CFG_LOGON_TYPE!='none' && !USER_ID && CFG_LOGON_TYPE!='http')
		||CFG_LOGON_COOKIE && request_command("logon")
		){
	$ich=pathinfo($_SERVER['SCRIPT_FILENAME'], PATHINFO_BASENAME);
	if ($ich=="ajax.php"){
		echo"!AJAX:Fehler beim Login!";exit;
	}else{
		login_form();
	}
}

//===============================================================================================
?>