<?php
include_once '../../config_start.php';
$page->init('demo_css','CSS');
include_jquery();

$views=array(
		new menu_topic2("one", "CSS"),
		new menu_topic2("two", "Tabellen"),
		new menu_topic2("three", "Formulare"),
);
$view=$page->init_views("one",$views);

if ($view=="two"){ include 'tabelle.php'; }
if ($view=="three"){ include 'formular.php'; }


$page->say(html_header1("Header 1"));
$page->say(html_div("Dee zip Boba Fett razzleshnoz!"));
$page->say(html_header2("Header 2"));
$page->say(html_div("Nip ha flap flop blapping jongely jangle quibblerazz? Shizzle bleep bloobity duh boozangle???
		Yap da Lisa wibbleblop! Nip flang nip dang dangely zanglequibble, zap kanoodle plop doo zunkity tanglewiggle flobble.
		\"Hum woogle ha?\" oodle Smithers. \"Yip woggle dee?\" hizzy blipwubble. \"Da bang zip?\" shizzle oodleboo.
		Kenny yip Kent Brockman boo flibbing bla-abracadabra.<hr>Bam zipping crongely zangity roo crungle zap bluppity shnuzzlecrangle."));
$page->say(html_code("Codeblock"));

$page->say(html_button("toggle","","$('#example').toggle('invisible');"));
$page->say(html_div(html_code(encode_html("Example"),"code"),"invisible","example"));

$page->say(html_header1("Buttons, Links"));
$page->say(html_div("Dilznoofus ho ".html_a("inline link","#")." flingity wiggleding."));
$page->say(html_div("Zoom shrubbery ".html_a_button("link-button","formular.".CFG_EXTENSION)." twaddleflobble, bleep."));
$page->say(html_div("Yip hum quabble ".html_a("external link","http://tethys-framework.de",null,true)." zupping crongely."));

$page->say(html_header1("html_iframe_fullsize(\$url)"));
$page->say(html_iframe_fullsize("http://sprichwortgenerator.de/","demo_css"));

$page->send();
exit;//============================================================================================
?>