<?php
include_once '../../config_start.php';

$page->init('core_settings','Konfiguration');

include_once ROOT_HDD_CORE.'/core/classes/form.php';

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
$skins=array("demo"=>"demo");
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
 * Formular
 */

$form=new form("update");

$form->add_fields(CFG_TITLE,null);

settings_add_field($form,"CFG_TITLE","Titel",'TEXT');
settings_add_field($form,"CFG_HOME_LABEL","Startseite-Menüeintrag",'TEXT');
settings_add_field($form,"CFG_HOME_URL","Startseite-URL",'TEXT');
settings_add_field($form,"CFG_HOME_TITLE","Index-Titel",'TEXT');

$form->add_fields("Aussehen",null);
$form->add_field( new form_field("CFG_SKIN","Skin",setting_value('CFG_SKIN'),'SELECT',null,$skins) );
settings_add_field($form,"CFG_CSS_VERSION","CSS-Version",'TEXT');
$form->add_field( new form_field("HM_ICONS","Haupmenü Icons",setting_get(null, 'HM_ICONS'),'CHECKBOX') );
$form->add_field( new form_field("HM_TEXT","Haupmenü Text",setting_get(null, 'HM_TEXT'),'CHECKBOX') );

$form->add_fields("Module",null);
settings_add_field($form,"CFG_MODULES","Module",'TEXT');

$form->add_fields("Features",null);
settings_add_field($form,"FEATURE_BETA","BETA-Features",'CHECKBOX');

$form->add_fields("Abwärtskompatibilität",null);
$form->add_field( new form_field("DEPRECATED_HMLICLASS","div.mainmenu li div.menutopic",setting_get(null, 'DEPRECATED_HMLICLASS'),'CHECKBOX') );

$page->say("Zur ".html_a("Server-Konfiguration", ROOT_HTTP_CORE."/install.".CFG_EXTENSION).".");
$page->say($form->toHTML());

$page->send();
exit;//============================================================================================
function settings_add_field($form,$cfg,$label,$type){
	$form->add_field( new form_field($cfg, $label, constant($cfg),$type,$cfg) );
}
function core_settings_update2($modul){
	global $view;
	if (!USER_ADMIN) return;
	$n=0;
	unset($_REQUEST['view']);
	request_extract_booleans2();
	if ($modul===null){
		if (setting_save(null, 'HM_ICONS', request_unset('HM_ICONS'), false)) $n++;
		if (setting_save(null, 'HM_TEXT', request_unset('HM_TEXT'), false)) $n++;
	}
	foreach ($_REQUEST as $key => $value) {
		if (setting_save($modul, $key, $value, false)) $n++;
	}
	ajax_refresh("Speichere Konfiguration...", "settings.".CFG_EXTENSION."?view=$view&cmd=updated&n=$n");
}
?>