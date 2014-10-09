<?php
include_once '../start.php';

$page->init('core_settings','Konfiguration');

if (request_command("update")) core_settings_update();

include_once CFG_HDDROOT.'/core/classes/form.php';

if (request_command("updated")) $page->add_div("--- Konfiguration aktualisiert. ---<br><br>");

$form=new form("update");

if(false/*Entwicklungshilfe*/)define('CFG_TITLE');
$form->add_field(new form_field(     "CFG_TITLE",
		"Titel",                      CFG_TITLE));

if(false/*Entwicklungshilfe*/)define('CFG_HOME_LABEL');
$form->add_field(new form_field(     "CFG_HOME_LABEL",
		"Startseite-Menüeintrag",     CFG_HOME_LABEL));

if(false/*Entwicklungshilfe*/)define('CFG_HOME_URL');
$form->add_field(new form_field(     "CFG_HOME_URL",
		"Startseite-URL",             CFG_HOME_URL));

if(false/*Entwicklungshilfe*/)define('CFG_HOME_TITLE');
$form->add_field(new form_field(     "CFG_HOME_TITLE",
		"Index-Titel",                CFG_HOME_TITLE));

if(false/*Entwicklungshilfe*/)define('CFG_SKIN');
$form->add_field(new form_field(     "CFG_SKIN",
		"Skin",                       CFG_SKIN));

if(false/*Entwicklungshilfe*/)define('CFG_MODULES');
$form->add_field(new form_field(     "CFG_MODULES",
		"Module",                     CFG_MODULES));

if(false/*Entwicklungshilfe*/)define('CFG_CSS_VERSION');
$form->add_field(new form_field(     "CFG_CSS_VERSION",
		"CSS-Version",                CFG_CSS_VERSION));

$page->add_html($form->toHTML());

$page->send();
exit;//============================================================================================
function core_settings_update(){
	if (!USER_ADMIN) return;
	foreach ($_REQUEST as $key => $value) {
		if ($value!=constant($key)) dbio_UPDATE("core_config", "phpname='$key'", array("value"=>$value));
	}
	ajax_refresh("Speichere Konfiguration...", "settings.".CFG_EXTENSION."?cmd=updated");
}
?>