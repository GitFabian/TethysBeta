<?php

/*
 * http://217.91.49.199/tethyswiki/index.php/Toolbox
 */

/**
 $backtrace=backtrace_to_html(debug_backtrace());
 
 http://217.91.49.199/tethyswiki/index.php/Toolbox#backtrace_to_html
 */
function backtrace_to_html($debug_backtrace){
	$backtrace="";
	foreach ($debug_backtrace as $step) {
		$backtrace.="<li>".$step['function'].' in '.$step['file'].':'.$step['line']."</li>";
	}
	$backtrace="<ul>$backtrace</ul>";
	return $backtrace;
}

function error_die($msg){
	$backtrace=(USER_ADMIN?"<div class=\"entwickler\">".backtrace_to_html(debug_backtrace())."</div>":"");
	echo $msg.$backtrace;
	exit;
}

function html_iframe_fullsize($url){
	return "<iframe src=\"$url\" width=\"100%\" frameborder=\"0\" class=\"fullsize\"></iframe>";
}

?>