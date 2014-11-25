<?php
include_once '../../config_start.php';
$page->init('core_rollen','Rollen');
if (!USER_ADMIN) page_send_exit("Keine Berechtigung!");

include_once ROOT_HDD_CORE.'/core/classes/table.php';
include_once ROOT_HDD_CORE.'/core/alertify.php';

/*
 * Rollen
 */
$page->say(html_header1("Rollen"));
$query=dbio_SELECT("core_rollen");
$rollen=array();
foreach ($query as $row) {
	$rollen[$row['id']]=$row['name'];
}
$table=new table($query,null,false);
#$table->set_options(true, true, true, "core_rollen");
$page->say($table);

/*
 * Benutzer
 */
$page->say(html_header1("Benutzer"));
$query=dbio_SELECT("core_user_rolle");
$users=dbio_SELECT("core_users","active");
$data=array();
foreach ($users as $u) {
	$uid=$u['id'];
	$row=array("id"=>$uid,"name"=>$u['nick']);
	foreach ($rollen as $rid => $dummy) {
		$row[$rid]=html_checkbox(null,false,"rolleSetzen(this,'$uid','$rid');");
	}
	$data[$uid]=$row;
}
foreach ($query as $row) {
	$uid=$row['user'];
	$rid=$row['rolle'];
	$data[$uid][$rid]=html_checkbox(null,true,"rolleSetzen(this,'$uid','$rid');");
}
$table=new table($data);
$header=array("name"=>"Name");
foreach ($rollen as $key => $value) {
	$header[$key]=$value;
}
$table->set_header($header);
#$table->set_options(true, true, true, "core_user_rolle");
$table->export_csv_id="core_rollen";
$page->say($table);
$page->add_inline_script("function rolleSetzen(e,uid,rid){
		".ajax_to_alertify("rolleSetzen&checked=\"+e.checked+\"&uid=\"+uid+\"&rid=\"+rid+\"")."
}");

$page->send();
exit;//============================================================================================
?>