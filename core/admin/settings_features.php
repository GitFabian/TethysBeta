<?php
if(!isset($page)){echo"Seite existiert nicht.";exit;}

if (request_command("update")) core_features_update();

$form=new form("update");
$form->add_hidden('view', "features");

$form->add_fields(CFG_TITLE, null);
settings_add_field($form,"FEATURE_BETA","BETA-Features",'CHECKBOX');

foreach ($modules as $modul_id => $module){
	$features=$module->get_features();
	if ($features){
		$form->add_fields($module->modul_name, null);
		foreach ($features as $fid=>$feature) {
			features_add_field($form,$modul_id, $fid, $feature->description, $feature->value);
		}
	}
}

$page->add_html($form->toHTML());

page_send_exit();//============================================================================================
function features_add_field($form,$modul,$feature_id,$label,$value){
	$form->add_field( new form_field("MODUL_".$modul."__".$feature_id, $label, $value,'CHECKBOX',"get_feature(\"".$modul."\",\"$feature_id\")") );
}
function core_features_update(){
	global $modules;
	if (!USER_ADMIN) return;
	$n=0;
	unset ($_REQUEST['view']);
	$booleans=request_extract_booleans();
	foreach ($booleans as $key => $value) {
		if (substr($key, 0, 6)=="MODUL_"){
			$index=strpos($key, "__");
			$modul=substr($key, 6, $index-6);
			$feature=substr($key, $index+2);
			$val=get_feature($modul, $feature);//TODO:Features puffern für weniger queries!
			if ($value!=$val){
				if($modules[$modul]->set_feature($feature,$value))
				$n++;
			}
		}else{
			if ($value!=constant($key)){
				dbio_UPDATE("core_features", "phpname='$key'", array("value"=>$value));
				$n++;
			}
		}
	}
	ajax_refresh("Speichere Konfiguration...", "settings.".CFG_EXTENSION."?view=features&cmd=updated&n=$n");
}
?>