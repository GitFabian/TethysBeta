<?php
include_once '../../config_start.php';

$page->init('core_rights','Rechte');

include_once ROOT_HDD_CORE.'/core/classes/table.php';
include_once ROOT_HDD_CORE.'/core/classes/rights.php';
include_once ROOT_HDD_CORE.'/core/alertify.php';
include_once 'rights_.php';

/*
 * Beschreibung aller Rechte
 */
$all_rights=all_rights();

/*
 * Tabelle Rechte
 */
$rights_table=array();
foreach ($all_rights as $key=>$right) {
	$name=$right->name;
	$desc=$right->description;
	$rights_table[]=array(
		"Berechtigung"=>$name." ($key)",
		"Beschreibung"=>$desc,
	);
}
$table_rights=new table($rights_table,null,false);
$page->add_html($table_rights->toHTML());


/*
 * Tabelle Benutzer-Rechte
 */
//Strg+Klick Strg+Click Ctrl+Klick Ctrl+Click:
$page->add_inline_script("var ctrlKey=false;
		function bind_click_modifier(){
			$('input').bind('click', function(event){ 
				ctrlKey=event.ctrlKey;
		 	}); 
		}
		function ajax_update_rights(id,right,elem){
			state=(elem.checked?'1':'0');
			".ajax("update_rights&id=\"+id+\"&right=\"+right+\"&state=\"+state+\"&modiCtrl=\"+ctrlKey+\"",null,"if(ctrlKey){window.location.reload();}else{alertify_ajax_response(response);}")."
		}");
$page->onload_JS.="bind_click_modifier();";
$query_users=dbio_SELECT("core_users","active=1","id,nick");
$query_user_right = dbio_SELECT("core_user_right");
$rights_grid=array();
foreach ($query_users as $user) {
	$user_rights=array("-USER-"=>$user['nick']." (".$user['id'].")");
	foreach ($all_rights as $right_id => $dummy) {
		$user_rights[$right_id]=rights_checkbox(false,$user['id'],$right_id);
	}
	$rights_grid[$user['id']]=$user_rights;
}
$headers=array("-USER-"=>"Benutzer");
foreach ($all_rights as $right_id => $right_object) {
	$header=$right_object->name;
	$header=preg_replace("/ /", "&nbsp;", $header);
	$header="<span title=\"$right_id\">$header</span>";
	$headers[$right_id]=$header;
}
foreach ($query_user_right as $right) {
	$rights_grid[$right['user']][$right['right']]=rights_checkbox(true,$right['user'],$right['right']);
}
$table = new table($rights_grid,'core_rights wide',false);
$table->set_header($headers);
$table->col_highlight=true;
$page->add_html( $table->toHTML() );



$page->send();
exit;//============================================================================================
function rights_checkbox($checked,$id,$right){
	return html_checkbox(null,$checked,"ajax_update_rights('$id','$right',this);");
}
?>