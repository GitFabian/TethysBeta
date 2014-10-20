<?php

function login(){
	global $user;
	
	define('USER_ID', 1);
	
	$user=dbio_SELECT_SINGLE("core_users", USER_ID);

	define('USER_NICK', $user['nick']);
}

?>