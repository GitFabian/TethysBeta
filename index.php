<?php
if(!file_exists('config_start.php'))include'install.php';
include_once 'config_start.php';

$page->init('core_index',CFG_HOME_TITLE);

if (CFG_HOME_URL){
	Header( "HTTP/1.1 301 Moved Permanently" );
	Header( "Location: ".CFG_HOME_URL );
	exit;
}

$wid_sets=array_val2key(explode(",", setting_get_user(null, "WIDGETS")));
foreach ($modules as $mod_id=>$modul) {
	$widgets=$modul->get_widgets();
	foreach ($widgets as $widget) {
		if(isset($wid_sets[$mod_id."_".$widget->name_id])){
// 			$widget->pos_left=300;
// 			$widget->pos_top=200;
			$page->add_html($widget);
		}
	}
}

$page->send();
?>