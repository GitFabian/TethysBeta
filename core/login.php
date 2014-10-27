<?php

function login(){
	global $user,$page;
	
	if (CFG_LOGON_TYPE=='none'){
		
		define('USER_ID', LOGON_NONE_DEF_USER);

	}else if (CFG_LOGON_TYPE=='http'){
		if (isset($_SERVER['REMOTE_USER'])){
			$http_auth=$_SERVER['REMOTE_USER'];

			$user=dbio_SELECT_SINGLE("core_users", $http_auth, "http_auth");
			
			if ($user){
				define('USER_ID', $user['id']);
			}else{
				define('USER_ID', 0);
				$page->say("---Benutzer \"$http_auth\" nicht gefunden!---");
			}
			
		}else{
			echo("Fehlerhafte Server-Konfiguration! HTTP-Auth übermittelte keinen Benutzer (\$_SERVER['REMOTE_USER']).");
			exit;
		}
		
	}else if (CFG_LOGON_TYPE=='cookie'){
		
		define('USER_ID', 0);
		
	}else{
		
		echo("Unbekannter Logon-Type!");
		exit;
		
	}

	if (!$user)
	if (USER_ID){
		$user=dbio_SELECT_SINGLE("core_users", USER_ID);
	}else{
		$user=array(
				"nick"=>"Gast",
		);
	}

	define('USER_NICK', $user['nick']);
	
}

function login_form(){
	global $page;
	$page->say("LOGIN-FORMULAR");
	$page->send();
	exit;
}

?>