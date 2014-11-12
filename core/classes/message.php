<?php

/*
include_once ROOT_HDD_CORE.'/core/classes/message.php';
 */

class message{
	var $type;
	var $text;
	function __construct($text,$type='ok'){
		$this->type=$type;
		$this->text=$text;
	}
	function toHTML(){
		return "<div class=\"message $this->type\">".$this->text."</div>";
	}
}

?>