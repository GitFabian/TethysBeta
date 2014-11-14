<?php

global $modules;
$modules['fun']=new modul_fun('Fun');

class modul_fun extends module{
	
	function get_menu($page_id){
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

function url_fun($page){
	return url_mod_pg('fun', $page);
}

?>