<?php

/*
include_once ROOT_HDD_CORE.'/core/admin/rights_.php';
 */

/*
 * Beschreibung aller Rechte
 */
function all_rights(){
	include_once ROOT_HDD_CORE.'/core/classes/rights.php';
	global $modules;
	$all_rights=array(
		"RIGHT_ADMIN"=>new right("Administrator/Entwickler","Vorsicht! ALLE Rechte. Auch instabile BETA-Features und Entwickler-Ausgaben!","CORE"),
		"RIGHT_USERMGMT"=>new right("Personalverwaltung","Benutzer anlegen, bearbeiten und löschen. Rollen zuweisen.","CORE"),
			//DEPRECATED: RIGHT_DATAACCESS / RIGHTxDATAACCESS
// 		"RIGHTxDATAACCESS"=>new right("DATA-Zugriffe","Zugriffsrechte anzeigen und verwalten.","CORE"),
	);
	/*
	 * Modulspezifische Rechte
	 */
	foreach ($modules as $modul) {
		$modul_rights=$modul->get_rights();
		if($modul_rights)
			foreach ($modul_rights as $key => $right) {
				if (isset($all_rights[$key])){
					if(USER_ADMIN)echo "!!!Berechtigung wurde überschrieben: \"$key\" (".$all_rights[$key]->name.")!!!";
				}
				$all_rights[$key]=$right;
				$right->modul=$modul->modul_name;
				//$right->description.=" (Modul \"".$modul->modul_name."\")";
			}
	}
	
	return $all_rights;
}

?>