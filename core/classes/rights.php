<?php

/*
include_once ROOT_HDD_CORE.'/core/classes/rights.php';
 */

class right{
	var $name;
	var $description;
	function __construct($name,$beschreibung){
		$this->name=$name;
		$this->description=$beschreibung;
	}
}

?>