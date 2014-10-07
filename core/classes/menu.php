<?php

class menu extends menu_topic{
	
	var $topics;
	var $highlight;
	
	function __construct($highlight,$label=null,$link=null,$menu_id=null){
		parent::__construct($menu_id,$label,$link);
		$this->topics=array();
		$this->highlight=$highlight;
	}
	
	function add($topic){
		$this->topics[]=$topic;
	}
	
	function toHTML(){
		$html="";
		if ($this->label){
			$html.="<div class=\"submenulabel\">".parent::toHTML($this->highlight)."</div>";
		}
		if ($this->topics){
			$sub="";
			foreach ($this->topics as $topic) {
				if ($topic)
					$sub.="\n\t<li>".$topic->toHTML($this->highlight)."</li>";
			}
			$html.="\n<ul>$sub\n</ul>";
		}
		return $html;
	}
	
}

class menu_topic{
	
	var $page_id;
	var $label;
	var $link;
	
	function __construct($page_id,$label,$link=null){
		$this->page_id=$page_id;
		$this->label=$label;
		$this->link=$link;
	}
	
	function toHTML($highlight){
		$hcl="";
		
		$html=$this->label;
		if ($this->link) $html="<a href=\"".$this->link."\">$html</a>";
		if ($highlight==$this->page_id) $hcl=" highlight";
		$html="<div class=\"menutopic $this->page_id$hcl\">$html</div>";
		return $html;
	}
	
}

function menu_get_default($page_id){
	global $modules;
	$menu=new menu($page_id);
	
	$menu->add(new menu_topic("core_index", CFG_HOME_LABEL, CFG_HTTPROOT."/index.".CFG_EXTENSION));
	
	if(USER_ADMIN){
		$menu->add($menu_admin=new menu($page_id,"Admin",null,"core_admin"));
		$menu_admin->add(new menu_topic("core_rights","Rechte",CFG_HTTPROOT."/core/admin/rights.".CFG_EXTENSION));
		$menu_admin->add(new menu_topic("core_config","Konfig" ));// ,CFG_HTTPROOT."/core/admin/config.".CFG_EXTENSION));
	}

	foreach ($modules as $module) {
		$menu->add($module->get_menu($page_id));
	}
	
	return $menu;
}

?>