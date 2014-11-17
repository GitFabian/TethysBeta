<?php
include_once '../../config_start.php';
$page->init('myqueries_index','Index');
include_once ROOT_HDD_CORE.'/core/classes/form.php';

$form=new form(null,null);
$form->tag="div";
$form->class="form";
$form->submit_bool=false;

$form->add_field(new form_field("name"));
$form->add_field(new form_field("query",null,"[REQ]",'TEXTAREA'));
$form->buttons.=html_button2("Ansehen","view();");
$form->buttons.=html_button2("Exportieren","export();");
$page->say($form->toHTML());

$page->say(html_div("","","target"));

page_send_exit();//===============================================================================
?>