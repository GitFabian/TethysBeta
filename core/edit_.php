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
	$infos=dbio_information_schema_constraints($db);
	#debug_out($infos);
	$col_info=dbio_info_columns($db);
	#debug_out($col_info);
	$new=(isset($_REQUEST['id'])&&$_REQUEST['id']=='NEW');
	foreach ($query as $key => $value) {
		if ($key!=$idkey){
			$options=null;
			/*
			 * Default-Value
			 */
			if($new){
				$value=$col_info[$key]['Default'];
			}
			$v=request_value($key,$value);

			/*
			 * Datentyp
			 */
			$typ='TEXT';
			$type=$col_info[$key]['Type'];
			if ($type=='text'){
				$typ='TEXTAREA';
			}
			else if ($type=='tinyint(1)'){
				$typ='CHECKBOX';
			}
			else if (substr($type,0,6)=="enum('"){
				$typ='SELECT';
				$options=array();
				foreach (explode(",", substr($type,5,strlen($type)-6)) as $option) {
					$o=trim($option,"'");
					$options[$o]=$o;
					if($col_info[$key]['Null']=="YES"){
						$options=array_unshift_assoc($options, "null", "(-/-)");
					}
				}
			}
			else if ($type=='date'){
				$typ='DATUM';
				$v=($v?format_datum_to_tmj($v):"");
			}
			else if (substr($type,0,4)=="int("){
				if($col_info[$key]['Null']=="YES" && !$value) $v="null";
			}


			/*
			 * Constraints
			 */
			if (isset($infos[$key])){
				$typ='SELECT';
				$ref_tbl=$infos[$key]['REFERENCED_TABLE_NAME'];
				$ref_col=$infos[$key]['REFERENCED_COLUMN_NAME'];
				$options=dbio_SELECT_asList($ref_tbl, format_default_for_column($ref_tbl,$ref_col), null, $ref_col);
				if($col_info[$key]['Null']=="YES"){
					$options=array_unshift_assoc($options, "null", "(-/-)");
				}
			}

			$form->add_field($ff=new form_field($key,null,$v,$typ,null,$options));
			
			//Maxlength
			if (substr($type,0,8)=="varchar("){
				$len=substr($type,8,strlen($type)-9);
				$ff->maxlength=$len;
			}
				
		}
	}
	return true;
}
?>