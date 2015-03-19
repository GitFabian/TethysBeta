<?php

/*
include_once ROOT_HDD_CORE.'/core/classes/form.php';

http://tethys-framework.de/wiki/?title=Formular
bzw. http://localhost/tethys/demo/modules/demo/formular.php
 */

if (USER_ADMIN){
	global $page;
	include_jquery();
	$page->add_library(ROOT_HTTP_CORE."/core/html/lorumipsum.js");
	$page->add_inline_script("lorumipsum_start('".ROOT_HTTP_CORE."','".CFG_EXTENSION."');");
}

class form{
	
	var $field_groups;
	var $submit_msg;
	var $submit_bool=true;
	var $class;
	var $action;
	var $method;
	var $hidden_fields;
	var $booleans;
	var $dates=array();
	var $buttons="";
	var $tag="form";
	var $onsubmit="";
	var $fileUpload=false;
	
	function __construct($cmd,$target="?",$submit_msg=null,$class=null){
		$this->method=(USER_ADMIN?"get":"post");
		$this->field_groups=array();
		$this->submit_msg=$submit_msg;
		$this->class=$class;
		$this->action=$target;
		$this->hidden_fields=array();
		if ($cmd){
			$this->add_hidden("cmd", $cmd);
		}
		$this->booleans=array();
	}
	
	function add_hidden($name,$value){
		$this->hidden_fields[]=new form_field($name,null,$value);
	}
	
	function set_blind(){
		$this->tag="div";
		$this->class="form ".($this->class);
		$this->submit_bool=false;
	}
	
	/**
	 * Eingabefeld zur letzten Gruppe hinzufügen
	 */
	function add_field($field){
		if (!$this->field_groups){ $this->field_groups[]=new form_group(); }
		$this->field_groups[count($this->field_groups)-1]->fields[]=$field;
		if ($field->type=='CHECKBOX'){
			$this->booleans[]=$field->name;
		}
		if ($field->type=='DATUM'){
			$this->dates[]=$field->name;
		}
		if ($field->type=='FILE'){
			$this->fileUpload=true;
			$this->method="post";
		}
	}
	
	/**
	 * Eine neue Gruppe erzeugen
	 */
	function add_fields($title,$fields){
		$this->field_groups[]=new form_group($title);
		if ($fields){
			foreach ($fields as $field) {
				$this->add_field($field);
			}
		}
	}
	
	function __toString(){ return $this->toHTML(); }
	function toHTML(){
		$form="";
		foreach ($this->field_groups as $g) {
			$group="";
			foreach ($g->fields as $field) {
				$group.="\n\t\t".$field->toHTML();
			}
			if ($g->title){
				$group="<fieldset><legend>$g->title</legend>$group\n\t</fieldset>";
			}
			$form.="\n\t".$group;
		}
		
		$submit_msg=($this->submit_msg?" value=\"".$this->submit_msg."\"":"");
		$submit=($this->submit_bool?"<input type=\"submit\" name=\"submit\"$submit_msg />":"");
		$buttons="\n\t<div class=\"form_buttons\"><label for=\"submit\"></label>$submit$this->buttons</div>";
		
		$class=($this->class?" class=\"".$this->class."\"":"");
		
		if($this->fileUpload){
			$this->onsubmit.=waitSpinner();
			$class.=" enctype=\"multipart/form-data\"";
		}

		$onsubmit=($this->onsubmit?" onsubmit=\"$this->onsubmit\"":"");
		
		$hidden_fields="";
		if ($this->hidden_fields){
			foreach ($this->hidden_fields as $field) {
				$id=($field->id?" id=\"".$field->id."\"":"");
				$hidden_fields.="\n\t<input$id type=\"hidden\" name=\"$field->name\" value=\"".escape_html($field->value)."\" />";
			}
		}
		if ($this->booleans){
			$booleans=implode(",", $this->booleans);
			$hidden_fields.="\n\t<input type=\"hidden\" name=\"booleans\" value=\"$booleans\" />";
		}
		if ($this->dates){
			$dates=implode(",", $this->dates);
			$hidden_fields.="\n\t<input type=\"hidden\" name=\"t_dates\" value=\"$dates\" />";
		}
		
		$action=($this->action?" action=\"$this->action\" method=\"$this->method\"":"");
		$html="\n<$this->tag$class$action$onsubmit>$hidden_fields$form$buttons\n</$this->tag>";
		return $html;
	}
	
}

class form_group{
	var $title;
	var $fields;
	function __construct($title=null){
		$this->title=$title;
		$this->fields=array();
	}
}

class form_field_info extends form_field{
	
	var $submit_value;

	function __construct($name,$label,$value,$title=null,$submit_value=null){
		parent::__construct($name,$label,$value,'TEXT',$title);
		$this->submit_value=$submit_value;
	}

	function toHTML(){
		$title=($this->title?" title=\"".encode_html($this->title)."\"":"");
		return "<div class=\"form_field info ".$this->outer_class."\"><label for=\"".$this->name."\"$title>".$this->label."</label><pre>$this->value</pre></div>"
				.($this->submit_value?"<input type=\"hidden\" name=\"$this->name\" value=\"$this->submit_value\">":"")
				;
	}

}

class form_field{
	
