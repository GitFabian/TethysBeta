<?php

global $modules;
$modules['tethys']=new modul_tethys('Entwickler-Modul');

class modul_tethys extends module{
	
	function get_menu($page_id){
		$menu=new menu(null,"tethys_index",$page_id,"Tethys" );
		new menu_topic($menu,"tethys_home",$page_id,"Home of Tethys","http://tethys-framework.de",true);
		new menu_topic($menu,"tethys_todo",$page_id,"TODO","https://github.com/GitFabian/TethysBeta/raw/master/README.md",true);
		new menu_topic($menu,"tethys_ftp",$page_id,"FTP",url_mod_pg('tethys', 'ftp'));
		new menu_topic($menu,"tethys_wiki",$page_id,"Wiki","http://tethys-framework.de/wiki/?title=Tethys_Wiki",true);
		new menu_topic($menu,"tethys_commits",$page_id,"Commits","https://github.com/GitFabian/TethysBeta/commits/master",true);
		new menu_topic($menu,"tethys_modules",$page_id,"Modules","http://tethys-framework.de/t/modules/tethys_shared/module.php",true);
		new menu_topic($menu,"tethys_skins",$page_id,"Skins","http://tethys-framework.de/t/modules/tethys_shared/skins.php",true);
		return $menu;
	}
	
}

?>