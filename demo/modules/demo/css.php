<?php
include_once '../../config_start.php';

$page->init('demo_css','CSS');

$page->say(html_header1("Header 1"));
$page->say(html_code("Codeblock"));

$page->say(html_header1("Buttons, Links"));
$page->say(html_div("Dilznoofus ho ".html_a("inline link","#")." flingity wiggleding."));
$page->say(html_div("Zoom shrubbery ".html_a_button("link-button","formular.".CFG_EXTENSION)." twaddleflobble, bleep."));
$page->say(html_div("Yip hum quabble ".html_a("external link","http://tethys-framework.de",null,true)." zupping crongely."));

$page->say(html_header1("html_iframe_fullsize(\$url)"));
$page->say(html_iframe_fullsize("http://sprichwortgenerator.de/","demo_css"));

$page->send();
exit;//============================================================================================
?>