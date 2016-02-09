<?php

/*
include_once ROOT_HDD_CORE.'/core/classes/filebrowser.php';
$browser=new filebrowser("modulname");
#$browser->downloadlink=ROOT_HTTP_CORE."/core/data.".CFG_EXTENSION."?";
#$browser->downloadlink_key="url";
$page->say($browser);
 */

class filebrowser{
	
	var $dir;
	/** $downloadlink."&file=..." */
	var $downloadlink=null;
	var $downloadlink_key="file";
	
	function __construct($dir){
		$this->dir=$dir;
	}
	
	function __toString(){ return $this->toHTML(); }
	function toHTML(){
		
		$dir_hdd=ROOT_HDD_DATA."/".$this->dir;
		$dir_http=ROOT_HTTP_DATA."/".$this->dir;

		include_once ROOT_HDD_CORE.'/core/classes/table.php';
		$html="";//html_header1($header);
		if (file_exists($dir_hdd)){
			$files=scandir($dir_hdd);
		}else{
			$files=array();
		}
		$filenames=array();
		foreach ($files as $file) {
			$file=utf8_encode($file);
			$filenames[$file]=$file;
		}
		unset($filenames["."]);
		unset($filenames[".."]);
		if(false){
			foreach ($excludes as $excl) {
				unset($filenames[$excl]);
			}
		}
		$data=array();
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
		$html.=$table->toHTML();
		return $html;
		
	}
	
}
?>