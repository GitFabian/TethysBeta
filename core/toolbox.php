<?php

/*
 * http://tethys-framework.de/wiki/?title=Toolbox
 */

/**
 $backtrace=backtrace_to_html(debug_backtrace());
 
 http://tethys-framework.de/wiki/?title=Toolbox#backtrace_to_html
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

function html_iframe_fullsize($url,$class){
//TODO:return htmlEntity($name, $html, $pars);
	return "<iframe src=\"$url\" width=\"100%\" frameborder=\"0\" class=\"fullsize $class\"></iframe>";
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

function request_unset($key){
	$value=request_value($key);
	unset($_REQUEST[$key]);
	return $value;
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

function page_send_exit($error_message=null){
	global $page;
	if ($error_message) $page->content="---$error_message---<br><br>".$page->content;
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
	if ($modul=='demo'||$modul=='tethys') return ROOT_HTTP_CORE."/demo/modules/$modul/$page.".CFG_EXTENSION;
	return ROOT_HTTP_MODULES."/$modul/$page.".CFG_EXTENSION;
}

function url_core_admin($page,$request=null){
	return ROOT_HTTP_CORE."/core/admin/$page.".CFG_EXTENSION.($request?"?$request":"");
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

function encode_html($text){
	return htmlentities($text,null,'UTF-8');
}

function include_jquery(){
	global $page;
	$page->add_library(ROOT_HTTP_CORE."/core/html/jquery-1.10.2.js");
}

function include_datatables(){
	include_jquery();
	global $page;
	$page->add_library(ROOT_HTTP_CORE."/core/html/jquery.dataTables.min.1.10.js");
}

function include_chosen(){
	include_jquery();
	global $page;
	$ok=$page->add_library(ROOT_HTTP_CORE."/core/html/chosen_v1.2.0/chosen.jquery.min.js");
	$page->add_stylesheet(ROOT_HTTP_CORE."/core/html/chosen_v1.2.0/chosen.min.css");
	$hdd_css=ROOT_HDD_SKINS."/".CFG_SKIN."/chosen.css";
	$http_css=ROOT_HTTP_SKINS."/".CFG_SKIN."/chosen.css";
	if (CFG_SKIN=='demo'){
		$hdd_css=ROOT_HDD_CORE."/demo/skins/demo/chosen.css";
		$http_css=ROOT_HTTP_CORE."/demo/skins/demo/chosen.css";
	}
	if (file_exists($hdd_css)) $page->add_stylesheet($http_css);
	if($ok)$page->onload_JS.="$('select.chosen').chosen();";
}
function chosen_select_multi($name,$options,$selecteds=null,$id=null,$onChange=null){
	$onChange=($onChange?" onChange=\"$onChange\"":"");
	$id=($id?" id=\"$id\"":"");
	
	$options_html="";
	foreach ($options as $key=>$value) {
		$selected=($selecteds&&isset($selecteds[$key])?" selected":"");
		$options_html.="\n\t<option$selected value=\"$key\">$value</option>";
	}
	return "\n<select$id$onChange name=\"$name\" multiple"
			." class=\"chosen\""
			.">$options_html\n</select>";
}

function html_checkbox($name=null,$checked=false,$js=null){
	$name=($name?" name=\"$name\"":"");
	$checked=($checked?" checked":"");
	$js=($js?" onChange=\"$js\"":"");
	return "<input type=\"checkbox\"$name$checked$js /><div class=\"checkbox_ghost\"></div>";
}

function ajax($cmd,$modul=null,$function=null,$escape=false){
	if ($modul==null){
		$page=ROOT_HTTP_CORE."/core/ajax.".CFG_EXTENSION;
	}else{
		if ($modul=='demo'){
			$page=ROOT_HTTP_CORE."/demo/modules/demo/ajax.".CFG_EXTENSION;
		}else{
			$page=ROOT_HTTP_MODULES."/$modul/ajax.".CFG_EXTENSION;
		}
	}
	$quot=($escape?"&quot;":"\"");
	return "tethys_ajax($quot$page?cmd=$cmd$quot,$quot$function$quot);";
}

function htmlEntity($name,$html,$pars){
	$pars_html="";
	if($pars)
	foreach ($pars as $key => $value) {
		if($value)
		$pars_html.=" $key=\"$value\"";
	}
	return "<$name$pars_html>$html</$name>";
}
function htmlEntity2($name,$pars){
	$pars_html="";
	if($pars)
		foreach ($pars as $key => $value) {
			if($value)
				$pars_html.=" $key=\"$value\"";
		}
	return "<$name$pars_html />";
}

function html_div($html,$class=null,$id=null){
	return "\n".htmlEntity('div', $html, array(
			"class"=>$class,
			"id"=>$id,
		));
}
function html_header1($html,$class=null){
	return "\n".htmlEntity('h1', $html, array(
			"class"=>$class,
		));
}
function html_pre($html,$class=null){
	return "\n".htmlEntity('pre', $html, array(
			"class"=>$class,
		));
}
function html_code($html){
	return html_pre($html,"code");
}
function html_button($value,$class=null,$onClick=null){
	return htmlEntity2('input', array(
			"type"=>"button",
			"value"=>$value,
			"class"=>$class,
			"onclick"=>$onClick,
	));
}
function html_a($html,$href,$class=null,$external=false){
	$pars=array(
			"href"=>$href,
			"class"=>$class,
	);
	if ($external) $pars["target"]="_blank";
	return htmlEntity('a', $html, $pars);
}
function html_a_button($html,$href,$class=null){
	return html_a($html, $href, "button $class");
}

function string_random_pass_aa0000(){
	return string_random(2,"PASSalpha")
		.string_random(4,"PASSNUM");
}
function string_random($length,$key){
	
	if ($key=="GERKEYS"){ $key="ß^°!\"§%&/()=?´`²³{[]}\\äöüÄÖÜ-.,_:;#'+*~<>@€|µABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789éèÉÈáàÁÀíìÍÌóòÓÒúùÚÙýÝâêîôû"; }
	else if ($key=="PASSALPHA"){ $key=("ABDEFGHJKLNPQRT"); }
	else if ($key=="PASSalpha"){ $key=("abdefghijnpqrt"); }
	else if ($key=="PASSNUM"){ $key=("23456789"); }
	
	$len=strlen(utf8_decode($key))-1;
	
	$string="";
	for ($i = 0; $i < $length; $i++) {
		$string.=mb_substr($key,rand(0,$len),1,'UTF-8');
	}

	return $string;
}

?>