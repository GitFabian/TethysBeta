<?php

include_once '../../config_start.php';

$cmd=request_value("cmd");
if ($cmd=="view") view();
if ($cmd=="save") save();

echo "!Unbekanntes AJAX-Kommando \"$cmd\"!";
exit;//===========================================================================================

function save(){
	if(!USER_ADMIN)ajax_exit("Keine Berechtigung!");
	$name=request_value("name");
	$query=request_value("query");
	if (!$query) ajax_exit("Kein Query angegeben!");
	if (!$name) ajax_exit("Kein Name angegeben!");
	dbio_INSERT("myqueries_queries", array(
		"name"=>$name,
		"beschreibung"=>request_value("desc"),
		"query"=>$query,
	));
	ajax_exit("Query \"$name\" gespeichert.");
}
function view(){
	if(!USER_ADMIN)ajax_exit("Keine Berechtigung!");
	#sleep(1);
	include_once ROOT_HDD_CORE.'/core/classes/table.php';
	$query=request_value("query");
	$data=dbio_query_to_array($query);
	$table=new table($data);
	$html=$table->toHTML();
	ajax_exit($html);
}
function ajax_exit($msg){
	echo $msg;exit;
}

?>