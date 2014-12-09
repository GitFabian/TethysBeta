<?php
if(!isset($page)){echo"Seite existiert nicht.";exit;}
$page->init('demo_bsp','Datenblätter');
#include_once ROOT_HDD_CORE.'/core/classes/form.php';
#include_chosen();
include_once ROOT_HDD_CORE.'/core/classes/datasheet.php';
include_once ROOT_HDD_CORE.'/core/log.php';

$id=request_value('id');
if (!$id){
	$query=dbio_SELECT("demo_lorumipsum",null,"id",null,null,true,"1");
	if (!$query) page_send_exit("Kein Datensatz vorhanden! ".html_a_button("Neuer Eintrag", ROOT_HTTP_CORE."/core/edit.".CFG_EXTENSION."?id=NEW&db=demo_lorumipsum"));
	$id=$query[0]['id'];
}

$flubtangle=dbio_SELECT_SINGLE("demo_lorumipsum", $id);
$name=$flubtangle['flubtangle'];
if (!$name)$name="Lorum Ipsum #$id";
$page->say(html_header1($name));

if ($flubtangle){
	$page->say(datasheet::from_db("demo", "demo_lorumipsum", $id));
}else{
	$page->say("(Datensatz nicht vorhanden)");
}

$page->say(logs_for_entity("demo_lorumipsum",$id));

$page->send();
exit;//============================================================================================
?>