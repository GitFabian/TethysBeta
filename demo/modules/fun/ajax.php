<?php

include_once '../../config_start.php';

$cmd=request_value("cmd");
if ($cmd=="sprichwortgenerator") sprichwortgenerator();
if ($cmd=="spw_q") spw_q();

echo "!Unbekanntes AJAX-Kommando \"$cmd\"!";
exit;//===========================================================================================

function spw_q(){
	$q=request_value("q");
	$id=request_value("id");
	if(USER_ADMIN)dbio_UPDATE("fun_logs_spw", "nr=$id", array("q"=>$q));
	ajax_exit("#$id: $q");
}
function sprichwortgenerator(){
	include_once ROOT_HDD_CORE.'/demo/modules/fun/fun.php';
	ajax_exit($content=fun_sprichwortgenerator(false));
}
function ajax_exit($msg){
	echo $msg;exit;
}

?>