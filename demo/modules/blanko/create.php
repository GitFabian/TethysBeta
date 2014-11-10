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
	
	function get_menu(\$page_id){
		\$menu=new menu(null,\"$id\",\$page_id,\"$name\");
		new menu_topic(\$menu,\"${id}_$index_id\",\$page_id,\"$index_name\",url_$id('$index_url'));
		return \$menu;
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
	
	mkdir($dir);
	$file=fopen($dir."/tethys.php", "w");
		fwrite($file, $tethys_file);
	fclose($file);
	$file=fopen($dir."/$index_id.php", "w");
		fwrite($file, $index_file);
	fclose($file);

	$m=array();
	foreach ($modules as $key => $dummy) { $m[]=$key; }
	$m[]=$id;
	dbio_UPDATE("core_settings", "`key`='CFG_MODULES'", array("value"=>implode(",", $m)));
	
	$page->say("--- OK ---<br><br>");
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