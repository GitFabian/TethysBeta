<?php
include_once '../../config_start.php';
$page->init('core_settings','Konfiguration');
if (!USER_ADMIN) page_send_exit("Keine Berechtigung!");

include_once ROOT_HDD_CORE.'/core/classes/form.php';
include_chosen();

/*
 * Module => Views
 */
$views=array(new menu_topic2("core", CFG_TITLE));
foreach ($modules as $mod_id=>$modul){
	if ($modul->global_settings(null)){
		$views[]=new menu_topic2($mod_id, $modul->modul_name);
	}
}
$view=$page->init_views(setting_get_user(null,'SET_PGSEL_SETTINGS'),$views);
setting_save(null, 'SET_PGSEL_SETTINGS', $view, true);

if (request_command("updated")){
	$n=request_value("n");
	if ($n){
		$page->say(html_div("--- $n Konfigurationen aktualisiert. ---<br><br>"));
	}else{
		$page->say(html_div("--- (Keine Änderung) ---<br><br>"));
	}
}

if ($view!="core" && isset($modules[$view])){ include 'settings_module.php'; }

if (request_command("update")) core_settings_update2(null);

/*
 * Skins ermitteln
 */
$skins=array("wireframe"=>"wireframe","terminal"=>"terminal");
if (!file_exists(ROOT_HDD_SKINS)){
	echo "Verzeichnis \"".ROOT_HDD_SKINS."\" nicht gefunden!";
	exit;
}
$skins_dir=opendir(ROOT_HDD_SKINS);
while (false !== ($file = readdir($skins_dir))) {
	if ($file!='.'&&$file!='..'){
		$datei=ROOT_HDD_SKINS."/".$file;
		if (is_dir($datei)){
			if (file_exists($datei."/screen.css")){
				$skins[$file]=$file;
			}
		}
	}
}

/*
 * Module ermitteln
 */
if (!file_exists(ROOT_HDD_MODULES)){
	echo "Verzeichnis \"".ROOT_HDD_MODULES."\" nicht gefunden!";
	exit;
}
$module=array("demo"=>"demo");
$modules_dir=opendir(ROOT_HDD_MODULES);
while (false !== ($file = readdir($modules_dir))) {
	if ($file!='.'&&$file!='..'){
		$datei=ROOT_HDD_MODULES."/".$file;
		if (is_dir($datei)){
			if (file_exists($datei."/tethys.php")){
				$module[$file]=$file;
			}
		}
	}
}

/*
 * Formular
 */

$form=new form("update");

$form->add_fields(CFG_TITLE,null);

settings_add_field($form,"CFG_TITLE","Titel",'TEXT');
settings_add_field($form,"CFG_HOME_LABEL","Startseite-Menüeintrag",'TEXT');
settings_add_field($form,"CFG_HOME_URL","Startseite-URL",'TEXT');
settings_add_field($form,"CFG_HOME_TITLE","Index-Titel",'TEXT');
settings_add_field2($form,"LOGON_MSG","Logon Message",'TEXTAREA');

$form->add_fields("Aussehen",null);
settings_add_field2($form,"CFG_SKIN","Skin",'SELECT',$skins);
settings_add_field($form,"CFG_CSS_VERSION","CSS-Version",'TEXT');
settings_add_field2($form,"CFG_HAUPTMENUE","Haupmenü");
$form->add_field( new form_field("HM_ICONS","Haupmenü Icons",setting_get(null, 'HM_ICONS'),'CHECKBOX') );
$form->add_field( new form_field("HM_TEXT","Haupmenü Text",setting_get(null, 'HM_TEXT'),'CHECKBOX') );

$form->add_fields("Module",null);
$form->add_field( new form_field("CFG_MODULES[]","Module",$modules,'SELECT_MULTIPLE',null,$module) );

$form->add_fields("Benutzer",null);
settings_add_field2($form,"CFG_EDIT_NICK","Eigenen Nick bearbeiten",'CHECKBOX');
settings_add_field2($form,"CFG_EDIT_FILE","Stammdaten bearbeiten",'CHECKBOX');
$form->add_field( new form_field("CFG_MAX_USERS","Maximale Benutzeranzahl",setting_get(null, 'CFG_MAX_USERS'),'TEXT',"setting_get(null,'CFG_MAX_USERS')") );

$form->add_fields("Abwärtskompatibilität",null);
$form->add_field( new form_field("FEATURE_PRERELEASE","Pre-Release",setting_get(null, 'FEATURE_PRERELEASE'),'CHECKBOX',"setting_get(null,'FEATURE_PRERELEASE')") );
settings_add_field2($form,"DEPRECATED_HMLICLASS","div.mainmenu li div.menutopic",'CHECKBOX');


$page->say("Zur ".html_a("Server-Konfiguration", ROOT_HTTP_CORE."/install.".CFG_EXTENSION).".");
$page->say($form->toHTML());

$page->send();
exit;//============================================================================================
function settings_add_field($form,$cfg,$label,$type){
	if (setting_override(null,$cfg)!==null){
		$form->add_field( new form_field_info($cfg, $label, "[SERVER OVERRIDE] ".constant($cfg)) );
	}else{
		$form->add_field( new form_field($cfg, $label, constant($cfg), $type, $cfg) );
	}
}
function settings_add_field2($form,$cfg,$label,$type='TEXT',$options=null){
	if (setting_override(null,$cfg)!==null){
		$form->add_field( new form_field_info($cfg, $label, "[SERVER OVERRIDE] ".setting_get(null,$cfg)) );
	}else{
		$form->add_field( new form_field($cfg,$label,setting_get(null,$cfg),$type,"setting_get(null,'$cfg')",$options) );
	}
}
function core_settings_update2($modul){
	global $view;
	if (!USER_ADMIN) return;
	$n=0;
	unset($_REQUEST['view']);
	request_extract_booleans2();
	foreach ($_REQUEST as $key => $value) {
		if ($modul===null&&$key=='CFG_MODULES')$value=implode(",", $value);
		if (setting_save($modul, $key, $value, false)) $n++;
	}
	ajax_refresh("Speichere Konfiguration...", "settings.".CFG_EXTENSION."?view=$view&cmd=updated&n=$n");
}
?>