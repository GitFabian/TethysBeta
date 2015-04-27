<?php
include_once '../../config_start.php';
$page->init('core_rights','Rechte');
if (!USER_ADMIN) page_send_exit("Keine Berechtigung!");

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
		"ID"=>$key,
		"Berechtigung"=>$name,
		"Modul"=>$right->modul,
		"Beschreibung"=>$desc,
	);
}
$table_rights=new table($rights_table,null,true);
$table_rights->datatable->paginate=true;
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

$from=0;
$too=count($all_rights);
$parts=15;
while($from<$too){
	$to=min(array($from+$parts,$too))-1;
	$all_rights2=array();
	$i=0;
	foreach ($all_rights as $right_row_key => $right_row_value) {
		if($i>=$from&&$i<=$to)
			$all_rights2[$right_row_key]=$right_row_value;
		$i++;
	}
	{
		$rights_grid=array();
		$user_sort_values=array();
		$table = new table(null,'core_rights wide',true);
		foreach ($query_users as $user) {
			$row=new table_row();
			$row_sort=array();
			$user_rights=array("-USER-"=>$user['nick']." (".$user['id'].")");
			$row->data["-USER-"]=$user['nick']." (".$user['id'].")";
			$user_sort_values[]=$user['id'];
			foreach ($all_rights2 as $right_id => $dummy) {
				$user_rights[$right_id]=rights_checkbox(false,$user['id'],$right_id);
				$row->data[$right_id]=rights_checkbox(false,$user['id'],$right_id);
				$row_sort[$right_id]=0;
			}
			$rights_grid[$user['id']]=$user_rights;
			$row->sort_values=$row_sort;
			$table->rows[$user['id']]=$row;
		}
		$headers=array("-USER-"=>"Benutzer");
		foreach ($all_rights2 as $right_id => $right_object) {
			$header=$right_object->name;
			$header=preg_replace("/ /", "&nbsp;", $header);
			$header="<span title=\"$right_id\">$header</span>";
			$headers[$right_id]=$header;
		}
		foreach ($query_user_right as $right) {
			if(isset($table->rows[$right['user']])){
				$rights_grid[$right['user']][$right['right']]=rights_checkbox(true,$right['user'],$right['right']);
				$table->rows[$right['user']]->data[$right['right']]=rights_checkbox(true,$right['user'],$right['right']);
				$table->rows[$right['user']]->sort_values[$right['right']]=1;
			}
		}
		
		// $table = new table($rights_grid);
		// $page->say($table);
		
		#$table = new table($rights_grid,'core_rights wide',true);
		// $table->set_sort_values("-USER-", $user_sort_values);
		$table->datatable->paginate=true;
		$table->set_header($headers);
		$table->col_highlight=true;
		$page->add_html( $table->toHTML() );
	}
	$from+=$parts;
}


$page->send();
exit;//============================================================================================
function rights_checkbox($checked,$id,$right){
	return html_checkbox(null,$checked,"ajax_update_rights('$id','$right',this);");
}
?>