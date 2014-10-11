<?php
include_once '../start.php';

$page->init('core_settings','Konfiguration');

include_once CFG_HDDROOT.'/core/classes/form.php';

/*
 * Settings-Module => Views
 */
$views=array(new menu_topic2('core', CFG_TITLE));
foreach ($settings as $sets_module=>$dummy) {
	if (isset($modules[$sets_module]))
	$views[]=new menu_topic2($sets_module, $modules[$sets_module]->modul_name);
}
$view=$page->init_views($user['set_pageselected_settings'],$views);
dbio_UPDATE("core_users", "id=".USER_ID, array("set_pageselected_settings"=>$view));

if (request_command("updated")){
	$n=request_value("n");
	if ($n){
		$page->add_div("--- $n Konfigurationen aktualisiert. ---<br><br>");
	}else{
		$page->add_div("--- (Keine Änderung) ---<br><br>");
	}
}

if ($view!="core" && isset($modules[$view])){ include 'settings_module.php'; }

if (request_command("update")) core_settings_update();

$form=new form("update");

$form->add_fields(CFG_TITLE,null);

if(false/*!DEV!*/)define('CFG_TITLE');
settings_add_field($form,"CFG_TITLE","Titel");
if(false/*!DEV!*/)define('CFG_HOME_TITLE');
settings_add_field($form,"CFG_HOME_LABEL","Startseite-Menüeintrag");
if(false/*!DEV!*/)define('CFG_HOME_URL');
settings_add_field($form,"CFG_HOME_URL","Startseite-URL");
settings_add_field($form,"CFG_HOME_TITLE","Index-Titel");
if(false/*!DEV!*/)define('CFG_HOME_LABEL');

$form->add_fields("Aussehen",null);
if(false/*!DEV!*/)define('CFG_SKIN');
settings_add_field($form,"CFG_SKIN","Skin");
if(false/*!DEV!*/)define('CFG_CSS_VERSION');
settings_add_field($form,"CFG_CSS_VERSION","CSS-Version");

$form->add_fields("Module",null);
if(false/*!DEV!*/)define('CFG_MODULES');
settings_add_field($form,"CFG_MODULES","Module");

$form->add_fields("Features",null);
if(false/*!DEV!*/)define('FEATURE_BETA');
settings_add_field($form,"FEATURE_BETA","BETA-Features",'CHECKBOX');

$page->add_html($form->toHTML());

$page->send();
exit;//============================================================================================
function settings_add_field($form,$cfg,$label,$type='TEXT'){
	$form->add_field( new form_field($cfg, $label, constant($cfg),$type,$cfg) );
}
function core_settings_update(){
	if (!USER_ADMIN) return;
	$n=0;
	request_extract_booleans2();
// 	$_REQUEST=array_merge($_REQUEST,$booleans);
	foreach ($_REQUEST as $key => $value) {
		if ($value!=constant($key)){
			dbio_UPDATE("core_config", "phpname='$key'", array("value"=>$value));
			$n++;
		}
	}
	ajax_refresh("Speichere Konfiguration...", "settings.".CFG_EXTENSION."?cmd=updated&n=$n");
}
?>