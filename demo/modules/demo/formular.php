<?php
if(!isset($page)){echo"Seite existiert nicht.";exit;}
$page->init('demo_formular','Formulare');
include_once ROOT_HDD_CORE.'/core/classes/form.php';
include_chosen();

if(request_command("update"))update_demoformular();

$page->say(html_header1("Formular 1"));

$form=new form("update",null,"Shnozzle");
$form->add_hidden("view", "three");
$form->add_hidden("id", USER_ID);
$form->add_fields(null, array(
		new form_field("cringle"),
		new form_field("fraggle"),
));
$form->add_fields("Hum wiggle zip", array(
		new form_field("noodle","Noodle",request_value("noodle","1"),"CHECKBOX"),
		new form_field("radio","Radio",request_value("radio"),"RADIO",null,array(
				"one"=>"One",
				"two"=>"Two",
		)),
		new form_field("zippity","Zippity",request_value("zippity","ts"),"SELECT",null,array(
				"ms"=>"Mr. Slave",
				"ts"=>"Tony Soprano",
		)),
		new form_field("nizzle[]","Nizzle","[REQ]","SELECT_MULTIPLE",null,array(
				"a"=>"Wobbledingle",
				"b"=>"Twiddle boo",
				"c"=>"Crangle",
				"d"=>"Hizzle-shrubbery",
		)),
		new form_field("jinglewoogle","Jinglewoogle",request_value("jinglewoogle","abracadabra"),"PASSWORD"),
		new form_field("duh","Duh",request_value("duh","Doo nippy do-da tangity")),
		new form_field("cronglewoob","Crongle-Wooble","[REQ]","TEXTAREA"),
));
#$form->add_fields("",array(new form_field("foo")));
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
		$page->message_error($fehler);
		return;
	}
	#dbio_UPDATE("demo_demo", "`id`='$id'", $_REQUEST);
}
?>