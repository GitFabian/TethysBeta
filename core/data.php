<?php
include_once '../config_start.php';
$page->init("core_data", "DATA");

$url=request_value("url");
if (!$url) page_send_exit("Seite existiert nicht!");
$file=ROOT_HDD_DATA."/".$url;
if (!file_exists($file)) page_send_exit("Datei nicht gefunden!");

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
exit;
?>