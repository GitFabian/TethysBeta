<?php
include_once '../config_start.php';
$page->init("core_edit", "Datensatz bearbeiten");
include_once ROOT_HDD_CORE.'/core/classes/form.php';
include_once ROOT_HDD_CORE.'/core/edit_rights.php';

request_extract_booleans2();

/*
 * Datentabelle und Datensatz ermitteln
 */
$idkey=request_value("idkey","id");
$db=request_value("db");
$id=request_value("id");

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
	
	dbio_UPDATE($db, "`$idkey`='$id'", $_REQUEST);
	
	if ($return){
		ajax_refresh("Speichere Datensatz...", $return);
	}else{
		page_send_exit("Datensatz gespeichert.");
	}
}

if (!edit_rights($modul, $db, $id)){
	page_send_exit("Keine Berechtigung! ($db,#$id)");
}

$query=dbio_SELECT_SINGLE($db, $id, $idkey);
if (!$query){
	page_send_exit("Datensatz nicht gefunden!");
}

/*
 * Ausgabe
 */

$page->say(html_header1("Datensatz bearbeiten"));

$referer=request_value("return",(isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:null));

$form=new form("do");
$form->add_hidden("return", $referer);
$form->add_hidden("db", $db);
$form->add_hidden("idkey", $idkey);
$form->add_hidden($idkey, $id);

edit_add_fields($form,$modul,$db,$query,$id,$idkey);

$page->say($form);

page_send_exit();//=======================================================================================
function edit_add_fields($form,$modul,$db,$query,$id,$idkey){
	global $modules;
	if ($modul=='core'){
		include_once 'edit_forms.php';
		$edit_form=get_edit_form($form,$db,$id);
	}else{
		$edit_form=$modules[$modul]->get_edit_form($form,$db,$id);
	}
	if ($edit_form===false) edit_default_form($form,$query,$db,$idkey);
}
function edit_default_form($form,$query,$db,$idkey){
	$col_info=dbio_info_columns($db);
	#echo $col_info['active']['Type'];
	foreach ($query as $key => $value) {
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
?>