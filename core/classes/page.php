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
	var $focus_downward_compatibility=false;
	var $messages=array();
	var $head="";
	var $waitSpinner=false;
	var $wap=false;
	var $bodyclass="";
	var $hints=array();
	
	function __construct(){
		$this->content="";
		$this->stylesheets=array();
		$this->add_stylesheet(ROOT_HTTP_CORE."/core/html/core.css");
		$this->libraries=array();
		$this->add_library(ROOT_HTTP_CORE."/core/html/toolbox.js");
		$this->inline_JS="";
		$this->onload_JS="";
		$this->focus=null;
	}
	
	function add_hint($desc,$request_key=null){
		include_once ROOT_HDD_CORE.'/core/classes/page_hint.php';
		$this->hints[]=new page_hint($desc,$request_key);
	}
	
	function send(){
		if ($this->title===null && USER_ADMIN) echo"Seite nicht initialisiert!";
		
		$favicon="";
		if(file_exists(ROOT_HDD_DATA."/core/favicon.gif")){
			$favicon="<link rel=\"icon\" href=\"".ROOT_HTTP_DATA."/core/favicon.gif\" type=\"image/gif\" />";
		}
		
		$content=$this->content;
		$title=$this->title;
		$menu=hauptmenue($this->page_id);
		$checkContent=($content&&$this->page_id!="core_index"?"":" empty");
		
		$stylesheets="";
		if(setting_get(null, "CFG_CSS_BLENDER") && ( !USER_ADMIN||!setting_get_user(null, "DEBUGMODE") ) ){
			include_once ROOT_HDD_CORE.'/core/css_blender_.php';
			$stylesheets=css_blender($this->stylesheets);
		}else{
			foreach ($this->stylesheets as $url => $media) {
				$mediahtml=($media?" media=\"$media\"":"");
				$stylesheets.="\n\t<link href=\"$url\" rel=\"stylesheet\" type=\"text/css\"$mediahtml />";
			}
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
			$delay=$this->focus_delay;
			if(USER_ADMIN&&isset($_REQUEST['tethys_focus_delay']))$delay=$_REQUEST['tethys_focus_delay'];
			$downward=($this->focus_downward_compatibility?".first()":"");
			$onload.="window.setTimeout(&quot;$('$this->focus')$downward.focus();&quot;,$delay);";
		}
		$onload=($onload?" onload=\"$onload\"":"");
		
		if (CFG_CSS_VERSION){
			include_once ROOT_HDD_CORE.'/core/classes/message.php';
			$this->messages[]=new message("CSS nicht aktuell! ".html_button("Neu laden",null,"location.reload();"),"error css_version v".CFG_CSS_VERSION);
		}
		
		$devel_zeitmessung=(setting_get_user(null,"DEBUGMODE")?$this::get_performance():"");
		
		$views="";
		if ($this->views){
			foreach ($this->views as $view) {
				$views.="\n\t<li".($view->highlight?" class=\"view_highlight\"":"").">".$view->toHTML()."</li>";
			}
			$dev=(USER_ADMIN&&CFG_SKIN=='terminal'?" onclick=\"style.display='none';".js_runLater("style.display='block';", 10)."\"":"");
			$views="\n<ul$dev class=\"views_menu\">$views\n</ul>";
		}
		
		$bodyclass=array();
		$mm_class="";
		if (setting_get(null, 'HM_ICONS')) $mm_class.=" icons";
		if (!setting_get(null, 'HM_TEXT')) $mm_class.=" notext";
		if (setting_get_user(null, "CMPCTVIEW")){
			$bodyclass[]="cmpctview";
			$mm_class.=" cmpctview";
		}
		if($this->bodyclass)$bodyclass[]=$this->bodyclass;
		if($bodyclass)$bodyclass=" class=\"".implode(" ", $bodyclass)."\"";else $bodyclass="";
		
		$menu=($menu?"<div class=\"mainmenu$mm_class\">
				$menu
				<div class=\"mainmenu_footer\"></div>
			</div>":"");
		
		$messages=array();
		foreach ($this->messages as $message) {
			$messages[]=$message->toHTML();
		}
		if ($messages){
			$messages=implode("", $messages);
			$messages="<div class=\"messages\">$messages</div>";
		}else{
			$messages="";
		}
		
		$liste="";
		if(isset($_REQUEST['tethys_liste'])){
			include_once ROOT_HDD_CORE.'/core/classes/liste.php';
			$liste=liste::load($_REQUEST['tethys_liste']);
		}
		
		$waitSpinner="";
		if($this->waitSpinner){
			$waitSpinner="<div id=\"uploadSpinner\"><div class=\"spinnerContent\"><img src=\"".CFG_SKINPATH."/spinner.gif\"><div>Bitte warten...</div></div></div>";
		}
		
		$wap=$this->wap?" PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\" \"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\"":"";
		
		$hints="";
		if($this->hints){
			foreach ($this->hints as $hint) {
				if($hint instanceof page_hint){
					$hints.="<div class=\"hint\">";
						$hints.="<b class='hint_key'>".$hint->request_key."</b>";
						$hints.="<span class='hint_desc'>".$hint->desc."</span>";
					$hints.="</div>";
				}
			}
			$hints="<div class=\"hints\">$hints</div>";
		}
		
		echo <<<ENDE
<!DOCTYPE HTML$wap>
<html>
<head>
	<title>$title</title>
	$stylesheets
	$libraries
	$inline_JS
	$this->head
	$favicon
</head>
<body id="$this->page_id"$onload$bodyclass>
	$waitSpinner
	<div class="outerbody">
		$menu
		$liste
		$views
		<div class="innerbody$checkContent">
			$messages
			$content
		</div>
		$hints
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

- oder -

$views=array(
	new menu_topic2("xxxxxxIDxxxxxxxx", "xxxxxxxxLABELxxxxxxx"),
);
$view=$page->init_views(setting_get_user('xxxMODULxxx','USRSET_xxxxx'),$views);
setting_save('xxxMODULxxx', 'USRSET_xxxxx', $view, true);

	 *</code> 
	 */
	function init_views($default,$views){
		$view=(isset($_REQUEST['view'])&&$_REQUEST['view']?$_REQUEST['view']:$default);
		$this->views=array();
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
		$this->title=CFG_TITLE;
		if($page_title)$this->title=$page_title." - ".$this->title;
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
	
	function message_ok($text){
		include_once ROOT_HDD_CORE.'/core/classes/message.php';
		$this->messages[]=new message($text,'ok');
	}
	function message_info($text){
		include_once ROOT_HDD_CORE.'/core/classes/message.php';
		$this->messages[]=new message($text,'info');
	}
	function message_error($text,$class=""){
		include_once ROOT_HDD_CORE.'/core/classes/message.php';
		$this->messages[]=new message($text,"error $class");
	}
	function message_ask($text){
		include_once ROOT_HDD_CORE.'/core/classes/message.php';
		$this->messages[]=new message($text,'ask');
	}
	
}

?>