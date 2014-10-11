<?php
include_once '../../core/start.php';

$page->init('demo_index','Demopage');

include_once CFG_HDDROOT.'/core/classes/table.php';

/*
 * Features:
 */
$page->add_div("<a href=\"".CFG_HTTPROOT."/core/admin/settings.".CFG_EXTENSION."?view=demo"."\">Feature 1</a>
		= ".($settings['demo']['FEATURE1']?"activated":"inactive"));

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