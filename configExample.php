<?php

function hauptmenue($page_id){
	$menu=menu_get_default($page_id);
	return $menu->toHTML();
}

?>