<?php

/*
include_once CFG_HDDROOT.'/core/classes/form.php';

http://217.91.49.199/tethyswiki/index.php/Formular
 */

class form{
	
	var $field_groups;
	var $submit_msg;
	var $class;
	var $action;
	var $method;
	var $hidden_fields;
	var $booleans;
	
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
		$buttons="\n\t<div class=\"form_buttons\"><label for=\"submit\"></label><input type=\"submit\" name=\"submit\"$submit_msg /></div>";
		
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

class form_field{
	
	var $label;
	var $name;
	var $type;
	var $value;
	var $title;
	
	function __construct($name,$label=null,$value="",$type="TEXT",$title=null){
		if ($label===null) $label=$name;
		$this->label=$label;
		$this->name=$name;
		$this->type=$type;
		$this->value=$value;
		$this->title=$title;
	}
	
	function toHTML(){
		$input="";
		$value=escape_html($this->value);
		if ($this->type=="CHECKBOX"){
			$checked=($value?" checked":"");
			$input="<input type=\"checkbox\" name=\"".$this->name."\"$checked /><div class=\"checkbox_ghost\"></div>";
		}else{
			$input="<input type=\"text\" name=\"".$this->name."\" value=\"$value\" />";
		}
		$title=($this->title?" title=\"".htmlentities($this->title)."\"":"");
		return "<div class=\"form_field\"><label for=\"".$this->name."\"$title>".$this->label."</label>$input</div>";
	}
	
}

?>