<?php
/*
include_once ROOT_HDD_CORE.'/core/classes/page_hint.php';
 */
class page_hint{
	var $desc;
	var $request_key;
	public function __construct($desc,$request_key=null){
		$this->desc=$desc;
		$this->request_key=$request_key;
	}
}