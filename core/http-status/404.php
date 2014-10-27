<?php
include_once '../../config_start.php';
$page->init("404", "404");

$page->say("--- Seite nicht gefunden! ---");

page_send_exit();
?>