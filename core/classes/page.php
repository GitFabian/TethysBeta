<?php

class page{
	
	var $title;
	var $content;
	var $page_id;
	var $stylesheets;
	var $inline_JS;
	
	function __construct(){
		$this->content="";
		$this->stylesheets=array();
		$this->inline_JS="";
	}
	
	function send(){
		
		$content=$this->content;
		$title=$this->title;
		$menu=hauptmenue($this->page_id);
		$checkContent=($content?"":" empty");
		
		$stylesheets="";
		foreach ($this->stylesheets as $url => $media) {
			$mediahtml=($media?" media=\"$media\"":"");
			$stylesheets.="<link href=\"$url\" rel=\"stylesheet\" type=\"text/css\"$mediahtml />\n";
		}
		
		$inline_JS=($this->inline_JS?"<script type=\"text/javascript\">".$this->inline_JS."</script>":"");
		
		echo <<<ENDE
<!DOCTYPE HTML>
<html>
<head>
	<title>$title</title>
	$stylesheets
	$inline_JS
</head>
<body id="$this->page_id">
	<div class="outerbody">
		<div class="mainmenu">
			$menu
		</div>
		<div class="innerbody$checkContent">
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
	
	function add_inline_script($skript){
		$this->inline_JS.=$skript;
	}
	
}

?>