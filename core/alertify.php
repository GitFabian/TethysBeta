<?php

/*
include_once ROOT_HDD_CORE.'/core/alertify.php';

http://fabien-d.github.io/alertify.js/
 */

//INCLUDE:
global $page;
$page->add_library(ROOT_HTTP_CORE."/core/html/alertify.js-shim-0.3.8/alertify.min.js");
$page->add_stylesheet(ROOT_HTTP_CORE."/core/html/alertify.js-shim-0.3.8/themes/alertify.core.css");
$page->add_stylesheet(ROOT_HTTP_CORE."/core/html/alertify.js-shim-0.3.8/themes/alertify.default.css");

function alertify_alert($text){
	return "alertify.alert(&quot;$text&quot;);";
}

function alertify_success($text){
	return "alertify.success(&quot;$text&quot;);";
}

function alertify_error($text){
	return "alertify.error(&quot;$text&quot;);";
}

?>