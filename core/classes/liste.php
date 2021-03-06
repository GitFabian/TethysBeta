<?php

/*
include_once ROOT_HDD_CORE.'/core/classes/liste.php';
 */

class liste{
	
	var $name;
	var $next_html;
	var $next_url=null;
	var $prev_html;
	var $prev_url=null;
	
	function __construct($query,$id){
		$this->name=$query['name'];
		
		$ids=array();
		$labels=array();
		$liste=explode("\n", $query['liste']);
		$j=0;
		$id_i=-1;
		foreach ($liste as $l) {
			$i=strpos($l, ":");
			$key=substr($l, 0, $i);
			$value=substr($l, $i+1);
			$ids[$j]=$key;
			$labels[$j]=$value;
			if($id==$key)$id_i=$j;
			$j++;
		}
		
		if($id_i>0){
			$this->prev_html=$labels[($id_i-1)];
			$this->prev_url="?id=".$ids[($id_i-1)]."&tethys_liste=".$query['id'];
		}
		if($id_i!=-1&&$id_i<$j-1){
			$this->next_html=$labels[($id_i+1)];
			$this->next_url="?id=".$ids[($id_i+1)]."&tethys_liste=".$query['id'];
		}
	}
	
	function __toString(){
		if (!$this->prev_url && !$this->next_url) return "";
		$html="";
		if($this->prev_url){
			$html.="<a class=\"prev\" href=\"".$this->prev_url."\" title=\"".$this->prev_html." [P]\" accesskey=\"P\">".$this->prev_html."</a>";
		}
		$html.="<i class=\"name\">".$this->name."</i>";
		if($this->next_url){
			$html.="<a class=\"next\" href=\"".$this->next_url."\" title=\"".$this->next_html." [N]\" accesskey=\"N\">".$this->next_html."</a>";
		}
		$html="<div class=\"liste\">$html</div>";
		return $html;
	}

	static function load($id){
		if(!$id) return null;
		if(!isset($_REQUEST['id']))return null;
		$entry=$_REQUEST['id'];
		$query=dbio_SELECT_SINGLE("core_listen", $id);
		if(!$query)return null;
		return new liste($query,$entry);
	}
	
	static function save($title,$data,$haltbarkeit=86400/*=24h*/){
		$liste=array();
		foreach ($data as $key => $value) {
			$liste[]=$key.":".$value;
		}
		$liste=implode("\n", $liste);
		$expires=time()+$haltbarkeit;
		dbio_INSERT("core_listen", array(
			"name"=>$title,
			"expires"=>$expires,
			"liste"=>$liste,
		));
		return mysql_insert_id();
	}
}

?>