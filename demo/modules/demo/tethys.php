<?php

global $modules;
$modules[]=new modul_demo('Demo-Modul');

class modul_demo extends module{
	
	function get_menu($page_id){
		$menu=new menu($page_id,"Demo",null,"demo");
		$menu->add(new menu_topic("demo_index","Index",CFG_HTTPROOT."/modules/demo/index.".CFG_EXTENSION));
		return $menu;
	}
	
}

?>