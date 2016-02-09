<?php

/*
include_once ROOT_HDD_CORE.'/core/classes/filebrowser.php';
$browser=new filebrowser("modulname");
#$browser->titel_root="Dateien";
#$browser->downloadlink=ROOT_HTTP_CORE."/core/data.".CFG_EXTENSION."?";
#$browser->downloadlink_key="url";
$page->say($browser);
 */

class filebrowser{

	var $titel_root;
	var $dir;
	/** $downloadlink."&file=..." */
	var $downloadlink=null;
	var $downloadlink_key="file";
	
	function __construct($dir){
		$this->dir=$dir;
		$this->titel_root=$dir;
	}
	
	function __toString(){ return $this->toHTML(); }
	function toHTML(){
		
		$dir_hdd=ROOT_HDD_DATA."/".$this->dir;
		$dir_http=ROOT_HTTP_DATA."/".$this->dir;
		
		$subdir=request_value("subdir",".");
		$subdir=preg_replace("/\\\\/", "/", $subdir);
		$subdirs=explode($subdir, "/");
		//HERE

		include_once ROOT_HDD_CORE.'/core/classes/table.php';
		$html="";//html_header1($header);
		if (file_exists($dir_hdd)){
			$files=scandir($dir_hdd);
		}else{
			$files=array();
		}
		$filenames=array();
		$subdirs=array();
		foreach ($files as $file) {
			$file_utf8=utf8_encode($file);
			if(is_file($dir_hdd."/".$file)){
				$filenames[$file_utf8]=$file_utf8;
			}else{
				$subdirs[$file_utf8]=$file_utf8;
			}
		}
		unset($subdirs["."]);
		unset($subdirs[".."]);
		if(false){
			foreach ($excludes as $excl) {
				unset($filenames[$excl]);
			}
		}
		$data=array();
		foreach ($subdirs as $file) {
			$filehtml=$file;
			#$filehtml=htmlentities($filehtml,null,"UTF-8");
			$d=array(
				"Name"=>$filehtml,
			);
			$d["Name"]=html_a($d["Name"], request_add2(array("subdir"=>$file)));
			$data[]=$d;
		}
		foreach ($filenames as $file) {
			$filehtml=$file;
			#$filehtml=htmlentities($filehtml,null,"UTF-8");
			$d=array(
			"Name"=>$filehtml,
			);
			if(true){
				#$filelink=preg_replace("/\\[FILE\\]/", $filehtml, $link);
				if($this->downloadlink){
					$filelink=$this->downloadlink."&".$this->downloadlink_key."=".urlencode($this->dir."/".$d["Name"]);
				}else{
					$filelink=$dir_http."/".$d["Name"];
				}
				$d["Name"]=html_a($d["Name"], $filelink);
			}
			$data[]=$d;
		}
		$table=new table($data);
		if($this->titel_root){
			$html.=html_header1($this->titel_root);
		}
		$html.=$table->toHTML();
		return $html;
		
	}
	
}
?>