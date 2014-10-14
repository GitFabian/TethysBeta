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
$query_user_right = dbio_SELECT("core_user_right");
$rights=array();
foreach ($query_user_right as $right) {
	$user=$right['user'];
	$berechtigung=$right['right'];
	if (!isset($rights[$user])) $rights[$user]=array("Benutzer"=>$user);
	$header=$all_rights[$berechtigung]->name;
	$header=preg_replace("/ /", "&nbsp;", $header);
	$header="<span title=\"$berechtigung\">$header</span>";
	$rights[$user][$header]="true";
}
$table = new table($rights,'core_rights',false);
$page->add_html( $table->toHTML() );



$page->send();
exit;//============================================================================================
?>