<?php
include_once '../../config_start.php';
$page->init('core_logs','Logs');
if (!USER_ADMIN) page_send_exit("Keine Berechtigung!");



$page->send();
exit;//============================================================================================
?>