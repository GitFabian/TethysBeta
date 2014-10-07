<?php

function rights_init(){
	$rights=array();
	
	$query_rights=dbio_SELECT("core_user_right", "user=".USER_ID,
			"r.phpname",
			array(new dbio_leftjoin("right","core_rights","r","phpname"))
			);
	
	foreach ($query_rights as $right) {
		$rights[$right['phpname']]=true;
	}
	
	define("USER_ADMIN", isset($rights['RIGHT_ADMIN']));

	return $rights;
}

function berechtigung($right){
	global $rights;
	return (isset($rights[$right]));
}

?>