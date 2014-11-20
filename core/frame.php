<?php
include_once '../config_start.php';

$titel=request_value("title");

$page->init("core_frame", $titel);

$url=request_value("url");
if (!$url) page_send_exit("Seite nicht gefunden!");

$page->say("<div class=\"iframe_fullscreen\"><iframe src=\"$url\"></iframe></div>");

page_send_exit();//====================================================================================================
?>