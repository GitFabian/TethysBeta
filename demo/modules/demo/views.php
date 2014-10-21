<?php
include_once '../../config_start.php';

$page->init('demo_views','Views');

$views=array(
	new menu_topic2("one", "One"),
	new menu_topic2("two", "Two"),
	new menu_topic2("three", "Three"),
);
$view=$page->init_views("one",$views);

if ($view=="one"){ include 'views_one.php'; }

if ($view=="two"){
	$page->say(html_header1("Two"));
}else if ($view=="three"){
	$page->say(html_header1("Three"));
}else{
	$page->say("?");
}

$page->send();
exit;//============================================================================================
?>