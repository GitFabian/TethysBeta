<?php
include_once '../../core/start.php';

$page->init('tethys_ftp','Tethys-FTP-Repo');


$page->add_html(html_iframe_fullsize("http://85.214.46.83/tethys/ftp/"));

$page->send();
exit;//============================================================================================
?>