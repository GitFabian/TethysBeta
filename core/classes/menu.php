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
					$sub.="\n\t<li class=\"menutopic ".$topic->page_id."\">".$topic->toHTML()."</li>";
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
	var $external;
	var $class_a;
	
	function __construct($parent_menu,$page_id,$highlight,$label,$link=null,$external=false){
		$this->page_id=$page_id;
		$this->label=$label;
		$this->link=$link;
		$this->highlight=($page_id==$highlight);
		$this->parent_menu=$parent_menu;
		if ($parent_menu){
			$parent_menu->add($this);
			if ($this->highlight){ $parent_menu->highlight(); }
		}
		$this->external=$external;
	}
	
	function toHTML(){
		$html=$this->label;
		$ext=($this->external?" target=\"_blank\"":"");
		$class_a=($this->class_a?" class=\"".$this->class_a."\"":"");
		if ($this->link) $html="<a href=\"".$this->link."\"$ext$class_a>$html</a>";
		$hcl=($this->highlight?" highlight":"");
		if (setting_get(null, 'DEPRECATED_HMLICLASS'))
		$html="<div class=\"menutopic $this->page_id$hcl\">$html</div>";
		return $html;
	}
	
}
class menu_topic2 extends menu_topic{
	function __construct($id, $label){
		parent::__construct(null, $id, null, $label);
	}
}

function menu_get_default($page_id){
	global $modules;
	$menu=new menu(null,null,$page_id);
	
	new menu_topic($menu,"core_index",$page_id, CFG_HOME_LABEL, (CFG_HOME_URL?CFG_HOME_URL:ROOT_HTTP_CORE."/index.".CFG_EXTENSION) );

	foreach ($modules as $module) {
		$menu->add($module->get_menu($page_id));
	}
	
	menu_add_default_user($menu, $page_id);
	menu_add_default_admin($menu, $page_id);

	return $menu;
}

function menu_add_default_user($menu,$page_id){
	if(USER_ID){
		$usermenu=new menu($menu,"core_user_",$page_id, USER_NICK );
		new menu_topic($usermenu,"core_user",$page_id, "Einstellungen", url_core_admin("user") );
		if (CFG_LOGON_TYPE=='cookie')
			new menu_topic($usermenu,"core_user_logoff",$page_id, "Abmelden", "?cmd=logoff" );
	}
}
function menu_add_default_admin($menu,$page_id){
	$menu_admin=new menu(null,"core_admin",$page_id,"Admin");
	if(berechtigung('RIGHT_USERMGMT')){
		new menu_topic($menu_admin,"core_users",$page_id,"Benutzer",url_core_admin("users"));
	}
	if(USER_ADMIN){
		new menu_topic($menu_admin,"core_rights",$page_id,"Rechte",url_core_admin("rights"));
		new menu_topic($menu_admin,"core_settings",$page_id,"Konfig",url_core_admin("settings"));
		if (file_exists(ROOT_HDD_CORE."/core/admin/import.php"))
			new menu_topic($menu_admin,"core_import",$page_id,"Import",url_core_admin("import"));
		new menu_topic($menu_admin,"core_update",$page_id,"Update",ROOT_HTTP_CORE."/demo/database/update.".CFG_EXTENSION);
	}
	if ($menu_admin->topics){
		$menu->add($menu_admin);
		if ($menu_admin->highlight){ $menu->highlight(); }
	}	
}

?>