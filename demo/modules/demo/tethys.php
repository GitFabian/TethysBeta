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
		new menu_topic($menu,"demo_index",$page_id,"Index",url_demo('index'));
		return $menu;
	}
	
	function global_settings($form){
		if ($form){
			$form->add_fields("",array(
				new_form_field('demo', "FEATURE1", "Feature 1", 'CHECKBOX'),
			));
		}
		return true;
	}
	
	function get_default_setting($key){
		//Global:
		if ($key=='FEATURE1') return "1";
		//User Specific:
		if ($key=='demosetting') return "Hello world!";
		if (USER_ADMIN) echo("Kein Default-Value für \"$key\"! /modules/demo/tethys.php:33");
		return null;
	}
	
	function get_user_page(){
		include_once CFG_HDDROOT.'/core/classes/form.php';
		if (request_command("update")) $this->update_settings(); 
		$form=new form("update");
		
		$form->add_fields("",array(
				new form_field("demosetting",null,setting_get_user('demo', 'demosetting'),'TEXT'),
		));
		
		return $form->toHTML();
	}
	function update_settings(){
		global $page;
		$n=0;
		$n+=$this->update_setting("demosetting");
		$page->add_div("--- $n Settings geändert. ---<br><br>");
	}
	function update_setting($key){
		$value=request_value($key,null);
		$set=false;
		if ($value!==null) $set=setting_save("demo", $key, $value, true);
		return ($set?1:0);
	}

}

function url_demo($page){
	return url_mod_pg('demo', $page);
}

?>