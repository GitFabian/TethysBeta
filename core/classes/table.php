<?php

/*
include_once ROOT_HDD_CORE.'/core/classes/table.php';

http://tethys-framework.de/wiki/?title=Tabelle
 */

class table{
	
	var $rows;
	var $headers;
	var $class;
	var $datatable;
	var $id;
	var $col_highlight=false;
	var $options=null;
	var $options2=null;
	
	function __construct($query=null,$class=null,$datatable=true,$id=null){
		$this->rows=array();
		$this->headers=null;
		$this->class=$class;
		if (!$id){
			global $global_id_counter;
			$id="table".($global_id_counter++);
		}
		$this->id=$id;
		$this->datatable=($datatable===true?new datatable("#".$id):$datatable);
		
		if ($query){ $this->add_query($query); }
	}
	
	function __toString(){
		return $this->toHTML();
	}
	
	function set_options($new,$edit,$delete,$db,$idkey='id'){
		include_once ROOT_HDD_CORE.'/core/edit_rights.php'; 
		$this->options="";
		$this->options2=null;
		if (!edit_rights2($db, null)) return;
		$idkeyquery=($idkey=='id'?"":"&idkey=$idkey");
		if ($edit){
			$this->options.=html_a_button("Bearbeiten", ROOT_HTTP_CORE."/core/edit.".CFG_EXTENSION."?db=$db&id=[ID:$idkey]$idkeyquery", "tbl_option tbl_edit");
		}
		if ($delete){
			#include_once ROOT_HDD_CORE.'/core/alertify.php';
			$url=ROOT_HTTP_CORE."/core/edit.".CFG_EXTENSION."?cmd=delete&db=$db&id=[ID:$idkey]$idkeyquery";
			#$this->options.=html_a_button("Löschen", $url, "tbl_option tbl_delete");
			$this->options.=html_a_button("Löschen", "", "tbl_option tbl_delete","ask_delete('$url');");
		}
		if ($new){
			$this->options2=html_div(html_a_button("Neuer Eintrag", ROOT_HTTP_CORE."/core/edit.".CFG_EXTENSION."?id=NEW&db=$db"),"tbl_new");
		}
	}
	
	function add_query($query){
		foreach ($query as $row) {
			$this->rows[]=new table_row($row);
		}
	}
	
	/**
	 * 
$table_X->set_header(array(
		"id" => "ID",
		"nick" => "Anzeigename",
		"vorname" => "Vorname",
		"nachname" => "Name",
		"http_auth" => "LDAP",
));
	 */
	function set_header($headers){
		$this->headers=$headers;
	}
	
	function toHTML(){
		if ($this->datatable){ $this->datatable->execute(); }
		
		$headers=$this->headers;
		if (!$headers){
			$headers=array();
			foreach ($this->rows as $row) {
				foreach ($row->data as $key => $value) {
					if (!isset($headers[$key])) $headers[$key]=$key;
				}
			}
		}
		
		$th="";
		foreach ($headers as $value) {
			$th.="<th>$value</th>";
		}
		
		$rows="";
		foreach ($this->rows as $row) {
			$rows.=$row->toHTML($headers,$this->options);
		}
		
		if ($this->options){
			$th.="<th></th>";
		}

		if ($th){$th="<tr>$th</tr>";}
		if (!$rows) $rows="<tr><td>(Keine Einträge)</td></tr>";
		
		$class=($this->class?" class=\"".$this->class."\"":"");
		
		if ($this->col_highlight){
			include_jquery();
			global $page;
			$page->onload_JS.="highlight_table_col('#$this->id');";
// 			$page->add_inline_script("highlight_table_col('#$this->id');");
		}
		
		$options=($this->options2?$this->options2:"");

		$html="\n<div class=\"table_wrapper\">"
				."\n<table$class id=\"$this->id\">"
					."\n\t<thead>\n\t\t$th\n\t</thead>"
					."\n\t<tbody>\n$rows\n\t</tbody>"
				."\n</table>$options"
			."\n</div>";
		return $html;
	}
	
}

class datatable{
	var $selector;
	var $paginate;
	function __construct($selector,$paginate=true){
		$this->selector=$selector;
		$this->paginate=$paginate;
	}
	function execute(){
		include_datatables();
		global $page;
		$options="'bLengthChange':false,"
				."'iDisplayLength':15,"
				."language:{url:'".ROOT_HTTP_CORE."/core/html/jquery.dataTables.German.json'},"
			;
		if (!$this->paginate) $options.='"bPaginate":false,';
		$page->add_inline_script("\$(document).ready(function(){ \$('$this->selector').dataTable({".$options."}); });");
	}
}

class table_row{
	
	var $data;
	
	function __construct($data=null){
		$this->data=$data;
	}
	
	function toHTML($headers=null,$options=null){
		$tr="";
		if (!$headers){
			foreach ($this->data as $data) {
				$tr.="\n\t\t\t<td>$data</td>";
			}
		}else{
			foreach ($headers as $th => $value) {
				if (isset($this->data[$th])){
					$tr.="\n\t\t\t<td>".$this->data[$th]."</td>";
				}else{
					$tr.="\n\t\t\t<td></td>";
				}
			}
		}
		if ($options){
			preg_match("/\\[ID\\:(.*?)\\]/", $options, $matches);
			$idkey=$matches[1];
			$value=$this->data[$idkey];
			$tr.="\n\t\t\t<td>".preg_replace("/\\[ID\\:$idkey\\]/", $value, $options)."</td>";
		}
		return "\n\t\t<tr>$tr\n\t\t</tr>";
	}
	
}

?>