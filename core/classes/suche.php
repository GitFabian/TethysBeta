<?php

/*
include_once ROOT_HDD_CORE.'/core/classes/suche.php';
 */

class suche{
	
	var $modul;
	var $ajax_cmd;
	
	function __construct($modul=null,$ajax_cmd="such"){
		$this->modul=$modul;
		$this->ajax_cmd=$ajax_cmd;
	}
	
	function __toString(){
		return $this->form();
	}
	
	function form(){
		include_once ROOT_HDD_CORE.'/core/classes/form.php';
		include_datatables();
		global $page;

		$form=new form("such");
		$form->add_field(new form_field("such","Suche","","TEXT",null,null,"id_suche"));
		$form->set_blind();
		$page->focus="input[type=text]";
		
		/*
		 * AJAX
		 */
#$page->onload_JS.="such();";
		$page->add_inline_script(
"function such(){
	query=$('#id_suche').val();
	".ajax_to_id($this->ajax_cmd."&such=\"+encodeURIComponent(query)+\"", "results", $this->modul, false, "datatable_init('table','".ROOT_HTTP_CORE."');")."
}"
			.js_document_ready(
"$('#id_suche').keydown(function(event) {
	if (event.keyCode == '13') {
		such();
    }
});"
			)
		);
		
		return $form->toHTML().html_div("",null,"results");
	}
	
}

?>