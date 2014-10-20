<?php

class page{
	
	var $title=null;
	var $content;
	var $page_id;
	var $stylesheets;
	var $libraries;
	var $inline_JS;
	var $onload_JS;
	var $views;
	var $focus;
	var $focus_delay=500;//ms
	
	function __construct(){
		$this->content="";
		$this->stylesheets=array();
		$this->add_stylesheet(ROOT_HTTP_CORE."/core/html/core.css");
		$this->libraries=array();
		$this->add_library(ROOT_HTTP_CORE."/core/html/toolbox.js");
		$this->inline_JS="";
		$this->onload_JS="";
		$this->views=array();
		$this->focus=null;
	}
	
	function send(){
		
		if ($this->title===null && USER_ADMIN) echo"Seite nicht initialisiert!";
		
		$content=$this->content;
		$title=$this->title;
		$menu=hauptmenue($this->page_id);
		$checkContent=($content?"":" empty");
		
		$stylesheets="";
		foreach ($this->stylesheets as $url => $media) {
			$mediahtml=($media?" media=\"$media\"":"");
			$stylesheets.="\n\t<link href=\"$url\" rel=\"stylesheet\" type=\"text/css\"$mediahtml />";
		}
		
		$libraries="";
		foreach ($this->libraries as $url=>$dummy) {
			$libraries.="\n\t<script type=\"text/javascript\" src=\"$url\"></script>";
		}
		
		$inline_JS=$this->inline_JS;
		$inline_JS=($inline_JS?"<script type=\"text/javascript\">".$inline_JS."\n\t</script>":"");
		
		$onload=$this->onload_JS;
		if ($this->focus){
			include_jquery();
			$onload.="window.setTimeout(&quot;$('$this->focus').first().focus();&quot;,$this->focus_delay);";
		}
		$onload=($onload?" onload=\"$onload\"":"");
		
		$dynamic_css_development=(CFG_CSS_VERSION?"<div class=\"css_version_".CFG_CSS_VERSION."\">CSS nicht aktuell!</div>":"");
		
		$devel_zeitmessung=(USER_ADMIN?$this::get_performance():"");
		
		$views="";
		if ($this->views){
			foreach ($this->views as $view) {
				$views.="\n\t<li>".$view->toHTML()."</li>";
			}
			$views="\n<ul class=\"views_menu\">$views\n</ul>";
		}
		
		echo <<<ENDE
<!DOCTYPE HTML>
<html>
<head>
	<title>$title</title>
	$stylesheets
	$libraries
	$inline_JS
</head>
<body id="$this->page_id"$onload>
	<div class="outerbody">
		$dynamic_css_development
		<div class="mainmenu">
			$menu
		</div>
		$views
		<div class="innerbody$checkContent">
			$content
		</div>
	</div>
	$devel_zeitmessung
</body>
</html>
ENDE;
	}

	/**
	 *<code> 

$view=$page->init_views('xxxxxDEFAULTIDxxxxxxx',array(
	new menu_topic2('xxxxxxIDxxxxxxxx', "xxxxxxxxLABELxxxxxxx"),
));

	 *</code> 
	 */
	function init_views($default,$views){
		$view=(isset($_REQUEST['view'])&&$_REQUEST['view']?$_REQUEST['view']:$default);
		foreach ($views as $topic) {
			$topic->highlight=($view==$topic->page_id);
			if(!$topic->link)$topic->link="?view=".$topic->page_id;
			$topic->class_a="button";
			$this->views[]=$topic;
		}
		return $view;
	}
	
	static function get_performance(){
		global $devel_zeitmessung_start,$devel_performance_query_counter;
		$zeitmessung_ende=microtime(true);
		$dauer=$zeitmessung_ende-$devel_zeitmessung_start;
		if ($dauer<1){
			$dauer=round($dauer*1000000)/1000;
			$dauer=$dauer." ms";
		}else{
			$dauer=round($dauer*1000)/1000;
			$dauer=$dauer." s";
		}
		
		$queries=$devel_performance_query_counter;
		$queries=$queries." queries";
		
		$html=$dauer."<br>".$queries;
		return "<div class=\"devel_performance\">$html</div>";
	}
	
	function init($page_id,$page_title){
		$this->title=CFG_TITLE.' - '.$page_title;
		$this->page_id=$page_id;
	}
	
	/**
	 * DEPRECATED! Use $page->say instead!
	 */
	function add_html($html){
		$this->content.=$html;
	}
	function say($html){
		$this->content.=$html;
	}
	
	function add_stylesheet($url,$media=null){
		$this->stylesheets[$url]=$media;
	}

	function add_library($url){
		$ok=!isset($this->libraries[$url]);
		$this->libraries[$url]=true;
		return $ok;
	}
	
	function add_inline_script($skript){
		$this->inline_JS.="\n".$skript;
	}
	
}

?>