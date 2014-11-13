<?php
include_once '../../config_start.php';
$page->init('core_logs','Logs');
if (!USER_ADMIN) page_send_exit("Keine Berechtigung!");
include_once ROOT_HDD_CORE.'/core/classes/table.php';

$page->say(new table(dbio_SELECT("core_logs")));

$page->say(new table(dbio_SELECT("core_logs_dbedit",null,"*",null,"time",false)));

$page->send();
exit;//============================================================================================
?>