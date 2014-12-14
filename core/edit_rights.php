<?php

/*
include_once ROOT_HDD_CORE.'/core/edit_rights.php'; 
 */

function edit_rights_core($db,$id){
	if ($db=="core_users"){
		if ($id=="NEW"){
			$max=setting_get(null, 'CFG_MAX_USERS');
			if ($max){
				$count=dbio_SELECT("core_users");
				$count=count($count);
				if ($max<=$count){
					#global $page;
					#$page->say("--- Maximale Anzahl an Benutzern erreicht! ---");
					return false;
				}
			}
		}else if(!USER_ADMIN&&$id){
			$query_exclude=dbio_SELECT("core_user_right","`user`=$id AND `right`='RIGHT_ADMIN'");
			if($query_exclude)return false;
		}
		return berechtigung('RIGHT_USERMGMT');
	}
	if ($db=="core_rollen"){
		return USER_ADMIN;
	}
	if ($db=="core_user_rolle"){
		return berechtigung('RIGHT_USERMGMT');
	}
	if (USER_ADMIN) echo"Kein edit_rights für $db!";
	return false;
}

function edit_rights2($db,$id){
	global $modules;
	$modul=substr($db, 0, strpos($db, "_"));
	if ($modul!='core'&&!isset($modules[$modul])){
		if(USER_ADMIN){echo "Modul \"$modul\" nicht gefunden! edit_rights.php:31";return false;}
	}
	return edit_rights($modul, $db, $id);
}

function edit_rights($modul,$db,$id){
	if ($modul=='core'){
		return edit_rights_core($db,$id);
	}else{
		global $modules;
		return $modules[$modul]->get_edit_right($db,$id);
	}
	return false;
	
}

?>