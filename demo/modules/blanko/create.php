<?php
include_once '../../../config_start.php';
$page->init("core_createModul", "Neues Modul erstellen");
include_once ROOT_HDD_CORE.'/core/classes/form.php';

if (!USER_ADMIN) page_send_exit("Keine Berechtigung!");

if (request_command("do")){
	
	foreach ($_REQUEST as $key => $value) { $$key=$value; }
	
	$dir=ROOT_HDD_MODULES."/$id";
	if (file_exists($dir)){
		page_send_exit("Modul \"$id\" existiert bereits!");
	}

	$tethys_file="<?php

global \$modules;
\$modules['$id']=new modul_$id('$name');

class modul_$id extends module{

	function __construct(\$modul_name){
		parent::__construct(\$modul_name);
		#\$this->has_user_page=true;
	}
	
	function get_menu(\$page_id){
		\$menu=new menu(null,\"$id\",\$page_id,\"$name\");
		new menu_topic(\$menu,\"${id}_$index_id\",\$page_id,\"$index_name\",url_$id('$index_url'));
		return \$menu;
	}
	
	function get_user_page(){
		if (USER_ADMIN) echo(\"Nicht implementiert: Funktion \\\"\".__FUNCTION__.\"\\\" in Modul \\\"\".\$this->modul_name.\"\\\"!\");
		//Rückgabewert: HTML-String
		return null;
	}
	
	function export_csv(\$table, \$identifier){
		if (USER_ADMIN) echo(\"Nicht implementiert: Funktion \\\"\".__FUNCTION__.\"\\\" in Modul \\\"\".\$this->modul_name.\"\\\"!\");
		#if (\$table=='demo_lorumipsum'){ csv_out(dbio_SELECT(\$table),\"\$table.csv\"); }
		if (USER_ADMIN) echo(\"Kein CSV-Export für Tabelle: \".\$table);
		return false;
	}

	function get_edit_right(\$table,\$id){
		if (USER_ADMIN) echo(\"Nicht implementiert: Funktion \\\"\".__FUNCTION__.\"\\\" in Modul \\\"\".\$this->modul_name.\"\\\"!\");
		#if (\$table=='demo_lorumipsum'){return berechtigung('RIGHT_DEMOMGMT');}
		if (USER_ADMIN) echo\"Kein edit_right für \$table!\";
		return false;
	}
	
	function get_rights(){
		return null;
		include_once ROOT_HDD_CORE.'/core/classes/rights.php';
		return array(
			'RIGHT_DEMOMGMT'=>new right(\"Demo-Administration\", \"Flang flub cakewhack, boo quabble roo shnuzzle.\"),
		);
	}

	function get_edit_form(\$form,\$table,\$id,\$query){
		return false;
		if (\$table=='demo_lorumipsum'){
			module::edit_form_field(\$form,\$query,'flubtangle',\"Flubtangle\",'TEXT');
			module::edit_form_field(\$form,\$query,'abracadabra',\"Abracadabra\",'TEXT');
			return true;
		}
		if (USER_ADMIN) echo\"Kein edit_form für \$table!\";
		return false;
	}
	
}

function url_$id(\$page){
	return url_mod_pg('$id', \$page);
}

?>";
	
	$index_file="<?php
include_once '../../config_start.php';
\$page->init('${id}_$index_id','$index_name');



page_send_exit();//===============================================================================
?>";

$id_uppercase=strtoupper($id);
$file_dbUpdate=<<<END_DBUPDATE
<?php
if(!defined('USER_ADMIN')||!USER_ADMIN){echo"Keine Berechtigung!";exit;}

#if (\$version<){dbio_query("");}

//=================================================================================================
dbio_query("UPDATE `core_meta_dbversion` SET `version` = '1' WHERE `modul_uc` = '$id_uppercase';");
//=================================================================================================
?>
END_DBUPDATE;

$file_ajax=<<<END_AJAX
<?php

include_once '../../config_start.php';

\$cmd=request_value("cmd");
#if (\$cmd=="xxxxxxxxxx") xxxxxxxxxx();

echo "!Unbekanntes AJAX-Kommando \"\$cmd\"!";
exit;//===========================================================================================

function ajax_exit(\$msg){
	echo \$msg;exit;
}

?>
END_AJAX;
	
	mkdir($dir);
	$file=fopen($dir."/tethys.php", "w");
		fwrite($file, $tethys_file);
	fclose($file);
	$file=fopen($dir."/$index_id.php", "w");
		fwrite($file, $index_file);
	fclose($file);
	$file=fopen($dir."/db_update.php", "w");
		fwrite($file, $file_dbUpdate);
	fclose($file);
	$file=fopen($dir."/ajax.php", "w");
		fwrite($file, $file_ajax);
	fclose($file);
	
	$m=array();
	foreach ($modules as $key => $dummy) { $m[]=$key; }
	$m[]=$id;
	dbio_UPDATE("core_settings", "`key`='CFG_MODULES'", array("value"=>implode(",", $m)));
	
	$page->message_ok("OK");
	$page->say(html_a($name, ROOT_HTTP_MODULES."/$id/$index_url.".CFG_EXTENSION));
	page_send_exit();
}

$page->say(html_header1("Neues Modul erstellen"));

$form=new form("do");
$form->add_field(new form_field("id"));
$form->add_field(new form_field("name"));
$form->add_field(new form_field("index_id",null,"index"));
$form->add_field(new form_field("index_name",null,"Index"));
$form->add_field(new form_field("index_url",null,"index"));
$page->say($form);

page_send_exit();
?>