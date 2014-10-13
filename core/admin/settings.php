<?php
include_once '../start.php';

$page->init('core_settings','Konfiguration');

include_once CFG_HDDROOT.'/core/classes/form.php';

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
		$page->add_div("--- $n Konfigurationen aktualisiert. ---<br><br>");
	}else{
		$page->add_div("--- (Keine Änderung) ---<br><br>");
	}
}

if ($view!="core" && isset($modules[$view])){ include 'settings_module.php'; }

if (request_command("update")) core_settings_update2(null);

$form=new form("update");

$form->add_fields(CFG_TITLE,null);

settings_add_field($form,"CFG_TITLE","Titel",'TEXT');
settings_add_field($form,"CFG_HOME_LABEL","Startseite-Menüeintrag",'TEXT');
settings_add_field($form,"CFG_HOME_URL","Startseite-URL",'TEXT');
settings_add_field($form,"CFG_HOME_TITLE","Index-Titel",'TEXT');

$form->add_fields("Aussehen",null);
settings_add_field($form,"CFG_SKIN","Skin",'TEXT');
settings_add_field($form,"CFG_CSS_VERSION","CSS-Version",'TEXT');

$form->add_fields("Module",null);
settings_add_field($form,"CFG_MODULES","Module",'TEXT');

$form->add_fields("Features",null);
settings_add_field($form,"FEATURE_BETA","BETA-Features",'CHECKBOX');
//$form->add_field( new form_field("FEAT_EDIT_NICK","Nick bearbeiten",setting_value('FEAT_EDIT_NICK'),'CHECKBOX') );

$page->add_html($form->toHTML());

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
	foreach ($_REQUEST as $key => $value) {
		if (setting_save($modul, $key, $value, false)) $n++;
	}
	ajax_refresh("Speichere Konfiguration...", "settings.".CFG_EXTENSION."?view=$view&cmd=updated&n=$n");
}
?>