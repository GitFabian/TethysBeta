<?php
include_once '../config_start.php';
$page->init("core_view", "Datensatz Details");
include_once ROOT_HDD_CORE.'/core/edit_.php';
include_once ROOT_HDD_CORE.'/core/classes/form.php';
include_once ROOT_HDD_CORE.'/core/classes/datasheet.php';

$db=request_value("db");
$id=request_value("id");

$query=dbio_SELECT_SINGLE($db, $id);

$form=new form(null,null,null,"datasheet");
edit_default_form($form,$query,$db,'id');

$datasheet=new datasheet("none", $db, $id);
$datasheet->edit=false;

foreach ($form->field_groups as $g) {
	foreach ($g->fields as $field) {
		$datasheet->add_data(new datasheet_data($field->name,$field->label, $field->value));
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