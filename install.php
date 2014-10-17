<?php
include_once 'core/classes/page.php';
include_once 'core/classes/menu.php';
include_once 'core/toolbox.php';
include_once 'core/classes/form.php';
$update=false;
if(file_exists('config_start.php')){
	$update=true;
}else{
	define('ROOT_HTTP_CORE','.');
	define('USER_ADMIN','0');
	define('CFG_CSS_VERSION','');
}
$page=new page();
$page->add_stylesheet("demo/skins/demo/screen.css");
if(request_command("run"))run();

$form=new form("run");
$form->add_fields("MySQL",array(
		new form_field("sql_server","Server"),
		new form_field("TETHYSDB","Datenbank"),
		new form_field("sql_user","Benutzername"),
		new form_field("sql_pass","Passwort"),
));
$form->add_fields("Server-Verzeichnisse (HDD)",array(
		new form_field("ROOT_HDD_CORE","Tethys"),
		new form_field("ROOT_HDD_MODULES","Module"),
		new form_field("ROOT_HDD_SKINS","Skins"),
		new form_field("ROOT_HDD_DATA","Data"),
));
$form->add_fields("Server-Pfade (HTTP)",array(
		new form_field("ROOT_HTTP_CORE","Tethys"),
		new form_field("ROOT_HTTP_MODULES","Module"),
		new form_field("ROOT_HTTP_SKINS","Skins"),
		new form_field("ROOT_HTTP_DATA","Data"),
));

$page->add_header1(($update?"Installation wiederholen":"Installation"));
$page->add_html($form->toHTML());

$page->send();
exit;
//===========================================================================
function hauptmenue($page_id){
	global $update;
	if (!$update) return null;
	$menu=new menu(null,null,$page_id);
	new menu_topic($menu,"core_index",$page_id, "Start", "." );
	return $menu->toHTML();
}
function run(){
	global $page;
	foreach ($_REQUEST as $key => $value) {
		$$key=$value;
	}
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
define('CFG_EXTENSION', 'php');

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
#include_once 'C:\\\\...\\\\MyProject\\\\shared\\\\config_xxxxxxxxxx.php';
function hauptmenue(\$page_id){
	\$menu=menu_get_default(\$page_id);
	return \$menu->toHTML();
}

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
	$page->add_pre(encode_html($config_file),"code");
	page_send_exit();
}
?>