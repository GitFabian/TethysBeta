<?php
include_once '../config_start.php';
$page->init("core_edit", "Datensatz bearbeiten");
include_once ROOT_HDD_CORE.'/core/classes/form.php';

$form=new form("do");
$form->add_field(new form_field("return",null,$_SERVER['HTTP_REFERER']));
$page->say($form);

page_send_exit();
?>