<?php

/*
include_once ROOT_HDD_CORE.'/core/classes/form.php';

http://tethys-framework.de/wiki/?title=Formular
bzw. http://localhost/tethys/demo/modules/demo/formular.php
 */

class form{
	
	var $field_groups;
	var $submit_msg;
	var $class;
	var $action;
	var $method;
	var $hidden_fields;
	var $booleans;
	var $buttons="";
	
	function __construct($cmd,$target=null,$submit_msg=null,$class=null){
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
	
	/**
	 * Eingabefeld zur letzten Gruppe hinzufügen
	 */
	function add_field($field){
		if (!$this->field_groups){ $this->field_groups[]=new form_group(); }
		$this->field_groups[count($this->field_groups)-1]->fields[]=$field;
		if ($field->type=='CHECKBOX'){
			$this->booleans[]=$field->name;
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
		$buttons="\n\t<div class=\"form_buttons\"><label for=\"submit\"></label><input type=\"submit\" name=\"submit\"$submit_msg />$this->buttons</div>";
		
		$class=($this->class?" class=\"".$this->class."\"":"");
		
		$hidden_fields="";
		if ($this->hidden_fields){
			foreach ($this->hidden_fields as $field) {
				$hidden_fields.="\n\t<input type=\"hidden\" name=\"$field->name\" value=\"".escape_html($field->value)."\" />";
			}
		}
		if ($this->booleans){
			$booleans=implode(",", $this->booleans);
			$hidden_fields.="\n\t<input type=\"hidden\" name=\"booleans\" value=\"$booleans\" />";
		}
		
		$html="\n<form$class action=\"$this->action\" method=\"$this->method\">$hidden_fields$form$buttons\n</form>";
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

	function __construct($name,$label,$value,$title=null){
		parent::__construct($name,$label,$value,'TEXT',$title);
	}

	function toHTML(){
		$title=($this->title?" title=\"".encode_html($this->title)."\"":"");
		return "<div class=\"form_field\"><label for=\"".$this->name."\"$title>".$this->label."</label>$this->value</div>";
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

	var $onChange=null;
	var $outer_id=null;
	
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
		$thisvalue=escape_html($this->value);
		$onChange=($this->onChange?" onChange=\"$this->onChange\"":"");
		$id=($this->id?" id=\"$this->id\"":"");
		$outer_id=($this->outer_id?" id=\"$this->outer_id\"":"");
		if ($this->type=="CHECKBOX"){
			$input=html_checkbox($this->name,$thisvalue);
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
			$input="<select$id$onChange name=\"".$this->name."\"".($this->type=="SELECT_MULTIPLE"?" multiple":"").""
					.($this->type=="SELECT_MULTIPLE"?" class=\"chosen\"":"")
					.">$options\n</select>";
		}else if ($this->type=="PASSWORD"){
			$input="<input$id type=\"password\" name=\"".$this->name."\" value=\"$thisvalue\" />";
		}else if ($this->type=="TEXTAREA"){
			$input="<textarea$id name=\"".$this->name."\">$thisvalue</textarea>";
		}else{
			$input="<input$id type=\"text\" name=\"".$this->name."\" value=\"$thisvalue\" />";
		}
		$title=($this->title?" title=\"".encode_html($this->title)."\"":"");
		return "<div class=\"form_field\"$outer_id><label for=\"".$this->name."\"$title>".$this->label."</label>$input</div>";
	}
	
}

?>