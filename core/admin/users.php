<?php
include_once '../../config_start.php';

$page->init('core_users','Benutzerverwaltung');

include_once ROOT_HDD_CORE.'/core/classes/table.php';

$query=dbio_SELECT("core_users");
$table=new table($query);
$table->set_options(true, true, true, "core_users");
$page->say($table);

$page->focus="input[type=search]";

$page->send();
exit;//============================================================================================
?>