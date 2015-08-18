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
		new form_field("mscsv","CSV für MS",setting_get_user(null, "MSCSV"),"CHECKBOX"),
	));
	
	if(setting_get(null, "CFG_UPROF_CMPCTVIEW")){
		$form->add_field(
			new form_field("CMPCTVIEW","Compact View",setting_get_user(null, "CMPCTVIEW"),"CHECKBOX")
		);
	}

	if(USER_ADMIN)$form->add_field(new form_field("PRESENTATIONMODE","Präsentationsmodus",setting_get_user(null, "PRESENTATIONMODE"),"CHECKBOX"));
	if(USER_ADMIN)$form->add_field(new form_field("DEBUGMODE","Debug-Modus",setting_get_user(null, "DEBUGMODE"),"CHECKBOX"));
	
	/*
	 * Module
	 */
	$widgets=array();
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
	
	if ($form->field_groups)
		$page->add_html($form->toHTML());
}else if(isset($modules[$view])){
	$page->add_html($modules[$view]->get_user_page());
}

$page->send();
exit;//============================================================================================
function core_user_update(){
	global $modules;
	if (setting_get(null,'CFG_EDIT_NICK')&&request_value("nick")){
		dbio_UPDATE("core_users", "id=".USER_ID, array("nick"=>request_value("nick")));
	}
	if (setting_get(null,'CFG_EDIT_FILE')){
		getUpload("picturee", "core_users/person".USER_ID.".jpg", true);
// 		dbio_UPDATE("core_users", "id=".USER_ID, array(
// 			#"picture"=>request_value("picture"),
// // 			"durchwahl"=>request_value("durchwahl"),
// // 			"handy"=>request_value("handy"),
// // 			"raum"=>request_value("raum"),
// 		));
	}
	setting_save(null, "MSCSV", request_value("mscsv"), true);
	setting_save(null, "CMPCTVIEW", request_value("CMPCTVIEW"), true);
	setting_save(null, "PRESENTATIONMODE", request_value("PRESENTATIONMODE"), true);
	setting_save(null, "DEBUGMODE", request_value("DEBUGMODE"), true);
	
	/*
	 * Module
	 */
	$wids_set=array();
	foreach ($modules as $mod_id=>$modul) {
		$widg=$modul->get_widgets();
		foreach ($widg as $widget) {
			if(request_value("widget_".$mod_id."_".$widget->name_id)) $wids_set[]=$mod_id."_".$widget->name_id;
		}
	}
	setting_save(null, "WIDGETS", implode(",", $wids_set), true);
	
	ajax_refresh("Speichere Daten...", "user.".CFG_EXTENSION."?cmd=updated");
}
?>