	var $label;
	var $name;
	var $type;
	var $value;
	var $title;
	var $options;
	var $id;
	
	var $maxlength=0;
	var $onChange=null;
	var $outer_id=null;
	var $outer_class="";
	var $accesskey=null;
	
	function __construct($name,$label=null,$value="[REQ]",$type="TEXT",$title=null,$options=null,$id=null){
		if ($label===null) $label=$name;
		$this->label=$label;
		$this->name=$name;
		$this->type=$type;
		if ($value=="[REQ]"){
			if ($type=="SELECT_MULTIPLE"){
				$id=preg_replace("/^(.*)\\[\\]$/", "$1", $name);
				if (isset($_REQUEST[$id])){
					$arr=$_REQUEST[$id];
					$value=array();
					foreach ($arr as $key) {
						$value[$key]=true;
					}
				}else{
					$value=null;
				}
			}else{
				$value=request_value($name,"");
			}
		}
		$this->value=$value;
		$this->title=$title;
		$this->options=$options;
		$this->id=$id;
	}
	
	function toHTML(){
		$input="";
		if ($this->type!="SELECT_MULTIPLE")
		$thisvalue=escape_html_unicode($this->value);
		$onChange=($this->onChange?" onChange=\"$this->onChange\"":"");
		$id=($this->id?" id=\"$this->id\"":"");
		$outer_id=($this->outer_id?" id=\"$this->outer_id\"":"");
		$maxlength=($this->maxlength?" maxlength=\"".$this->maxlength."\"":"");
		$outer_class=$this->outer_class;
		$label=$this->label;
		$accesskey=($this->accesskey?" accesskey=\"$this->accesskey\"":"");
		if($accesskey){
			$original_value=$label;
			$label=preg_replace("/^(.*?)([".strtolower($this->accesskey).strtoupper($this->accesskey)."])(.*)$/", "$1<u>$2</u>$3", $label);
			if($label==$original_value) $label.=" [$this->accesskey]";
		}
		if ($this->type=="CHECKBOX"){
			$outer_class.=" checkbox";
			$input=html_checkbox($this->name,$thisvalue,null,$this->id,$this->onChange);
		}else if ($this->type=="SELECT"||$this->type=="SELECT_MULTIPLE"){
			$options="";
			if($this->options)foreach ($this->options as $key=>$value) {
				if ($this->type=="SELECT"){
					$selected=($this->value==$key?" selected":"");
				}
				if ($this->type=="SELECT_MULTIPLE"){
					$selected=(isset($this->value[$key])?" selected":"");
				}
				$options.="\n\t<option$selected value=\"$key\">$value</option>";
			}
			$input="<select$id$onChange name=\"".$this->name."\"".($this->type=="SELECT_MULTIPLE"?" multiple":"")
					." class=\"chosen\""
					.">$options\n</select>";
		}else if ($this->type=="PASSWORD"){
			$input="<input$id type=\"password\" name=\"".$this->name."\" value=\"$thisvalue\" />";
		}else if ($this->type=="TEXTAREA"){
			$input="<textarea$id name=\"".$this->name."\">$thisvalue</textarea>";
		}else if ($this->type=="DATUM"){
			$id=($this->id?:get_next_id());
			datepicker($id);
			$input="<input$onChange id=\"$id\" type=\"text\" datum name=\"".$this->name."\" value=\"$thisvalue\" />";
		}else if ($this->type=="RADIO"){
			if($this->options){
				$input.="<ul class=\"radio\">";
				foreach ($this->options as $key=>$value) {
					$selected=($this->value==$key?" checked":"");
					$input.="\n<li><input$id$onChange type=\"radio\"$selected name=\"".$this->name."\" value=\"$key\" /><span class=\"label radio\">$value</span></li>";
				}
				$input.="</ul>";
			}
		}else if ($this->type=="RADIO+"){
			if($this->options){
				$input.="<ul class=\"radio\">";
				foreach ($this->options as $o) {
					$input.=$o->toHTML($this->name,$this->value);
				}
				$input.="</ul>";
			}
		}else if ($this->type=="FILE"){
			$input="<input type=\"file\" name=\"$this->name\" />";
		}else{
			$input="<input$id$onChange$maxlength$accesskey type=\"text\" name=\"".$this->name."\" value=\"$thisvalue\" />";
		}
		$title=($this->title?" title=\"".encode_html($this->title)."\"":"");
		return "<div class=\"form_field $outer_class $this->name\"$outer_id><label for=\"".$this->name."\"$title>".$label."</label>$input</div>";
	}
	
}

class form_radio_option{
	var $key;
	var $value;
	function __construct($key,$value){
		$this->key=$key;
		$this->value=$value;
	}
	function toHTML($name,$selected=null){
		$selected=($this->key==$selected?" checked":"");
		return "\n<li><input type=\"radio\"$selected name=\"$name\" value=\"$this->key\" /><span class=\"label radio\">$this->value</span></li>";
	}
}

class form_radio_option_ajax extends form_radio_option{
	var $id;
	var $placeholder;
	function __construct($id, $placeholder){
		$this->id=$id;
		$this->placeholder=$placeholder;
	}
	function toHTML($name,$selected=null){
		return "\n<div id=\"$this->id\"><li class=\"ajax\">$this->placeholder</li></div>";
	}
}

?>