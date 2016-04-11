<?php
include_once '../../config_start.php';

$page->init('core_users','Benutzerverwaltung');

if (!berechtigung('RIGHT_USERMGMT')) page_send_exit("Keine Berechtigung!");

include_once ROOT_HDD_CORE.'/core/classes/table.php';
include_once ROOT_HDD_CORE.'/core/edit_rights.php';

$modul_inventur=isset($modules['inventur']);
$modul_team=isset($modules['team']);

$joins=array();
if($modul_inventur)$joins[]=new dbio_leftjoin("id", "inventur_user", "i");
if($modul_team)$joins[]=new dbio_leftjoin("id", "team_user_file", "t");
$query=dbio_SELECT("core_users",null,
		"core_users.id,active,nachname,vorname,http_auth,nick,email,geb"
// 		.($modul_inventur?",i.raum":"")
// 		.($modul_team?",t.durchwahl,t.handy":"")
		,$joins);
if(USER_ADMIN){
	$query_exclude=array();
}else{
	$query_exclude=dbio_SELECT_asList("core_user_right","[id]","`right`='RIGHT_ADMIN'","user");
}
$data=array();
foreach ($query as $row) {
	if(!isset($query_exclude[$row['id']]))
	#$row["geb"]=$row["geb"]?date("j.n.Y",strtotime($row["geb"])):"";
	$data[]=$row;
}
$table=new table($data);
$table->set_options(edit_rights_core("core_users","NEW"), true, true, "core_users");
$table->export_csv_id="core_users";
$page->say($table);

$page->focus="input[type=search]";

$page->send();
exit;//============================================================================================
?>