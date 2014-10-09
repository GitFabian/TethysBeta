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

function request_command($cmd){
	if (isset($_REQUEST['cmd']) && $_REQUEST['cmd']==$cmd){
		unset($_REQUEST['cmd']);
		unset($_REQUEST['submit']);
		return true;
	}
	return false;
}

function request_value($key,$else=null){
	if (isset($_REQUEST[$key])) return $_REQUEST[$key];
	return $else;
}

function page_send_exit(){
	global $page;
	$page->send();
	exit;
}

function sqlEscape($text){
	$text=str_replace("\\", "\\\\", $text);
	$text=str_replace("'", "\'", $text);
	return $text;
}

function ajax_refresh($msg,$url){
	global $page;
	$page->content=$msg;
	$page->onload_JS.="location.href='$url';";
	page_send_exit();
}

function escape_html($text){
	return htmlentities(utf8_decode($text));
}

function url_mod_pg($modul,$page){
	return CFG_HTTPROOT."/modules/$modul/$page.".CFG_EXTENSION;
}

function url_core_admin($page){
	return CFG_HTTPROOT."/core/admin/$page.".CFG_EXTENSION;
}

function encode_query_to_utf8($query){
	$i=0;
	foreach ($query as $row) {
		foreach ($row as $key => $value) {
			$query[$i][$key]=utf8_encode($value);
		}
		$i++;
	}
	return $query;
}

function encode_query_to_utf8_assoc($query){
	foreach ($query as $rowkey=>$row) {
		foreach ($row as $key => $value) {
			$query[$rowkey][$key]=utf8_encode($value);
		}
	}
	return $query;
}

?>