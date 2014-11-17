<?php

/*
include_once ROOT_HDD_CORE.'/core/edit_.php';
 */

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
		#debug_out($col_info);
		#echo $col_info['active']['Type'];
		if ($key!=$idkey){
			$options=null;
			
			/*
			 * Datentyp
			 */
			$typ='TEXT';
			$type=$col_info[$key]['Type'];
			if ($type=='text') $typ='TEXTAREA';
			if ($type=='tinyint(1)') $typ='CHECKBOX';
			if (substr($type,0,6)=="enum('"){
				$typ='SELECT';
				$options=array();
				foreach (explode(",", substr($type,5,strlen($type)-6)) as $option) {
					$o=trim($option,"'");
					$options[$o]=$o;
				}
			}
			
			$form->add_field(new form_field($key,null,request_value($key,$value),$typ,null,$options));
		}
	}
	return true;
}
function edit_get_empty_table($table){
	
}
?>