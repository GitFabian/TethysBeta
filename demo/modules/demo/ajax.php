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
	
	$query_users=dbio_SELECT_keyValueArray("demo_flubtangle_user", 'user', 'id', "flubtangle=$gid");
	$users_new=explode(",", $ids);
	
	$dazu=array_diff($users_new, $query_users);
	$hinfort=array_diff($query_users, $users_new);
	
	$msg=array();
	
	foreach ($dazu as $id) {
		dbio_INSERT("demo_flubtangle_user", array(
			"flubtangle"=>$gid,
			"user"=>$id,
		));
		$msg[]="Hinzugefügt: #$id";
	}

	foreach ($hinfort as $id) {
		dbio_DELETE("demo_flubtangle_user", "flubtangle=$gid AND user=$id");
		$msg[]="Entfernt: #$id";
	}
	
	if (!$msg) $msg[]="Keine Änderung.";
	
	ajax_exit(implode("<br>", $msg));
};
function ajax_exit($msg){
	echo $msg;exit;
}

?>