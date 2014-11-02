<?php

function edit_form($form,$table,$id,$query){
	if ($table=='demo_lorumipsum'){
		include_chosen();
		module::edit_form_field($form,$query,'flubtangle',"Flubtangle",'TEXTAREA');
		module::edit_form_field($form,$query,'abracadabra',"Abracadabra",'TEXTAREA');
		
		$query_users=dbio_SELECT("core_users");
		$users=array();
		foreach ($query_users as $user) {
			$users[$user['id']]=$user['vorname']." ".$user['nachname'];
		}
		
		$members=($id=="NEW"?null:dbio_SELECT_keyValueArray("demo_flubtangle_user", "id", "user", "flubtangle=$id"));

		$form->add_field(new form_field('members[]',"Mitglieder",request_value("members",$members),'SELECT_MULTIPLE',null,$users));
		return true;
	}
	if (USER_ADMIN) echo"Kein edit_form für $table!";
	return false;
}

function save_form($table,$id){
	if ($table=='demo_lorumipsum'){
		if ($id=="NEW"){
			$id=dbio_NEW_FROM_REQUEST("demo_lorumipsum");
		}
		dbio_UPDATE_groupMember("demo_flubtangle_user", request_value('members'), "flubtangle", $id);
		unset($_REQUEST['members']);
		return true;
	}
	return false;
}

?>