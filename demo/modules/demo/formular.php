<?php
include_once '../../config_start.php';
$page->init('demo_formular','Formular');
include_once ROOT_HDD_CORE.'/core/classes/form.php';

if(request_command("update"))update_demoformular();

$form=new form("update",null,"Shnozzle");
$form->add_hidden("id", USER_ID);
$form->add_fields(null, array(
		new form_field("cringle"),
		new form_field("fraggle"),
));
$form->add_fields("Hum wiggle zip", array(
		new form_field("noodle","Noodle",request_value("noodle","1"),"CHECKBOX"),
		new form_field("zippity","Zippity",request_value("zippity","ts"),"SELECT",null,array(
				"ms"=>"Mr. Slave",
				"ts"=>"Tony Soprano",
		)),
		new form_field("jinglewoogle","Jinglewoogle",request_value("jinglewoogle","abracadabra"),"PASSWORD"),
		new form_field("duh","Duh",request_value("duh","Doo nippy do-da tangity")),
		#new form_field("Crongle-Wooble","cronglewoob"),
));
$form->buttons.=html_a_button("link-button","css.".CFG_EXTENSION);
$page->say($form->toHTML());

$page->send();
exit;//============================================================================================
function update_demoformular(){
	global $page;
	request_extract_booleans2();
	$id=request_unset("id");
	$fehler=null;
	if (!request_value("cringle")) $fehler="Bitte Wert für \"cringle\" angeben!";
	if ($fehler){
		$page->say("---$fehler---<br><br>");
		return;
	}
	#dbio_UPDATE("demo_demo", "`id`='$id'", $_REQUEST);
}
?>