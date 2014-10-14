<?php
include_once '../start.php';

$page->init('core_rights','Rechte');

include_once CFG_HDDROOT.'/core/classes/table.php';
include_once CFG_HDDROOT.'/core/classes/rights.php';

/*
 * Beschreibung aller Rechte
 */
$all_rights=array(
	"RIGHT_ADMIN"=>new right("Administrator/Entwickler","Vorsicht! ALLE Rechte. Auch instabile BETA-Features und Entwickler-Ausgaben!"),
	"RIGHT_EDIT_NICK"=>new right("Nick bearbeiten","Eigenen Nick ändern"),
);

/*
 * Modulspezifische Rechte
 */
foreach ($modules as $modul) {
	$modul_rights=$modul->get_rights();
	if($modul_rights)
	foreach ($modul_rights as $key => $right) {
		$all_rights[$key]=$right;
		$right->description.=" (Modul \"".$modul->modul_name."\")";
	}
}


/*
 * Tabelle Rechte
 */
$rights_table=array();
foreach ($all_rights as $key=>$right) {
	$name=$right->name;
	$desc=$right->description;
	$rights_table[]=array(
		"Berechtigung"=>$name." ($key)",
		"Beschreibung"=>$desc,
	);
}
$table_rights=new table($rights_table,null,false);
$page->add_html($table_rights->toHTML());


/*
 * Tabelle Benutzer-Rechte
 */
$query_users=dbio_SELECT("core_users","active=1","id,nick");
$query_user_right = dbio_SELECT("core_user_right");
$rights=array();
foreach ($query_users as $user) {
	$user_rights=array("-USER-"=>$user['nick']." (".$user['id'].")");
	foreach ($all_rights as $right_id => $dummy) {
		$user_rights[$right_id]=rights_checkbox(false);
	}
	$rights[$user['id']]=$user_rights;
}
$headers=array("-USER-"=>"Benutzer");
foreach ($all_rights as $right_id => $right_object) {
	$header=$right_object->name;
	$header=preg_replace("/ /", "&nbsp;", $header);
	$header="<span title=\"$right_id\">$header</span>";
	$headers[$right_id]=$header;
}
foreach ($query_user_right as $right) {
	$rights[$right['user']][$right['right']]=rights_checkbox(true);
}
$table = new table($rights,'core_rights',false);
$table->set_header($headers);
$page->add_html( $table->toHTML() );



$page->send();
exit;//============================================================================================
function rights_checkbox($checked){
	return html_checkbox(null,$checked,"ajax(...);");
}
?>