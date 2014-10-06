<?php

class page{
	
	var $title;
	var $content;
	
	function __construct(){
		$content="";
	}
	
	function send(){
		
		$content=$this->content;
		$title=$this->title;
		
		echo <<<ENDE
<!DOCTYPE HTML>
<html>
<head>
	<title>$title</title>
</head>
<body>
	$content
</body>
</html>
ENDE;
	}
	
	function init($page_id,$page_title){
		$this->title=CFG_TITLE.' - '.$page_title;
	}
	
	function add_html($html){
		$this->content.=$html;
	}
	
}

?>