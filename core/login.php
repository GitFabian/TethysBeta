<?php

function login(){
	global $user,$page,$login_candidates;
	
	$logoff=request_command("logoff");
	$login=request_command("login");

	if ($login){

		$fehler=false;
		
		if (CFG_LOGON_TYPE=='cookie' || CFG_LOGON_COOKIE){
			$id=request_value("id");
			$name=request_value("name");
			$pass=request_value("pass");
			
			if ($id){
					
				$users=dbio_SELECT_SINGLE("core_users",$id);
				if ($users&&$users['active']
						//Logon Override: Passwort erforderlich:
						&&(!CFG_LOGON_COOKIE||$users['password'])
						){
			
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
			
				$users=dbio_SELECT("core_users","vorname='".sqlEscape($name)."' AND active");
					
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
				}else{
					$fehler=true;
				}
			
			}

			if (CFG_LOGON_COOKIE && $login_candidates){
				$_REQUEST['cmd']="logon";
			}

		}else{
			
			$page->message_error("Anmeldung nicht möglich!");
			
		}
		
		if($fehler){
			$page->message_info("Anmeldung fehlgeschlagen. Bitte Benutzername und Passwort überprüfen.");
		}

	}

	$LOGON_COOKIE_OVERR=false;
	if (CFG_LOGON_TYPE=='cookie' || CFG_LOGON_COOKIE){
		$cookie=login_getCookie();
		
		if ($cookie){
			$trenner=strpos($cookie, ':');
			if ($trenner){
				$id=substr($cookie, 0, $trenner);
				$value=substr($cookie, $trenner+1);
				$jetzt=time();
				$query_cookies=dbio_SELECT("core_logons","user='".sqlEscape($id)."' AND cookie='".sqlEscape($value)."' AND expires>$jetzt");
				if ($query_cookies){
					$LOGON_COOKIE_OVERR=true;
					$cookie_id=$query_cookies[0]['id'];
					if ($logoff){
						dbio_DELETE("core_logons", "id=$cookie_id");
						if(!CFG_LOGON_COOKIE){define('USER_ID', 0);}
					}else{
						define('USER_ID', $id);
						login_refreshCookie($cookie_id);
					}
				}
			}
		}
	}
	define("LOGON_COOKIE_OVERR", $LOGON_COOKIE_OVERR);
	if (defined('USER_ID')){
		//(Cookie-Login)
	}else if (CFG_LOGON_TYPE=='cookie'){
	
		define('USER_ID', 0);
	
	}else if (CFG_LOGON_TYPE=='http'){
		if (isset($_SERVER['REMOTE_USER'])){
			$http_auth=$_SERVER['REMOTE_USER'];

			$user=dbio_query_to_array("SELECT * FROM `core_users` WHERE `http_auth` COLLATE utf8_general_ci = '$http_auth' AND active");
			if ($user) $user=$user[0];
			
			if ($user){
				define('USER_ID', $user['id']);
			}else{
				define('USER_ID', 0);
				$page->message_error("Benutzer \"$http_auth\" nicht gefunden!");
			}
			
		}else{
			echo("Fehlerhafte Server-Konfiguration! HTTP-Auth übermittelte keinen Benutzer (\$_SERVER['REMOTE_USER']).");
			exit;
		}
		
	}else if (CFG_LOGON_TYPE=='none'){
		
		define('USER_ID', LOGON_NONE_DEF_USER);

	}else{
		
		echo("Unbekannter Logon-Type!");
		exit;
		
	}
	
	if ($logoff){
		if(CFG_LOGON_COOKIE)$_REQUEST['cmd']="logoff";
		if (($user || USER_ID && !$LOGON_COOKIE_OVERR) ){
			$page->message_error("Abmeldung nicht möglich!");
		}else{
			include_once ROOT_HDD_CORE.'/core/alertify.php';
			$page->onload_JS.=alertify_success("Auf Wiedersehen!");
		}
	}
	
	if ($login && USER_ID &&!$login_candidates){
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
// 	$page->say($form);
	$msg=preg_replace("/\\[LOGON\\]/", $form->toHTML(), setting_get(null, 'LOGON_MSG'));
	$page->say("<div class=\"logon_msg\">$msg</div>");
	
	$page->init("logon", "Login");
	$page->send();
	exit;
}

?>