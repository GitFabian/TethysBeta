<?php
include_once '../../config_start.php';
$page->init('core_logs','Logs');
if (!USER_ADMIN) page_send_exit("Keine Berechtigung!");
include_once ROOT_HDD_CORE.'/core/classes/table.php';
include_once ROOT_HDD_CORE.'/core/classes/form.php';
include_chosen();
include_once ROOT_HDD_CORE.'/core/classes/user.php';

$views=array(
	new menu_topic2("dbedit","DB-Edit"),
	new menu_topic2("others","Others"),
);
#if(isset($_REQUEST["reload"]))$view=request_value("view","dbedit");else
$view=$page->init_views("dbedit",$views);

if($view=="others"){
	$query1=dbio_SELECT("core_logs");
	$table=new table($query1);
// 	if($table->rows)
		$page->say($table);
	page_send_exit();
}

if(request_command("filter")){
	// 	print_r(request_value("module"));
	// 	echo implode(";", request_value("module"));
	setting_save(null, "LOG_VIEW_MODULES", request_value("module")?implode(",",request_value("module")):"", true);
}
$filter=setting_get_user(null, "LOG_VIEW_MODULES");
if($filter)$filter=explode(",",$filter);

/*
 * DB-Edit
 */
$where="";
if($filter){
	$where="WHERE";
	$w=array();
	foreach ($filter as $f) {
		$w[]="modul='$f'";
	}
	$where.=" ".implode(" OR ", $w);
}
// echo "-$where-";
$seite=request_value("page","1");
$seite_sql=(isset($_REQUEST['page'])?(($_REQUEST['page']-1)*100).",":"");
$query2=dbio_query_to_array("SELECT * FROM `core_logs_dbedit` $where ORDER BY `time` DESC LIMIT {$seite_sql} 100");

// $query=combine_sort($query1,$query2,"time");
#$table=new table($query2);
$user_nicklist=dbio_SELECT_asList("core_users", "[nick]");
$table="";
foreach ($query2 as $dat) {
	$table.=logs_entry($dat);
}
$table=html_div($table,"log_list");
function logs_entry($dat){
	global $user_nicklist,$modules;
	$html="";
	
	if(!$dat["modul"]||$dat["modul"]=="core"||!isset($modules[$dat["modul"]])){
		$log_entry=core_get_log_entry($dat["action"],$dat["tabelle"],$dat["zeile"],$dat["pars"]);
	}else{
		$log_entry=$modules[$dat["modul"]]->get_log_entry($dat["action"],$dat["tabelle"],$dat["zeile"],$dat["pars"]);
	}
	
	$html.=html_div($dat["id"],"id");
	$html.=html_div("<span title=\""
			.($dat["user"]?$user_nicklist[$dat["user"]]." @ ":"")
			.$dat["ip"]."\">".get_user_thumb($dat["user"])."</span>","user");
	$html.=html_div(format_Wochentag_Uhrzeit($dat["time"]),"time");
	$html.=html_div($dat["action"],"action");
	$html.=html_div($dat["tabelle"],"tabelle");
	$html.=html_div($log_entry->name_and_link,"zeile");
	$html.=html_div($log_entry->description,"pars");
	
	$html=html_div($html,"log_entry");
	return $html;
}

$blaettern="Seite ".$seite;
$blaettern.=html_a_button("&gt;", "?page=".($seite+1));
if($seite>1){
	$blaettern=html_a_button("&lt;", "?page=".($seite-1)).$blaettern;
}

$filterform=new form("filter");
$filterform->add_hidden("page", $seite);
$modullist=array();
foreach ($modules as $key=>$modul) {
	$modullist[$key]=$modul->modul_name;
}
$filterform->add_field(new form_field("module[]","Module",array_val2key($filter),"SELECT_MULTIPLE",null,$modullist));

if(!isset($_REQUEST["reload"]))
$page->say($filterform);
$page->say($table);
$page->say($blaettern);

if(isset($_REQUEST["reload"])) auto_reload($_REQUEST["reload"]);

$page->send();
exit;//============================================================================================
class log_entry{
	var $name_and_link;
	var $description;
	function __construct($name_and_link, $description){
		$this->name_and_link=$name_and_link;
		$this->description=$description;
	}
}
function core_get_log_entry($action,$table,$row,$pars){
	return new log_entry(
			html_a("#".$row, ROOT_HTTP_CORE."/core/view.".CFG_EXTENSION."?db=".$table."&id=".$row),
			$pars);
}

?>