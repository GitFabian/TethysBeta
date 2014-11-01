<?php

function get_edit_form($form,$db,$id,$query){
	if ($db=='core_users'){
		//$query=dbio_SELECT_SINGLE($db,$id);
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

?>