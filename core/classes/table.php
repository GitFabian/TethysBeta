<?php

/*
include_once ROOT_HDD_CORE.'/core/classes/table.php';

http://217.91.49.199/tethyswiki/index.php/Tabelle
 */

class table{
	
	var $rows;
	var $headers;
	var $class;
	var $datatable;
	var $id;
	var $col_highlight=false;
	
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
		if ($th){$th="<tr>$th</tr>";}
		
		$rows="";
		foreach ($this->rows as $row) {
			$rows.=$row->toHTML($headers);
		}
		if (!$rows) $rows="<tr><td>(Keine Einträge)</td></tr>";
		
		$class=($this->class?" class=\"".$this->class."\"":"");
		
		if ($this->col_highlight){
			include_jquery();
			global $page;
			$page->onload_JS.="highlight_table_col('#$this->id');";
// 			$page->add_inline_script("highlight_table_col('#$this->id');");
		}

		$html="\n<table$class id=\"$this->id\">\n\t<thead>\n\t\t$th\n\t</thead>\n\t<tbody>\n$rows\n\t</tbody>\n</table>";
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
// 				."'fnInitComplete':function(oSettings,json){alert('!');},"
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
	
	function toHTML($headers=null){
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
		return "\t\t<tr>$tr\n\t\t</tr>";
	}
	
}

?>