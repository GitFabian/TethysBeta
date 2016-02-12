<?php

// define('ROLLE_X','1');
// define('ROLLE_Y','2');

function hauptmenue($page_id){
	$menu=menu_get_default($page_id);
	return $menu->toHTML();
}

// function get_user_setcard($uid=USER_ID,$query){
// 	$setcard=new set_card("(TODO)");
// 	return $setcard;
// }

// function logon_message(){
// 	return "[LOGON]";
// }

// function datafolder_access($file){
// 	return false;
// }

?>