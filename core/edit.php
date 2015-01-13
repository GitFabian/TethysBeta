<?php
include_once '../config_start.php';
$page->init("core_edit", "Datensatz bearbeiten");
include_once ROOT_HDD_CORE.'/core/classes/form.php';
include_once ROOT_HDD_CORE.'/core/edit_rights.php';
include_jquery();
include_once "edit_.php";
include_once ROOT_HDD_CORE.'/core/log.php';

request_extract_booleans2();
request_extract_dates();

/*
 * Datentabelle und Datensatz ermitteln
 */
$idkey=request_value("idkey","id");
$db=request_value("db");
$id=request_value("id");

if (!$db){
	page_send_exit();
}

if (!dbio_table_exists($db)){
	page_send_exit("Datensatz nicht gefunden!");
}

$modul=substr($db, 0, strpos($db, "_"));
if ($modul!='core'&&!isset($modules[$modul])){
	page_send_exit("Modul \"$modul\" nicht gefunden!");
}

if (request_command("do")){
	$id=request_value($idkey);
	
	if (!edit_rights($modul, $db, $id)){
		page_send_exit("Keine Berechtigung! ($db,#$id)");
	}
		
	unset($_REQUEST['db']);
	unset($_REQUEST['idkey']);

	$return=request_value('return');
	unset($_REQUEST['return']);
	
	if ($modul=='core'){
		$new_handeled=false;
		//TODO
	}else{
		$new_handeled=$modules[$modul]->save_data($db, $id);
	}
	
	if(isset($_REQUEST['return']))$return=request_value('return');

	if ($id=="NEW"){
		if ($new_handeled){
			if ($new_handeled===true){
				$id=mysql_insert_id();
			}else{
				$id=$new_handeled;
			}
		}else{
			$id=dbio_NEW_FROM_REQUEST($db,$idkey);
		}
		log_db_new2($modul, $db, $id, $_REQUEST);
	}else{
		$old=dbio_SELECT_SINGLE($db, $id, $idkey);
		$new=$_REQUEST;
		$delta=array();
		foreach ($new as $key => $value) {
			if($old[$key]!=$value&&!(($value===null||$value=='null')&&$old[$key]=='')){
				$delta[$key]=$value;
			}
		}
		if($delta){
			dbio_UPDATE($db, "`$idkey`='$id'", $delta);
			log_db_edit($modul, $db, $id, json_encode($delta));
		}
	}
	
	$return=preg_replace("/\\[NEWID\\]/", $id, $return);
	
	if ($return){
		ajax_refresh("Speichere Datensatz #$id...", $return);
	}else{
		$page->message_ok("Datensatz #$id gespeichert.");
		page_send_exit();
	}
}
if (request_command("delete")){
	$id=request_value($idkey);
	
	if (!edit_rights($modul, $db, $id)){
		page_send_exit("Keine Berechtigung! ($db,#$id)");
	}
	
	$fehler=null;
	
	if ($modul=='core'){
		include_once 'edit_forms.php';
		$modok=pre_delete($db,$id);
		if (!$modok) $fehler="CORE";
	}
	
	foreach ($modules as $module) {
		$modok=$module->pre_delete($db, $id);
		if (!$modok&&!$fehler) $fehler=$module->modul_name;
	}

	$return=(isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:null);
	
	if (!$fehler){
		dbio_DELETE($db, "`$idkey`='$id'");
		log_db_delete($modul, $db, $id);
	}else{
		if ($return) $page->say(html_div(html_a_button("Zurück", $return)));
		page_send_exit("Verhindert durch Modul \"$fehler\".");
	}
	
	if ($return){
		ajax_refresh("Lösche Datensatz #$id...", $return);
	}else{
		page_send_exit("Datensatz #$id gelöscht.");
	}
}

if (!edit_rights($modul, $db, $id)){
	page_send_exit("Keine Berechtigung! ($db,#$id)");
}

$new_with_id=null;
if ($id!="NEW"){
	$query=dbio_SELECT_SINGLE($db, $id, $idkey);
	if (!$query){
		$new_with_id=$id;
		$id="NEW";
	}
}

if ($id=="NEW"){
	$col_info=dbio_info_columns($db);
	$query=array();
	foreach ($col_info as $key => $dummy) {
		$query[$key]="";
	}
}

/*
 * Formular
 */

$referer=request_value("return",(isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:null));

$form=new form("do","?",null,$db);
$form->add_hidden("return", $referer);
$form->add_hidden("db", $db);
$form->add_hidden("idkey", $idkey);
$form->add_hidden($idkey, $id);
if ($new_with_id)
$form->add_hidden("new_id", $new_with_id);
	
edit_add_fields($form,$modul,$db,$query,$id,$idkey);

$view_url=request_value('view_url');
if ($view_url&&$id!="NEW") $form->buttons.=html_button("Details",null,"location.href='$view_url';");

/*
 * Ausgabe
 */

$datensatz=request_value("datensatz");
if (!$datensatz) $datensatz="Datensatz";
if ($id=="NEW"){
	$page->say(html_header1("$datensatz erstellen"));
}else{
	$page->say(html_header1("$datensatz bearbeiten"));
}

$page->say($form);
$page->focus="label:first-child + *";

page_send_exit();//=======================================================================================
?>