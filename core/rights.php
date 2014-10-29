<?php

function rights_init(){
	$rights=array();
	
	$query_rights=dbio_SELECT("core_user_right", "user=".USER_ID, "`right`");
	
	foreach ($query_rights as $right) {
		$rights[$right['right']]=true;
	}
	
	define("USER_ADMIN", isset($rights['RIGHT_ADMIN']));

	return $rights;
}

function berechtigung_or_quit($right){
	if (!berechtigung($right)){
		page_send_exit("Keine Berechtigung! (\"$right\")");
	}
}

function berechtigung($right){
	global $rights;
	return (isset($rights[$right]));
}

/**
 * Berechtigung zum Setzen des Rechts muss bereits überprüft sein!
 */
function right_set($user,$right,$state){
	if ($state){
		dbio_INSERT("core_user_right", array("user"=>$user,"right"=>$right));
	}else{
		dbio_DELETE("core_user_right", "user='$user' AND `right`='$right'");
	}
}

?>