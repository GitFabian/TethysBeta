<?php

global $modules;
$modules['myqueries']=new modul_myqueries('MyQueries');

class modul_myqueries extends module{
	
	function get_menu($page_id){
		$menu=new menu(null,"myqueries",$page_id,"MyQueries");
		new menu_topic($menu,"myqueries_index",$page_id,"Index",url_myqueries('index'));
		return $menu;
	}
	
}

function url_myqueries($page){
	return url_mod_pg('myqueries', $page);
}

?>