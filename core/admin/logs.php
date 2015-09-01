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

if($view=="others"){logs_others();}
function logs_others(){
	global $page;
	/*
	 * Presets
	 */
	$presets=array(
		"view_index"=>"VIEW INDEX",
	);
	$preset_selector=new form("preset");
	$preset_selector->add_field(new form_field("selection","","[REQ]","SELECT",null,$presets));
	$preset_selector->add_hidden("view", "others");
	$page->say($preset_selector);
	
	if(request_command("preset")){
		$preset=request_value("selection");
		if($preset=="view_index")preset_view_index();
	}
	
	/*
	 * Tabelle
	 */
	$query1=dbio_SELECT("core_logs",null,"*",null,"time",false,"999");
	$table=new table($query1);
	$table->export_csv_id="core_logs";
	$page->say($table);
	page_send_exit();
}
function preset_view_index(){
	global $page;
	$query_users=dbio_SELECT_asList("core_users", "[nick]");
	$data=array();
	$sort_time=array();
	$sort_uid=array();
	foreach ($query_users as $uid=>$nick) {
		$query_access=dbio_SELECT("core_logs","uid=$uid AND keyword='VIEW' AND pars='INDEX'","*",null,"time",false,"1");
		$time=$query_access?$query_access[0]["time"]:0;
		$data[]=array(
			"user"=>"$nick (#$uid)",
			"view_index"=>$query_access?format_Wochentag_Uhrzeit($time):"-/-",
		);
		$sort_uid[]=$uid;
		$sort_time[]=$time;
	}
	$table=new table($data);
	//$table->set_sort_values("user", $sort_uid);
	$table->set_sort_values("view_index", $sort_time);
	$page->say($table);
	page_send_exit();
}

if(request_command("filter")){
	// 	print_r(request_value("module"));
	// 	echo implode(";", request_value("module"));
	setting_save(null, "LOG_VIEW_MODULES", request_value("module")?implode(",",request_value("module")):"", true);
	setting_save(null, "LOG_SHOW_MINE", request_value("mine"), true);
}
$filter=setting_get_user(null, "LOG_VIEW_MODULES");
if($filter)$filter=explode(",",$filter);
$eigene=setting_get_user(null, "LOG_SHOW_MINE");

/*
 * DB-Edit
 */
$where=array();
if($filter){
	$w=array();
	foreach ($filter as $f) {
		$w[]="modul='$f'";
	}
	$where[]="(".implode(" OR ", $w).")";
}
if(!$eigene)$where[]="(user!=".USER_ID." OR user IS NULL)";
if(!$where)$where[]="1";
// echo "-$where-";
$seite=request_value("page","1");
$seite_sql=(isset($_REQUEST['page'])?(($_REQUEST['page']-1)*100).",":"");
$query2=dbio_query_to_array("SELECT * FROM `core_logs_dbedit` WHERE ".implode(" AND ", $where)." ORDER BY `time` DESC LIMIT {$seite_sql} 100");

// $query=combine_sort($query1,$query2,"time");
#$table=new table($query2);
$user_nicklist=dbio_SELECT_asList("core_users", "[nick]");
$table="";
$datum=0;
$heute=floor(time()/86400);
foreach ($query2 as $dat) {
	$dat_neu=floor($dat["time"]/86400);
	if($datum!=$dat_neu){
		$datum=$dat_neu;
		$table.=html_header2(format_Wochentag_tm_j($datum*86400,null,"Y",true)
				."<span class=\"h2_right\">".format_days_delta($datum-$heute)."</span>");
	}
	$table.=logs_entry($dat);
}
$table=html_div($table,"log_list");

$blaettern="Seite ".$seite;
if(count($query2)>=100)
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
$filterform->add_field(new form_field("mine","Eigene Einträge anzeigen",$eigene,"CHECKBOX"));

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
	$html.=html_div($dat["tabelle"],"tabelle");
	$html.=html_div($dat["action"],"action");
	$html.=html_div($log_entry->name_and_link,"zeile");
	$html.=html_div($log_entry->description,"pars");
	
	$html=html_div($html,"log_entry");
	return $html;
}
function core_get_log_entry($action,$table,$row,$pars){
	return new log_entry(
			html_a("#".$row, ROOT_HTTP_CORE."/core/view.".CFG_EXTENSION."?db=".$table."&id=".$row),
			$pars);
}

?>