<?php
include_once '../../config_start.php';
$page->init('fun_demo','Spaßdemo');
include_once ROOT_HDD_CORE.'/core/alertify.php';
#include_once ROOT_HDD_CORE.'/core/classes/datasheet.php';
#include_once ROOT_HDD_CORE.'/core/classes/set.php';

#global $modules;
#if (isset($modules['fun']))
include_once ROOT_HDD_CORE.'/demo/modules/fun/fun.php';

/*
 * Sprichwortgenerator
 */
$page->say(html_header1("Sprichwortgenerator"));
$page->say(fun_sprichwortgenerator());

page_send_exit();//================================================================================
?>