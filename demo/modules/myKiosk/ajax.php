<?php

include_once '../../config_start.php';

$cmd=request_value("cmd");
#if ($cmd=="xxxxxxxxxx") xxxxxxxxxx();

echo "!Unbekanntes AJAX-Kommando \"$cmd\"!";
exit;//===========================================================================================

function ajax_exit($msg){
	echo $msg;exit;
}

?>