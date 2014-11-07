<?php

include_once '../config_start.php';
$page->init("core_export", "Tabelle exportieren");

$db=request_value("db");
$id=request_value("id");

$modul=substr($db, 0, strpos($db, "_"));

$ok=$modules[$modul]->export_csv($db, $id);

if (!$ok) page_send_exit("Export fehlgeschlagen!");

page_send_exit();//====================================================================================================
function csv_out($data,$filename="export.csv"){
	header("Content-Disposition: attachment; filename=\"$filename\"");
	$row_counter=0;
	foreach ($data as $row) {
		$row_counter++;
		if ($row_counter==1){
			foreach ($row as $key => $dummy) {
				$key=utf8_decode($key);
				$key=preg_replace("/\"/", "\"\"", $key);
				$key='"'.$key.'"';
				echo $key."\t";
			}
			echo "\r\n";
		}
		foreach ($row as $value) {
			$value=utf8_decode($value);
			$value=preg_replace("/\"/", "\"\"", $value);
			$value='"'.$value.'"';
			echo $value."\t";
		}
		echo "\r\n";
	}
	exit;
}
?>