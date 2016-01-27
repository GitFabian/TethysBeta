<?php
/*
include_once ROOT_HDD_CORE."/core/export_csv_.php";
 */
function csv_out($data,$filename="export.csv",$prefix="",$suffix=""){
	header('Content-type: text/csv; charset=ISO-8859-1');
	header("Content-Disposition: attachment; filename=\"$filename\"");
	#header('Content-type: text/html; charset=ISO-8859-1');echo"<pre>";
	if(setting_get_user(null,"MSCSV"))echo "sep=,\r\n";
	if($prefix)echo utf8_decode($prefix)."\r\n";
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
	if($suffix)echo "\r\n".utf8_decode($suffix);
	exit;
}
?>