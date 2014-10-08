<?php
include_once '../../core/start.php';

$page->init('tethys_index','Entwickler-Modul');

include_once CFG_HDDROOT.'/core/classes/table.php';

#$page->add_html("Hello world!");

/*
 * Tabelle X
 */
$query_X = dbio_SELECT("core_users");
$table_X = new table();
$table_X->add_query($query_X);
$table_X->set_header(array(
		"id" => "ID",
		"nick" => "Anzeigename",
		"vorname" => "Vorname",
		"nachname" => "Name",
		"http_auth" => "LDAP",
));
#$page->add_html( $table_X->toHTML() );

$page->send();
exit;//============================================================================================
?>