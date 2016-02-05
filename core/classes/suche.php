<?php

/*
include_once ROOT_HDD_CORE.'/core/classes/suche.php';
 */

class suche{
	
	var $modul;
	var $ajax_cmd;
	var $initial_html="";
	var $options=array();
	var $options_checked=array();
	var $paginate=true;
	
	function __construct($modul=null,$ajax_cmd="such"){
		$this->modul=$modul;
		$this->ajax_cmd=$ajax_cmd;
	}
	
	function __toString(){
		return $this->form()->toHTML().html_div($this->initial_html,null,"results");
	}
	
	function form(){
		include_once ROOT_HDD_CORE.'/core/classes/form.php';
		include_datatables();
		global $page;

		$form=new form("such","?",null,"suche");
		$form->add_field(new form_field("such","Suche","[REQ]","TEXT",null,null,"id_suche"));
		$form->set_blind();
		$page->focus="input[type=text]";
		
		/*
		 * Options
		 */
		$ajax_options=array();
		foreach ($this->options as $key => $value) {
			$id=get_next_id();
			$form->add_field(new form_field($key,$value,(isset($_REQUEST[$key])?$_REQUEST[$key]:(isset($this->options_checked[$key])?$this->options_checked[$key]:"0")),"CHECKBOX",null,null,$id));
			$ajax_options[]="&$key=\"+$('#$id').is(':checked')+\"";
		}
		
		/*
		 * AJAX
		 */
#$page->onload_JS.="such();";
		$page->add_inline_script(
"function such(){
	query=$('#id_suche').val();
	".ajax_to_id($this->ajax_cmd."&such=\"+encodeURIComponent(query)+\""
			.implode("", $ajax_options)
			, "results", $this->modul, false, "datatable_init2('table','".ROOT_HTTP_CORE."',".($this->paginate?"true":"false").");")."
}"
			.js_document_ready(
"$('#id_suche').keydown(function(event) {
	if (event.keyCode == '13') {
		such();
    }
});"
			)
		);
		
		return $form;
	}
	
}

function suche_sql_queriy_like($field,$q){
	return "`$field` COLLATE utf8_general_ci LIKE '%".sqlEscape($q)."%'";
}

?>