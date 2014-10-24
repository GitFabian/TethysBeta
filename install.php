<?php
$update=false;
if(file_exists('config_start.php')){
	include_once 'config_start.php';
	if (!USER_ADMIN) page_send_exit("Keine Berechtigung!");
	$http_core=ROOT_HTTP_CORE;
	$update=true;
	
	$file=fopen("config_start.php", "r");
	$content=fread($file, 9999);
	fclose($file);
	
	preg_match("/\\n\\s*mysql_connect\\s*\\(\\s*'(.*?)'\\s*,\\s*'(.*?)'\\s*,\\s*'(.*?)'\\s*\\)\\s*;/", $content, $matches);
	$sql_server=$matches[1];
	$sql_user=$matches[2];
	$sql_pass=$matches[3];
	
	preg_match("/\\n\\/\\*HM=\\*\\/include_once '(.*?)';/", $content, $matches);
	$hauptmenue=$matches[1];
	$hauptmenue=preg_replace("/\\\\\\\\/", "\\\\", $hauptmenue);
}else{
	include_once 'core/classes/page.php';
	include_once 'core/classes/menu.php';
	include_once 'core/toolbox.php';
	function setting_get($modul,$key){
		if ($modul===null && $key=='HM_ICONS') return "0";
		if ($modul===null && $key=='HM_TEXT') return "1";
		echo "Setting nicht definiert: $key (".($modul?$modul:"CORE").")";
		return null;
	}
	define('ROOT_HTTP_CORE','.');
	define('USER_ADMIN','0');
	define('CFG_CSS_VERSION','');
	define('CFG_TITLE','Installation');
	function hauptmenue($page_id){return null;}
	$page=new page();
	$page->add_stylesheet("demo/skins/demo/screen.css");
	define('CFG_EXTENSION', 'php');
	$mydir=getcwd();
	define('ROOT_HDD_CORE', $mydir);
	define('ROOT_HDD_MODULES', $mydir.'\\modules');
	define('ROOT_HDD_SKINS', $mydir.'\\skins');
	define('ROOT_HDD_DATA', $mydir.'\\DATA');
	$http_core=substr($_SERVER["SCRIPT_NAME"],0,strrpos($_SERVER["SCRIPT_NAME"], "/"));
	define('ROOT_HTTP_MODULES', $http_core.'/modules');
	define('ROOT_HTTP_SKINS', $http_core.'/skins');
	define('ROOT_HTTP_DATA', $http_core.'/DATA');
	define('TETHYSDB', 'tethys');
	$sql_server=$_SERVER["SERVER_NAME"];
	$sql_user="";
	$sql_pass="";
	$hauptmenue=$mydir."\configExample.php";
}
$page->init("core_admin", "Server-Konfiguration");
if(request_command("run"))run($update);
include_once 'core/classes/form.php';

$form=new form("run");
$form->add_fields("MySQL",array(
		new form_field("sql_server","Server",request_value("sql_server",$sql_server)),
		new form_field("TETHYSDB","Datenbank",request_value("TETHYSDB",TETHYSDB)),
		new form_field("sql_user","Benutzername",request_value("sql_user",$sql_user)),
		new form_field("sql_pass","Passwort",request_value("sql_pass",$sql_pass),"PASSWORD"),
));
$form->add_fields("Server-Verzeichnisse (HDD)",array(
		new form_field("ROOT_HDD_CORE","Tethys",request_value("ROOT_HDD_CORE",ROOT_HDD_CORE)),
		new form_field("ROOT_HDD_MODULES","Module",request_value("ROOT_HDD_MODULES",ROOT_HDD_MODULES)),
		new form_field("ROOT_HDD_SKINS","Skins",request_value("ROOT_HDD_SKINS",ROOT_HDD_SKINS)),
		new form_field("ROOT_HDD_DATA","Data",request_value("ROOT_HDD_DATA",ROOT_HDD_DATA)),
));
$form->add_fields("Server-Pfade (HTTP)",array(
		new form_field("ROOT_HTTP_CORE","Tethys",request_value("ROOT_HTTP_CORE",$http_core)),
		new form_field("ROOT_HTTP_MODULES","Module",request_value("ROOT_HTTP_MODULES",ROOT_HTTP_MODULES)),
		new form_field("ROOT_HTTP_SKINS","Skins",request_value("ROOT_HTTP_SKINS",ROOT_HTTP_SKINS)),
		new form_field("ROOT_HTTP_DATA","Data",request_value("ROOT_HTTP_DATA",ROOT_HTTP_DATA)),
));
$form->add_fields("",array(
		new form_field("CFG_EXTENSION","Virtuelle Extension",request_value("CFG_EXTENSION",CFG_EXTENSION)),
		new form_field("hauptmenue","Hauptmenü",request_value("hauptmenue",$hauptmenue),'text',"Pfad zur Hauptmenü-PHP"),
));

