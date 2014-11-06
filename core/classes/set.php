<?php

/*
include_once ROOT_HDD_CORE.'/core/classes/set.php';
 */

class set{
	var $cards=array();
	var $class;
	function __construct($class){
		$this->class=$class;
	}
	function __toString(){ return $this->toHTML(); }
	function toHTML(){
		$cards=array();
		foreach ($this->cards as $card) {
			$cards[]=$card->toHTML();
		}
		$cards=implode("", $cards);
		return "\n<div class=\"t_set $this->class\">$cards\n</div>";
	}
	function add_card($card){
		$this->cards[]=$card;
	}
	static function from_db($modul_id,$name,$query){
		$set=new set($modul_id." ".$name);
		global $modules;
		foreach ($query as $row) {
			$card=$modules[$modul_id]->get_set_card($name, $row);
			$set->add_card($card);
		}
		return $set;
	}
}

class set_card{
	var $header3;
	var $infotext;
	var $picture;
	var $data=array();
	var $buttons=array();
	function __construct($title,$infotext="",$picture=""){
		$this->header3=$title;
		$this->infotext=$infotext;
		$this->picture=$picture;
	}
	function add_data($data){
		$this->data[]=$data;
	}
	function toHTML(){
		$data=array();
		foreach ($this->data as $d) {
			$data[]=$d->toHTML();
		}
		$data=implode("",$data);
		$data="\n\t\t<ul class=\"data\">$data\n\t\t</ul>";
		
		$title="\n\t\t\t<h3>$this->header3</h3>";

		if ($this->picture){
			$picture="\n\t\t<div class=\"picture_wrapper\"><img src=\"$this->picture\" /></div>";
		}else{
			$picture="\n\t\t<div class=\"picture_wrapper leer\"></div>";
		}
		
		$infotext="\n\t\t\t<div class=\"infotext\">$this->infotext</div>";
		
		return "\n\t<div class=\"set_card\">$picture\n\t\t<div class=\"set_head\">$title$infotext\n\t\t</div>$data</div>";
	}
	static function get_default($name,$data){
		$card=new set_card($name);
		foreach ($data as $key => $value) {
			$card->add_data(new set_card_data($key, $key, $value));
		}
		return $card;
	}
}

class set_card_data{
	var $id;
	var $title;
	var $value;
	function __construct($id,$title,$value){
		$this->id=$id;
		$this->title=$title;
		$this->value=$value;
	}
	function toHTML(){
		return "\n\t\t\t<li class=\"set_card_data\"><div class=\"title\">$this->title</div><div class=\"value\">$this->value</div></li>";
	}
}

?>