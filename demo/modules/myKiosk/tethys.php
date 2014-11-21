<?php

global $modules;
$modules['myKiosk']=new modul_myKiosk('my kiosk');

class modul_myKiosk extends module{

	function __construct($modul_name){
		parent::__construct($modul_name);
		#$this->has_user_page=true;
	}
	
	function get_menu($page_id){
		$menu=new menu(null,"myKiosk",$page_id,"my kiosk");
		new menu_topic($menu,"myKiosk_start",$page_id,"Start",url_myKiosk('start'));
		return $menu;
	}
	
	function get_user_page(){
		if (USER_ADMIN) echo("Nicht implementiert: Funktion \"".__FUNCTION__."\" in Modul \"".$this->modul_name."\"!");
		//Rückgabewert: HTML-String
		return null;
	}
	
	function export_csv($table, $identifier){
		if (USER_ADMIN) echo("Nicht implementiert: Funktion \"".__FUNCTION__."\" in Modul \"".$this->modul_name."\"!");
		#if ($table=='demo_lorumipsum'){ csv_out(dbio_SELECT($table),"$table.csv"); }
		if (USER_ADMIN) echo("Kein CSV-Export für Tabelle: ".$table);
		return false;
	}

	function get_edit_right($table,$id){
		if (USER_ADMIN) echo("Nicht implementiert: Funktion \"".__FUNCTION__."\" in Modul \"".$this->modul_name."\"!");
		#if ($table=='demo_lorumipsum'){return berechtigung('RIGHT_DEMOMGMT');}
		if (USER_ADMIN) echo"Kein edit_right für $table!";
		return false;
	}
	
	function get_rights(){
		return null;
		include_once ROOT_HDD_CORE.'/core/classes/rights.php';
		return array(
			'RIGHT_DEMOMGMT'=>new right("Demo-Administration", "Flang flub cakewhack, boo quabble roo shnuzzle."),
		);
	}

	function get_edit_form($form,$table,$id,$query){
		return false;
		if ($table=='demo_lorumipsum'){
			module::edit_form_field($form,$query,'flubtangle',"Flubtangle",'TEXT');
			module::edit_form_field($form,$query,'abracadabra',"Abracadabra",'TEXT');
			return true;
		}
		if (USER_ADMIN) echo"Kein edit_form für $table!";
		return false;
	}
	
}

function url_myKiosk($page){
	return url_mod_pg('myKiosk', $page);
}

?>