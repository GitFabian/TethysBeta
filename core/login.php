<?php

function login(){
	global $user,$page,$login_candidates;
	
	$logoff=request_command("logoff");
	$login=request_command("login");

	if ($login){

		$fehler=false;
		
		if (CFG_LOGON_TYPE=='cookie'){
			$id=request_value("id");
			$name=request_value("name");
			$pass=request_value("pass");
			
			if ($id){
					
				$users=dbio_SELECT_SINGLE("core_users",$id);
				if ($users){
			
					if ($pass==$users['password']){
						login_setCookie($id);
					}else{
						$fehler=true;
						$login_candidates=array($id=>login_user_line($users));
					}
			
				}else{
					$fehler=true;
				}
					
			}else if (!$name){
				$fehler=true;
			}else{
			
				$users=dbio_SELECT("core_users","vorname='".sqlEscape($name)."'");
					
				if ($users){
			
					if (count($users)==1){
						
						if ($pass==$users[0]['password']){
							login_setCookie($users[0]['id']);
						}else{
							$fehler=true;
						}

					}else{
						$login_candidates=array();
						foreach ($users as $row) {
							$login_candidates[$row['id']]=login_user_line($row);
						}
					}
				}
			
			}

		}else{
			
			$page->say("--- Anmeldung nicht möglich! ---<br><br>");
			
		}
		
		if($fehler)$page->say("---Anmeldung fehlgeschlagen. Bitte Benutzername und Passwort überprüfen.---<br><br>");
		
	}
	
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
		
		$cookie=login_getCookie();
		
		if ($cookie){
			$trenner=strpos($cookie, ':');
			if ($trenner){
				$id=substr($cookie, 0, $trenner);
				$value=substr($cookie, $trenner+1);
				$jetzt=time();
				$query_cookies=dbio_SELECT("core_logons","user='".sqlEscape($id)."' AND cookie='".sqlEscape($value)."' AND expires>$jetzt");
				if ($query_cookies){
					$cookie_id=$query_cookies[0]['id'];
					if ($logoff){
						dbio_DELETE("core_logons", "id=$cookie_id");
						define('USER_ID', 0);
					}else{
						define('USER_ID', $id);
						login_refreshCookie($cookie_id);
					}
				}
			}
		}
		
		if (!defined('USER_ID')){
			$logoff=false;
			define('USER_ID', 0);
		}
		
	}else{
		
		echo("Unbekannter Logon-Type!");
		exit;
		
	}
	
	if ($logoff){
		if ($user || USER_ID){
			$page->say("--- Abmeldung nicht möglich! ---<br><br>");
		}else{
			include_once ROOT_HDD_CORE.'/core/alertify.php';
			$page->onload_JS.=alertify_success("Auf Wiedersehen!");
		}
	}
	
	if ($login && USER_ID){
		include_once ROOT_HDD_CORE.'/core/alertify.php';
		$page->onload_JS.=alertify_success("Willkommen!");
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

function login_setCookie($id){
	$cookie=string_random(20,"GERKEYS");
	$_COOKIE["TethysLogin"]="$id:$cookie";
	dbio_INSERT("core_logons", array(
		"user"=>$id,
		"cookie"=>$cookie,
		"expires"=>(time()+60),
	));
	//Datenbank aufräumen:
	dbio_DELETE("core_logons", "expires<".time());
}
function login_refreshCookie($id){
	$expires=time()+60*60*24*7;
	dbio_UPDATE("core_logons", "id='$id'", array( "expires"=>$expires, ));
	setcookie("TethysLogin",$_COOKIE["TethysLogin"],$expires,'/');
}
function login_getCookie(){
	if (isset($_COOKIE["TethysLogin"])) return $_COOKIE["TethysLogin"];
	return false;
}

function login_user_line($row){
	#return $row['nick']." (".$row['vorname'].' '.$row['nachname'].", #".$row['id'].")";
	return $row['vorname'].' '.$row['nachname']." (#".$row['id'].")";
}

function login_form(){
	global $page,$login_candidates;
	include_once ROOT_HDD_CORE.'/core/classes/form.php';
	include_jquery();
	
	$form=new form("login",null,"Anmelden","logon");
	if ($login_candidates){
		$form->add_field(new form_field("id","Name","[REQ]","SELECT",null,$login_candidates,"id_focus"));
	}else{
		$form->add_hidden("id", request_value("id"));
		$form->add_field(new form_field("name","Name","[REQ]",'TEXT',null,null,"id_focus"));
	}
	$page->focus="#id_focus";
	$form->add_field(new form_field("pass","Passwort","[REQ]",'PASSWORD'));
	$page->say($form);
	$page->say(setting_get(null, 'LOGON_MSG'));
	
	$page->send();
	exit;
}

?>