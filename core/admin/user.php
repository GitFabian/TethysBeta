<?php
include_once '../start.php';

$page->init('core_user','Benutzereinstellungen');

include_once CFG_HDDROOT.'/core/classes/form.php';

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
	if (berechtigung('RIGHT_EDIT_NICK')){
		$persoenlich[]=new form_field("nick","Nutzername",USER_NICK);
	}
	if ($persoenlich) $form->add_fields("Persönliche Daten", $persoenlich);
	
	if ($form->field_groups)
		$page->add_html($form->toHTML());
}else if(isset($modules[$view])){
	$page->add_html($modules[$view]->get_user_page());
}

$page->send();
exit;//============================================================================================
function core_user_update(){
	if (berechtigung('RIGHT_EDIT_NICK')){
		//...
	}
	ajax_refresh("Speichere Daten...", "user.".CFG_EXTENSION."?cmd=updated");
}
?>