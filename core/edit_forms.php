<?php

function get_edit_form($form,$db,$id,$query){
	if ($db=='core_users'){
		if ($id=="NEW"){
			$query['active']="1";
		}
		module::edit_form_field($form,$query,'vorname',"Vorname");
		module::edit_form_field($form,$query,'nachname',"Nachname");
		module::edit_form_field($form,$query,'active',"Aktiv",'CHECKBOX');
		module::edit_form_field($form,$query,'nick',"Nick");
		module::edit_form_field($form,$query,'http_auth');
		$pass_field=module::edit_form_field($form,$query,'password',"Passwort");
		if ($id=="NEW"){
			if(!$pass_field->value)$pass_field->value=string_random_pass_aa0000();
		}
		return true;
	}
	return false;
}

function pre_delete($table,$id){
	if ($table=='core_users'){
		if ($id==USER_ID) return false;
		dbio_DELETE("core_user_right", "`user`=$id");
	}
	return true;
}

?>