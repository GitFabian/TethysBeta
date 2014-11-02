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
		module::edit_form_field($form,$query,'password',"Passwort");
		return true;
	}
	return false;
}

function pre_delete($table,$id){
	if ($table=='core_users'){
		dbio_DELETE("core_user_right", "`user`=$id");
	}
}

?>