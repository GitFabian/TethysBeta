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

/**
 * Formular überträgt Checkboxen in Extra-Array, sonst würden nicht aktivierte Checkboxen verlorengehen.
 */
function request_extract_booleans2(){
	if (!isset($_REQUEST['booleans'])) return array();
	$booleans=$_REQUEST['booleans'];
	unset($_REQUEST['booleans']);
	$booleans=explode(",", $booleans);
	foreach ($booleans as $bool) {
		$value=false;
		if (isset($_REQUEST[$bool])){
			if ($_REQUEST[$bool]=='on') $value=true;
		}
		$_REQUEST[$bool]=($value?"1":"0");
	}
}
/** DEPRECATED */
function request_extract_booleans(){
	if (!isset($_REQUEST['booleans'])) return array();
	$booleans=$_REQUEST['booleans'];
	unset($_REQUEST['booleans']);
	$booleans=explode(",", $booleans);
	$r=array();
	foreach ($booleans as $bool) {
		$value=false;
		if (isset($_REQUEST[$bool])){
			if ($_REQUEST[$bool]=='on') $value=true;
			unset($_REQUEST[$bool]);
		}
		$r[$bool]=($value?"1":"0");
	}
	return $r;
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

function get_setting_global($modul,$key,$user=null){
	global $settings;
	//TODO:Fehlerabfrage
	return $settings[$modul][$key];
}
function get_setting_user($modul,$key,$init=true,$user=USER_ID){
	global $user_settings;
	if (!isset($user_settings[$modul])||!isset($user_settings[$modul][$key])){
		if ($init){
			global $modules;
			$setting=$modules[$modul]->user_setting_default($key);
			if($setting)set_setting_user_save($setting);
		}else{
			return null;
		}
	}
	return $user_settings[$modul][$key];
}
function setting_create($modul,$key,$label,$type,$value){
	return array(
		"key"=>$key,
		"modul"=>$modul,
		"user"=>USER_ID,
		"type"=>$type,
		"value"=>$value,
		"label"=>$label,
	);
}
function set_setting_user($modul,$key,$value){
	global $user_settings;
	if (!isset($user_settings[$modul])) $user_settings[$modul]=array();
	$user_settings[$modul][$key]=$value;
}
function set_setting_user_save($setting){
	set_setting_user($setting['modul'], $setting['key'], $setting['value']);
	dbio_INSERT("core_settings", $setting);
}
function update_setting_user($modul,$key,$value){
	dbio_UPDATE("core_settings", "`key`='$key' AND `modul`='demo' AND `user`=".USER_ID,array("value"=>$value));
}

?>