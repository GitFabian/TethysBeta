<?php

global $modules;
$modules['demo']=new modul_demo('Demo-Modul');

class modul_demo extends module{
	
	function __construct($modul_name){
		parent::__construct($modul_name);
		$this->has_user_page=true;
	}
	
	function get_menu($page_id){
		$menu=new menu(null,"demo",$page_id,"Demo");
		new menu_topic($menu,"demo_css",$page_id,"CSS",url_demo('css'));
		#new menu_topic($menu,"demo_views",$page_id,"Views",url_demo('views'));
		new menu_topic($menu,"demo_tabelle",$page_id,"Tabellen",url_demo('css')."?view=two");
		new menu_topic($menu,"demo_formular",$page_id,"Formulare",url_demo('css')."?view=three");
		#new menu_topic($menu,"demo_",$page_id,"",url_demo(''));
		return $menu;
	}
	
	function global_settings($form){
		if ($form){
			$form->add_fields("",array(
				new_form_field('demo', "DEMOFEATURE1", "Abracadabra Bananarama", 'CHECKBOX'),
			));
		}
		return true;
	}
	
	function get_default_setting($key){
		//Global:
		if ($key=='DEMOFEATURE1') return "1";
		//User Specific:
		if ($key=='demosetting') return "Duh bleepity gobble nizzle!";
		if (USER_ADMIN) echo("Kein Default-Value für \"$key\"! /modules/demo/tethys.php:33");
		return null;
	}
	
	function get_user_page(){
		include_once ROOT_HDD_CORE.'/core/classes/form.php';
		if (request_command("update")) $this->update_settings(); 
		$form=new form("update");

		$form->add_fields("",array(
				new form_field("demosetting","Crongely zoomflip crangle",setting_get_user('demo','demosetting'),'TEXT',(USER_ADMIN?"setting_get_user('demo','demosetting')":"")),
		));
		
		return $form->toHTML();
	}
	function update_settings(){
		global $page;
		$n=0;
		$n+=$this->update_setting("demosetting");
		$page->say(html_div("--- $n Settings geändert. ---<br><br>"));
	}
	function update_setting($key){
		$value=request_value($key,null);
		$set=false;
		if ($value!==null) $set=setting_save("demo", $key, $value, true);
		return ($set?1:0);
	}

	function get_rights(){
		include_once ROOT_HDD_CORE.'/core/classes/rights.php';
		return array(
				"RIGHT_DEMOMGMT"=>new right("Demo-Administration", "Flang flub cakewhack, boo quabble roo shnuzzle."),
		);
	}
	
	function get_edit_right($table,$id){
		if ($table=='demo_lorumipsum'){
			return berechtigung('RIGHT_DEMOMGMT');
		}
		if (USER_ADMIN) echo"Kein edit_right für $table!";
		return false;
	}
	
	function save_data($table,$id){
		include_once ROOT_HDD_CORE.'/demo/modules/demo/edit_forms.php';
		return save_form($table, $id);
	}

	function get_edit_form($form,$table,$id,$query){
		include_once ROOT_HDD_CORE.'/demo/modules/demo/edit_forms.php';
		return edit_form($form, $table, $id, $query);
	}
	
	function pre_delete($table,$id){
		include_once ROOT_HDD_CORE.'/demo/modules/demo/edit_forms.php';
		return demo_pre_delete($table, $id);
	}
	
}

function url_demo($page){
	return url_mod_pg('demo', $page);
}

?>