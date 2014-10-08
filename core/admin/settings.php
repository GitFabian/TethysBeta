<?php
include_once '../start.php';

$page->init('core_settings','Konfiguration');

if (request_command("update")) core_settings_update();

include_once CFG_HDDROOT.'/core/classes/form.php';

if (request_command("updated")) $page->add_div("--- Konfiguration aktualisiert. ---<br><br>");

$form=new form("update");

$form->add_field(new form_field("CFG_TITLE","Titel",CFG_TITLE));
$form->add_field(new form_field("CFG_HOME_LABEL","Startseite-Menüeintrag",CFG_HOME_LABEL));
// $form->add_field(new form_field("CFG_","Startseite-URL",CFG_));
$form->add_field(new form_field("CFG_HOME_TITLE","Index-Titel",CFG_HOME_TITLE));
$form->add_field(new form_field("CFG_SKIN","Skin",CFG_SKIN));
$form->add_field(new form_field("CFG_MODULES","Module",CFG_MODULES));

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