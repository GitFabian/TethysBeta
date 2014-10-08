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
	
	$modules=explode(",", CFG_MODULES);

	foreach ($modules as $module) {
		$php=CFG_HDDROOT.'/modules/'.$module.'/tethys.php';
		if (file_exists($php)){
			include_once $php;
		}else{
			if (USER_ADMIN) echo "Modul nicht gefunden: \"$module\"!";
		}
	}
	
}

?>