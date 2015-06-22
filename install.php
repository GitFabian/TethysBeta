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

	//preg_match("/\\n\\s*\\\$sql\\s*=\\s*mysql_connect\\s*\\(\\s*'(.*?)'\\s*,\\s*'(.*?)'\\s*,\\s*'(.*?)'\\s*\\)\\s*;/", $content, $matches);
	//Abwärtskompatibilität: (11'14)
	preg_match("/\\n\\s*(?:\\\$sql\\s*=\\s*)?mysql_connect\\s*\\(\\s*'(.*?)'\\s*,\\s*'(.*?)'\\s*,\\s*'(.*?)'\\s*\\)\\s*;/", $content, $matches);
	$sql_server=$matches[1];
	$sql_user=$matches[2];
	$sql_pass=$matches[3];
	
	preg_match("/\\nfunction setting_override\\(\\\$modul,\\\$key\\){\\r?\\n(.*?)\\s*?return null;\\r?\\n}\\/\\*OE\\*\\//s", $content, $matches);
	$override=$matches[1];
	
}else{
	include_once 'core/classes/page.php';
	include_once 'core/classes/menu.php';
	include_once 'core/toolbox.php';
	include_once 'core/settings.php';
	define('ROOT_HTTP_CORE','.');
	define('USER_ADMIN','0');
	define('CFG_CSS_VERSION','');
	define('CFG_TITLE','Installation');
	function hauptmenue($page_id){return null;}
	function setting_override($modul,$key){return null;}
	$page=new page();
	header('Content-type: text/html; charset=UTF-8');
	$page->add_stylesheet("demo/skins/terminal/screen.css");
	$extension=substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], '.')+1);
	define('CFG_EXTENSION', $extension);
	$mydir=getcwd();
	define('ROOT_HDD_CORE', $mydir);
	define('ROOT_HDD_MODULES', $mydir.'/modules');
	define('ROOT_HDD_SKINS', $mydir.'/skins');
	define('ROOT_HDD_DATA', $mydir.'/DATA');
	$http_core=substr($_SERVER["SCRIPT_NAME"],0,strrpos($_SERVER["SCRIPT_NAME"], "/"));
	define('ROOT_HTTP_MODULES', $http_core.'/modules');
	define('ROOT_HTTP_SKINS', $http_core.'/skins');
	define('ROOT_HTTP_DATA', $http_core.'/DATA');
	define('CFG_LOGON_TYPE', 'none');
	define('LOGON_NONE_DEF_USER', '1');
	define('CFG_LOGON_COOKIE', '0');
	define('TETHYSDB', 'tethys');
	$sql_server="localhost";#$_SERVER["SERVER_NAME"];
	$sql_user="";
	$sql_pass="";
	$override="#if(\$modul==null&&\$key=='FEATURE_PRERELEASE')return \"1\";";
}
$page->init("core_admin", "Server-Konfiguration");
if(request_command("run"))run($update);
include_once 'core/classes/form.php';
include_jquery();

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

$form->add_fields("Benutzerverwaltung",array(
		$logonType=new form_field("CFG_LOGON_TYPE","Logon",request_value("CFG_LOGON_TYPE",CFG_LOGON_TYPE),'SELECT',null,array(
				"none"=>"(Kein)",
				"http"=>"HTTP-Auth",
				"cookie"=>"Cookie",
		),"id_logonType"),
		$defUser=new form_field("LOGON_NONE_DEF_USER","Default User",request_value("LOGON_NONE_DEF_USER",LOGON_NONE_DEF_USER)),
		$logonoverride=new form_field("CFG_LOGON_COOKIE","Logon Override (Cookies)",request_value("CFG_LOGON_COOKIE",CFG_LOGON_COOKIE),"CHECKBOX"),
));
$logonType->onChange="update_form();";
$page->onload_JS.="update_form();";
$defUser->outer_id="id_defUser";
$logonoverride->outer_id="id_logonoverride";
$page->add_inline_script("function update_form(){
		type=document.getElementById('id_logonType').options[document.getElementById('id_logonType').selectedIndex].value;
		//Default User:
		if (type=='none'){
			$('#id_defUser').removeClass('invisible');
		}else{
			$('#id_defUser').addClass('invisible');
		}
		//Override:
		if (type=='cookie'){
			$('#id_logonoverride').addClass('invisible');
		}else{
			$('#id_logonoverride').removeClass('invisible');
		}
}");

