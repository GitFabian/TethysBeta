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
		if ($modul_id=='core'){include_once ROOT_HDD_CORE.'/core/classes/user.php';}
		foreach ($query as $row) {
			if ($modul_id=='core'){
				$card=get_user_setcard_CORE($row['id'],$row);
			}else{
				$card=$modules[$modul_id]->get_set_card($name, $row);
			}
			$set->add_card($card);
		}
		return $set;
	}
}

class set_card{
	var $header3;
	var $infotext;
	var $picture;
	var $picture_more="";
	var $data=array();
	var $buttons=array();
	var $edit;
	var $delete=true;
	var $details;
	function __construct($title,$infotext="",$picture="",$edit_db_=null,$details=null){
		$this->header3=$title;
		$this->infotext=$infotext;
		$this->picture=$picture;
		$this->edit=$edit_db_;
		$this->details=$details;
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
			$picture="\n\t\t<div class=\"picture_wrapper\"><img src=\"$this->picture\" class=\"picture\" />$this->picture_more</div>";
		}else{
			$picture="\n\t\t<div class=\"picture_wrapper leer\"></div>";
		}
		
		$infotext="\n\t\t\t<div class=\"infotext\">$this->infotext</div>";
		
		$buttons=$this->buttons;
		if ($this->edit){
			array_unshift($buttons,html_a_button("Bearbeiten", ROOT_HTTP_CORE."/core/edit.".CFG_EXTENSION."?".$this->edit));
			if($this->delete){
				include_once ROOT_HDD_CORE.'/core/alertify.php';
				$url=ROOT_HTTP_CORE."/core/edit.".CFG_EXTENSION."?cmd=delete&".$this->edit;
				$buttons[]=html_a_button("Löschen", "", "","ask_delete('$url','".html_to_plain($this->header3)."');");
			}
		}
		if ($this->details){array_unshift($buttons,html_a_button("Details", $this->details));}
		$buttons=($buttons?"\n<div class=\"buttons\">".(implode("", $buttons))."</div>":"");
		
		return "\n\t<div class=\"set_card\">$picture\n\t\t<div class=\"set_head\">$title$infotext\n\t\t</div>$data$buttons</div>";
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
	var $edit;
	function __construct($id,$title,$value,$edit=false){
		$this->id=$id;
		$this->title=$title;
		$this->value=$value;
		$this->edit=$edit;
	}
	function toHTML(){
		$edit=($this->edit?" a_edit":"");
		return "\n\t\t\t<li class=\"set_card_data $this->id\"><div class=\"title\">$this->title</div><div class=\"value val_$this->id$edit\">$this->value</div></li>";
	}
}

?>