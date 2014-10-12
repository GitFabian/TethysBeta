<?php
include_once '../../core/start.php';

$page->init('demo_index','Demopage');

include_once CFG_HDDROOT.'/core/classes/table.php';
include_once CFG_HDDROOT.'/core/classes/form.php';

/*
 * Request verarbeiten:
 */
if (request_command("update")) update();

/*
 * Global Feature:
 */
// $page->add_div("<a href=\"".CFG_HTTPROOT."/core/admin/settings.".CFG_EXTENSION."?view=demo"."\">Feature 1</a>
// 		= ".(get_setting_global('demo','FEATURE1')?"activated":"inactive"));

/*
 * Formular, User Specific Setting:
 */
$form=new form("update");
$form->add_fields("User Specific Settings", array(
	new form_field("demosetting",null,get_setting_user('demo','demosetting')),
));
#$page->add_html($form->toHTML());

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
function update(){
	request_extract_booleans2();
	foreach ($_REQUEST as $key => $value) {
		update_setting_user('demo', $key, $value);
	}
	ajax_refresh("Speichere Settings...", "index.".CFG_EXTENSION);
}
?>