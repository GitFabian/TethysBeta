<?php
include_once '../../config_start.php';

$page->init('core_user','Benutzereinstellungen');

include_once ROOT_HDD_CORE.'/core/classes/form.php';

if(!USER_ID)page_send_exit("Bitte anmelden!");

/*
 * Module => Views
 */
$views=array(new menu_topic2("core", CFG_TITLE));
foreach ($modules as $mod_id=>$modul){
	if ($modul->has_user_page){
		$views[]=new menu_topic2($mod_id, $modul->modul_name);
	}
}
$view=$page->init_views(setting_get_user(null,'SET_PGSEL_USERSETS'),$views);
setting_save(null, 'SET_PGSEL_USERSETS', $view, true);

if ($view=="core"){
	if (request_command("updated")) $page->add_html("UPDATED!");
	if (request_command("update")) core_user_update();
	$form=new form("update");
	
	/*
	 * Persönliche Daten
	 */
	$persoenlich=array();
	if (setting_get(null,'CFG_EDIT_NICK')){
		$persoenlich[]=new form_field("nick","Nutzername",USER_NICK);
	}
	if (setting_get(null,'CFG_EDIT_FILE')){
		#$persoenlich[]=new form_field("picture","Foto",$user['picture']);
		$persoenlich[]=new form_field("picturee","Foto",null,"FILE");
// 		$persoenlich[]=new form_field("durchwahl","Durchwahl",$user['durchwahl']);
// 		$persoenlich[]=new form_field("handy","Handy",$user['handy']);
// 		$persoenlich[]=new form_field("raum","Raum",$user['raum']);
	}
	if ($persoenlich) $form->add_fields("Persönliche Daten", $persoenlich);
	
	$form->add_fields("", array(
		new form_field("MSCSV","CSV für MS",setting_get_user(null, "MSCSV"),"CHECKBOX"),
	));
	
	if(setting_get(null, "CFG_UPROF_CMPCTVIEW")){
		$form->add_field(
			new form_field("CMPCTVIEW","Compact View",setting_get_user(null, "CMPCTVIEW"),"CHECKBOX")
		);
	}

	if(USER_ADMIN)$form->add_field(new form_field("PRESENTATIONMODE","Präsentationsmodus",setting_get_user(null, "PRESENTATIONMODE"),"CHECKBOX"));
	if(USER_ADMIN)$form->add_field(new form_field("DEBUGMODE","Debug-Modus",setting_get_user(null, "DEBUGMODE"),"CHECKBOX"));
	
	/*
	 * Widgets
	 */
	if(setting_get(null, "CFG_EDITWIDGETS")){
		$widgets=array();
		$widgets[]=new form_field_info("xyz", "", html_a_button("Alle Positionen zurücksetzen", "?resetallwidgetpositions"));
		$wid_sets=array_val2key(explode(",", setting_get_user(null, "WIDGETS")));
		foreach ($modules as $mod_id=>$modul) {
			$widg=$modul->get_widgets();
			foreach ($widg as $widget) {
				$widgets[]=new form_field("widget_".$mod_id."_".$widget->name_id,$widget->name_full,
						isset($wid_sets[$mod_id."_".$widget->name_id])?"1":"0"
						,"CHECKBOX");
			}
		}
		if($widgets)$form->add_fields("Widgets", $widgets);
		#$form->add_field(new form_field("","SETTING",setting_get_user(null, "WIDGETS")));
	}
	if(isset($_REQUEST["resetallwidgetpositions"])){
		dbio_DELETE("core_widgetpos", "user=".USER_ID);
		$page->message_ok("Alle Widget-Positionen zurückgesetzt.");
	}
		
	if ($form->field_groups)
		$page->add_html($form->toHTML());
}else if(isset($modules[$view])){
	$page->add_html($modules[$view]->get_user_page());
}

$page->send();
exit;//============================================================================================
function core_user_update(){
	global $modules;
	$changes=array();
	
	if (setting_get(null,'CFG_EDIT_NICK')&&request_value("nick")){
		dbio_UPDATE("core_users", "id=".USER_ID, array("nick"=>request_value("nick")));
	}
	if (setting_get(null,'CFG_EDIT_FILE')){
		$uploaded=getUpload("picturee", "core_users/person".USER_ID.".jpg", true);
		if($uploaded){ $changes["picture"]=$uploaded; }
	}
	foreach (array(
			"MSCSV","CMPCTVIEW","PRESENTATIONMODE","DEBUGMODE"
			) as $par) {
		$new=request_value($par);
		if($new!=(setting_get_user(null, $par)?"on":null))$changes[$par]=$new;
		setting_save(null, $par, $new, true);
	}
	
	/*
	 * Widgets
	 */
	if(setting_get(null, "CFG_EDITWIDGETS")){
		$wids_set=array();
		foreach ($modules as $mod_id=>$modul) {
			$widg=$modul->get_widgets();
			foreach ($widg as $widget) {
				if(request_value("widget_".$mod_id."_".$widget->name_id)) $wids_set[]=$mod_id."_".$widget->name_id;
			}
		}
		$string=implode(",", $wids_set);
		if($string!=setting_get_user(null, "WIDGETS")){
			$alt=array_val2key(explode(",", setting_get_user(null, "WIDGETS")));
			setting_save(null, "WIDGETS", $string, true);
			$neu=array_val2key($wids_set);
			$dazu=array();
			foreach ($neu as $w=>$dummy) {
				if (isset($alt[$w])) unset($alt[$w]); else $dazu[]=$w; 
			}
			if($alt)$changes["WIDGETS_AUS"]=$alt;
			if($dazu)$changes["WIDGETS_EIN"]=$dazu;
		}
	}
	
	if($changes){
		include_once ROOT_HDD_CORE.'/core/log.php';
		log_db_edit("CORE", "users", USER_ID, json_encode($changes));
		ajax_refresh("Speichere Daten...", "user.".CFG_EXTENSION."?cmd=updated");
	}
	
// 	global $page;
// 	$page->message_info("Keine Änderung.");
}
?>