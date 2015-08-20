<?php
include_once '../../config_start.php';
$page->init('core_widgets','Widgets');
if (!USER_ADMIN) page_send_exit("Keine Berechtigung!");

include_once ROOT_HDD_CORE.'/core/classes/table.php';

$page->say(html_header1("Widgets"));

$query_widgets=dbio_SELECT("core_settings","`key`='WIDGETS' AND modul IS NULL");
$query_users=dbio_SELECT("core_users","active");

$widgets=array();
foreach ($modules as $mod_id=>$modul) {
	$widg=$modul->get_widgets();
	foreach ($widg as $widget) {
		$widget->modul=$mod_id;
		$widgets[$mod_id."_".$widget->name_id]=$widget;
	}
}

$users_widgets=array();
foreach ($query_widgets as $row) {
	$uid=$row["user"];
	#if(!isset($users_widgets[$uid]))$users_widgets[$uid]=array();
	#$users_widgets[$uid][]=
	$users_widgets[$uid]=array_val2key(explode(",", $row["value"]));
}

$data=array();
foreach ($query_users as $u) {
	$uid=$u["id"];
	$row=array(
		"Benutzer"=>$u["nick"],
	);
	foreach ($widgets as $wid => $widget) {
		$row[$wid]=html_checkbox(null,false,ajax_to_alertify("widgetcheck&user=$uid&state=&quot;+this.checked+&quot;&modul=".$widget->modul."&widget=".$widget->name_id,null,true));
		if(isset($users_widgets[$uid]) && isset($users_widgets[$uid][$wid])){
			$row[$wid]=html_checkbox(null,true,ajax_to_alertify("widgetcheck&user=$uid&state=&quot;+this.checked+&quot;&modul=".$widget->modul."&widget=".$widget->name_id,null,true));
		}
	}
	$data[]=$row;
}
$table=new table($data,"wide");
$table->datatable->paginate=true;
$page->say($table);

// $page->say(new table($query_widgets));
// $page->say(new table($query_users));

$page->send();
exit;//============================================================================================
?>