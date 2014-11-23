<?php

/*
include_once ROOT_HDD_CORE.'/core/classes/user.php';
 */

function get_user_setcard_CORE($uid=USER_ID){
	include_once ROOT_HDD_CORE.'/core/classes/set.php';
	if (function_exists('get_user_setcard')) return get_user_setcard($uid);
	
	$user=dbio_SELECT_SINGLE("core_users", $uid);
	$infotext=$user["vorname"]." ".$user["nachname"];
	$picture=get_user_picture_url($uid);
	$edit=(USER_ADMIN?"db=core_users&id=$uid":"");
	$setcard=new set_card($user["nick"],$infotext,$picture,$edit,ROOT_HTTP_CORE."/core/user_detail.".CFG_EXTENSION."?id=$uid");
	$setcard->add_data(new set_card_data("http_auth", "HTTP-Auth", $user['http_auth']));
	$setcard->add_data(new set_card_data("durchwahl", "Durchwahl", $user['durchwahl']));
	$setcard->add_data(new set_card_data("handy", "Handy", $user['handy']));
	$setcard->add_data(new set_card_data("raum", "Raum", $user['raum']));
	
	return $setcard; 
}

function get_user_picture_url($uid=USER_ID){
	return(file_exists(ROOT_HDD_DATA."/core_users/person$uid.png")?
			ROOT_HTTP_DATA."/core_users/person$uid.png"
			:CFG_SKINPATH."/img/nopic.png");
}

?>