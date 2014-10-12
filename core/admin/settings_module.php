<?php
if(!isset($page)){echo"Seite existiert nicht.";exit;}
if(!isset($modules[$view])){echo"Interner Fehler! settings_module.php:3";exit;}
$modul=$modules[$view];

if (request_command("update")) core_features_update();

$form=new form("update");
$form->add_hidden('view', $view);

if (isset($settings[$view])){
	$settings_module=$settings[$view];
	
	$query_settings=dbio_query_to_array("SELECT `type`,`label`,`key` FROM `core_settings` WHERE `modul`='$view' AND `user` IS NULL",null,"key");
	
	foreach ($settings_module as $key => $value) {
		$type=$query_settings[$key]['type'];
		$label=$query_settings[$key]['label'];
		if (!$label) $label=$key;
		$form->add_field( new form_field($key, $label, $value, $type, "get_setting_global('$view','$key')") );
	}
}else{
	$page->add_html("--- Keine Settings für \"".$modul->modul_name."\" definiert! ---");
}

$page->add_html($form->toHTML());

page_send_exit();//============================================================================================
function core_features_update(){
	global $view;
	if (!USER_ADMIN) return;
	unset ($_REQUEST['view']);
	request_extract_booleans2();
	$n=0;
	foreach ($_REQUEST as $key => $value) {
		//TODO:UPDATE_hasChanged nur, wenn Änderung
		dbio_UPDATE("core_settings", "`key`='$key' AND `modul`='$view' AND `user` IS NULL", array("value"=>$value));
		$n++;
	}
	ajax_refresh("Speichere Konfiguration...", "settings.".CFG_EXTENSION."?view=$view&cmd=updated&n=$n");
}
?>