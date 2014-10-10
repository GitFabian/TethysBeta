<?php
include_once '../start.php';

$page->init('core_settings','Konfiguration');

include_once CFG_HDDROOT.'/core/classes/form.php';

$view=$page->init_views($user['set_pageselected_settings'],array(
	new menu_topic2('settings', "Settings"),
	new menu_topic2('features', "Features"),
));
dbio_UPDATE("core_users", "id=".USER_ID, array("set_pageselected_settings"=>$view));

if (request_command("updated")){
	$n=request_value("n");
	if ($n){
		$page->add_div("--- $n Konfigurationen aktualisiert. ---<br><br>");
	}else{
		$page->add_div("--- (Keine Änderung) ---<br><br>");
	}
}

if ($view=="features"){ include 'settings_features.php'; }

if (request_command("update")) core_settings_update();

$form=new form("update");

if(false/*!DEV!*/)define('CFG_TITLE');
settings_add_field($form,"CFG_TITLE","Titel");

if(false/*!DEV!*/)define('CFG_HOME_LABEL');
settings_add_field($form,"CFG_HOME_LABEL","Startseite-Menüeintrag");

if(false/*!DEV!*/)define('CFG_HOME_URL');
settings_add_field($form,"CFG_HOME_URL","Startseite-URL");

if(false/*!DEV!*/)define('CFG_HOME_TITLE');
settings_add_field($form,"CFG_HOME_TITLE","Index-Titel");

if(false/*!DEV!*/)define('CFG_SKIN');
settings_add_field($form,"CFG_SKIN","Skin");

if(false/*!DEV!*/)define('CFG_MODULES');
settings_add_field($form,"CFG_MODULES","Module");

if(false/*!DEV!*/)define('CFG_CSS_VERSION');
settings_add_field($form,"CFG_CSS_VERSION","CSS-Version");

$page->add_html($form->toHTML());

$page->send();
exit;//============================================================================================
function settings_add_field($form,$cfg,$label,$type='TEXT'){
	$form->add_field( new form_field($cfg, $label, constant($cfg),$type,$cfg) );
}
function core_settings_update(){
	if (!USER_ADMIN) return;
	$n=0;
	foreach ($_REQUEST as $key => $value) {
		if ($value!=constant($key)){
			dbio_UPDATE("core_config", "phpname='$key'", array("value"=>$value));
			$n++;
		}
	}
	ajax_refresh("Speichere Konfiguration...", "settings.".CFG_EXTENSION."?cmd=updated&n=$n");
}
?>