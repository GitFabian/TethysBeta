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
	
	function get_set_card($name,$data){
		return set_card::get_default($name,$data);
	}
	
	function export_csv($table, $identifier){
		if (USER_ADMIN) echo("Nicht implementiert: Funktion \"".__FUNCTION__."\" in Modul \"".$this->modul_name."\"!");
		return false;
	}
	
	static function edit_form_field($form,$query,$key,$label=null,$type='TEXT',$options=null,$maxlength=null){
		$form->add_field($ff=new form_field($key,$label,request_value($key,(isset($query[$key])?$query[$key]:"")),$type,null,$options));
		if($maxlength!==null)$ff->maxlength=$maxlength;
		return $ff;
	}
	
	function format_default_for_column($table,$column){
		return "[$column]";
	}
	
	function send_chronjob($command,$value){
		if (USER_ADMIN) echo("Nicht implementiert: Funktion \"".__FUNCTION__."\" in Modul \"".$this->modul_name."\"!");
		return null;
	}
	
	function get_log_entry($action,$table,$id,$pars){
		$link_html=html_a("#".$id, ROOT_HTTP_CORE."/core/view.".CFG_EXTENSION."?db=".$table."&id=".$id);
		$message=htmlentities(string_kuerzen($pars,500),null,"UTF-8");
// 		if($table=="kunden_firma"){
// 			$query=dbio_SELECT_SINGLE("kunden_firma", $id);
// 			if(!$query)return new log_entry( "<strike>".$link_html."</strike>", $message );
// 			$link_html=html_a(trim($query["name"])?:"???", url_kunden("firma0")."?id=$id");
// 		}
		return new log_entry( $link_html, $message );
	}

	function get_widgets(){
		#include_once ROOT_HDD_MODULES.'/xxxxxxxxxxxx/widgets.php';
		return array(
			#new widget_xxxxxxxxxxxx_widget1(),
		);
	}

}

function new_form_field($modul,$key,$label,$type,$options=null){
	return new form_field($key,$label,setting_get($modul,$key),$type,"setting_get('$modul','$key')",$options);
}

function module_read(){
	global $modules;
	
	$module_count=0;
	if(CFG_MODULES){
		$module=explode(",", CFG_MODULES);
		foreach ($module as $modul) {
			$modul=trim($modul);
			if ($modul){
				if (strcasecmp($modul, "demo")==0||strcasecmp($modul, "fun")==0||strcasecmp($modul, "myqueries")==0){
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