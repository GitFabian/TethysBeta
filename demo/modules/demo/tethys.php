<?php

global $modules;
$modules[]=new modul_demo('Demo-Modul');

class modul_demo extends module{
	
	function get_menu($page_id){
		$menu=new menu(null,"demo",$page_id,"Demo");
		new menu_topic($menu,"demo_index",$page_id,"Index",CFG_HTTPROOT."/modules/demo/index.".CFG_EXTENSION);
		return $menu;
	}
	
}

?>