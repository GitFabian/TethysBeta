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
		"core_users.id,active,nachname,vorname,http_auth,password,nick,picture,email"
		.($modul_inventur?",i.raum":"")
		.($modul_team?",t.durchwahl,t.handy":"")
		,$joins);
$table=new table($query);
$table->set_options(edit_rights_core("core_users","NEW"), true, true, "core_users");
$table->export_csv_id="core_users";
$page->say($table);

$page->focus="input[type=search]";

$page->send();
exit;//============================================================================================
?>