<?php

/*
include_once ROOT_HDD_CORE.'/core/classes/gantt.php';

Tethys Gantt-Chart
==================

$gantt=new gantt();
$start=time()-302400;
$end=time()+302400;
$gantt->add_project(new gantt_project("My Project", $start, $end, 0.5));
$page->say($gantt);

 */

class gantt{
	
	var $data;
	var $size_factor;
	var $start;
	var $end;
	var $cols;

	/**
	 * @param string $cols = ["KW"|"DAY"]
	 */
	function __construct($size_factor=0.0001654/*1 Woche=100 px*/,$cols="KW"){
		$this->data=array();
		$this->size_factor=$size_factor;
		$this->start=strtotime(date("Y-m-d"));
		$this->end=$this->start+86400;
		$this->cols=$cols;
	}
	
	function __toString(){
		global $page;
		$html="";
		$head="<br>";
		$begin=strtotime("Monday this week",$this->start-86400);
		
		//Überschrift
		if($this->cols=="KW"){
			$width=floor(604800/*1 Woche*/*$this->size_factor);
			$start=$begin;
			while($start<$this->end){
				$year=date("M'y",$start+302400/*Mitte der Woche*/);
				$kw=date("W",$start+302400/*Mitte der Woche*/);
				$left=floor(($start-$begin)*$this->size_factor);
				$html.="<div class=\"gantt_head\" style=\"width:{$width}px;left:{$left}px;\"><span class=\"gantt_head_text\">$year, KW$kw</span></div>";
				$start+=604800/*1 Woche*/;
			}
		}
		if($this->cols=="DAY"){
// 			$begin-=86400;
			$monate=array("Januar","Februar","März","April","Mai","Juni","Juli","August","September","Oktober","November","Dezember");
			$head.="<br>";
			$width=floor(86400/*1 Tag*/*$this->size_factor);
			$start=$begin;
			$monat="";
			while($start<$this->end){
				$left=floor(($start-$begin)*$this->size_factor);
				$day=date("j",$start+1800/*Mitte des Tages*/);
				$mon=$monate[date("n",$start+1800/*Mitte des Tages*/)-1]."&nbsp;".date("Y",$start+1800/*Mitte des Tages*/);
				if($mon!=$monat){ $monat=$mon; }else{ $mon=""; }
				$html.="<div class=\"gantt_head\" style=\"width:{$width}px;left:{$left}px;\">"
						."<span class=\"gantt_head_text\">$mon<br>$day</span>"
					."</div>";
				$start+=86400/*1 Tag*/;
			}
			$html.="<br>";
		}
		$html.="<br>";
		
		foreach ($this->data as $project) {
			$width=floor(($project->end-$project->begin)*$this->size_factor);
			$left=floor((($project->begin-$begin)*$this->size_factor));
			$head.="<div class=\"gantt_title\">".$project->title."</div>";
			
			if($project->progress===null){
				$progress="";
			}else{
				$progress="<div class=\"gantt_progress\" style=\"width:".(floor($project->progress*100))."%\"></div>";
			}
			
			$html.="<div class=\"gantt_bar\" style=\"width:".$width."px;left:".$left."px;\">$progress</div>";
			$html.="<br>";
		}
		
		$heute_morgen=strtotime(date("Y-m-d"));
		$left=floor(($heute_morgen-$begin)*$this->size_factor);
		
		$id=get_next_id();
		$page->onload_JS.="document.getElementById('$id').scrollLeft=$left;";
		
		$width=86400*$this->size_factor;
		$html.="<div class=\"gantt_today\" style=\"left:{$left}px;width:{$width}px;\"></div>";
		
		$head="<div class=\"gantt_legend\">$head</div>";
		$html="<div class=\"gantt_chart\" id=\"$id\">$html</div>";
		return $head.$html;
	}
	
	function add_project($project){
		$this->data[]=$project;
		if($project->end>$this->end)$this->end=$project->end;
		if($project->begin<$this->start)$this->start=$project->begin;
	}
	
}

class gantt_project{
	var $title;
	var $begin;
	var $end;
	var $progress;//Normiert!
	function __construct($title,$begin,$end,$progress=null){
		$this->title=$title;
		$this->begin=$begin;
		$this->end=$end;
		$this->progress=$progress;		
	}
}

?>