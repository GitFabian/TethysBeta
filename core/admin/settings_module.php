<?php
if(!isset($page)){echo"Seite existiert nicht.";exit;}
if(!isset($modules[$view])){echo"Interner Fehler! settings_module.php:3";exit;}
$modul=$modules[$view];

if (request_command("update")) core_settings_update2($view);

$form=new form("update");
$form->add_hidden('view', $view);

$modul->global_settings($form);

$page->add_html($form->toHTML());

page_send_exit();//============================================================================================
?>