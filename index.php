<?php
if(!file_exists('config_start.php'))include'install.php';
include_once 'config_start.php';
if (CFG_HOME_URL){
	Header( "HTTP/1.1 301 Moved Permanently" );
	Header( "Location: ".CFG_HOME_URL );
	exit;
}
include_once ROOT_HDD_CORE.'/core/log.php';

log_others("VIEW","core","INDEX");

$page->init('core_index',CFG_HOME_TITLE);

if (file_exists(CFG_SKINDIR."/widgets.css")){
	$page->add_stylesheet(CFG_SKINPATH."/widgets.css");
}

$query_positions=dbio_SELECT("core_widgetpos","user=".USER_ID);
$widget_positions=array();
foreach ($query_positions as $row) {
	$widget_positions[$row["modul"]."_".$row["widget"]]=$row;
}

$wid_sets=array_val2key(explode(",", setting_get_user(null, "WIDGETS")));
foreach ($modules as $mod_id=>$modul) {
	$widgets=$modul->get_widgets();
	foreach ($widgets as $widget) {
		$widget_unique_name=$mod_id."_".$widget->name_id;
		if(isset($wid_sets[$widget_unique_name])){
			if(isset($widget_positions[$widget_unique_name])){
				$widget->pos_left=$widget_positions[$widget_unique_name]["x"];
				$widget->pos_top=$widget_positions[$widget_unique_name]["y"];
			}
			$widget->modul=$mod_id;
			$page->add_html($widget);
		}
	}
}

if(setting_get(null, "CFG_MOVEWIDGETS")) widgets_move_js();

$page->send();
//=================================================================================
// function widgets_containment_js(){
// 	return "[ 0, 100, window.innerWidth, window.innerHeight ]"
// 		#.",snap:true"
// 		#.",stack:'div.widget'"
// 	;
// }
function widgets_move_js(){
	global $page;
	include_jquery_ui();
	$containment=function_exists('widgets_containment_js')?widgets_containment_js():"'window'";
	if($containment)$containment="containment: $containment,";
	$page->add_inline_script(
"function start_widget_editor(){
	$( \"div.widget\" ).draggable({
		grid: [ 10, 10 ],
		stop: handleDragStop,
		handle: \"div.widget_header\",
		$containment
	});
}
function handleDragStop( event, ui ) {
	var offsetXPos = parseInt( ui.offset.left );
	var offsetYPos = parseInt( ui.offset.top );
	var modul=ui.helper.data('modul');
	var widget=ui.helper.data('widget');
	".ajax_to_alertify("widgetposition&x=\"+offsetXPos+\"&y=\"+offsetYPos+\"&modul=\"+modul+\"&widget=\"+widget+\"")."
}");
	$page->add_inline_script(js_document_ready("start_widget_editor();"));
}
?>