$form->add_fields("",array(
		new form_field("CFG_EXTENSION","Virtuelle Extension",request_value("CFG_EXTENSION",CFG_EXTENSION)),
		new form_field("override","Server-spezifische Konfiguration",request_value("override",$override),'TEXTAREA'),
));

$page->say(html_header1(($update?"Server-Konfiguration":"Installation")));
$page->say($form->toHTML());

$page->send();
exit;
//===========================================================================
function run($update){
	include_jquery();
	global $page;
	request_extract_booleans2();
	foreach ($_REQUEST as $key => $value) {
		$$key=$value;
	}
	$fehler=null;
	//SQL-Server
	if (!@mysql_connect($sql_server,$sql_user,$sql_pass)){
		$fehler="Fehler bei SQL-Verbindung: ".mysql_error();
	}else if(!file_exists($ROOT_HDD_CORE."/install.php")){
		$fehler="Server-Verzeichnis nicht gefunden!";
	}
	
	if ($fehler){
		$page->message_error($fehler);
		return;
	}
	
	$ROOT_HDD_CORE2=preg_replace("/\\\\/", "\\\\\\\\", $ROOT_HDD_CORE);
	$ROOT_HDD_MODULES2=preg_replace("/\\\\/", "\\\\\\\\", $ROOT_HDD_MODULES);
	$ROOT_HDD_SKINS2=preg_replace("/\\\\/", "\\\\\\\\", $ROOT_HDD_SKINS);
	$ROOT_HDD_DATA2=preg_replace("/\\\\/", "\\\\\\\\", $ROOT_HDD_DATA);
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

define('ROOT_HDD_CORE', '$ROOT_HDD_CORE2');
define('ROOT_HDD_MODULES', '$ROOT_HDD_MODULES2');
define('ROOT_HDD_SKINS', '$ROOT_HDD_SKINS2');
define('ROOT_HDD_DATA', '$ROOT_HDD_DATA2');

define('ROOT_HTTP_CORE', '$ROOT_HTTP_CORE');
define('ROOT_HTTP_MODULES', '$ROOT_HTTP_MODULES');
define('ROOT_HTTP_SKINS', '$ROOT_HTTP_SKINS');
define('ROOT_HTTP_DATA', '$ROOT_HTTP_DATA');

/*
 * Benutzerverwaltung
 */
define('CFG_LOGON_TYPE','$CFG_LOGON_TYPE');
define('LOGON_NONE_DEF_USER','$LOGON_NONE_DEF_USER');
define('CFG_LOGON_COOKIE', '$CFG_LOGON_COOKIE');

/*
 * Datenbank
 */
define('TETHYSDB', '$TETHYSDB');
\$sql=mysql_connect('$sql_server','$sql_user','$sql_pass');
mysql_select_db(TETHYSDB);

/*
 * Server-spezifische Konfiguration
 */
function setting_override(\$modul,\$key){
$override
return null;
}/*OE*/
		
/*
 * Start
 */
if (!isset(\$configOnly)) include_once ROOT_HDD_CORE.'/core/start.php';
ENDE;
	$config_file="<?php\n$config_file\n?>";
	
	$file=fopen("config_start.php", "w");
	fwrite($file, $config_file);
	fclose($file);
	
	if (!file_exists($ROOT_HDD_SKINS)){ mkdir($ROOT_HDD_SKINS); }
	if (!file_exists($ROOT_HDD_MODULES)){ mkdir($ROOT_HDD_MODULES); }
	
	$page->say(html_div("Konfigurationsdatei erstellt."));
	if (!mysql_select_db($TETHYSDB)||!$update)
		$page->say(html_a_button("Datenbank initialisieren", "demo/database/update.".$CFG_EXTENSION.($update?"":"?install")));
	$page->say(" ".html_a("Zur Konfiguration", ROOT_HTTP_CORE."/core/admin/settings.".CFG_EXTENSION));
// 	$page->say(html_button("config_start.php","","$('#cfg_file_content').toggle('invisible');"));
// 	$page->say(html_div(html_pre(encode_html($config_file),"code"),"invisible","cfg_file_content"));

	/*
	 * Apache-Konfiguration
	 */
	$page->say(html_header1("Apache-Konfiguration"));
	$ROOT_HDD_CORE3=preg_replace("/\\\\/", "/", $ROOT_HDD_CORE);
	$ROOT_HDD_MODULES3=preg_replace("/\\\\/", "/", $ROOT_HDD_MODULES);
	$ROOT_HDD_SKINS3=preg_replace("/\\\\/", "/", $ROOT_HDD_SKINS);
	$ROOT_HDD_DATA3=preg_replace("/\\\\/", "/", $ROOT_HDD_DATA);
	
	$aliasse=<<<END_ALIAS
Alias $ROOT_HTTP_CORE "$ROOT_HDD_CORE3"
<Directory "$ROOT_HDD_CORE3">
	Allow from all
	AllowOverride All
</Directory>
END_ALIAS;
	
	if (substr($ROOT_HDD_MODULES, 0, strlen($ROOT_HDD_CORE))!=$ROOT_HDD_CORE
			||substr($ROOT_HTTP_MODULES, 0, strlen($ROOT_HTTP_CORE))!=$ROOT_HTTP_CORE
			||substr($ROOT_HDD_MODULES3, strlen($ROOT_HDD_CORE))!=substr($ROOT_HTTP_MODULES, strlen($ROOT_HTTP_CORE))
			||substr($ROOT_HDD_MODULES3, strlen($ROOT_HDD_CORE), 1)!='/'
			){
		$alias_mod=<<<END_ALIAS_MOD
Alias $ROOT_HTTP_MODULES "$ROOT_HDD_MODULES3"
<Directory "$ROOT_HDD_MODULES3">
	Allow from all
	AllowOverride All
</Directory>
END_ALIAS_MOD;
		$aliasse=$alias_mod."\n\n".$aliasse;
	}

	if (substr($ROOT_HDD_SKINS, 0, strlen($ROOT_HDD_CORE))!=$ROOT_HDD_CORE
			||substr($ROOT_HTTP_SKINS, 0, strlen($ROOT_HTTP_CORE))!=$ROOT_HTTP_CORE
			||substr($ROOT_HDD_SKINS3, strlen($ROOT_HDD_CORE))!=substr($ROOT_HTTP_SKINS, strlen($ROOT_HTTP_CORE))
			||substr($ROOT_HDD_SKINS3, strlen($ROOT_HDD_CORE), 1)!='/'
	){
		$alias_skin=<<<END_ALIAS_MOD
Alias $ROOT_HTTP_SKINS "$ROOT_HDD_SKINS3"
<Directory "$ROOT_HDD_SKINS3">
	Allow from all
	AllowOverride All
</Directory>
END_ALIAS_MOD;
		$aliasse=$alias_skin."\n\n".$aliasse;
	}

	$alias_data=<<<END_ALIAS_DATA
Alias $ROOT_HTTP_DATA "$ROOT_HDD_DATA3"
<Directory "$ROOT_HDD_DATA3">
	Allow from all
	AllowOverride All
</Directory>
END_ALIAS_DATA;
	$aliasse.="\n\n".$alias_data;
	
	$rewrite="";
	if ($CFG_EXTENSION!='php'){
		$rewrite=<<<END_REWRITE
RewriteRule ^$ROOT_HTTP_CORE/(.*)\.$CFG_EXTENSION\$ "$ROOT_HDD_CORE3/\$1.php"
END_REWRITE;
		$rewrite.="\n";
	}
	
	$info=<<<END_INFO
LoadModule alias_module modules/mod_alias.so
LoadModule rewrite_module modules/mod_rewrite.so

$aliasse

RewriteEngine on
RewriteRule ^$ROOT_HTTP_DATA/(.*)\$ "$ROOT_HDD_CORE3/core/data.php?url=\$1"
$rewrite#RewriteRule ^$ROOT_HTTP_CORE/export/(.*)\.csv\$ "$ROOT_HDD_CORE3/core/export_csv.php?db=\$1"
END_INFO;
	$page->say(html_code(encode_html($info)));
	
	page_send_exit();
}
?>