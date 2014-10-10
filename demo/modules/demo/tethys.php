<?php

global $modules;
$modules['demo']=new modul_demo('Demo-Modul');

class modul_demo extends module{
	
	function get_menu($page_id){
		$menu=new menu(null,"demo",$page_id,"Demo");
		new menu_topic($menu,"demo_index",$page_id,"Index",url_demo('index'));
		return $menu;
	}
	
	function get_features(){
		$query_features=dbio_SELECT_keyValueArray("demo_features", "value", "ID");
		return array(
			"FEATURE1"=>new feature($query_features['FEATURE1'], "Feature 1"),
		);
	}
	
	function set_feature($feature,$value){
		if (!USER_ADMIN) return false;
		dbio_UPDATE("demo_features", "ID='$feature'", array("value"=>$value));
		return true;
	}
	
}

function url_demo($page){
	return url_mod_pg('demo', $page);
}

?>