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

function request_extract_dates(){
	if (!isset($_REQUEST['t_dates'])) return;
	$dates=$_REQUEST['t_dates'];
	unset($_REQUEST['t_dates']);
	
	$dates=explode(",", $dates);
	
	foreach ($dates as $d) {
		$value=false;
		if (isset($_REQUEST[$d])){
			$_REQUEST[$d]=format_datum_to_sql($_REQUEST[$d]);
		}
	}
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

function request_add($pars){
	$request=(isset($_SERVER['QUERY_STRING'])&&$_SERVER['QUERY_STRING']?$_SERVER['QUERY_STRING']."&":"");
	return "?".$request.$pars;
}

function page_send_exit($error_message=null){
	global $page;
	if ($error_message){$page->message_error($error_message);}
	$page->send();
	exit;
}

function sqlEscape($text){
	$text=str_replace("\\", "\\\\", $text);
	$text=str_replace("'", "\'", $text);
	return $text;
}

function ajax_refresh($msg,$url,$break=true){
	global $page;
	$page->content=$msg;
	if (USER_ADMIN&&$break){
		$page->say(html_div(html_a_button("Zurück", $url, "admin_back")));
		include_jquery();
		$page->focus="a.admin_back";
	}else{
		$page->onload_JS.="location.href='$url';";
	}
	page_send_exit();
}

function escape_html($text){
	return htmlentities(utf8_decode($text));
}
function escape_inline_js($text){
	$text=escape_html($text);
	$text=preg_replace("/'/", "\\'", $text);
	$text=preg_replace("/[\\n\\r]/", " ", $text);
	return $text;
}

function string_kuerzen2($string, $maxlen, $escape=true, $teaser="[mehr...]"){
	if (strlen($string)>$maxlen){
		global $global_id_counter;
		$text=substr($string,0,$maxlen-1-strlen($teaser));
		return "<div id=\"n".($global_id_counter)."\">"
				.($escape?escape_html($text):$text)
				." <a onclick=\""
						."document.getElementById('n".($global_id_counter++)."').innerHTML='".escape_inline_js(($escape?escape_html($string):$string))."';"
					."\">$teaser</a>"
			."</div>";
	}
	return ($escape?escape_html($string):$string);
}

function url_mod_pg($modul,$page){
	if ($modul=='demo'||$modul=='fun'||$modul=='myqueries') return ROOT_HTTP_CORE."/demo/modules/$modul/$page.".CFG_EXTENSION;
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

function include_jquery_ui(){
	include_jquery();
	global $page;
	$page->add_library(ROOT_HTTP_CORE."/core/html/jquery-ui-1.11.2/jquery-ui.min.js");
	$page->add_stylesheet(ROOT_HTTP_CORE."/core/html/jquery-ui-1.11.2/jquery-ui.min.css");
}

function include_datatables(){
	include_jquery();
	global $page;
	#$page->add_library(ROOT_HTTP_CORE."/core/html/jquery.dataTables.min.1.10.js");
	$page->add_library(ROOT_HTTP_CORE."/core/html/jquery.dataTables.min.1.10.4.js");
// 	$page->add_library(ROOT_HTTP_CORE."/core/html/jquery.dataTables.fixedHeader.js");
}

function include_chosen(){
	include_jquery();
	global $page;
	$ok=$page->add_library(ROOT_HTTP_CORE."/core/html/chosen_v1.2.0/chosen.jquery.min.js");
	$page->add_stylesheet(ROOT_HTTP_CORE."/core/html/chosen_v1.2.0/chosen.min.css");
	if (file_exists(CFG_SKINDIR."/chosen.css")) $page->add_stylesheet(CFG_SKINPATH."/chosen.css");
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

function datepicker($id){
	include_jquery_ui();
	global $page;
	$page->onload_JS.="\$('#$id').datepicker();";
}

function html_checkbox($name=null,$checked=false,$js=null,$id=null,$onChange=null){
	$name=($name?" name=\"$name\"":"");
	$checked=($checked?" checked":"");
	$js=($js?" onChange=\"$js\"":"");
	$id=($id?" id=\"$id\"":"");
	$onChange=($onChange?" onChange=\"$onChange\"":"");
	return "<input$id$onChange type=\"checkbox\"$name$checked$js /><div class=\"checkbox_ghost\"></div>";
}

function ajax($cmd,$modul=null,$function=null,$escape=false){
	if ($modul==null){
		$page=ROOT_HTTP_CORE."/core/ajax.".CFG_EXTENSION;
	}else{
		if ($modul=='demo'||$modul=='fun'){
			$page=ROOT_HTTP_CORE."/demo/modules/$modul/ajax.".CFG_EXTENSION;
		}else{
			$page=ROOT_HTTP_MODULES."/$modul/ajax.".CFG_EXTENSION;
		}
	}
	$quot=($escape?"&quot;":"\"");
	return "tethys_ajax($quot$page?cmd=$cmd$quot,$quot$function$quot);";
}

function ajax_to_alertify($cmd,$modul=null,$escape=false){
	include_once ROOT_HDD_CORE.'/core/alertify.php';
	return ajax($cmd,$modul,"alertify_ajax_response(response);",$escape);
}

function ajax_to_id($cmd,$id,$modul=null,$escape=false,$function=null){
	if ($modul==null){
		$page=ROOT_HTTP_CORE."/core/ajax.".CFG_EXTENSION;
	}else{
		if ($modul=='demo'||$modul=='fun'||$modul=='myqueries'){
			$page=ROOT_HTTP_CORE."/demo/modules/$modul/ajax.".CFG_EXTENSION;
		}else{
			$page=ROOT_HTTP_MODULES."/$modul/ajax.".CFG_EXTENSION;
		}
	}
	$quot=($escape?"&quot;":"\"");
	return "tethys_ajax_to_id2($quot$page?cmd=$cmd$quot,$quot$id$quot,$quot$function$quot);";
}

function js_getSelectedValue($id){
	return "document.getElementById('$id').options[document.getElementById('$id').selectedIndex].value";
}

function js_document_ready($function){
	return "$( document ).ready(function() { $function });";
}

function html_select_options($data,$selected=null){
	$html=array();
	foreach ($data as $key => $value) {
		$html[]="<option"
				.($selected&&$key==$selected?" selected":"")
				." value=\"$key\">$value</option>";
	}
	return implode("", $html);
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

function html_div($html,$class=null,$id=null,$style=null){
	return "\n".htmlEntity('div', $html, array(
			"class"=>$class,
			"id"=>$id,
			"style"=>$style,
		));
}

function div_edit($html,$onclick){
	return "\n".htmlEntity('div', $html, array(
			"class"=>"edit",
			"onclick"=>$onclick,
		));
}

function html_header1($html,$class=null){
	return "\n".htmlEntity('h1', $html, array(
			"class"=>$class,
		));
}
function html_header2($html,$class=null){
	return "\n".htmlEntity('h2', $html, array(
			"class"=>$class,
		));
}
function html_header3($html,$class=null){
	return "\n".htmlEntity('h3', $html, array(
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
function html_button2($value,$onClick=null){
	return htmlEntity2('input', array(
			"type"=>"button",
			"value"=>$value,
			"onclick"=>$onClick,
	));
}

function html_url($link,$text=null,$external=false){
	if(!$text)$text=$link;
	$protocol=parse_url($link,PHP_URL_SCHEME);
	if(!$protocol)$link="http://".$link;
	return html_a($text, $link, null, $external);
}

function html_a($html,$href,$class=null,$external=false,$onclick=null){
	$pars=array(
			"href"=>$href,
			"class"=>$class,
	);
	if ($external) $pars["target"]="_blank";
	if ($onclick) $pars["onclick"]=$onclick;
	return htmlEntity('a', $html, $pars);
}
function html_a_button($html,$href,$class=null,$onclick=null,$extern=false){
	return html_a($html, $href, "button $class", $extern, $onclick);
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
	else if ($key=="hex"){ $key=("0123456789abcdef"); }
	
	$len=strlen(utf8_decode($key))-1;
	
	$string="";
	for ($i = 0; $i < $length; $i++) {
		$string.=mb_substr($key,rand(0,$len),1,'UTF-8');
	}

	return $string;
}

function string_kuerzen($string,$maxlen){
	if($string&&strlen($string)>$maxlen){
		return substr($string, 0, $maxlen-3)."...";
	}
	return $string;
}

$wochentage=array("So","Mo","Di","Mi","Do","Fr","Sa");

/**
 * So, 02.11.14 15:19
 */
function format_Wochentag_Uhrzeit($time=null){
	global $wochentage;
	if ($time===null) $time=time();
	return $wochentage[date("w",$time)].date(", d.m.y H:i",$time);
}

function format_datum_to_tmj($string=null){
	return date("j.n.Y",($string?strtotime($string):time()));
}
function format_datum_to_tm_j($string,$j=null,$Y='Y'){
	if($j===null)$j=date("Y");
	$time=strtotime($string);
	if($j==date("Y",$time)){
		$format="j.n.";
	}else{
		$format="j.n.$Y";
	}
	return date($format,$time);
}

function format_Wochentag_tm_j($string,$j=null,$Y='Y'){
	global $wochentage;
	$time=strtotime($string);
	return $wochentage[date("w",$time)].", ".format_datum_to_tm_j($string, $j, $Y);
}

function format_datum_to_sql($string=null){
	return date("Y-m-d",($string===null?time():strtotime($string)));
}

function time_delta($time,$futur='noch'){
	$delta=time()-$time;
	if ($delta>0){
		$vz="vor";
	}else{
		$delta=-$delta;
		$vz=$futur;
	}
	if ($delta>47304000) return "$vz ".(round($delta/31536000))." ".($vz=='noch'?"Jahre":"Jahren");
	if ($delta>3952800) return "$vz ".(round($delta/2635200))." ".($vz=='noch'?"Monate":"Monaten");
	if ($delta>907200) return "$vz ".(round($delta/604800))." Wochen";
	if ($delta>129600) return "$vz ".(round($delta/86400))." ".($vz=='noch'?"Tage":"Tagen");
	if ($delta>5400) return "$vz ".(round($delta/3600))." Stunden";
	if ($delta>90) return "$vz ".(round($delta/60))." Minuten";
	return "$vz $delta Sekunden";
}

function time_delta_days($then,$now=null){
	return floor((($now?strtotime($now):time())-strtotime($then))/86400);
}

function date_sql($time=null){
	if($time) return date("Y-m-d",$time);
	return date("Y-m-d");
}

function html_progress($fortschritt_normiert){
	if($fortschritt_normiert<0)$fortschritt_normiert=0;
	else if($fortschritt_normiert>1)$fortschritt_normiert=1;
	$percent=floor($fortschritt_normiert*100)."%";
	$percent="<div class=\"tprogress\"><div class=\"tprogressbar\" style=\"width:$percent;\"><span class=\"tp_number\">$percent</span></div></div>";
	return $percent;
}

function noch_n_werktage($datestring){
	include_once ROOT_HDD_CORE.'/core/toolbox_dates.php';
	$epd=-time_delta_days($datestring);
	if($epd>1&&$epd<18){
		$wt=werktage(date_sql(), $datestring)-1;
		$bisdahin="in $wt ".($wt==1?"Werktag":"Werktagen");
	}else{
		$bisdahin=format_days_delta($epd);
	}
	return "$bisdahin (".format_Wochentag_tm_j($datestring).")";
}

function format_days_delta($delta){
	if($delta<0){
		if($delta==-1) return "gestern";
		$delta=-$delta;
		$vz="vor";
	}else{
		if($delta==0) return "heute";
		if($delta==1) return "morgen";
		$vz="in";
	}
	if ($delta>10) return "$vz ".round($delta/7)." Wochen";
	return "$vz $delta Tagen";
}

$sonderzeichen_regex=array(
	'/[Ää]/u'=>'ae',
	'/[Öö]/u'=>'oe',
	'/[Üü]/u'=>'ue',
	'/[ÁáÀàÂâ]/u'=>'a',
	'/[ÉéÈèÊê]/u'=>'e',
	'/[ÍíÌìÎî]/u'=>'i',
	'/[ÓóÒòÔô]/u'=>'o',
	'/[ÚúÙùÛû]/u'=>'u',
	'/ß/u'=>'ss',
	'/[Ýý]/u'=>'y',
);
$sonderzeichen_regex_patterns=null;

function sort_sonderzeichen($text){
	global $sonderzeichen_regex,$sonderzeichen_regex_patterns;
	if (!$sonderzeichen_regex_patterns){
		$sonderzeichen_regex_patterns=array();
		foreach ($sonderzeichen_regex as $key => $dummy) {
			$sonderzeichen_regex_patterns[]=$key;
		}
	}
	$text=strtolower($text);
	$text=preg_replace($sonderzeichen_regex_patterns, $sonderzeichen_regex, $text);
	return $text;
}

function and_return($modul,$page){
	if ($modul=='demo'){
		$root=ROOT_HTTP_CORE."/demo/modules/".$modul;
	}else{
		$root=ROOT_HTTP_MODULES."/".$modul;
	}
	return "&return=$root/$page.".CFG_EXTENSION."?id=[NEWID]";
}
function and_return2($url){
	return "&return=".urlencode($url);
}

function js_runLater($code,$delay_seconds,$repeat=false){
	$delay=round($delay_seconds*1000);
	if ($repeat){
		return "setInterval(function(){{$code}},$delay);";
	}else{
		return "setTimeout(function(){{$code}},$delay);"; 
	}
}

function sql_openNewConnection($host,$user,$pass,$db){
	global $sql;
	
	//Alte Verbindung:
	$file=fopen(ROOT_HDD_CORE."/config_start.php", "r");
	$content=fread($file, 9999);
	fclose($file);
	preg_match("/\\n\\s*(?:\\\$sql\\s*=\\s*)?mysql_connect\\s*\\(\\s*'(.*?)'\\s*,\\s*'(.*?)'\\s*,\\s*'(.*?)'\\s*\\)\\s*;/", $content, $matches);
	$sql_server=$matches[1];
	$sql_user=$matches[2];
	$sql_pass=$matches[3];
	
	//Neue Verbindung
	$new=mysql_connect($host,$user,$pass);
	mysql_select_db($db);
	mysql_query ('SET NAMES utf8');
	
	//Zurücksetzen auf alte Verbindung:
	mysql_connect($sql_server,$sql_user,$sql_pass);
	
	return $new;
}

function sql_like($field,$string){
	return " `$field` COLLATE utf8_general_ci LIKE '$string' ";
}

function debug_out($variable){
	if(USER_ADMIN){echo"<pre>";print_r($variable);}
}

function get_next_id(){
	global $global_id_counter;
	return "id_".($global_id_counter++);
}

function autofill_password($target_id,$more_on_click=""){
	return " <a class=\"autofill password\" onClick=\"autofill_password('$target_id');$more_on_click\">(erstellen)</a>";
}

function autofill_manuell($function){
	return " <a class=\"autofill password\" onClick=\"$function\">(erstellen)</a>";
}

function format_default_for_column($table,$column){
	global $modules;
	$modul=substr($table, 0, strpos($table, "_"));
	if ($modul=='core'){
		return format_default_core($table,$column);
	}
	if (isset($modules[$modul])){
		$modul=$modules[$modul];
		return $modul->format_default_for_column($table,$column);
	}
	return "[$column]";
}

function format_default_core($table,$column){
	if ($table=='core_users') return "[vorname] [nachname] (#[id])";
	return "[$column]";
}

function iframe_link($url,$title=null){
	return ROOT_HTTP_CORE."/core/frame.".CFG_EXTENSION."?title=".urlencode($title)."&url=".urlencode($url);
}

function array_htmlentities_pre($arrayarray){
	$new=array();
	foreach ($arrayarray as $id => $row) {
		$new[$id]=array();
		foreach ($row as $key => $value) {
			$new[$id][$key]="<pre>".htmlentities(utf8_decode($value))."</pre>";
		}
	}
	return $new;
}

function csv_pfad($csv_id){
	if (setting_get(null, 'APACHE_CSV_ALIAS')) return ROOT_HTTP_CORE."/export/".$csv_id.".csv";
	return ROOT_HTTP_CORE."/core/export_csv.".CFG_EXTENSION."?db=".$csv_id;
}

function query_from_array($array){
	$pairs=array();
	foreach ($array as $key => $value) {
		$pairs[]=urlencode($key)."=".urlencode($value);
	}
	return "?".implode("&", $pairs);
}

function parse_url_modul_and_file($url){
	$dir=pathinfo($url,PATHINFO_DIRNAME);
	$modul=substr($dir, strrpos($dir, "/")+1);
	$file=pathinfo($url,PATHINFO_FILENAME);
	return $modul."/".$file;
}

function parse_url_query($url,$key='id'){
	$url=parse_url($url);
	$query=$url['query'];
	parse_str($query,$pars);
	return $pars[$key];
}

function html_to_plain($html){
	$plain=preg_replace("/\\<.*?\\>/s", "", $html);
	$plain=trim($plain);
	return $plain;
}

function array_unshift_assoc($array,$key,$value){
	$array=array_reverse($array,true);
	$array[$key]=$value;
	$array=array_reverse($array,true);
	return $array;
}

/**
 * <code>
$then=time();
"<span class=\"td_ajax\" data-timestamp=\"".$then."\">".time_delta($then)."</span>"
 * </code>
 */
function include_time_delta($seconds=1){
	global $page;
	include_jquery();
	$page->onload_JS.="time_delta_start($seconds);";
}

?>
