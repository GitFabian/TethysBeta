<?php
include_once '../../config_start.php';
$page->init('core_rollen','Rollen');
if (!USER_ADMIN) page_send_exit("Keine Berechtigung!");

include_once ROOT_HDD_CORE.'/core/classes/table.php';
#include_once ROOT_HDD_CORE.'/core/classes/rights.php';
#include_once ROOT_HDD_CORE.'/core/alertify.php';

/*
 * Rollen
 */
$page->say(html_header1("Rollen"));
$query=dbio_SELECT("core_rollen");
$table=new table($query);
$table->set_options(true, true, true, "core_rollen");
$page->say($table);

/*
 * Benutzer
 */
$page->say(html_header1("Benutzer"));
$query=dbio_SELECT("core_user_rolle");
$table=new table($query);
$table->set_options(true, true, true, "core_user_rolle");
$page->say($table);


$page->send();
exit;//============================================================================================
?>