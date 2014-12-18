<?php
include_once '../../config_start.php';
$page->init('demo_bsp','CSS');
include_jquery();
include_once ROOT_HDD_CORE.'/core/alertify.php';
include_once ROOT_HDD_CORE.'/core/classes/set.php';
include_once ROOT_HDD_CORE.'/core/classes/user.php';

$views=array(
		new menu_topic2("one", "CSS"),
		new menu_topic2("two", "Tabellen"),
		new menu_topic2("three", "Formular"),
		new menu_topic2("datasheets", "Datenblatt / Set"),
);
$view=$page->init_views("one",$views);

if ($view=="two"){ include 'tabelle.php'; }
if ($view=="three"){ include 'formular.php'; }
if ($view=="datasheets"){ include 'datasheets.php'; }

$page->message_info("Info.");
$page->message_ask("Frage?");
$page->message_error("Fehler!");
$page->message_ok("OK!");

$page->say(html_header1("Header 1"));
$page->say(html_div("Dee zip Boba Fett razzleshnoz!"));
$page->say(html_header2("Header 2"));
$page->say(html_div("Duh doo yap Cartman flapizzle. Nip flippity wobble flooble wibble loo zingle."));
$page->say(html_header3("Header 3"));
$page->say(html_div("Nip ha flap flop blapping jongely jangle quibblerazz? Shizzle bleep bloobity duh boozangle???
		Yap da Lisa wibbleblop! Nip flang nip dang dangely zanglequibble, zap kanoodle plop doo zunkity tanglewiggle flobble.
		\"Hum woogle ha?\" oodle Smithers. \"Yip woggle dee?\" hizzy blipwubble. \"Da bang zip?\" shizzle oodleboo.
		Kenny yip Kent Brockman boo flibbing bla-abracadabra.<hr>Bam zipping crongely zangity roo crungle zap bluppity shnuzzlecrangle."));
$page->say(html_code("Codeblock"));

$page->say(html_button("toggle","","$('#example').toggle('invisible');"));
$page->say(html_div(html_code(encode_html("Example"),"code"),"invisible","example"));

$page->say(html_header1("Buttons, Links"));
$page->say(html_div("Dilznoofus ho ".html_a("inline link","#")." flingity wiggleding."));
$page->say(html_div("Zoom shrubbery ".html_a_button("link-button","css.".CFG_EXTENSION."?view=three")." twaddleflobble, bleep."));
$page->say(html_div("Yip hum quabble ".html_a("external link","http://tethys-framework.de",null,true)." zupping crongely."));
$page->say(html_div(html_a("I'm a link, too!","")));

$page->say(html_header1("Alertify"));
$page->say("\n".html_button("Dialog","","alertify.confirm(&quot;Message?&quot;,function(e){});"));
$page->say("\n".html_button("Alert","",alertify_alert("Alert")));
#$page->onload_JS.="alertify.confirm(&quot;Message?&quot;,function(e){});";
$page->say("\n".html_button("Success","",alertify_success("Success")));
$page->say("\n".html_button("Error","",alertify_error("Error")));

$page->send();
exit;//============================================================================================
?>