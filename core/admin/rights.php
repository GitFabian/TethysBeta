<?php
include_once '../start.php';

$page->init('core_rights','Rechte');

include_once CFG_HDDROOT.'/core/classes/table.php';

/*
 * Beschreibung aller Rechte
 */
$all_rights=array(
	"RIGHT_ADMIN"=>new right("Administrator/Entwickler","Vorsicht! ALLE Rechte. Auch instabile BETA-Features und Entwickler-Ausgaben!"),
);


/*
 * Tabelle Rechte
 */

$query_rights=dbio_SELECT("core_rights");

$rights_table=array();
foreach ($query_rights as $right) {
	$phpname=$right['phpname'];
	$name="";
	$desc="";
	if (isset($all_rights[$phpname])){
		$name=$all_rights[$phpname]->name;
		$desc=$all_rights[$phpname]->description;
	}
	$rights_table[]=array(
		"Berechtigung"=>$name." ($phpname)",
		"Beschreibung"=>$desc,
	);
}

$table_rights=new table();
$table_rights->add_query($rights_table);
$page->add_html($table_rights->toHTML());


/*
 * Tabelle Benutzer-Rechte
 */
$query = dbio_SELECT("core_user_right");
$table = new table();
$table->add_query($query);
$table->set_header(array(
		"user" => "Benutzer",
		"right" => "Berechtigung",
));
$page->add_html( $table->toHTML() );



$page->send();
exit;//============================================================================================
class right{
	var $name;
	var $description;
	function __construct($name,$beschreibung){
		$this->name=$name;
		$this->description=$beschreibung;
	}
}
?>