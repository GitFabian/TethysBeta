<?php
include_once '../config_start.php';
$page->init("core_edit", "Datensatz bearbeiten");
include_once ROOT_HDD_CORE.'/core/classes/form.php';
include_once ROOT_HDD_CORE.'/core/edit_rights.php';
include_jquery();

request_extract_booleans2();

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
		
	$return=request_value('return');
	
	unset($_REQUEST['return']);
	unset($_REQUEST['db']);
	unset($_REQUEST['idkey']);
	
	if ($modul=='core'){
		$new_handeled=false;
		//TODO
	}else{
		$new_handeled=$modules[$modul]->save_data($db, $id);
	}
	
	if ($id=="NEW"){
		if (!$new_handeled) $id=dbio_NEW_FROM_REQUEST($db);
	}else{
		dbio_UPDATE($db, "`$idkey`='$id'", $_REQUEST);
	}
	
	if ($return){
		ajax_refresh("Speichere Datensatz #$id...", $return);
	}else{
		page_send_exit("Datensatz #$id gespeichert.");
	}
}
if (request_command("delete")){
	
	if ($modul=='core'){
		include_once 'edit_forms.php';
		pre_delete($db,$id);
	}
	
	$fehler=null;
	foreach ($modules as $module) {
		$modok=$module->pre_delete($db, $id);
		if (!$modok&&!$fehler) $fehler=$module->modul_name;
	}

	$return=(isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:null);
	
	if (!$fehler){
		dbio_DELETE($db, "`$idkey`='$id'");
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

if ($id=="NEW"){
	$col_info=dbio_info_columns($db);
	$query=array();
	foreach ($col_info as $key => $dummy) {
		$query[$key]="";
	}
}else{
	$query=dbio_SELECT_SINGLE($db, $id, $idkey);
	if (!$query){
		page_send_exit("Datensatz nicht gefunden!");
	}
}

/*
 * Ausgabe
 */

if ($id=="NEW"){
	$page->say(html_header1("Datensatz erstellen"));
}else{
	$page->say(html_header1("Datensatz bearbeiten"));
}

$referer=request_value("return",(isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:null));

$form=new form("do");
$form->add_hidden("return", $referer);
$form->add_hidden("db", $db);
$form->add_hidden("idkey", $idkey);
$form->add_hidden($idkey, $id);

edit_add_fields($form,$modul,$db,$query,$id,$idkey);

$page->say($form);
$page->focus="label:first-child + *";

page_send_exit();//=======================================================================================
function edit_add_fields($form,$modul,$db,$query,$id,$idkey){
	global $modules;
	if ($modul=='core'){
		include_once 'edit_forms.php';
		$edit_form=get_edit_form($form,$db,$id,$query);
	}else{
		$edit_form=$modules[$modul]->get_edit_form($form,$db,$id,$query);
	}
	if ($edit_form===false) edit_default_form($form,$query,$db,$idkey);
}
function edit_default_form($form,$query,$db,$idkey){
	foreach ($query as $key => $value) {
		$col_info=dbio_info_columns($db);
		#echo $col_info['active']['Type'];
		if ($key!=$idkey){
			
			/*
			 * Datentyp
			 */
			$typ='TEXT';
			$type=$col_info[$key]['Type'];
			if ($type=='text') $typ='TEXTAREA';
			if ($type=='tinyint(1)') $typ='CHECKBOX';
			
			$form->add_field(new form_field($key,null,request_value($key,$value),$typ));
		}
	}
	return true;
}
function edit_get_empty_table($table){
	
}
?>