<?php
if(!isset($page)){echo"Seite existiert nicht.";exit;}
$page->init('demo_bsp','Formulare');
include_once ROOT_HDD_CORE.'/core/classes/form.php';
include_chosen();

if(request_command("update"))update_demoformular();

$page->say(html_header1("Formular 1"));

$form=new form("update","?","Shnozzle");
$form->add_hidden("view", "three");
$form->add_hidden("id", USER_ID);
$form->add_fields(null, array(
		new form_field("wert1"),
		new form_field_info("fraggle","Info-Feld","Hum wacko duh twaddle flopping wobbleblob"),
));
$form->add_fields("Überschrift / Sektion", array(
		new form_field("noodle","Checkbox (CHECKBOX)",request_value("noodle","1"),"CHECKBOX"),
		new form_field("radio","Radio-Buttons (RADIO)",request_value("radio"),"RADIO",null,array(
				"one"=>"One",
				"two"=>"Two",
		)),
		new form_field("radio2","Radio-Buttons nebeneinander (RADIO2)",request_value("radio2"),"RADIO2",null,array(
				"eins"=>"Eins",
				"zwei"=>"Zwei",
		)),
		new form_field("zippity","Dropdown (SELECT)",request_value("zippity","ts"),"SELECT",null,array(
				"ms"=>"Mr. Slave",
				"ts"=>"Tony Soprano",
		)),
		new form_field("nizzle[]","Mehrfachauswahl (SELECT_MULTIPLE)","[REQ]","SELECT_MULTIPLE",null,array(
				"a"=>"Wobbledingle",
				"b"=>"Twiddle boo",
				"c"=>"Crangle",
				"d"=>"Hizzle-shrubbery",
		)),
		new form_field("jinglewoogle","Passwort (PASSWORD)",request_value("jinglewoogle","abracadabra"),"PASSWORD"),
		new form_field("duh","Text (TEXT)",request_value("duh","Doo nippy do-da tangity")),
		new form_field("cronglewoob","Textfeld (TEXTAREA)","[REQ]","TEXTAREA"),
		new form_field("datei1","Datei (FILE)",null,"FILE"),
));
#$form->add_fields("",array(new form_field("foo")));
$form->buttons.=html_a_button("link-button","css.".CFG_EXTENSION);
// $form->buttons.=html_button("spinner-test",null,waitSpinner());
$page->say($form->toHTML());

$page->send();
exit;//============================================================================================
function update_demoformular(){
	global $page;
	request_extract_booleans2();
	$id=request_unset("id");
	$fehler=null;
	$file=getUpload('datei1','demo/uploads/:FILENAME:',true,false);
	#$page->message_ok($file);
	if (!request_value("wert1")) $fehler="Bitte Wert für \"wert1\" angeben!";
	if ($fehler){
		$page->message_error($fehler);
		return;
	}
	#dbio_UPDATE("demo_demo", "`id`='$id'", $_REQUEST);
}
?>