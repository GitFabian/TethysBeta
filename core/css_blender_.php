<?php

/*
include_once ROOT_HDD_CORE.'/core/css_blender_.php';
 */

function css_blender($page_stylesheets){
	global $page;

	$stylesheets="";
	if(setting_get(null, "CFG_CSS_BLENDER")){
		$blend_url=array();
		$server=setting_get(null, "CFG_SERVER");
		foreach ($page_stylesheets as $url => $media) {
			if($media){
				$stylesheets.="\n\t<link href=\"$url\" rel=\"stylesheet\" type=\"text/css\" media=\"$media\" />";
			}else{
				$blend_url[]=$url;
			}
		}
		$hashkey=blender_hash($blend_url);
		$blended_file=ROOT_HDD_CORE."/core/html/CSS/$hashkey.css";
		if(file_exists($blended_file)){
			$stylesheets.="\n\t<link href=\""
					.ROOT_HTTP_CORE."/core/html/CSS/$hashkey.css"
					."\" rel=\"stylesheet\" type=\"text/css\" />";
		}else{
			foreach ($blend_url as $value) {
				$stylesheets.="\n\t<link href=\"$value\" rel=\"stylesheet\" type=\"text/css\" />";
			}
			$page->onload_JS.=ajax("AJAX_CSS_BLEND&urls=".urlencode(implode(",", $blend_url)),null,null,true);
		}
	}else{
		foreach ($page_stylesheets as $url => $media) {
			$mediahtml=($media?" media=\"$media\"":"");
			$stylesheets.="\n\t<link href=\"$url\" rel=\"stylesheet\" type=\"text/css\"$mediahtml />";
		}
	}
	
	return $stylesheets;
}

function blender_hash($blend_url){
	$hash=hash("sha256", implode("+", $blend_url));
	return $hash;
}
		
?>