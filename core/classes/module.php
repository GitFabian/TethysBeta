<?php

class module{
	
	var $modul_name;
	
	function __construct($modul_name){
		$this->modul_name=$modul_name;
	}
	
	function get_menu($page_id){
		if (USER_ADMIN) echo("Nicht implementiert: Funktion \"".__FUNCTION__."\" in Modul \"".$this->modul_name."\"!");
		return null;
	}
	
}

function module_read(){
	
	include_once CFG_HDDROOT.'/modules/demo/tethys.php';
}

?>