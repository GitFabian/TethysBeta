<?php
include_once '../../config_start.php';
$page->init('myqueries_cons','Connections');
if(!USER_ADMIN)page_send_exit("Keine Berechtigung!");
include_once ROOT_HDD_CORE.'/core/classes/table.php';

/*
 * Connections
 */
$page->say(html_header1("Verbindungen"));
$query=dbio_SELECT("myqueries_connections");
$table=new table($query);
$table->set_options(true, true, true, "myqueries_connections");
$page->say($table);

/*
 * Benutzer
 */
$page->say(html_header1("Freigaben"));
$query=dbio_SELECT("myqueries_user_query");
$table=new table($query);
$table->set_options(true, true, true, "myqueries_user_query");
$page->say($table);

page_send_exit();//===============================================================================
?>