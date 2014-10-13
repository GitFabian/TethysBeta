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

function berechtigung($right){
	global $rights;
	return (isset($rights[$right]));
}

?>