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

function dbio_query_to_array($anfrage){
	$result=dbio_query($anfrage);
	if (!$result) return array();

	$array=array();
	while ( $myrow = mysql_fetch_assoc ( $result ) ) {
		array_push($array, $myrow);
	}
	return $array;
}

function dbio_query($anfrage){
	$query = mysql_query ( $anfrage ) or die ( error_die(mysql_error()."<br><code>".$anfrage."</code>") );
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

?>