<?php

function get_edit_form($form,$db,$id,$query){
	if ($db=='core_users'){
		global $page;
		if ($id=="NEW"){
			$query['active']="1";
			$form->add_hidden("logon_time","0");
			$form->add_hidden("logon_ip","");
		}
		$ff=module::edit_form_field($form,$query,'vorname',"Vorname");
		$ff->id="id_vorname";
		$ff=module::edit_form_field($form,$query,'nachname',"Nachname");
		$ff->id="id_nachname";
		module::edit_form_field($form,$query,'active',"Aktiv",'CHECKBOX');
		module::edit_form_field($form,$query,'nick',"Nick");
		
		$ff=module::edit_form_field($form,$query,'http_auth',"HTTP-Auth".(setting_get(null, "CFG_AUTHPATTERN")?autofill_manuell("autofill_auth();"):""));
		$ff->id=($nid=get_next_id());
		$page->add_inline_script("function autofill_auth(){
				vorname=document.getElementById('id_vorname').value.toLowerCase().replace(/[^a-z]/g,'');
				nachname=document.getElementById('id_nachname').value.toLowerCase().replace(/[^a-z]/g,'');
				document.getElementById('$nid').value=".setting_get(null, "CFG_AUTHPATTERN").";
			}");
		
		$pass_field=module::edit_form_field($form,$query,'password',"Passwort".autofill_password($nid=get_next_id()));
		$pass_field->id=$nid;
		
		#module::edit_form_field($form,$query,'geb',"Geburtstag","DATUM");
		$form->add_field(new form_field("geb","Geburtstag",$query["geb"]?date("j.n.Y",strtotime($query["geb"])):"","DATUM"));
		
		module::edit_form_field($form,$query,'picture',"Bild");
// 		module::edit_form_field($form,$query,'durchwahl',"Durchwahl");
// 		module::edit_form_field($form,$query,'handy',"Handy");
// 		module::edit_form_field($form,$query,'raum',"Raum");
		
		$ff=module::edit_form_field($form,$query,'email',"E-Mail".(setting_get(null, "CFG_MAILPATTERN")?autofill_manuell("autofill_mail();"):""));
		$ff->id=($nid=get_next_id());
		$page->add_inline_script("function autofill_mail(){
				vorname=document.getElementById('id_vorname').value.toLowerCase().replace(/[^a-z]/g,'_');
				nachname=document.getElementById('id_nachname').value.toLowerCase().replace(/[^a-z]/g,'_');
				document.getElementById('$nid').value=".setting_get(null, "CFG_MAILPATTERN").";
			}");
		
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