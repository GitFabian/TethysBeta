<?php

global $modules;
$modules['xxxxxxxxxx']=new modul_xxxxxxxxxx('Xxxxxxxxxx');

class modul_xxxxxxxxxx extends module{
	
	function get_menu($page_id){
		$menu=new menu(null,"xxxxxxxxxx",$page_id,"Xxxxxxxxxx");
		new menu_topic($menu,"xxxxxxxxxx_xxxxxxxxxx",$page_id,"Xxxxxxxxxx",url_xxxxxxxxxx('index'));
		return $menu;
	}
	
}

function url_xxxxxxxxxx($page){
	return url_mod_pg('xxxxxxxxxx', $page);
}

?>