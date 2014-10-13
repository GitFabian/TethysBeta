<?php

class module{
	
	var $modul_name;
	var $has_user_page=false;
	
	function __construct($modul_name){
		$this->modul_name=$modul_name;
	}
	
	function get_menu($page_id){
		return null;
	}
	
	function global_settings($form){
		return false;
	}
	
	function get_default_setting($key){
		if (USER_ADMIN) echo("Nicht implementiert: Funktion \"".__FUNCTION__."\" in Modul \"".$this->modul_name."\"!");
		return null;
	}
	
	function get_user_page(){
		if (USER_ADMIN) echo("Nicht implementiert: Funktion \"".__FUNCTION__."\" in Modul \"".$this->modul_name."\"!");
		return null;
	}

}

function new_form_field($modul,$key,$label,$type){
	return new form_field($key,$label,setting_get($modul,$key),$type,"setting_get('$modul','$key')");
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