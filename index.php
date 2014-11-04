<?php
if(!file_exists('config_start.php'))include'install.php';
include_once 'config_start.php';

$page->init('core_index',CFG_HOME_TITLE);

if (CFG_HOME_URL){
	Header( "HTTP/1.1 301 Moved Permanently" );
	Header( "Location: ".CFG_HOME_URL );
	exit;
}

$page->send();
?>