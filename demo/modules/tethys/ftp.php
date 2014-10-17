<?php
include_once '../../config_start.php';

$page->init('tethys_ftp','Tethys-FTP-Repo');


$page->add_html(html_iframe_fullsize("http://tethys-framework.de/tethys/ftp/"));

$page->send();
exit;//============================================================================================
?>