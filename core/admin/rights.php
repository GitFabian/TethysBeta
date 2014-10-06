<?php

include_once '../start.php';
$page->init('core_rights','Rechte');
include_once CFG_HDDROOT.'/core/classes/table.php';


$page->add_html(":-)");

$query_rights=dbio_SELECT("core_rights");

print_r($query_rights);


$query_user_right=dbio_SELECT("core_user_right");

print_r($query_user_right);

$page->send();
?>