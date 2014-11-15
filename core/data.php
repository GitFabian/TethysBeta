<?php
include_once '../config_start.php';
$page->init("core_data", "DATA");

$url=request_value("url");
if (!$url) exit_404("Seite existiert nicht!");
$file=ROOT_HDD_DATA."/".$url;
if (!file_exists($file)){exit_404("Datei nicht gefunden!");}

/*
 * Berechtigung überprüfen
 */
//TODO

/*
 * Dateierweiterung
 */
$extension=pathinfo($file, PATHINFO_EXTENSION);
$extension=strtolower($extension);
if ($extension=='png'){
	$mime="image/".$extension;
}else{
	$mime="application/octet-stream";
}

header('Content-type: '.$mime);
readfile($file);
exit;//============================================================================================
function exit_404($msg){
	header("HTTP/1.0 404 Not Found");
	page_send_exit($msg);
}
?>