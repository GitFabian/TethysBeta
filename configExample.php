<?php

function hauptmenue($page_id){
	$menu=menu_get_default($page_id);
	return $menu->toHTML();
}

// function get_user_setcard($uid=USER_ID){
// 	$setcard=new set_card("(TODO)");
// 	return $setcard;
// }

?>