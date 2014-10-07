<?php

class page{
	
	var $title;
	var $content;
	var $page_id;
	var $stylesheets;
	
	function __construct(){
		$this->content="";
		$this->stylesheets=array();
	}
	
	function send(){
		
		$content=$this->content;
		$title=$this->title;
		$menu=hauptmenue($this->page_id);
		
		$stylesheets="";
		foreach ($this->stylesheets as $url => $media) {
			$mediahtml=($media?" media=\"$media\"":"");
			$stylesheets.="<link href=\"$url\" rel=\"stylesheet\" type=\"text/css\"$mediahtml />\n";
		}
		
		echo <<<ENDE
<!DOCTYPE HTML>
<html>
<head>
	<title>$title</title>
	$stylesheets
</head>
<body>
	<div class="outerbody">
		<div class="mainmenu">
			$menu
		</div>
		<div class="innerbody">
				$content
		</div>
	</div>
</body>
</html>
ENDE;
	}
	
	function init($page_id,$page_title){
		$this->title=CFG_TITLE.' - '.$page_title;
		$this->page_id=$page_id;
	}
	
	function add_html($html){
		$this->content.=$html;
	}
	
	function add_stylesheet($url,$media=null){
		$this->stylesheets[$url]=$media;
	}
	
}

?>