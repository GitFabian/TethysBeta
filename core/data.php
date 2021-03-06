<?php
include_once '../config_start.php';
$page->init("core_data", "DATA");

$url=request_value("url");
if (!$url) exit_404("Seite existiert nicht!");
$file_utf=ROOT_HDD_DATA."/".$url;
$file=utf8_decode($file_utf);
if (!file_exists($file)){exit_404("Datei nicht gefunden!");}

/*
 * Berechtigung überprüfen
 */
$access=false;
// $query_accessrights=dbio_SELECT("coreXaccessrights","user=".USER_ID);
// foreach ($query_accessrights as $r) {
// 	$prefix=$r["file"];
// 	if(string_startswith($url, $prefix))$access=true;
// }
$slash=strpos($url, "/");
if($slash){
	$modul=substr($url, 0, $slash);
	if(isset($modules[$modul])){
		$access=$modules[$modul]->datafolder_access($url);
	}
}
if (!$access){
	if (function_exists('datafolder_access')) $access=datafolder_access($url);
}
if (!$access){exit_404("Keine Berechtigung!");}

/*
 * Dateierweiterung
 */
#$mime=finfo_file(finfo_open(FILEINFO_MIME_TYPE), $file);//PHP >= 5.3.0, PECL fileinfo >= 0.1.0
$filename=pathinfo($file_utf, PATHINFO_BASENAME);
$extension=pathinfo($file, PATHINFO_EXTENSION);
$extension=strtolower($extension);
if ($extension=='png'
	||$extension=='jpg'
	){
	$mime="image/".$extension;
}else if($extension=='txt'){
	$mime="text/plain";
}else{
	$mime="application/octet-stream";
}
header('Content-type: '.$mime);
header("Content-Disposition: attachment; filename=\"$filename\"");
readfile($file);
exit;//============================================================================================
function exit_404($msg){
	header("HTTP/1.0 404 Not Found");
	page_send_exit($msg);
}
?>