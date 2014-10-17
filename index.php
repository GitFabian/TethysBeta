<?php
if(!file_exists('config_start.php'))include'install.php';
include_once 'config_start.php';

$page->init('core_index',CFG_HOME_TITLE);



$page->send();
?>