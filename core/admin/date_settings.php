<?php
include_once '../../config_start.php';
$page->init('core_datesettings','Date Settings');
if (!USER_ADMIN) page_send_exit("Keine Berechtigung!");
include_once ROOT_HDD_CORE.'/core/classes/form.php';

if(request_command("update")) update();

if(isset($modules['azeit']))
	$page->say(html_div("Achtung! Änderung an den Feiertagen setzt gesamten Urlaubstage-Cache zurück!"));

$form=new form("update");

$form->add_fields("Feiertage", null);
#$form->add_field(new form_field("FT_neujahr","Neujahr",request_value("FT_neujahr",setting_get(null, "FT_neujahr")),"CHECKBOX"));
$form->add_field(new form_field("FT_weitere_fest","Weitere feste Feiertage",request_value("FT_weitere_fest",setting_get(null, "FT_weitere_fest")),"TEXTAREA"));

$page->say($form);

page_send_exit();//====================================================================
function update(){
	global $page,$modules;
	request_extract_booleans2();
	foreach ($_REQUEST as $key => $value) {
		setting_save(null, $key, $value, false);
	}
	$page->message_ok("Gespeichert.");
	if(isset($modules['azeit'])){
		dbio_UPDATE("azeit_urlaub", "1", array("werktage"=>null));
		dbio_query("TRUNCATE `azeit_urlaub_summe`");
		dbio_query("DELETE azeit_urlaub_uebertrag.*"
				." FROM `azeit_urlaub_uebertrag`"
				." LEFT JOIN `azeit_users` ON azeit_urlaub_uebertrag.user=azeit_users.user"
				." WHERE urlaub_start!=jahr");
		$page->message_ok("Urlaubstage-Cache zurückgesetzt.");
	}
}
?>