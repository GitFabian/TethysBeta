<?php
/*

$views=array(
	new menu_topic2("core", CFG_TITLE),
);
$view=$page->init_views("core",$views);

=====================================

$views=array(new menu_topic2("core", CFG_TITLE));
foreach ($modules as $mod_id=>$modul){
	if ($modul->has_user_page){
		$views[]=new menu_topic2($mod_id, $modul->modul_name);
	}
}
$view=$page->init_views(setting_get_user(null,'SET_PGSEL_USERSETS'),$views);
setting_save(null, 'SET_PGSEL_USERSETS', $view, true);

 */
include_once '../config_start.php';
if(!USER_ADMIN)page_send_exit("Keine Berechtigung!");
$page->init("core_view", "Datensatz Details");
include_once ROOT_HDD_CORE.'/core/edit_.php';
include_once ROOT_HDD_CORE.'/core/classes/form.php';
include_once ROOT_HDD_CORE.'/core/classes/datasheet.php';

$db=request_value("db");
$id=request_value("id");

$modul=substr($db, 0, strpos($db, "_"));
/*
 * Modul "a_b", Tabelle "c_d":
 * ::_a_b,c_d
*/
$db0=$db;
if($modul=="::"){
	$mod_db=explode(",", $db);
	$modul=substr($mod_db[0], 3);
	$db=$mod_db[1];
}

$query=dbio_SELECT_SINGLE($db, $id);

$form=new form(null,null,null,"datasheet");
edit_default_form($form,$query,$db,'id');

$datasheet=new datasheet($modul, $db, $id);
$datasheet->edit=false;

foreach ($form->field_groups as $g) {
	foreach ($g->fields as $field) {
		$datasheet->add_data(new datasheet_data($field->name,$field->label, html_pre($field->value) ));
	}
}

$page->say(html_header1("Details"));
$page->say($datasheet->toHTML());

/*
 * Logs
 */
include_once ROOT_HDD_CORE.'/core/log.php';
$page->say(logs_for_entity($db,$id));

page_send_exit();
?>