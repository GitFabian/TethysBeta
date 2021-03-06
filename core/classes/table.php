<?php

/*
include_once ROOT_HDD_CORE.'/core/classes/table.php';

http://tethys-framework.de/wiki/?title=Tabelle

$query=dbio_SELECT("sms1_duhshrubbery");
$data=array();
foreach ($query as $row) {
	$data[]=$row;
}
$table=new table($data);
$table->details=true;//ROOT_HTTP_MODULES."/sms1/duhshrubbery.".CFG_EXTENSION."?id=[ID:id]";
$table->set_options(true, true, true, "sms1_duhshrubbery");
$page->say(html_header1("Index"));
$page->say($table);

 */

class table{
	
	var $rows;
	var $headers;
	var $class;
	var $datatable;
	var $id;
	var $export_csv_id;
	var $col_highlight=false;
	var $options=null;
	var $options2=null;
	var $details=false;
	var $neuer_eintrag="Neuer Eintrag";
	
	function __construct($query=null,$class=null,$datatable=true,$id=null,$export_csv_id=null){
		$this->rows=array();
		$this->headers=null;
		$this->class=$class;
		if (!$id){
			global $global_id_counter;
			$id="table".($global_id_counter++);
		}
		$this->id=$id;
		$this->datatable=($datatable===true?new datatable("#".$id):$datatable);
		$this->export_csv_id=$export_csv_id;
		
		if ($query){ $this->add_query($query); }
	}
	
	function __toString(){
		return $this->toHTML();
	}
	
	/**
	 * @param string $db
	 * Modul "modul_name", Tabelle "table_name":
	 * $db="::_modul_name,table_name"
	 * 
	 * 
	 */
	function set_options($new,$edit,$delete,$db,$idkey='id',$datensatz=null,$and_return=null,$details=false){
		include_once ROOT_HDD_CORE.'/core/edit_rights.php';
		$this->options=array();
		$this->options2="";
		if($details)$this->details=$details;
		$idkeyquery=($idkey=='id'?"":"&idkey=$idkey");
		if($this->details){
			$details=$this->details;
			if($details===true)$details=ROOT_HTTP_CORE."/core/view.".CFG_EXTENSION."?db=$db&id=[ID:$idkey]".$idkeyquery;
			$this->options[]=html_a_button("Details",$details,"tbl_option tbl_detail");
		}
		if (!edit_rights2($db, null)) return;
		if ($edit){
			$this->options[]=html_a_button("Bearbeiten",
					ROOT_HTTP_CORE."/core/edit.".CFG_EXTENSION."?db=$db&id=[ID:$idkey]$idkeyquery&datensatz=$datensatz",
					"tbl_option tbl_edit");
		}
		if ($delete){
			include_once ROOT_HDD_CORE.'/core/alertify.php';
			$url=ROOT_HTTP_CORE."/core/edit.".CFG_EXTENSION."?cmd=delete&db=$db&$idkey=[ID:$idkey]$idkeyquery";
			#$this->options.=html_a_button("Löschen", $url, "tbl_option tbl_delete");
			$this->options[]=html_a_button("Löschen", "", "tbl_option tbl_delete","ask_delete('$url','$datensatz');");
		}
		if ($new){
			$this->options2.="\n\t".html_a_button($this->neuer_eintrag, ROOT_HTTP_CORE."/core/edit.".CFG_EXTENSION
					."?id=NEW"
					."&db=$db"
					."&datensatz=$datensatz"
					.$and_return
				,"tbl_new");
		}
	}
	
	function set_sort_values($column,$values){
		$i=0;
		foreach ($this->rows as $row) {
			$row->sort_values[$column]=$values[$i];
			$i++;
		}
	}

	function set_highlight_ids($keys){
		$i=0;
		foreach ($this->rows as $row) {
			if(isset($keys[$i])){
				$row->highlight=true;
			}
			$i++;
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
		
		$options=$this->options2;
		if ($this->export_csv_id){
			$options.="\n\t".html_a_button("CSV-Export", csv_pfad($this->export_csv_id),"tbl_export export_csv");
		}
		if ($options) $options=html_div($options."\n","tbl_buttons");

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
	var $localize=true;
	var $fixedheader=false;
	function __construct($selector,$paginate=false){
		$this->selector=$selector;
		$this->paginate=$paginate;
	}
	function get_execute($varname=null){
		$options="'bLengthChange':false,"
				."'aaSorting':[],"//Initial Sorting/Default Sorting
// 				."'iDisplayLength':15,"
			;
		if($this->localize)$options.="language:{url:'".ROOT_HTTP_CORE."/core/html/jquery.dataTables.German.json'},";
		if (!$this->paginate) $options.="'bPaginate':false,";
		$varname2=($varname?"$varname=":"");
		$runmore="";
		if($this->fixedheader){
			global $page;
			$page->add_library(ROOT_HTTP_CORE."/core/html/jquery.dataTables.fixedHeader.js");
			$offsetTop=setting_get(null, "CFG_OFFSETTOP");
			$runmore=$varname?"new $.fn.dataTable.FixedHeader( $varname, { \"offsetTop\":$offsetTop, } );":"";
			$runmore=js_runLater($runmore, 1);//DataTables muss fertig sein mit der Formatierung
		}
		return "$varname2\$('$this->selector').dataTable({".$options."});$runmore";
	}
	function execute(){
		include_datatables();
		global $page;
		$page->add_inline_script("\$(document).ready(function(){ "
				.$this->get_execute("datatable")
// 				."new $.fn.dataTable.FixedHeader( datatable );"
			." });");
	}
}

class table_row{
	
	var $data;
	var $sort_values=array();
	var $highlight=false;
	
	function __construct($data=null){
		$this->data=$data;
	}
	
	function toHTML($headers=null,$options=null){
		$tr="";
		if (!$headers){
			foreach ($this->data as $th => $data) {
				$sortValue="";
				if (isset($this->sort_values[$th])){
					$sortValue=" data-sort=\"".$this->sort_values[$th]."\"";
				}
				$tr.="\n\t\t\t<td$sortValue>$data</td>";
			}
		}else{
			foreach ($headers as $th => $value) {
				$sortValue="";
				if (isset($this->sort_values[$th])){
					$sortValue=" data-sort=\"".$this->sort_values[$th]."\"";
				}
				if (isset($this->data[$th])){
					$tr.="\n\t\t\t<td$sortValue>".$this->data[$th]."</td>";
				}else{
					$tr.="\n\t\t\t<td$sortValue></td>";
				}
			}
		}
		if ($options){
			$line="\n\t\t\t\t".implode("&nbsp;", $options)."\n\t\t\t";
			preg_match_all("/\\[ID\\:(.*?)\\]/", $line, $matches);
			for ($i = 0; $i < count($matches[1]); $i++) {
				$idkey=$matches[1][$i];
				$value=$this->data[$idkey];
				$line=preg_replace("/\\[ID\\:$idkey\\]/", $value, $line);
			}
			$tr.="\n\t\t\t<td>$line</td>";
		}
		$highlight=($this->highlight?" class=\"highlight\"":"");
		return "\n\t\t<tr$highlight>$tr\n\t\t</tr>";
	}
	
}

?>