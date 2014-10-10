<?php

global $modules;
$modules['tethys']=new modul_tethys('Entwickler-Modul');

class modul_tethys extends module{
	
	function get_menu($page_id){
		$menu=new menu(null,"tethys_index",$page_id,"Tethys" );// ,CFG_HTTPROOT."/modules/tethys/index.".CFG_EXTENSION);
		new menu_topic($menu,"tethys_wiki",$page_id,"Wiki","http://217.91.49.199/tethyswiki/index.php/Tethys_Wiki",true);
		new menu_topic($menu,"tethys_commits",$page_id,"Commits","https://github.com/GitFabian/TethysBeta/commits/master",true);
		new menu_topic($menu,"tethys_ftp",$page_id,"FTP",CFG_HTTPROOT."/modules/tethys/ftp.".CFG_EXTENSION);
		return $menu;
	}
	
}

?>