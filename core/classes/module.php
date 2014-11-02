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
	
	function get_rights(){
		return null;
	}
	
	function get_edit_form($form,$table,$id,$query){
		return false;
	}

	function get_edit_right($table,$id){
		if (USER_ADMIN) echo("Nicht implementiert: Funktion \"".__FUNCTION__."\" in Modul \"".$this->modul_name."\"!");
		return false;
	}

	function save_data($table,$id){
		return false;
	}

	function pre_delete($table,$id){
		return true;
	}
	
	static function edit_form_field($form,$query,$key,$label=null,$type='TEXT',$options=null){
		$form->add_field(new form_field($key,$label,request_value($key,$query[$key]),$type,null,$options));
	}
}

function new_form_field($modul,$key,$label,$type){
	return new form_field($key,$label,setting_get($modul,$key),$type,"setting_get('$modul','$key')");
}

function module_read(){
	global $modules;
	
	$module_count=0;
	if(CFG_MODULES){
		$module=explode(",", CFG_MODULES);
		foreach ($module as $modul) {
			$modul=trim($modul);
			if ($modul){
				if (strcasecmp($modul, "demo")==0||strcasecmp($modul, "tethys")==0){
					$php=ROOT_HDD_CORE.'/demo/modules/'.$modul.'/tethys.php';
				}else{
					$php=ROOT_HDD_MODULES.'/'.$modul.'/tethys.php';
				}
				if (file_exists($php)){
					$last_modul=null;
					foreach ($modules as $key => $value) { $last_modul=$key; }
					include_once $php;
					$new_modul=null;
					foreach ($modules as $key => $value) { $new_modul=$key; }
					if ($new_modul==null || $new_modul==$last_modul){
						echo "Fehler beim initialisieren von Modul \"$modul\"!";
					}
					$module_count++;
				}else{
					if (USER_ADMIN) echo "Modul nicht gefunden: \"$modul\"!";
				}
			}
		}
	}

	if (!$module_count) if (USER_ADMIN) echo "Keine Module geladen!";	
}

?>