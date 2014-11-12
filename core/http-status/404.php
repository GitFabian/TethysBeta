<?php
include_once '../../config_start.php';
$page->init("404", "404 - Not Found");

$page->message_error("Seite nicht gefunden!");

page_send_exit();
?>