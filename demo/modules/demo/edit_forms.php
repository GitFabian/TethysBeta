<?php

function edit_form($form,$table,$id){
	if ($table=='demo_lorumipsum'){
		include_chosen();
		$query=dbio_SELECT_SINGLE($table,$id);
		module::edit_form_field($form,$query,'flubtangle',"Flubtangle",'TEXTAREA');
		module::edit_form_field($form,$query,'abracadabra',"Abracadabra",'TEXTAREA');
		
		$query_users=dbio_SELECT("core_users");
		$users=array();
		foreach ($query_users as $user) {
			$users[$user['id']]=$user['vorname']." ".$user['nachname'];
		}
		
		$members=dbio_SELECT_keyValueArray("demo_flubtangle_user", "id", "user", "flubtangle=$id");

		$form->add_field(new form_field('members[]',"Mitglieder",request_value("members",$members),'SELECT_MULTIPLE',null,$users));
		return true;
	}
	if (USER_ADMIN) echo"Kein edit_form für $table!";
	return false;
}

function save_form($table,$id){
	if ($table=='demo_lorumipsum'){
		dbio_DELETE("demo_flubtangle_user", "flubtangle=$id");
		$data=array();
		foreach ($_REQUEST['members'] as $member) {
			dbio_INSERT("demo_flubtangle_user", array(
				"flubtangle"=>$id,
				"user"=>$member,
			));
		}
		unset($_REQUEST['members']);
	}
}

?>