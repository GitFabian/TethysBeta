<?php
if(!isset($page)){echo"Seite existiert nicht.";exit;}
$page->init('demo_bsp','Charts/Diagramme');
include_once ROOT_HDD_CORE.'/core/classes/gantt.php';
include_jquery();
include_once ROOT_HDD_CORE.'/core/classes/table.php';



/*
 * Gantt-Chart
 */
$query=dbio_SELECT("demo_projekt","fortschritt<1");
$gantt=new gantt();
foreach ($query as $row) {
	$begin=strtotime($row["start"]);
	$end=strtotime($row["end"])/*Ende des Tages:*/+86400;
	$gantt->add_project(new gantt_project($row["name"], $begin, $end, $row["fortschritt"]));
}



/*
 * Projekte
 */
$query=dbio_SELECT("demo_projekt");
$data=array();
foreach ($query as $row) {
	$data[]=$row;
}
$table=new table($data);
$table->details=true;//ROOT_HTTP_MODULES."/sms1/duhshrubbery.".CFG_EXTENSION."?id=[ID:id]";
$table->set_options(true, true, true, "demo_projekt");



/*
 * Ausgabe
 */
$page->say(html_header1("Gantt-Chart"));
$page->say($gantt);

$page->say(html_header1("Projekte"));
$page->say($table);

$page->send();
exit;//============================================================================================
?>