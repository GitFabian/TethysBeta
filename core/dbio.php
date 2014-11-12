<?php

function dbio_SELECT($db,$where=null,$fields="*",$leftjoins=null,$order=null,$orderAsc=true,$limit=null){

	$anfrage="SELECT $fields FROM `$db`";

	if ($leftjoins){
		foreach ($leftjoins as $join) {
			if ($join->as){
				$anfrage.=" LEFT JOIN `".$join->table."` AS ".$join->as." ON `$db`.".$join->field."=".$join->as.".".$join->id;
			}else{
				$anfrage.=" LEFT JOIN `".$join->table.                 "` ON `$db`.".$join->field."=".              $join->id;
			}
		}
	}

	if ($where) $anfrage.=" WHERE ".$where;

	if ($order){ $anfrage.=" ORDER BY `$order` ".($orderAsc?"ASC":"DESC"); }

	if ($limit){ $anfrage.=" LIMIT $limit"; }

	return dbio_query_to_array($anfrage);
}

function dbio_SELECT_SINGLE($db,$id,$id_key="id"){
	$query=dbio_SELECT($db,"`$id_key`='$id'");
	if (!$query) return null;
	return $query[0];
}

function dbio_SELECT_keyValueArray($db,$field,$key="id",$where=null){
	$query=dbio_SELECT($db,$where,"`$field`,`$key`");
	$list=array();
	foreach ($query as $row) {
		$list[$row[$key]]=$row[$field];
	}
	return $list;
}

/**
 * $format="[vorname] [nachname]";
 */
function dbio_SELECT_asList($db,$format,$where=null,$key="id",$sort=null,$sortAsc=true){
	preg_match_all("/\\[(.*?)\\]/", $format, $fields);
	$f=array("`$key`");
	$patterns=array();
	foreach ($fields[1] as $field) {
		$f[]="`$field`";
		$patterns[]="/\\[$field\\]/";
	}
	$f=implode(",", $f);
	$query=dbio_SELECT($db,$where,$f,null,$sort,$sortAsc);
	$list=array();
	foreach ($query as $row) {
		$replacements=array();
		foreach ($fields[1] as $field) {
			$replacements[]=$row[$field];
		}
		$value=preg_replace($patterns, $replacements, $format);
		$list[$row[$key]]=$value;
	}
	return $list;
}

function dbio_DELETE($db,$where){
	$anfrage="DELETE FROM `$db` WHERE $where;";
	dbio_query($anfrage);
}

function dbio_NEW_FROM_REQUEST($db,$idkey="id",$unsets=null){
	$data=array();
	foreach ($_REQUEST as $key => $dummy) {
		$data[$key]=request_value($key);
	}
	if($unsets)foreach ($unsets as $unset_key) {
		unset($data[$unset_key]);
	}
	if (isset($_REQUEST['new_id'])){
		$data[$idkey]=$_REQUEST['new_id'];
		unset($data['new_id']);
	}else{
		unset($data[$idkey]);
	}
	dbio_INSERT($db, $data);
	return mysql_insert_id();
}

function dbio_INSERT($db,$data){

	$values=array();
	$keys=array();
	foreach ($data as $key => $value) {
		$keys[]="`$key`";
		if ($value===null) $values[]="NULL"; else
			$values[]="'".sqlEscape($value)."'";
	}
	$values=implode(", ", $values);
	$keys=implode(", ", $keys);
	
	$anfrage="INSERT INTO `$db` ( $keys ) VALUES ( $values );";
	
	dbio_query($anfrage);
}

function dbio_UPDATE($db,$where,$data){
	$anfrage="UPDATE `$db` SET ";

	$sets="";
	foreach ($data as $key => $value) {
		if ($value===null) $sets.=", `".$key."`=NULL"; else
			$sets.=", `".$key."`='".sqlEscape($value)."'";
	}
	$anfrage.=substr($sets,2);

	$anfrage.=" WHERE $where;";

	dbio_query($anfrage);
}

function dbio_UPDATE_groupMember($db,$new,$group,$gid,$user="user"){
	$query_users=dbio_SELECT_keyValueArray($db, $user, 'id', "`$group`=$gid");

	if ($new){
		$dazu=array_diff($new, $query_users);
		$hinfort=array_diff($query_users, $new);
	}else{
		$dazu=array();
		$hinfort=$query_users;
	}

	$msg=array();

	foreach ($dazu as $id) {
		dbio_INSERT($db, array(
		$group=>$gid,
		$user=>$id,
		));
		$msg[]="Hinzugefügt: #$id";
	}

	foreach ($hinfort as $id) {
		dbio_DELETE($db, "`$group`=$gid AND $user=$id");
		$msg[]="Entfernt: #$id";
	}

	return $msg;
}

function dbio_query_to_array($anfrage,$link_identifier=null,$assoc_key=null){
	$result=dbio_query($anfrage,$link_identifier);
	if (!$result) return array();

	$array=array();
	if ($assoc_key){
		while ( $myrow = mysql_fetch_assoc ( $result ) ) {
			$array[$myrow[$assoc_key]]=$myrow;
		}
	}else{
		while ( $myrow = mysql_fetch_assoc ( $result ) ) {
			array_push($array, $myrow);
		}
	}
	return $array;
}

function dbio_query($anfrage,$link_identifier=null){
	global $devel_performance_query_counter;
	$devel_performance_query_counter++;
	if ($link_identifier){
		$query = mysql_query( $anfrage,$link_identifier ) or die ( error_die(mysql_error()."<br><code>".$anfrage."</code>") );
	}else{
		$query = mysql_query( $anfrage                  ) or die ( error_die(mysql_error()."<br><code>".$anfrage."</code>") );
	}
	return $query;
}

class dbio_leftjoin{

	var $as;
	var $table;
	var $field;
	var $id;

	function __construct($field,$table,$as=false,$id='id'){
		$this->as=$as;
		$this->table=$table;
		$this->field=$field;
		$this->id=$id;
	}

}

function dbio_table_exists($tbl){
	return (mysql_num_rows(dbio_query("SHOW TABLES LIKE '".sqlEscape($tbl)."'"))==1);
}

/**
 * <code>
	$col_info=dbio_info_columns("demo_lorumipsum");
	echo "id.Type=".$col_info['id']['Type'];
	echo "id.Null=".$col_info['id']['Null'];
	echo "id.Key=".$col_info['id']['Key'];
	echo "id.Default=".$col_info['id']['Default'];
	echo "id.Extra=".$col_info['id']['Extra'];
	echo "flubtangle.Type=".$col_info['flubtangle']['Type'];
 * </code>
 */
function dbio_info_columns($table){
	$columns=dbio_query_to_array("SHOW COLUMNS FROM `$table`");
	$list=array();
	foreach ($columns as $col) {
		$list[$col['Field']]=$col;
	}
	return $list;
}

?>