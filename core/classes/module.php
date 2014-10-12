<?php

class module{
	
	var $modul_name;
	
	function __construct($modul_name){
		$this->modul_name=$modul_name;
	}
	
	function get_menu($page_id){
		#if (USER_ADMIN) echo("Nicht implementiert: Funktion \"".__FUNCTION__."\" in Modul \"".$this->modul_name."\"!");
		return null;
	}
	
	function user_setting_default($key){
		if (USER_ADMIN) echo("Nicht implementiert: Funktion \"".__FUNCTION__."\" in Modul \"".$this->modul_name."\"!");
		return null;
	}
	
}

function module_read(){
	
	$module_count=0;
	if(CFG_MODULES){
		$modules=explode(",", CFG_MODULES);
		foreach ($modules as $module) {
			$module=trim($module);
			if ($module){
				$php=CFG_HDDROOT.'/modules/'.$module.'/tethys.php';
				if (file_exists($php)){
					include_once $php;
					$module_count++;
				}else{
					if (USER_ADMIN) echo "Modul nicht gefunden: \"$module\"!";
				}
			}
		}
	}

	if (!$module_count) if (USER_ADMIN) echo "Keine Module geladen!";	
}

?>