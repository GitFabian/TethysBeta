<?php
include_once '../../config_start.php';
$page->init('core_chronjobs','Chronjobs');
if (!USER_ADMIN) page_send_exit("Keine Berechtigung!");
include_once ROOT_HDD_CORE.'/core/classes/table.php';

$table=new table(dbio_SELECT("core_chronjobs",null,"*",null,"schedule",false));
$table->set_options(true, true, true, "core_chronjobs");
$page->say($table);
// if($table->rows)
// 	$page->say($table);
// else
// 	$page->message_info("Tabelle ist leer.");

$page->send();
exit;//============================================================================================
?>