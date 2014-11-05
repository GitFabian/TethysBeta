<?php

include_once '../config_start.php';

$cmd=request_value("cmd");
if ($cmd=="update_rights") update_rights();
if ($cmd=="lorumipsum") lorumipsum();

echo "!Unbekanntes AJAX-Kommando \"$cmd\"!";
exit;//===========================================================================================

function lorumipsum(){
	$length=request_value("length");
	$content = file_get_contents("http://loripsum.net/api/1/$length/plaintext");
	//Ersten Satz abschneiden:
	$content=substr($content, strpos($content, ".")+1);
	$content=trim($content);
	ajax_exit($content);
}
function update_rights(){
	if (!USER_ADMIN) ajax_exit("!Keine Berechtigung!");
	include_once ROOT_HDD_CORE.'/core/admin/rights_.php';
	$id=request_value("id");
	$right=request_value("right");
	$state=request_value("state");
	$modiCtrl=(request_value("modiCtrl")=="true");
	if ($id==USER_ID&&$right=='RIGHT_ADMIN') ajax_exit("!Keine gute Idee!");
	
	right_set($id, $right, $state);
	
	$all_rights=all_rights();
	$recht=$all_rights[$right]->name;
	$user=dbio_SELECT_SINGLE("core_users", $id);
	$name=$user['vorname']." ".$user['nachname'];
	if (!$state) $recht="<span class=\"strike\">$recht</span>";
	echo"$name: $recht";exit;
}
function ajax_exit($msg){
	echo $msg;exit;
}

?>