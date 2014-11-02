<?php

include_once '../../config_start.php';

$cmd=request_value("cmd");
if ($cmd=="update_member") update_member();

echo "!Unbekanntes AJAX-Kommando \"$cmd\"!";
exit;//===========================================================================================

function update_member(){
	if (!berechtigung("RIGHT_DEMOMGMT")) ajax_exit("!Keine Berechtigung!");
	$gid=request_value("id");
	$ids=request_value("ids");
	
	$users_new=($ids?explode(",", $ids):null);
	
	$msg=dbio_UPDATE_groupMember("demo_flubtangle_user", $users_new, "flubtangle", $gid);
	
	if (!$msg) $msg[]="Keine Änderung.";
	
	ajax_exit(implode("<br>", $msg));
};
function ajax_exit($msg){
	echo $msg;exit;
}

?>