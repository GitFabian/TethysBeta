<?php

include_once '../../config_start.php';

$cmd=request_value("cmd");
if ($cmd=="view") view();

echo "!Unbekanntes AJAX-Kommando \"$cmd\"!";
exit;//===========================================================================================

function view(){
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