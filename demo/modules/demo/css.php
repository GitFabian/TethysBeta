<?php
include_once '../../config_start.php';
$page->init('demo_css','CSS');
include_jquery();
include_once ROOT_HDD_CORE.'/core/alertify.php';
include_once ROOT_HDD_CORE.'/core/classes/set.php';

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

$page->say(html_header1("html_iframe_fullsize(\$url)"));
$page->say(html_iframe_fullsize("http://sprichwortgenerator.de/","demo_css"));

$page->say(html_header1("Alertify"));
$page->say("\n".html_button("Dialog","","alertify.confirm(&quot;Message?&quot;,function(e){});"));
$page->say("\n".html_button("Alert","",alertify_alert("Alert")));
#$page->onload_JS.="alertify.confirm(&quot;Message?&quot;,function(e){});";
$page->say("\n".html_button("Success","",alertify_success("Success")));
$page->say("\n".html_button("Error","",alertify_error("Error")));

$page->say(html_header1("Set-Cards"));
$set=new set("");
$set->add_card($card1=new set_card("Karte 1","Dizzle flong ho whack da razz",ROOT_HTTP_CORE."/demo/DATA/core_users/person1.png"));
$card1->add_data(new set_card_data("name", "Name", "Chef"));
$card1->add_data(new set_card_data("hobbies", "Hobbys", "Quibblenip-bloobing, Zingity"));
$card1->buttons[]=html_a_button("Homepage", "http://qnote.de",null,null,true);
$set->add_card($card2=new set_card("Karte 2","Jingle da zang! Zap ho Maggie ingleblang! Yip bam bizzlerizzle bam duh ting, nip ting a loo dang zip.",
		ROOT_HTTP_CORE."/demo/DATA/core_users/person2.png"));
$card2->add_data(new set_card_data("name", "Name", "Lisa"));
$card2->add_data(new set_card_data("hobbies", "Hobby", "Dee flang"));
$card2->buttons[]=html_a_button("Homepage", "http://sourcecode-snippets.de",null,null,true);
$page->say($set);

$page->send();
exit;//============================================================================================
?>