<?php

include_once ROOT_HDD_CORE.'/core/toolbox.php';

function start_standalone($skin='demo',$title='Tethys'){
	global $page;
	include_once ROOT_HDD_CORE.'/core/classes/page.php';
	$page=new page();
	header('Content-type: text/html; charset=UTF-8');
	if ($skin=="wireframe"
			||$skin=="terminal"){
		$css_http=ROOT_HTTP_CORE."/demo/skins/$skin";
	}else{
		$css_http=ROOT_HTTP_SKINS."/$skin";
	}
	$page->add_stylesheet($css_http."/screen.css");
	
	define('CFG_TITLE', $title);
	define('CFG_CSS_VERSION', '');
	define('USER_ADMIN', '0');
}

function setting_get($modul, $key){
	if ($modul==null && $key=='HM_ICONS') return "0";
	if ($modul==null && $key=='HM_TEXT') return "1";
	if (!$modul) $modul="CORE";
	echo "Kein Default-Value für \"$modul:$key\"! /core/start_standalone.php:14";
}

?>