$page->say(html_header1(($update?"Server-Konfiguration":"Installation")));
$page->say($form->toHTML());

$page->send();
exit;
//===========================================================================
function run($update){
	include_jquery();
	global $page;
	foreach ($_REQUEST as $key => $value) {
		$$key=$value;
	}
	$fehler=null;
	//SQL-Server
	if (!@mysql_connect($sql_server,$sql_user,$sql_pass)){
		$fehler="Fehler bei SQL-Verbindung: ".mysql_error();
	}else if(!file_exists($ROOT_HDD_CORE."\install.php")){
		$fehler="Server-Verzeichnis nicht gefunden!";
	}
	
	if ($fehler){
		$page->say("<div>--- $fehler ---</div>");
		return;
	}
	
	$ROOT_HDD_CORE=preg_replace("/\\\\/", "\\\\\\\\", $ROOT_HDD_CORE);
	$ROOT_HDD_MODULES=preg_replace("/\\\\/", "\\\\\\\\", $ROOT_HDD_MODULES);
	$ROOT_HDD_SKINS=preg_replace("/\\\\/", "\\\\\\\\", $ROOT_HDD_SKINS);
	$ROOT_HDD_DATA=preg_replace("/\\\\/", "\\\\\\\\", $ROOT_HDD_DATA);
	$hauptmenue=preg_replace("/\\\\/", "\\\\\\\\", $hauptmenue);
	$config_file=<<<ENDE
/*
 * Server-Konfiguration
 * http://tethys-framework.de/wiki/?title=Konfiguration
 * =====================================================================
 * !!!ACHTUNG!!!
 * Automatisch erzeugte Datei.
 * Manuelle Änderungen können vom Tethys-Installer überschrieben werden!
 * =====================================================================
 */
define('CFG_EXTENSION', '$CFG_EXTENSION');

define('ROOT_HDD_CORE', '$ROOT_HDD_CORE');
define('ROOT_HDD_MODULES', '$ROOT_HDD_MODULES');
define('ROOT_HDD_SKINS', '$ROOT_HDD_SKINS');
define('ROOT_HDD_DATA', '$ROOT_HDD_DATA');

define('ROOT_HTTP_CORE', '$ROOT_HTTP_CORE');
define('ROOT_HTTP_MODULES', '$ROOT_HTTP_MODULES');
define('ROOT_HTTP_SKINS', '$ROOT_HTTP_SKINS');
define('ROOT_HTTP_DATA', '$ROOT_HTTP_DATA');

/*
 * Datenbank
 */
define('TETHYSDB', '$TETHYSDB');
mysql_connect('$sql_server','$sql_user','$sql_pass');
mysql_select_db(TETHYSDB);

/*
 * Hauptmenü
 */
/*HM=*/include_once '$hauptmenue';

/*
 * Default Settings
 */
function get_default_setting(\$key){
	#if (\$key=='CFG_SKIN') return "demo";
	return null;
}

/*
 * Start
 */
if (!isset(\$configOnly)) include_once ROOT_HDD_CORE.'\\\\core\\\\start.php';
ENDE;
	$config_file="<?php\n$config_file\n?>";
	
	$file=fopen("config_start.php", "w");
	fwrite($file, $config_file);
	fclose($file);
	
	$page->say(html_div("Konfigurationsdatei erstellt."));
	if (!mysql_select_db($TETHYSDB)||!$update)
	$page->say(html_a_button("Datenbank initialisieren", "demo/database/update.".$CFG_EXTENSION.($update?"":"?install")));
// 	$page->say(html_button("config_start.php","","$('#cfg_file_content').toggle('invisible');"));
// 	$page->say(html_div(html_pre(encode_html($config_file),"code"),"invisible","cfg_file_content"));
	page_send_exit();
}
?>