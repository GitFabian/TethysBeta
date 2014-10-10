<?php

function login(){
	global $user;
	
	define('USER_ID', 1);
	define('USER_NICK', 'Fabian');
	
	$user=dbio_SELECT_SINGLE("core_users", USER_ID);
}

?>