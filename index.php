<?php
if(!file_exists('config_start.php'))include'install.php';
include_once 'config_start.php';

$page->init('core_index',CFG_HOME_TITLE);

if (file_exists(CFG_SKINDIR."/widgets.css")){
	$page->add_stylesheet(CFG_SKINPATH."/widgets.css");
}

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
			$widget->modul=$mod_id;
			$page->add_html($widget);
		}
	}
}

widgets_move_js();

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
  alert( \"Drag stopped!\\n\\nOffset: (\" + offsetXPos + \", \" + offsetYPos + \")\\n\");
}

			
");
	$page->add_inline_script(js_document_ready("start_widget_editor();"));
}
?>