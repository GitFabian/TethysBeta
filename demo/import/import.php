<?php

//Diese Datei ins Verzeichnis /core/admin/ kopieren => Erscheint im Admin-Menü.

//Verbindung mit der Export-DB muss vor der Import-DB erfolgen!
$sql_source=mysql_connect('xxxxxxxHOSTxxxxxxxx','xxxxxxxxUSERNAMExxxxxxxxx','xxxxxxxxPASSxxxxxxxxx');
mysql_select_db('xxxxxxxxDBASExxxxxxxxxx');

include_once '../../config_start.php';
if (!USER_ADMIN) exit;
$page->init("core_import", "Import");

include_once ROOT_HDD_CORE.'/core/classes/table.php';

$status="Bitte Datenbank auswählen.";

$targets=array(
	"---1"=>"Kunden",
	"Kunden(Firmen)"=>"kunden_firmen",
	"Kunden(Personen)"=>"kunden_personen",
);

if ($db=request_value("db")) db_import($db);
if ($check=request_value("check")) db_testlisting(dbio_SELECT($targets[$check]),$targets[$check]);

$dblis="";
foreach ($targets as $db_id => $db) {
	if (substr($db_id,0,3)=="---"){
		$dblis.="</ul><u>$db</u><ul>";
	}else{
		$dblis.=db_li($db_id);
	}
}
$page->add_html("<h1>Datenbank-Import</h1><ul>$dblis</ul><hr>Status: $status");

$page->send();
exit;//============================================================================================
function db_check($db_id){
	global $targets;
	foreach ($targets as $key => $value) {
		if ($db_id==$key){
			$query=dbio_SELECT($targets[$db_id]);
			if ($query){
				return "[<a href=\"?check=$db_id\">".count($query)."</a>]";
			}else{
				return "[LEER]";
			}
		}
	}
	return "[DB_UNBEKANNT]";
}
function db_import($db_id){
	global $status,$sql_source,$targets;
	$status="Importiere \"$db_id\"...";
	$fehler="";
	$db_target=$targets[$db_id];
	
	if ($db_id=="Kunden(Firmen)"){//==========================================================================
		//Neue Spalten:
			#$save_=dbio_SELECT_keyValueArray($db_target, "kurzname");
		//Datenbank und abhängige löschen:
			db_delete("kunden_personen");
			db_delete($db_target,false);
		//Einlesen:
			$db_source="firma";
			$query_source=db_query_source($db_source);
			#/*TEST:*/db_testlisting($query_source,$db_source);
		foreach ($query_source as $data) {
			//Konvertieren:

				$id=$data['FirmaId'];

				$kurzname=substr($data['FirmaName'],0,20);
				#if (isset($save_[$id])){ $kurzname=$save_[$id]; }

			//Import:
			dbio_INSERT($db_target, array(
				"id"=>$id,
				"name"=>$data['FirmaName'],
				"kurzname"=>$kurzname,
				"url"=>$data['Website'],
			));
			log_new("kunden_personen");
		}
	}else if ($db_id=="xxxxxxDBIDxxxxxxx"){//==========================================================================
		//...
	}else{
		$fehler="Konverter \"$db_id\" nicht implementiert!";
	}

	$status.=($fehler?"FEHLER<br>".$fehler:"OK");
}
function log_new($tabelle){
	dbio_INSERT("core_logs_dbedit", array(
	"time"=>time(),
	"user"=>null,
	"modul"=>"core",
	"ip"=>$_SERVER['REMOTE_ADDR'],
	"action"=>"new",
	"tabelle"=>$tabelle,
	"zeile"=>mysql_insert_id(),
	"pars"=>"IMPORT,".USER_ID,
	));
}
function db_query_source($db_source,$correct_encoding=true){
	global $sql_source;
	$query=dbio_query_to_array("SELECT * FROM `$db_source`",$sql_source);
	if ($correct_encoding) $query=encode_query_to_utf8($query);
	return $query;
}
function db_testlisting($query_source,$titel=null){
	global $page;
	$page->focus="div.dataTables_filter input";
	$table = new table($query_source);
	if ($titel) $page->add_html("<h1>$titel</h1>");
	$page->add_html( $table->toHTML() );
	page_send_exit();
}
function db_delete($db_target,$hard=true){
	if ($hard){
		dbio_query("TRUNCATE `$db_target`");
	}else{
		dbio_query("DELETE FROM `$db_target`");
	}
}
function db_li($db_id){
	global $datenbanken_neu;
	return "<li><a href='?db=$db_id'>$db_id</a> ".db_check($db_id)."</li>";
}
?>