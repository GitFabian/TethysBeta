<?php

/*
include_once ROOT_HDD_CORE.'/core/classes/rights.php';
 */

class right{
	var $name;
	var $description;
	var $modul;
	function __construct($name,$beschreibung,$modul=null){
		$this->name=$name;
		$this->description=$beschreibung;
		$this->modul=$modul;
	}
}

?>