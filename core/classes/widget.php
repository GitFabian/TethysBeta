<?php

/*
include_once ROOT_HDD_CORE.'/core/classes/widget.php';
 */

class widget{
	
	var $name_id;
	var $name_full;
	var $pos_left=300;
	var $pos_top=300;
	
	function __construct($name_id,$name_full){
		$this->name_id=$name_id;
		$this->name_full=$name_full;
	}
	
	function getContent(){
		return USER_ADMIN?"(Nicht implementiert)":"-/-";
	}
	
	function __toString(){
		$html=html_div($this->name_full,"widget_header")
			.html_div($this->getContent(),"widget_body");
		$html=html_div($html,"widget",null,"left:".$this->pos_left."px;top:".$this->pos_top."px;");
		return $html;
	}
	
}

?>