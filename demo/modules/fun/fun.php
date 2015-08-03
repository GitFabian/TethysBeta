<?php

/*
include_once ROOT_HDD_CORE.'/core/alertify.php';
#global $modules;
#if (isset($modules['fun']))
include_once ROOT_HDD_CORE.'/demo/modules/fun/fun.php';
 */

function fun_sprichwortgenerator($ajax=true,$plain=false){
	if ($ajax){
		$id=get_next_id();
		global $page;
		$page->add_inline_script("function sprichwortgenerator(id){
			".ajax_to_id("sprichwortgenerator", "\"+id+\"", "fun")."
		}");
		$page->onload_JS.="sprichwortgenerator('$id');";
		return "<span id=\"$id\">...</span>";
	}
	if ($plain){
		$content = file_get_contents("http://sprichwort.gener.at/or/");
		
		#$content=htmlentities($content);
// 		$content=utf8_encode($content);
		preg_match("/<div class=\"spwort\"\\>(.*?)\\<\\/div\\>/", $content, $matches);
		if (!isset($matches[1])){
			return ((USER_ADMIN?"--- Sprichwortgenerator kaputt! ---":""));
		}
		$content=$matches[1];
		
		return $content;
	}else{
		$content=fun_sprichwortgenerator(false,true);
		$key=substr($content, 0, 200);
		$spw=dbio_SELECT_SINGLE("fun_logs_spw", sqlEscape($key));
		if($spw){
			$id=$spw['nr'];
		}else{
			dbio_INSERT("fun_logs_spw", array("id"=>$key));
			$id=mysql_insert_id();
		}
		
		$content.=" <a class=\"sprichwort_tribute\" href=\"http://sprichwort.gener.at/or/\" target=\"_blank\">(?)</a>";
#$content=$id." ".$content;

		if (USER_ADMIN){
			$content.=" <a onclick=\"".ajax_to_alertify("spw_q&q=like&id=$id","fun",true)."\">[&nbsp;GUT&nbsp;]</a>";
			$content.=" <a onclick=\"".ajax_to_alertify("spw_q&q=dislike&id=$id","fun",true)."\">SCHLECHT</a>";
		}
		
		return $content;
	}
}

?>