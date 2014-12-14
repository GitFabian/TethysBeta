<?php

include_once '../config_start.php';
$page->init("core_export", "Tabelle exportieren");

$db=request_value("db");
$id=request_value("id");

$modul=substr($db, 0, strpos($db, "_"));

if ($modul=='core'){
	if ($db=="core_users"){
		$query=dbio_SELECT("core_users");
		if(USER_ADMIN){
			$query_exclude=array();
		}else{
			$query_exclude=dbio_SELECT_asList("core_user_right","[id]","`right`='RIGHT_ADMIN'","user");
		}
		$data=array();
		foreach ($query as $row) {
			if(!isset($query_exclude[$row['id']]))
				$data[]=$row;
		}
		csv_out($data,"users.csv");
	}
	if ($db=="core_rollen"){
		$rollen=dbio_SELECT_keyValueArray("core_rollen", "name");
		$users=dbio_SELECT("core_users","active","*",null,"nick");
		$data=array();
		foreach ($users as $u) {
			$uid=$u['id'];
			$row=array("Name"=>$u['nick']);
			foreach ($rollen as $rid) {
				$row[$rid]="";
			}
			$data[$uid]=$row;
		}
		$query=dbio_SELECT("core_user_rolle");
		foreach ($query as $row) {
			$uid=$row['user'];
			$rid=$rollen[$row['rolle']];
			$data[$uid][$rid]="X";
		}
		csv_out($data,"rollen.csv");
	}
	$ok=false;
}else{
	$ok=$modules[$modul]->export_csv($db, $id);
}

if (!$ok) page_send_exit("Export fehlgeschlagen!");

page_send_exit();//====================================================================================================
function csv_out($data,$filename="export.csv"){
	header('Content-type: text/csv; charset=ISO-8859-1');
	header("Content-Disposition: attachment; filename=\"$filename\"");
	#header('Content-type: text/html; charset=ISO-8859-1');echo"<pre>";
	echo "sep=,\r\n";
	$row_counter=0;
	foreach ($data as $row) {
		$row_counter++;
		/*
		 * Header
		 */
		if ($row_counter==1){
			$keys=array();
			foreach ($row as $key => $dummy) {
				$key=utf8_decode($key);
				$key=preg_replace("/\"/", "\"\"", $key);
				$keys[]='"'.$key.'"';
			}
			echo implode(",", $keys)."\r\n";
		}
		/*
		 * DATA
		 */
		$values=array();
		foreach ($row as $value) {
			$value=utf8_decode($value);
			$value=preg_replace("/\"/", "\"\"", $value);
			$values[]='"'.$value.'"';
		}
		echo implode(",", $values)."\r\n";
	}
	exit;
}
?>