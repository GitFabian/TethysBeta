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
		include_once ROOT_HDD_CORE.'/core/classes/table.php';
		
		$subdir=request_value("subdir",".");
		$subdir=preg_replace("/\\\\/", "/", $subdir);
		$subdirA=explode("/", $subdir);
		foreach ($subdirA as $subdir_check) {
			if(strpos($subdir_check, "..")===0)page_send_exit("Ungültiges Verzeichnis!");
		}
		#debug_out($subdirA);
		//TODO:Recht checken des Verzeichnisses!
		
		$dir_rel=$this->dir.($subdir=="."?"":"/".$subdir);
		#echo $dir_rel;
		$dir_hdd=ROOT_HDD_DATA."/".$dir_rel;
			$dir_hdd=utf8_decode($dir_hdd);
		$dir_http=ROOT_HTTP_DATA."/".$dir_rel;
		
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
			//TODO:Recht checken des Unterverzeichnisses!
			$filehtml=$file;
			#$filehtml=htmlentities($filehtml,null,"UTF-8");
			$d=array(
				"Name"=>$filehtml,
			);
			$file_path=($subdir=="."?"":$subdir."/").$file;
			$d["Name"]=html_a($d["Name"], request_add2(array("subdir"=>$file_path)));
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
					$filelink=$this->downloadlink."&".$this->downloadlink_key."=".urlencode($dir_rel."/".$d["Name"]);
				}else{
					$filelink=$dir_http."/".$d["Name"];
				}
				$d["Name"]=html_a($d["Name"], $filelink);
			}
			$data[]=$d;
		}
		$table=new table($data);
		
		/*
		 * Header
		 */
		$header="";
		if($this->titel_root){
			if($subdir=="."){
				$header.=$this->titel_root;
			}else{
				$header.=html_a($this->titel_root, request_add2(array("subdir"=>".")), "filebrowserheader");
			}
		}
		if($subdir!="."){
			$subpath=$subdirA[0];
			for ($i = 0; $i < count($subdirA)-1; $i++) {
				$header.=" &gt; <a href=\"".request_add2(array("subdir"=>$subpath))."\">".$subdirA[$i]."</a>";
				$subpath.="/".$subdirA[$i+1];
			}
			$header.=" &gt; ".$subdirA[count($subdirA)-1];
			if(!$this->titel_root)$header=substr($header, 6);
		}

		/*
		 * Ausgabe
		 */
		$html="";
		if($header)$html.=html_header1($header);
		$html.=$table->toHTML();
		
		/*
		 * Drop-Target
		 */
// 		$query_write_right=dbio_SELECT("coreXaccessrights","user=".USER_ID." AND level='write'");
// 		$right=false;
// 		$dir_rel_as_dir=$dir_rel."/";
// 		foreach ($query_write_right as $r) {
// 			$prefix=$r["file"];
// 			if(string_startswith($dir_rel_as_dir, $prefix))$right=true;
// 		}
// 		if($right)
		{
			#$html.="Datei(en) hinzufügen durch Drag & Drop.";
		}
		
		return $html;

	}
	
}
?>