<?php
include_once '../../config_start.php';
$page->init('core_logs','Logs');
if (!USER_ADMIN) page_send_exit("Keine Berechtigung!");
include_once ROOT_HDD_CORE.'/core/classes/table.php';

$table=new table(dbio_SELECT("core_logs"));
if($table->rows)
	$page->say($table);

$seite=(isset($_REQUEST['page'])?(($_REQUEST['page']-1)*500).",":"");
$table=new table(dbio_query_to_array("SELECT * FROM `core_logs_dbedit` ORDER BY `time` DESC LIMIT {$seite} 500"));
$page->say($table);

$page->send();
exit;//============================================================================================
?>