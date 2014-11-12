<?php

/*
include_once ROOT_HDD_CORE.'/core/log.php';
 */

function log_db_new($modul,$tabelle,$zeile,$pars=null){
	dbio_INSERT("core_logs_dbedit", array(
		"time"=>time(),
		"user"=>USER_ID,
		"modul"=>$modul,
		"ip"=>$_SERVER['REMOTE_ADDR'],
		"action"=>"new",
		"tabelle"=>$tabelle,
		"zeile"=>$zeile,
		"pars"=>$pars,
	));
}

function log_db_edit($modul,$tabelle,$zeile,$pars=null){
	dbio_INSERT("core_logs_dbedit", array(
		"time"=>time(),
		"user"=>USER_ID,
		"modul"=>$modul,
		"ip"=>$_SERVER['REMOTE_ADDR'],
		"action"=>"edit",
		"tabelle"=>$tabelle,
		"zeile"=>$zeile,
		"pars"=>$pars,
	));
}

function log_db_delete($modul,$tabelle,$zeile,$pars=null){
	dbio_INSERT("core_logs_dbedit", array(
		"time"=>time(),
		"user"=>USER_ID,
		"modul"=>$modul,
		"ip"=>$_SERVER['REMOTE_ADDR'],
		"action"=>"del",
		"tabelle"=>$tabelle,
		"zeile"=>$zeile,
		"pars"=>$pars,
	));
}

function logs_for_entity($table,$row_id){
	include_once ROOT_HDD_CORE.'/core/classes/table.php';
	$query=dbio_SELECT("core_logs_dbedit","tabelle='$table' AND zeile='$row_id'");
	$table=new table($query);
	return (USER_ADMIN?html_header1("Verlauf").$table->toHTML():"");
}

?>