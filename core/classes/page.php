<?php

class page{
	
	var $title;
	var $content;
	var $page_id;
	var $stylesheets;
	var $inline_JS;
	var $onload_JS;
	var $views;
	
	function __construct(){
		$this->content="";
		$this->stylesheets=array();
		$this->inline_JS="";
		$this->onload_JS="";
		$this->views=array();
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
		
		$onload=($this->onload_JS?" onload=\"$this->onload_JS\"":"");
		
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
	
	function add_html($html){
		$this->content.=$html;
	}

	function add_div($html,$pars=""){
		$this->content.="<div $pars>$html</div>";
	}

	function add_header1($html){
		$this->content.="<h1>$html</h1>";
	}
	
	function add_stylesheet($url,$media=null){
		$this->stylesheets[$url]=$media;
	}
	
	function add_inline_script($skript){
		$this->inline_JS.=$skript;
	}
	
}

?>