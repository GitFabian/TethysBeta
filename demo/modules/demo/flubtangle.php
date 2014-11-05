<?php
include_once '../../config_start.php';
$page->init('demo','Flubtangle');
include_once ROOT_HDD_CORE.'/core/classes/datasheet.php';
include_once ROOT_HDD_CORE.'/core/classes/set.php';

$id=request_value("id");

$query=dbio_SELECT_SINGLE("demo_lorumipsum", $id);

$page->say(html_header1($query['flubtangle']));
$page->say(datasheet::from_db("demo", "demo_lorumipsum", $id, $query));

$page->say(html_header2("Mitglieder"));
$query=dbio_SELECT("demo_flubtangle_user","flubtangle=$id AND active","vorname,nachname,nick,http_auth,picture",array(
	new dbio_leftjoin("user", "core_users", "u"),
));
$page->say(set::from_db("demo", "user", $query));

page_send_exit();//================================================================================
?>