<?php

/*
include_once ROOT_HDD_CORE.'/core/classes/set.php';
 */

class set{
	var $header2;
	var $fields=array();
	function __construct($header2=null){
		$this->header2=$header2;
	}
	function add_field($field){
		$this->fields[]=$field;
	}
	function toHTML(){
		$header=($this->header2?html_header2($this->header2):"");
			$fields=array();
		foreach ($this->fields as $field) {
			$fields[]=$field->toHTML();
		}
		$fields=implode("", $fields);
		return "$header<ul class=\"set\">$fields</ul>";
	}

	static function from_db($db,$id){
		$set=new set();
		$query=dbio_SELECT_SINGLE($db, $id);
		foreach ($query as $key => $value) {
			$set->add_field(new set_field($key, $value));
		}
		return $set;
	}
}

class set_table extends set{
	var $table;
	function __construct($header2,$table){
		parent::__construct($header2);
		$this->table=$table;
	}
	function toHTML(){
		$header=($this->header2?html_header2($this->header2):"");
		$table=$this->table->toHTML();
		return "<div class=\"settable\">$header$table</div>";
	}
}

class set_field{
	var $name;
	var $value;
	/** ['txt',''] */
	var $type='txt';
	var $html=false;
	function __construct($name,$value){
		$this->name=$name;
		$this->value=$value;
	}
	function toHTML(){
		return "<li class=\"setfield\">$this->name = $this->value</li>";
	}
}

class set_card{
	var $extended=true;
	var $header1;
	var $sets=array();
	function __construct($title){
		$this->header1=$title;
	}
	function add_set($set){
		$this->sets[]=$set;
	}
	function __toString(){ return $this->toHTML(); }
	function toHTML(){
		$header=html_header1($this->header1);
		$sets=array();
		foreach ($this->sets as $set) {
			$sets[]=$set->toHTML();
		}
		$sets=implode("", $sets);
		return "<div class=\"setcard\">$header$sets</div>";
	}
}

?>