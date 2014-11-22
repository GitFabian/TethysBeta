<?php

global $modules;
$modules['myqueries']=new modul_myqueries('MyQueries');

class modul_myqueries extends module{
	
	function get_menu($page_id){
		$menu=new menu(null,"myqueries",$page_id,"MyQueries");
		new menu_topic($menu,"myqueries_index",$page_id,"Index",url_myqueries('index'));
		if(USER_ADMIN)new menu_topic($menu,"myqueries_cons",$page_id,"Connections",url_myqueries('connections'));
		return $menu;
	}
	
	function get_edit_right($table,$id){
		if ($table=='myqueries_connections'||$table=='myqueries_user_query'){
			return USER_ADMIN;
		}
		if (USER_ADMIN) echo"Kein edit_right für $table!";
		return false;
	}
	
	function format_default_for_column($table,$column){
		if ($table=="myqueries_queries") return "[name] (#[id])";
		return "[$column]";
	}
	
}

function url_myqueries($page){
	return url_mod_pg('myqueries', $page);
}

?>