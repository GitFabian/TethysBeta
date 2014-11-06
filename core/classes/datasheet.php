<?php

/*
include_once ROOT_HDD_CORE.'/core/classes/datasheet.php';
 */

class datasheet{
	
	var $data=array();
	var $edit=true;
	var $modul;
	var $db;
	var $id;
	
	function __construct($modul, $db, $id){
		$this->modul=$modul;
		$this->db=$db;
		$this->id=$id;
	}
	
	function add_data($data){
		$this->data[$data->idname]=$data;
	}
	
	function __toString(){ return $this->toHTML(); }
	function toHTML(){
		include_once ROOT_HDD_CORE.'/core/edit_rights.php'; 
		$data=array();
		foreach ($this->data as $d) {
			$data[]=$d->toHTML();
		}
		$data=implode("", $data);
		
		$buttons=array();
		$edit=($this->edit&&edit_rights($this->modul, $this->db, $this->id));
		if ($edit) $buttons[]=html_a_button("Bearbeiten",ROOT_HTTP_CORE."/core/edit.".CFG_EXTENSION."?db=".$this->db."&id=".$this->id);
		$btn_html=($buttons?"\n<div class=\"ds_btns\">\n\t".implode("\n\t", $buttons)."\n</div>":"");
		
		return "\n<div class=\"datasheet $this->modul $this->db\">\n<ul class=\"datasheet\">$data\n</ul>$btn_html\n</div>";
	}
	
	static function from_db($modul, $db, $id, $query=null, $idkey='id'){
		global $modules;
		include_once ROOT_HDD_CORE.'/core/classes/form.php';
		if ($query===null) $query=dbio_SELECT_SINGLE($db, $id);
		
		$datasheet=new datasheet($modul, $db, $id);
		
		$form=new form(null,null,null,"datasheet");
		$ok=$modules[$modul]->get_edit_form($form, $db, $id, $query);
		if ($ok===false){
			include_once ROOT_HDD_CORE.'/core/edit_.php';
			edit_default_form($form,$query,$db,$idkey);
		}
		
		foreach ($form->field_groups as $g) {
			foreach ($g->fields as $field) {
				$datasheet->add_data(new datasheet_data($field->name,$field->label, $field->value));
			}
		}
		
		return $datasheet;	
	}
}

class datasheet_data{
	var $idname;
	var $label;
	var $value;
	function __construct($idname, $label, $value){
		$this->idname=$idname;
		$this->label=$label;
		$this->value=$value;
	}
	function toHTML(){
		return "\n\t<li><div class=\"data_label $this->idname\">$this->label</div><div class=\"data_value\">$this->value</div></li>";
	}
}

?>