<?php

global $modules;
$modules['demo']=new modul_demo('Demo-Modul');

class modul_demo extends module{
	
	function get_menu($page_id){
		$menu=new menu(null,"demo",$page_id,"Demo");
		new menu_topic($menu,"demo_index",$page_id,"Index",url_demo('index'));
		return $menu;
	}
	
}

function url_demo($page){
	return url_mod_pg('demo', $page);
}

?>