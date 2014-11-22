<?php
include_once '../../config_start.php';
$page->init('myqueries_index','Index');
include_once ROOT_HDD_CORE.'/core/classes/form.php';
$page->add_library(ROOT_HTTP_CORE."/demo/modules/myqueries/toolbox.js");
/*?*/include_datatables();
include_once ROOT_HDD_CORE.'/core/classes/table.php';

if ($r=request_value("response")) $page->message_info($r);
$save=request_command("save");

/*
 * Views: Queries aus Datenbank
 */
$query=dbio_SELECT("myqueries_queries");
$views=array();
foreach ($query as $row) {
	$views[]=new menu_topic2($row["id"], $row["name"]);
}
$view=$page->init_views(null, $views);

$name="";
$desc="";
$query="";
if($view){
	$q=dbio_SELECT_SINGLE("myqueries_queries",$view);
	$name=$q['name'];
	$desc=$q['beschreibung'];
	$query=$q['query'];
}

/*
 * Formular
 */

$page->focus="input[type=text],textarea";

$form=new form(null,null);
$form->tag="div";
$form->class="form";
$form->submit_bool=false;

if($save){
	$form->add_field(new form_field("name",null,request_value('name',$name),'TEXT',null,null,"id_name"));
}
if($save||$view){
	$form->add_field(new form_field("beschreibung",null,request_value('beschreibung',$desc),'TEXTAREA',null,null,"id_desc"));
}
$form->add_field(new form_field("query",null,request_value('query',$query),'TEXTAREA',null,null,"id_query"));
$form->buttons.=html_button2("Ansehen","view('".ROOT_HTTP_CORE."','".CFG_EXTENSION."');");
$form->buttons.=html_button2("Exportieren");
if($save){
	$form->buttons.=html_button2("Speichern","save('".ROOT_HTTP_CORE."','".CFG_EXTENSION."');");
}else{
	$form->buttons.=html_button2("Speichern","save_create('".ROOT_HTTP_CORE."','".CFG_EXTENSION."');");
}
if($view){
	$form->buttons.=html_button2("Löschen");
}
$page->say($form->toHTML());

$page->say(html_div("","","target"));

$datatable=new datatable("table");
$page->add_inline_script("function datatable_exe(){ ".$datatable->get_execute()." }");

page_send_exit();//===============================================================================
?>