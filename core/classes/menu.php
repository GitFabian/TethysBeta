<?php

class menu extends menu_topic{
	
	var $topics;
	
	function __construct($parent_menu,$menu_id,$highlight,$label=null,$link=null){
		parent::__construct($parent_menu,$menu_id,$highlight,$label,$link);
		$this->topics=array();
	}
	
	function add($topic){
		$this->topics[]=$topic;
	}
	
	function toHTML(){
		$html="";
		if ($this->label){
			$html.="<div class=\"submenulabel\">".parent::toHTML()."</div>";
		}
		if ($this->topics){
			$sub="";
			foreach ($this->topics as $topic) {
				if ($topic)
					$sub.="\n\t<li>".$topic->toHTML()."</li>";
			}
			$html.="\n<ul>$sub\n</ul>";
		}
		return $html;
	}
	
	function highlight(){
		$this->highlight=true;
		if ($this->parent_menu){
			$this->parent_menu->highlight();
		}
	}
	
}

class menu_topic{
	
	var $page_id;
	var $label;
	var $link;
	var $highlight;
	var $parent_menu;
	
	function __construct($parent_menu,$page_id,$highlight,$label,$link=null){
		$this->page_id=$page_id;
		$this->label=$label;
		$this->link=$link;
		$this->highlight=($page_id==$highlight);
		$this->parent_menu=$parent_menu;
		if ($parent_menu){
			$parent_menu->add($this);
			if ($this->highlight){ $parent_menu->highlight(); }
		}
	}
	
	function toHTML(){
		$html=$this->label;
		if ($this->link) $html="<a href=\"".$this->link."\">$html</a>";
		$hcl=($this->highlight?" highlight":"");
		$html="<div class=\"menutopic $this->page_id$hcl\">$html</div>";
		return $html;
	}
	
}

function menu_get_default($page_id){
	global $modules;
	$menu=new menu(null,null,$page_id);
	
	new menu_topic($menu,"core_index",$page_id, CFG_HOME_LABEL, CFG_HTTPROOT."/index.".CFG_EXTENSION);
	
	if(USER_ADMIN){
		$menu_admin=new menu($menu,"core_admin",$page_id,"Admin");
		new menu_topic($menu_admin,"core_rights",$page_id,"Rechte",CFG_HTTPROOT."/core/admin/rights.".CFG_EXTENSION);
		new menu_topic($menu_admin,"core_config",$page_id,"Konfig" );// ,CFG_HTTPROOT."/core/admin/config.".CFG_EXTENSION);
	}

	foreach ($modules as $module) {
		$menu->add($module->get_menu($page_id));
	}
	
	return $menu;
}

?>