<?php

include_once '../config_start.php';

$cmd=request_value("cmd");
if ($cmd=="update_rights") update_rights();
if ($cmd=="lorumipsum") lorumipsum();
if ($cmd=="rolleSetzen") rolleSetzen();
if ($cmd=="sendmail") sendmail();
if ($cmd=="widgetposition") widgetposition();
if ($cmd=="widgetcheck") widgetcheck();
if ($cmd=="AJAX_CSS_BLEND") AJAX_CSS_BLEND();

echo "!Unbekanntes AJAX-Kommando \"$cmd\"!";
exit;//===========================================================================================

function AJAX_CSS_BLEND(){
	include_once ROOT_HDD_CORE.'/core/css_blender_.php';
	
	$blend_url=explode(",", request_value("urls"));
	
	$hashkey=blender_hash($blend_url);
	$blended_file=ROOT_HDD_CORE."/core/html/CSS/$hashkey.css";
	
	$server=setting_get(null, "CFG_SERVER");

	$blend=array();
	foreach ($blend_url as $value) {
		$blend[]=file_get_contents($server.$value);
	}
	file_put_contents($blended_file, implode("\n", $blend));

	exit;
}
function widgetcheck(){
	if(!USER_ADMIN)ajax_exit("!Keine Berechtigung!");
	$state=request_value("state")=="true"?true:false;
	$modul=request_value("modul");
	$widget=request_value("widget");
	$user=request_value("user");

	$wuid=$modul."_".$widget;
	
	$query_widgets=dbio_SELECT("core_settings","`key`='WIDGETS' AND modul IS NULL AND user=$user");
	$widgets=array();
	if($query_widgets && $query_widgets[0]["value"]){
		$widgets=array_val2key(explode(",", $query_widgets[0]["value"]));
	}
	if($state){
		$widgets[$wuid]=true;
	}else{
		unset($widgets[$wuid]);
	}
	
	$widgets_sql_arr=array();
	foreach ($widgets as $wid => $dummy) {
		$widgets_sql_arr[]=$wid;
	}
	dbio_UPDATE_OR_INSERT2("core_settings", "`key`='WIDGETS' AND modul IS NULL AND user=$user", array(
		"key"=>"WIDGETS",
		"modul"=>null,
		"user"=>$user,
		"value"=>implode(",", $widgets_sql_arr),
	));
	ajax_exit("Gespeichert.");
}
function widgetposition(){
	$pos_x=request_value("x");
	$pos_y=request_value("y");
	$modul=request_value("modul");
	$widget=request_value("widget");
	dbio_UPDATE_OR_INSERT2("core_widgetpos", "user=".USER_ID." AND modul='".sqlEscape($modul)."' AND widget='".sqlEscape($widget)."'", array(
		"user"=>USER_ID,
		"modul"=>$modul,
		"widget"=>$widget,
		"x"=>$pos_x,
		"y"=>$pos_y,
	));
	ajax_exit("Position gespeichert."
			.(USER_ADMIN?" {$pos_x}x$pos_y":"")
			#.(USER_ADMIN?"<br>$modul:$widget":"")
			);
}
function sendmail(){
	$id=request_value("id");
	if (!$id) ajax_exit("!E-Mail nicht verschickt.");
	include_once ROOT_HDD_CORE.'/core/email.php';
	email_send($id);
	ajax_exit("E-Mail verschickt.".(USER_ADMIN?" #$id":""));
}
function rolleSetzen(){
	if (!berechtigung('RIGHT_USERMGMT')) ajax_exit("!Keine Berechtigung!");
	$uid=request_value('uid');
	$rid=request_value('rid');
	$checked=(request_value('checked')=='true'?1:0);
	
	$rolle=dbio_SELECT_SINGLE("core_rollen", $rid);
	$user=dbio_SELECT_SINGLE("core_users", $uid);
	if ($checked){
		dbio_INSERT("core_user_rolle", array("user"=>$uid,"rolle"=>$rid));
	}else{
		dbio_DELETE("core_user_rolle", "user=$uid AND rolle=$rid");
	}
	
	ajax_exit($user['nick'].": ".($checked?$rolle['name']:"<span class=\"strike\">".$rolle['name']."</span>"));
}
function lorumipsum(){
	$content=file_get_contents("http://bff.orangehairedboy.com/?paragraphs=1&sentences=4&caps=no&action=generate");
	#ajax_exit($content);
	preg_match("/<h1>Your Blippity Fling-Flang<\\/h1>.*?<blockquote>(.*?)<\\/blockquote>/s", $content, $matches);
	#print_r($matches);exit;
	$content=$matches[1];
	$content=trim($content);
	ajax_exit($content);
	
// 	$length=request_value("length");
// 	$content = file_get_contents("http://loripsum.net/api/1/$length/plaintext");
// 	//Ersten Satz abschneiden:
// 	$content=substr($content, strpos($content, ".")+1);
// 	$content=trim($content);
// 	ajax_exit($content);
}
function update_rights(){
	if (!USER_ADMIN) ajax_exit("!Keine Berechtigung!");
	include_once ROOT_HDD_CORE.'/core/admin/rights_.php';
	$id=request_value("id");
	$right=request_value("right");
	$state=request_value("state");
	$modiCtrl=(request_value("modiCtrl")=="true");
	if ($id==USER_ID&&$right=='RIGHT_ADMIN') ajax_exit("!Keine gute Idee!");
	
	right_set($id, $right, $state);
	
	$all_rights=all_rights();
	$recht=$all_rights[$right]->name;
	$user=dbio_SELECT_SINGLE("core_users", $id);
	$name=$user['vorname']." ".$user['nachname'];
	if (!$state) $recht="<span class=\"strike\">$recht</span>";
	echo"$name: $recht";exit;
}
function ajax_exit($msg){
	echo $msg;exit;
}

?>