<?php

include_once '../../config_start.php';

$cmd=request_value("cmd");
if ($cmd=="view") view();
if ($cmd=="save") save();

echo "!Unbekanntes AJAX-Kommando \"$cmd\"!";
exit;//===========================================================================================

function save(){
	$con=request_value("con");
	if(!$con&&!USER_ADMIN)ajax_exit("Keine Berechtigung!");
	$query_admin=dbio_SELECT("myqueries_admins","user=".USER_ID." AND con=$con");
	if(!$query_admin&&!USER_ADMIN)ajax_exit("Keine Berechtigung für diese Verbindung!");
	$query=request_value("query");
	if (!$query) ajax_exit("Kein Query angegeben!");
	$name=request_value("name");
	if (!$name) ajax_exit("Kein Name angegeben!");
	dbio_INSERT("myqueries_queries", array(
		"connection"=>(request_value("con")?:null),
		"name"=>$name,
		"beschreibung"=>request_value("desc"),
		"query"=>$query,
	));
	if (!USER_ADMIN){
		dbio_INSERT("myqueries_user_query", array("user"=>USER_ID,"query"=>mysql_insert_id()));
	}
	ajax_exit("Query \"$name\" gespeichert.");
}
function view(){
	$con=request_value("con");
	if(!$con&&!USER_ADMIN)ajax_exit("Keine Berechtigung!");
	$query_admin=dbio_SELECT("myqueries_admins","user=".USER_ID." AND con=$con");
	if(!$query_admin&&!USER_ADMIN)ajax_exit("Keine Berechtigung für diese Verbindung!");
	include_once ROOT_HDD_CORE.'/core/classes/table.php';
	include_once ROOT_HDD_CORE.'/demo/modules/myqueries/index_.php';
	$query=request_value("query");
	$data=dbio_query_to_array($query,get_connection($con));
	$data=array_htmlentities_pre($data);
	$table=new table($data);
	$html=$table->toHTML();
	ajax_exit($html);
}
function ajax_exit($msg){
	echo $msg;exit;
}

?>