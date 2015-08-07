<?php

/*
include_once ROOT_HDD_CORE.'/core/classes/datasheet.php';
 */

class datasheet{
	
	var $data=array();
	var $edit=true;
	var $delete=true;
	var $modul;
	var $db;
	var $id;
	var $datensatz;
	var $buttons=array();
	
	function __construct($modul, $db, $id, $datensatz=null){
		$this->modul=$modul;
		$this->db=$db;
		$this->id=$id;
		$this->datensatz=$datensatz;
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
		
		$buttons=$this->buttons;
		$edit=($this->edit&&edit_rights($this->modul, $this->db, $this->id));
		$datensatz=($this->datensatz?"&datensatz=".$this->datensatz:"");
		if ($edit) $buttons[]=html_a_button("Bearbeiten",ROOT_HTTP_CORE."/core/edit.".CFG_EXTENSION."?db=".$this->db."&id=".$this->id.$datensatz);
		$delete=($edit&&$this->delete);
		if ($delete){
			include_once ROOT_HDD_CORE.'/core/alertify.php';
			$url=ROOT_HTTP_CORE."/core/edit.".CFG_EXTENSION."?cmd=delete&db=$this->db&id=".$this->id;
			$buttons[]=html_a_button("Löschen", "", "","ask_delete('$url','$this->datensatz');");
		}
		$btn_html=($buttons?"\n<div class=\"ds_btns\">\n\t".implode("\n\t", $buttons)."\n</div>":"");
		
		return "\n<div class=\"datasheet $this->modul $this->db\">\n<ul class=\"datasheet\">$data\n</ul>$btn_html\n</div>";
	}
	
	/**
	 * DEFAULT DATASHEET PAGE:
	 * <code>
function crungely_detail($id){
	include_once ROOT_HDD_CORE.'/core/classes/datasheet.php';
	global $page;
	
	$query=dbio_SELECT_SINGLE("crungely_flobble",$id);
	
	$datasheet=datasheet::from_db("crungely", "crungely_flobble", $id);

	$page->say(html_header1($query["razzle"]));
	$page->say($datasheet);
	
	include_once ROOT_HDD_CORE.'/core/log.php';
	$logs=logs_for_entity("crungely_flobble", $id);
	$page->say($logs);
	
	page_send_exit();
}
	 * </code>
	 */
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
		$divclass=(setting_get(null, 'DEPRECATED_DSDLCLASS')?" $this->idname":"");
		return "\n\t<li class=\"$this->idname\"><div class=\"data_label$divclass\">$this->label</div><div class=\"data_value\">$this->value</div></li>";
	}
}

?>