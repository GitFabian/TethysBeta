<?php

/*
include_once CFG_HDDROOT.'/core/classes/table.php';

http://217.91.49.199/tethyswiki/index.php/Tabelle
 */

class table{
	
	var $rows;
	var $headers;
	
	function __construct($query=null){
		$this->rows=array();
		$this->headers=null;
		
		if ($query){ $this->add_query($query); }
	}
	
	function add_query($query){
		foreach ($query as $row) {
			$this->rows[]=new table_row($row);
		}
	}
	
	function set_header($headers){
		$this->headers=$headers;
	}
	
	function toHTML(){
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
			$rows.=$row->toHTML($headers);
		}
		
		$html="\n<table>\n\t<tr>$th</tr>\n$rows\n</table>";
		return $html;
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
				$tr.="\n\t\t<td>$data</td>";
			}
		}else{
			foreach ($headers as $th => $value) {
				if (isset($this->data[$th])){
					$tr.="\n\t\t<td>".$this->data[$th]."</td>";
				}else{
					$tr.="\n\t\t<td></td>";
				}
			}
		}
		return "\t<tr>$tr\n\t</tr>";
	}
	
}

?>