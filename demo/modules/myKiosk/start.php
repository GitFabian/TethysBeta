<?php
$configOnly=true;
include_once '../../config_start.php';
include_once ROOT_HDD_CORE.'/core/start_standalone.php';
start_standalone('terminal','My Kiosk');
$page->init('myKiosk_start',"");
function hauptmenue($page_id){return null;}

$page->head.="<meta http-equiv=\"refresh\" content=\"5; URL=?refresh\">";

$page->say(format_Wochentag_Uhrzeit());

page_send_exit();//===============================================================================
?>