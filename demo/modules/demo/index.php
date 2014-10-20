<?php
include_once '../../config_start.php';

$page->init('demo','Demopage');

$list="";
$list.="<li>".html_a("CSS",ROOT_HTTP_CORE."/demo/modules/demo/css.".CFG_EXTENSION)."</li>";
$list.="<li>".html_a("Views",ROOT_HTTP_CORE."/demo/modules/demo/views.".CFG_EXTENSION)."</li>";
$list.="<li>".html_a("Tabelle",ROOT_HTTP_CORE."/demo/modules/demo/tabelle.".CFG_EXTENSION)."</li>";
$list.="<li>".html_a("Formular",ROOT_HTTP_CORE."/demo/modules/demo/formular.".CFG_EXTENSION)."</li>";
$page->say("<ul>$list</ul>");

$page->send();
exit;//============================================================================================
?>