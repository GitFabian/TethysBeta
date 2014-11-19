<?php
include_once '../../config_start.php';
$page->init('myqueries_index','Index');
include_once ROOT_HDD_CORE.'/core/classes/form.php';
$page->add_library(ROOT_HTTP_CORE."/demo/modules/myqueries/toolbox.js");
/*?*/include_datatables();
include_once ROOT_HDD_CORE.'/core/classes/table.php';

$form=new form(null,null);
$form->tag="div";
$form->class="form";
$form->submit_bool=false;

$form->add_field(new form_field("name"));
$form->add_field(new form_field("query",null,"[REQ]",'TEXTAREA',null,null,"id_query"));
$form->buttons.=html_button2("Ansehen","view('".ROOT_HTTP_CORE."','".CFG_EXTENSION."');");
$form->buttons.=html_button2("Exportieren");
$page->say($form->toHTML());

$page->say(html_div("","","target"));

$datatable=new datatable("table");
$page->add_inline_script("function exe(){ ".$datatable->get_execute()." }");

page_send_exit();//===============================================================================
?>