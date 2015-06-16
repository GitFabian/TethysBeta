<?php

if (isset($argv[1])&&strcasecmp($argv[1],"WRAPPER")==0) wrapper();

$configOnly=true;
include_once '../../config_start.php';
include_once ROOT_HDD_CORE.'/core/toolbox.php';
standalone_start(array(
	"id"=>"0",
	"nick"=>"Tethys",
));
include_once ROOT_HDD_CORE.'\\core\\start.php';
$page->init("core_chronjobs_do", "Chronjobs");

include_once ROOT_HDD_CORE.'/core/classes/table.php';

#echo(new table( dbio_SELECT("core_chronjobs","sent IS NULL OR sent=0") ));


$query_unsent_jobs=dbio_SELECT("core_chronjobs","sent IS NULL OR sent=0");
foreach ($query_unsent_jobs as $job) {
	$id=$job["id"];
	$schedule=$job["schedule"];
	$now=time();
	$modul=$job["modul"];
	$command=$job["command"];
	echo "#$id ($modul:$command): ".format_datum_to_tm_j($schedule,null,"Y",true)."\n";
	if ($schedule<$now){
		echo "--SEND:";
		if(isset($modules[$modul])){
			$response=$modules[$modul]->send_chronjob($command, $job["value"]);
			if ($response){
				echo $response;
				dbio_UPDATE("core_chronjobs", "id=$id", array("sent"=>time()));
			}else{
				echo "FAILED!";
			}
		}else{
			echo "Modul \"$modul\" nicht gefunden!";
		}
		echo "\n";
	}
}


#$page->send();
exit;//============================================================================================
function wrapper(){
	while (true) {
		echo date("[y-m-d H:i:s]")." chronjobs_do...\n";
		passthru("php chronjobs_do.php");
		sleep(15);
	}
}
?>