<?php

/*
include_once CFG_HDDROOT.'/core/classes/rights.php';
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