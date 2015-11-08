<?php
include_once '../../config_start.php';
$page->init('core_chronjobs','Chronjobs');
if (!USER_ADMIN) page_send_exit("Keine Berechtigung!");
include_once ROOT_HDD_CORE.'/core/classes/table.php';

$query=dbio_SELECT("core_chronjobs",null,"*",null,"schedule",false);

$data=array();
foreach ($query as $d) {
	$d["schedule"]=format_Wochentag_Uhrzeit($d["schedule"]);
	$d["sent"]=$d["sent"]?format_Wochentag_Uhrzeit($d["sent"]):"";
	$data[]=$d;
}

$table=new table($data);
$table->set_options(true, true, true, "core_chronjobs");
$table->datatable->paginate=true;
$page->say($table);
// if($table->rows)
// 	$page->say($table);
// else
// 	$page->message_info("Tabelle ist leer.");

$page->send();
exit;//============================================================================================
?>