<?php
/*
include_once ROOT_HDD_CORE.'/demo/modules/myqueries/index_.php';
 */

function get_connection($id){
	global $sql;
	if ($id){
		$c=dbio_SELECT_SINGLE("myqueries_connections", $id);
		return sql_openNewConnection($c['server'], $c['username'], $c['password'], $c['dbase']);
	}else{
		return $sql;
	}
}

?>